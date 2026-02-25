@include('layouts.header')
@include('layouts.style-global')
@include('layouts.auth-background')
</head>
<body>
  <div class="auth-bg">
    <div class="auth-card">
      <div class="auth-logo">
        <img src="{{ asset('assets/images/logo.svg') }}" alt="logo">
      </div>
        <h4 class="auth-title">Halo! mari kita mulai</h4>
        <h6 class="auth-subtitle">Masuk untuk melanjutkan.</h6>

        <form method="POST" action="{{ route('login') }}" class="pt-3">
          @csrf

          <div class="form-group">
            <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email">
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="form-group">
            <input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="mt-3">
            <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">MASUK</button>
          </div>

          <!-- Google sign-in button (official look) -->
          <div class="mt-3">
            <style>
              .btn-google{display:flex;align-items:center;justify-content:center;height:46px;border-radius:8px;border:1px solid #dadce0;background:#fff;color:#202124;font-weight:600;padding:0 14px}
              .btn-google svg{width:18px;height:18px;margin-right:10px}
              .btn-google:hover{background:#f7f7f7;text-decoration:none}
            </style>

            <a href="{{ url('auth/google') }}" class="btn btn-google btn-block" role="button">
              <svg viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path fill="#4285F4" d="M17.64 9.2c0-.63-.06-1.23-.17-1.8H9v3.41h4.84c-.21 1.12-.85 2.07-1.82 2.7v2.25h2.95c1.73-1.6 2.73-3.95 2.73-6.56z"/>
                <path fill="#34A853" d="M9 18c2.43 0 4.47-.8 5.96-2.17l-2.95-2.25C11.3 13.1 10.25 13.59 9 13.59 6.6 13.59 4.66 12.04 3.94 9.84H1.01v2.29C2.5 15.97 5.52 18 9 18z"/>
                <path fill="#FBBC05" d="M3.94 9.84C3.66 9.03 3.66 8.14 3.94 7.33V5.04H1.01A8.99 8.99 0 0 0 0 9c0 1.48.36 2.88 1.01 4.04l2.93-2.2z"/>
                <path fill="#EA4335" d="M9 3.41c1.32 0 2.53.45 3.47 1.34l2.6-2.6C13.45.82 11.43 0 9 0 5.52 0 2.5 2.03 1.01 4.96l2.93 2.07C4.66 5.96 6.6 4.41 9 4.41z"/>
              </svg>
              <span>MASUK DENGAN GOOGLE</span>
            </a>
          </div>

          <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
              <label class="form-check-label" for="remember">Ingat saya</label>
            </div>
            <div>
              @if (Route::has('password.request'))
                <a class="text-primary" href="{{ route('password.request') }}">Lupa password?</a>
              @endif
            </div>
          </div>

          <div class="text-center mt-4">
            <span class="text-muted">Belum punya akun? <a href="{{ route('register') }}">Buat</a></span>
          </div>
        </form>
        </form>
      </div>
    </div>
  </div>

  @include('layouts.js-global')
</body>
</html>
