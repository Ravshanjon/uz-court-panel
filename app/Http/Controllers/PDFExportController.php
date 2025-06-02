<?php

namespace App\Http\Controllers;

use App\Models\Judges;
use App\Models\Judges_Stages;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Spatie\Browsershot\Browsershot;

class PDFExportController extends Controller
{
    public function downloadPDF($id)
    {
        $judge = Judges::findOrFail($id);

        // Pass the judge data to the PDF view
        $pdf = Pdf::loadView('pdf.judge', compact('judge'));

        // Return the PDF as a download
        return $pdf->download('judge-details.pdf');
    }

    public function profilePdf($id)
    {
        $judge = Judges::findOrFail($id);

        // 1. Blade viewni render qilish
        $html = View::make('pdf.profile', compact('judge'))->render();

        // 2. Foydalanuvchi uchun vaqtinchalik fayl yo‘li yaratish
        $pdfPath = storage_path('app/public/judge_profile_' . $judge->id . '.pdf');

        // 3. Browsershot orqali PDF yaratish
        Browsershot::html($html)
            ->format('A4')
            ->showBackground()
            ->margins(10, 10, 10, 10)
            ->waitUntilNetworkIdle()
            ->savePdf($pdfPath);

        // 4. Yuklab berish
        return response()->download($pdfPath)->deleteFileAfterSend(true);
    }
}
