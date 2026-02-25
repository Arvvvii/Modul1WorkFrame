<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google, create or update the local user,
     * generate a 6-digit OTP, save it to the user record, and redirect to OTP verification.
     *
     * Important: do NOT create the session/login here. OTP verification happens next.
     */
    public function handleGoogleCallback(Request $request)
    {
        // Try stateful first (preferred). If it fails (session/state issues),
        // fall back to stateless and log the original exception for debugging.
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Exception $e) {
            Log::warning('Socialite stateful failed: ' . $e->getMessage());
            try {
                $googleUser = Socialite::driver('google')->stateless()->user();
                Log::info('Socialite stateless succeeded as fallback.');
            } catch (Exception $e2) {
                Log::error('Socialite stateless also failed: ' . $e2->getMessage());
                return redirect('/login')->withErrors(['oauth' => 'Google authentication failed.']);
            }
        }

        $email = $googleUser->getEmail();
        Log::info('Google callback received for email: ' . ($email ?? 'n/a'));
        if (! $email) {
            return redirect('/login')->withErrors(['oauth' => 'Google account did not provide an email.']);
        }

        // Find existing user by email or create new one
        $user = User::where('email', $email)->first();

        if ($user) {
            // update id_google when different or empty
            if (empty($user->id_google) || $user->id_google !== $googleUser->getId()) {
                $user->id_google = $googleUser->getId();
            }
        } else {
            // create a new user record (password set to random string)
            $user = User::create([
                'name' => $googleUser->getName() ?: $googleUser->getNickname() ?: 'Google User',
                'email' => $email,
                'password' => Str::random(40),
                'id_google' => $googleUser->getId(),
            ]);
        }

        // Ensure user's email is set to the Google email (should be same)
        $user->email = $email;

        // Generate 6-digit OTP and save to user. Use random_int for better randomness.
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->otp = $otp;
        $user->save();

        // Send OTP by email (plain text). If mail not configured, this may throw.
        try {
            Mail::raw('Kode OTP Anda: ' . $user->otp, function ($message) use ($user) {
                $message->to($user->email)->subject('Kode OTP Verifikasi');
            });
            Log::info('OTP terkirim ke: ' . $user->email . ' (OTP: ' . $user->otp . ')');
        } catch (Exception $e) {
            // don't block flow if mail fails; log and continue
            // Optionally you can flash a warning message
            Log::error('Gagal mengirim OTP ke: ' . ($user->email ?? 'n/a') . ' - ' . $e->getMessage());
        }

        // Store the email being verified in session so OTP page knows which email
        $request->session()->put('otp_email', $user->email);

        // Redirect to named OTP verify route
        return redirect()->route('otp.verify');
    }

    /**
     * Show the OTP verification form.
     */
    public function showOtpForm(Request $request)
    {
        // Tampilkan form OTP tanpa memaksa redirect ke login.
        // (Verifikasi OTP akan memeriksa session saat POST.)
        $email = $request->session()->get('otp_email');
        return view('auth.otp-verify', ['email' => $email]);
    }

    /**
     * Verify submitted OTP and login the user if valid.
     */
    public function verifyOtp(Request $request)
    {
        $email = $request->session()->get('otp_email');
        if (! $email) {
            return redirect()->route('login')->withErrors(['otp' => 'Session expired, silakan mulai ulang proses login.']);
        }

        $data = $request->only('otp');
        $validator = Validator::make($data, [
            'otp' => ['required', 'digits:6'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $otp = $data['otp'];
        $user = User::where('email', $email)->first();
        if (! $user) {
            return redirect()->route('login')->withErrors(['otp' => 'Pengguna tidak ditemukan.']);
        }

        if (! isset($user->otp) || $user->otp !== $otp) {
            return redirect()->back()->withErrors(['otp' => 'Kode OTP salah atau sudah kadaluarsa']);
        }

        // OTP cocok: login user, clear otp and session
        Auth::login($user);
        $user->otp = null;
        $user->save();
        $request->session()->forget('otp_email');

        return redirect()->intended('/home');
    }
}
