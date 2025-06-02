<?php

namespace App\Console\Commands;

use App\Models\Judges;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramBotPollCommand extends Command
{
    protected $signature = 'telegram:poll';
    protected $description = 'Poll Telegram bot updates and respond to users';

    public function handle()
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        $ngrok = rtrim(env('NGROK_URL', 'http://127.0.0.1:8000'), '/');
        $offset = cache()->get('telegram_update_offset', 0);

        while (true) {
            $updates = Http::get("https://api.telegram.org/bot{$token}/getUpdates", [
                'offset' => $offset,
                'timeout' => 5,
            ])->json('result');

            foreach ($updates ?? [] as $update) {
                $offset = $update['update_id'] + 1;

                // 1️⃣ Callback tugmani ushlash
                if (isset($update['callback_query'])) {
                    $callbackData = $update['callback_query']['data'];
                    $chatId = $update['callback_query']['message']['chat']['id'];

                    if (str_starts_with($callbackData, 'pdfs_')) {
                        $judgeId = str_replace('pdfs_', '', $callbackData);
                        $judge = Judges::with('serviceinspection')->find($judgeId);

                        if (!$judge) {
                            Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                                'chat_id' => $chatId,
                                'text' => '❌ Sudya topilmadi.',
                            ]);
                            continue;
                        }

                        $pdfs = $judge->serviceinspection->filter(fn($s) => $s->file)->values();

                        if ($pdfs->isEmpty()) {
                            Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                                'chat_id' => $chatId,
                                'text' => '❌ Hujjat topilmadi.',
                            ]);
                            continue;
                        }

                        $text = "📄 *Xizmat tekshiruv hujjatlari:*\n\n";
                        foreach ($pdfs as $pdf) {
                            $url = str_replace('http://127.0.0.1:8000', $ngrok, asset('storage/attachments/' . $pdf->file));
                            $text .= "🔗 [Yuklab olish]($url)\n";
                        }

                        Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                            'chat_id' => $chatId,
                            'text' => $text,
                            'parse_mode' => 'Markdown',
                            'disable_web_page_preview' => true,
                        ]);
                        continue;
                    }
                }

                // 2️⃣ Oddiy text — ism-familiya qidiruvi
                $text = $update['message']['text'] ?? null;
                $chatId = $update['message']['chat']['id'] ?? null;
                if (!$text || !$chatId) continue;

                $fullName = trim($text, '/');
                $res = Http::get("{$ngrok}/api/judges/" . urlencode($fullName));

                if (!$res->successful()) {
                    Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                        'chat_id' => $chatId,
                        'text' => "❌ Sudya topilmadi: {$fullName}",
                    ]);
                    continue;
                }

                $judges = $res->json();
                foreach ($judges as $judge) {
                    $message = "✅ *Sudya ma'lumotlari:*\n\n";
                    $message .= "*ФИШ:* {$judge['last_name']} {$judge['first_name']} {$judge['middle_name']}\n";
                    $message .= "*Tug‘ilgan sana:* {$judge['birth_date']}\n";
                    $message .= "*Yoshi:* {$judge['age']}\n";
                    $message .= "*Tug‘ilgan joyi:* {$judge['birth_place']}\n";
                    $message .= "*Lavozimi:* {$judge['position']}\n";
                    $message .= "*Reyting:* {$judge['rating']}\n";

                    $buttons = [
                        [['text' => '📥 Xizmat tekshiruv hujjatlari', 'callback_data' => 'pdfs_' . $judge['id']]],
                    ];

                    if (!empty($judge['photo_url'])) {
                        Http::post("https://api.telegram.org/bot{$token}/sendPhoto", [
                            'chat_id' => $chatId,
                            'photo' => $judge['photo_url'],
                            'caption' => $message,
                            'parse_mode' => 'Markdown',
                            'reply_markup' => json_encode(['inline_keyboard' => $buttons]),
                        ]);
                    } else {
                        Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                            'chat_id' => $chatId,
                            'text' => $message,
                            'parse_mode' => 'Markdown',
                            'reply_markup' => json_encode(['inline_keyboard' => $buttons]),
                        ]);
                    }
                }
            }

            cache()->put('telegram_update_offset', $offset);
            sleep(1); // doimiy pooling
        }
    }
}
