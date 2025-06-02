<?php

namespace App\Http\Controllers;

use App\Models\OcrText;
use Illuminate\Http\Request;

class OcrTextController extends Controller
{
    public function uploadAndProcess(Request $request)
    {
        $request->validate([
            'judge_id' => 'required|exists:judges,id',
            'file' => 'required|file|mimes:pdf',
        ]);

        $file = $request->file('file');
        $judgeId = $request->input('judge_id');
        $fileName = $file->hashName();
        $file->storeAs('public/ocr_pdfs', $fileName);

        $pdfFile = storage_path('app/public/ocr_pdfs/' . $fileName);

        try {
            $imagick = new Imagick();
            $imagick->setResolution(300, 300);
            $imagick->readImage($pdfFile);
            $pageCount = $imagick->getNumberImages();

            $fullText = '';
            $pageTexts = [];

            foreach (range(0, $pageCount - 1) as $i) {
                $imagick->setIteratorIndex($i);
                $imagick->setImageFormat('png');

                $imagePath = storage_path("app/public/tmp_page_{$i}.png");
                $imagick->writeImage($imagePath);

                $ocrText = (new TesseractOCR($imagePath))
                    ->lang('uzb', 'rus')
                    ->run();

                $pageTexts[] = [
                    'page' => $i + 1,
                    'text' => $ocrText,
                ];

                $fullText .= "📄 Sahifa " . ($i + 1) . ":\n" . $ocrText . "\n\n";
            }

            $imagick->clear();
            $imagick->destroy();

            OcrText::create([
                'judge_id' => $judgeId,
                'source_pdf' => $fileName,
                'ocr_text' => $fullText,
                'pages' => array_map(fn ($item) => $item['page'], $pageTexts),
                'page_texts' => $pageTexts,
            ]);

            return response()->json([
                'message' => '✅ OCR muvaffaqiyatli saqlandi',
                'pages' => $pageCount,
            ]);

        } catch (\Throwable $e) {
            Log::error('❌ OCR xatolik: ' . $e->getMessage());
            return response()->json(['error' => 'OCR xatolik: ' . $e->getMessage()], 500);
        }
    }
}
