<?php

namespace App\Http\Controllers;

use App\Models\Judges;
use App\Models\service_inspection;
use App\Observers\ServiceInspectionObserver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Imagick;
use Smalot\PdfParser\Parser;
use Spatie\PdfToText\Pdf;
use thiagoalessio\TesseractOCR\TesseractOCR;


class JudgesController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function testJudgeStage()
    {
        $judge = Judges::first();
        $stages = $judge?->judges_stages;

        if (!$stages || $stages->isEmpty()) {
            dd('❌ Sudyaga bosqich (judges_stages) biriktirilmagan');
        }

        $est = $stages->last()?->establishment;

        if (!$est) {
            dd('❌ Establishment topilmadi');
        }

        dd([
            'court_type_id' => $est->court_type_id,
            'court_specialty_id' => $est->court_specialty_id,
            'region_id' => $est->region_id,
            'establishment_id' => $est->id,
            'establishment_name' => $est->name ?? 'Nomaʼlum',
        ]);
    }
    public function index()
    {

        $judges = Judges::all();
        return response($judges);
//        $judges = Judges::with(['serviceinspection', 'judges_stages'])
//            ->where('middle_name', 'Исламов')
//            ->take(5)->get();
//        return response()->json($judges);
    }


//    public function testPdfRead()
//    {
//        try {
//            $pdfFile = storage_path('app/public/attachments/01JV3ZFS0V3QZ98SRS5K6X7QF8.pdf');
//
//            if (!file_exists($pdfFile)) {
//                return response("❌ PDF topilmadi", 404);
//            }
//
//            $imagick = new \Imagick();
//            $imagick->setResolution(300, 300);
//            $imagick->readImage($pdfFile); // ❗️ Bu safar to‘liq PDF
//            $pageCount = $imagick->getNumberImages();
//
//            $fullText = '';
//
//            foreach (range(0, $pageCount - 1) as $i) {
//                $imagick->setIteratorIndex($i);
//                $imagick->setImageFormat('png');
//
//                $pageImagePath = storage_path("app/public/tmp_page_{$i}.png");
//                $imagick->writeImage($pageImagePath);
//
//                $ocrText = (new TesseractOCR($pageImagePath))
//                    ->lang('uzb', 'rus')
//                    ->run();
//
//                $fullText .= "📄 Sahifa " . ($i + 1) . ":\n" . $ocrText . "\n\n";
//            }
//
//            $imagick->clear();
//            $imagick->destroy();
//
//            return response("✅ OCR orqali o‘qilgan matn:\n\n" . $fullText, 200)
//                ->header('Content-Type', 'text/plain');
//
//        } catch (\Throwable $e) {
//            Log::error("❌ OCR xatolik: " . $e->getMessage());
//            return response("❌ Xatolik: " . $e->getMessage(), 500);
//        }
//    }


    public function getJudges()
    {

        return Judges::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function showCv(Judges $id)
    {
        $judge = Judges::findOrFail($id);
        return view('judge.cv', compact('judge'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Judges $judges)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Judges $judges)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Judges $judges)
    {
        //
    }

}
