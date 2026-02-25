<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    /**
     * Generate PDF certificate for the authenticated user.
     */
    public function generatePDF()
    {
        $user = Auth::user();

        $pdf = Pdf::loadView('certificate', compact('user'));

        return $pdf->download('Sertifikat_Modul1.pdf');
    }
}
