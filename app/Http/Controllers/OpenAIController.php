<?php

namespace App\Http\Controllers;

use App\Models\Judges;
use App\Models\OcrText;
use App\Models\service_inspection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\PdfToText\Pdf;


class OpenAIController extends Controller
{
    public function handle(Request $request)
    {
        $text = $request->input('text', 'Судя ҳақида маълумот беринг');
        [$lastName, $firstName, $middleName] = array_pad(explode(' ', $text), 3, null);

        $parts = explode(' ', $text);
        $lastName = $parts[0] ?? null;
        $firstName = $parts[1] ?? null;
        $middleName = $parts[2] ?? null;



        try {
            $judges = Judges::with('serviceinspection')
                ->when($lastName, fn($q) => $q->where('last_name', 'like', "%$lastName%"))
                ->when($firstName, fn($q) => $q->where('first_name', 'like', "%$firstName%"))
                ->when($middleName, fn($q) => $q->where('middle_name', 'like', "%$middleName%"))
                ->get();

            $context = '';

            foreach ($judges as $judge) {
                $context .= "🧾 Биографик маълумотлар:\n";
                $context .= "- ФИО: {$judge->last_name} {$judge->first_name} {$judge->middle_name}\n";
                $context .= "- Туғилган сана: " . ($judge->birth_date ?? '---') . "\n";
                $context .= "- Туғилган жой: " . ($judge->birth_place ?? '---') . "\n\n";


                $ocrRecords = OcrText::where('judge_id', $judge->id)->get();

                foreach ($ocrRecords as $ocr) {
                    $context .= "📝 OCR хулоса (файл: {$ocr->source_pdf}):\n";
                    $context .= Str::limit($ocr->ocr_text, 1000) . "\n";
                    $context .= "---------------------------\n";
                }
            }

            if (trim($context) === '') {
                return response()->json(['error' => 'Maʼlumot topilmadi.'], 404);
            }

            Log::info("🧠 OpenAI ga yuborilayotgan kontekst:\n" . $context);

            $response = Http::withToken(env('OPENAI_API_KEY'))->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o',
                'messages' => [
                    ['role' => 'system', 'content' => 'Siz xizmat tekshiruvlariga asoslangan AI yordamchisiz.'],
                    ['role' => 'user', 'content' => "Quyidagi ma'lumotlarga asoslanib sudya faoliyatiga tahliliy xulosa bering:\n\n$context"]
                ],
            ]);

            if ($response->failed()) {
                Log::error('OpenAI javobda xato: ' . $response->body());
                return response()->json(['error' => 'OpenAI bilan aloqa xatosi.'], 500);
            }

            return response()->json([
                'response' => $response->json()['choices'][0]['message']['content']
            ]);

        } catch (\Throwable $e) {
            Log::error('❌ Laravel server xatolik: ' . $e->getMessage());
            return response()->json(['error' => 'Xatolik: ' . $e->getMessage()], 500);
        }
    }
}
