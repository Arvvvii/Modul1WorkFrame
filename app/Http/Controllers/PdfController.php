<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

public function sertifikat()
{
    $user = Auth::user();
    ini_set('memory_limit', '256M');
    set_time_limit(300);

    // Ambil file gambar dan ubah ke Base64 agar pasti muncul di PDF
    $path = public_path('assets/images/bg-sertifikat.jpg');
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    $baseContent = 'data:image/' . $type . ';base64,' . base64_encode($data);

    // Kirim variabel $base64 ke view
    $pdf = Pdf::loadView('pdf.sertifikat', [
        'user' => $user,
        'base64' => $baseContent
    ])->setPaper('a4', 'landscape');

    $pdf->setOptions([
        'isRemoteEnabled' => true,
        'isHtml5ParserEnabled' => true,
    ]);

    return $pdf->download('sertifikat.pdf');
}

    public function undangan()
    {
        $user = Auth::user();
        $data = [
            'user' => $user,
            'instansi' => config('app.name', 'Instansi Anda'),
            'nomor_surat' => '001/UND/' . date('Y')
        ];

        $pdf = Pdf::loadView('pdf.undangan', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->download('undangan.pdf');
    }

    // (Preview methods removed) PDF previews were deleted — only generation methods remain.
}
