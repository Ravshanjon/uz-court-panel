<?php

namespace App\Http\Controllers;

use App\Models\Judges;
use App\Models\Judges_Stages;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Spatie\Browsershot\Browsershot;

class PDFExportController extends Controller
{
    public function downloadPDF($id)
    {
        $judge = Judges::findOrFail($id);
        $html = view('pdf.judge', compact('judge'))->render();

        $pdfPath = storage_path('app/public/judge.pdf');

        Browsershot::html($html)
            ->format('A4')
            ->showBackground()
            ->margins(10, 10, 10, 10)
            ->waitUntilNetworkIdle()
            ->savePdf($pdfPath);

        return response()->download($pdfPath)->deleteFileAfterSend();
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

    public function generate($judgeAId, $judgeBId)
    {
        $user = Auth::user();

        $judgeA = Judges::findOrFail($judgeAId);
        $judgeB = Judges::findOrFail($judgeBId);

        // Blade view HTML sifatida render qilinadi
        $html = View::make('pdf.compare-judges', compact('judgeA', 'judgeB','user'))->render();

        // Saqlanadigan PDF manzili
        $filePath = storage_path('app/public/judge_compare_' . $judgeA->id . '_' . $judgeB->id . '.pdf');

        // Browsershot orqali PDF yaratish
        Browsershot::html($html)
            ->format('A4')
            ->landscape()
            ->showBackground()
            ->margins(10, 10, 10, 10)
            ->waitUntilNetworkIdle()
            ->savePdf($filePath);

        // Yuklab olishga qaytarish
        return response()->download($filePath)->deleteFileAfterSend(true);
    }
}
