<?php

namespace App\Console\Commands;

use App\Models\Judges;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class TelegramBotPollCommand extends Command
{
    protected $signature = 'telegram:poll {--once}';
    protected $description = 'Telegram long polling';

    public function handle()
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        if (!$token) {
            $this->error('TELEGRAM_BOT_TOKEN yo‘q');
            return self::FAILURE;
        }

        $baseUrl = rtrim(config('app.url'), '/');
        $offset  = 0;

        do {
            $resp = Http::timeout(65)->get("https://api.telegram.org/bot{$token}/getUpdates", [
                'timeout' => 60,
                'offset'  => $offset,
                'limit'   => 50,
            ]);

            if (!$resp->successful()) {
                $this->error('getUpdates xato: '.$resp->status());
                if ($this->option('once')) break;
                sleep(2);
                continue;
            }

            $updates = $resp->json('result') ?? [];

            foreach ($updates as $u) {
                $offset = $u['update_id'] + 1;

                /* ---------- callback_query ---------- */
                if (isset($u['callback_query'])) {
                    $chatId = $u['callback_query']['message']['chat']['id'] ?? null;
                    $data   = $u['callback_query']['data'] ?? '';
                    if (!$chatId) continue;

                    if (str_starts_with($data, 'pdfs_')) {
                        $judgeId = substr($data, 5);
                        $judge   = Judges::with('serviceinspection')->find($judgeId);

                        if (!$judge) {
                            Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                                'chat_id' => $chatId,
                                'text'    => '❌ Sudya topilmadi.',
                            ]);
                            continue;
                        }

                        $pdfs = $judge->serviceinspection->filter(fn($s) => !empty($s->file))->values();
                        if ($pdfs->isEmpty()) {
                            Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                                'chat_id' => $chatId,
                                'text'    => '❌ Hujjat topilmadi.',
                            ]);
                            continue;
                        }

                        $text = "📄 *Xizmat tekshiruv hujjatlari:*\n\n";
                        foreach ($pdfs as $pdf) {
                            $url  = "{$baseUrl}/storage/attachments/{$pdf->file}";
                            $text .= "🔗 [Yuklab olish]({$url})\n";
                        }

                        Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                            'chat_id' => $chatId,
                            'text'    => $text,
                            'parse_mode' => 'Markdown',
                            'disable_web_page_preview' => true,
                        ]);
                    }

                    continue;
                }

                /* ---------- oddiy xabar ---------- */
                $text   = $u['message']['text'] ?? null;
                $chatId = $u['message']['chat']['id'] ?? null;
                if (!$text || !$chatId) continue;

                $raw = trim(ltrim($text, '/'));
                if ($raw === '') continue;

                // Normalizatsiya (ё→е, g‘/ғ→g, apostroflarni olib tashlash va h.k.)
                $norm = $this->normalize(mb_strtolower($raw));
                $parts = preg_split('/\s+/', $norm);
                $n1 = $parts[0] ?? '';
                $n2 = $parts[1] ?? '';

                // Avval lokal DB’dan qidiramiz: middle_name + first_name
                $query = Judges::query()
                    ->when(count($parts) >= 2, function ($q) use ($n1, $n2) {
                        // 1) middle=n1 AND first=n2
                        // 2) middle=n2 AND first=n1
                        $q->where(function ($qq) use ($n1, $n2) {
                            $qq->whereRaw('LOWER(REPLACE(REPLACE(REPLACE(middle_name, \'ё\', \'е\'), \'‘\', \'\'), \'’\', \'\')) LIKE ?', ["%{$n1}%"])
                                ->whereRaw('LOWER(REPLACE(REPLACE(REPLACE(first_name,  \'ё\', \'е\'), \'‘\', \'\'), \'’\', \'\')) LIKE ?', ["%{$n2}%"]);
                        })->orWhere(function ($qq) use ($n1, $n2) {
                            $qq->whereRaw('LOWER(REPLACE(REPLACE(REPLACE(middle_name, \'ё\', \'е\'), \'‘\', \'\'), \'’\', \'\')) LIKE ?', ["%{$n2}%"])
                                ->whereRaw('LOWER(REPLACE(REPLACE(REPLACE(first_name,  \'ё\', \'е\'), \'‘\', \'\'), \'’\', \'\')) LIKE ?', ["%{$n1}%"]);
                        });
                    })
                    ->when(count($parts) === 1, function ($q) use ($n1) {
                        $q->where(function ($qq) use ($n1) {
                            $qq->whereRaw('LOWER(REPLACE(REPLACE(REPLACE(first_name,  \'ё\', \'е\'), \'‘\', \'\'), \'’\', \'\')) LIKE ?', ["%{$n1}%"])
                                ->orWhereRaw('LOWER(REPLACE(REPLACE(REPLACE(middle_name, \'ё\', \'е\'), \'‘\', \'\'), \'’\', \'\')) LIKE ?', ["%{$n1}%"]);
                        });
                    })
                    ->limit(20);

                $judges = $query->get();

                // Agar DB bo‘yicha topilmasa — API fallback
                if ($judges->isEmpty()) {
                    $res = Http::get("{$baseUrl}/api/judges/" . urlencode($raw));
                    if (!$res->successful() || empty($res->json())) {
                        Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                            'chat_id' => $chatId,
                            'text'    => "❌ Sudya topilmadi: {$raw}",
                        ]);
                        continue;
                    }
                    $found = collect($res->json());
                } else {
                    // DB natijalarini API’ga o‘xshash ko‘rinishga keltiramiz
                    $found = $judges->map(function ($j) {
                        return [
                            'id'          => $j->id,
                            'last_name'   => $j->last_name,
                            'first_name'  => $j->first_name,
                            'middle_name' => $j->middle_name,
                            'birth_date'  => $j->birth_date,
                            'birth_place' => $j->birth_place,
                            'position'    => $j->position ?? ($j->workplace->position ?? null),
                            'rating'      => $j->rating ?? null,
                            'image'   => $j->photo_url ?? null,
                        ];
                    });
                }

                foreach ($found as $j) {
                    // --- Ismlar (fallback camelCase -> snake_case)
                    $lastName   = $j['last_name']   ?? ($j['lastName']   ?? '—');
                    $firstName  = $j['first_name']  ?? ($j['firstName']  ?? '—');
                    $middleName = $j['middle_name'] ?? ($j['middleName'] ?? '—');

                    // --- Tug‘ilgan sana / joy
                    $birthDate  = $j['birth_date']  ?? ($j['birthDate']  ?? null);
                    $birthPlace = $j['birth_place'] ?? ($j['birthPlace'] ?? '—');

                    // --- Lavozim (bir nechta ehtimoliy kalit)
                    $position =
                        $j['position'] ??
                        $j['position_name'] ??
                        ($j['workplace']['position'] ?? null) ??
                        ($j['job']['title'] ?? null) ??
                        ($j['position_title'] ?? null) ??
                        '—';

                    // --- Yosh (agar API bermasa, birth_date dan hisoblaymiz)
                    $age = $j['age'] ?? ($birthDate ? optional(Carbon::parse($birthDate))->age : null);
                    $ageText = $age !== null ? $age : '—';

                    // --- Reyting
                    $rating = $j['rating'] ?? ($j['quality_score'] ?? '—');

                    $msg  = "*Ф.И.Ш:* {$middleName} {$firstName} {$lastName}\n";
                    $msg .= "*Туғилган жойи:* {$birthPlace}\n";
                    $msg .= "*Туғилган санаси:* " . ($birthDate ?: '—') . "\n";
                    $msg .= "*Ёши:* {$ageText}\n";
                    $msg .= "*Лавозими:* {$position}\n";
                    $msg .= "*Рейтинги:* {$rating}\n";

                    $buttons = [
                        [['text' => '📥 Хизмат текшируви', 'callback_data' => 'pdfs_' . ($j['id'] ?? '')]],
                    ];

                    if (!empty($j['photo_url'] ?? null)) {
                        Http::post("https://api.telegram.org/bot{$token}/sendPhoto", [
                            'chat_id' => $chatId,
                            'photo'   => $j['photo_url'],
                            'caption' => $msg,
                            'parse_mode'   => 'Markdown',
                            'reply_markup' => json_encode(['inline_keyboard' => $buttons]),
                        ]);
                    } else {
                        Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                            'chat_id' => $chatId,
                            'text'    => $msg,
                            'parse_mode'   => 'Markdown',
                            'reply_markup' => json_encode(['inline_keyboard' => $buttons]),
                        ]);
                    }
                }
            }

            if ($this->option('once')) break;
        } while (true);

        return self::SUCCESS;
    }

    /**
     * Kiruvchi matnni izlash uchun normalizatsiya:
     * - ё -> е
     * - ғ/g‘/g' -> g ; қ -> k (istak bo‘lsa)
     * - turli apostroflarni olib tashlash
     */
    private function normalize(string $s): string
    {
        $map = [
            'ё' => 'е',
            'ғ' => 'g', 'g‘' => 'g', "g'" => 'g', 'g`' => 'g', 'gʼ' => 'g',
            'ў' => 'o', 'o‘' => 'o', "o'" => 'o', 'o`' => 'o', 'oʼ' => 'o',
            'қ' => 'k',
            'ҳ' => 'h',
            'ʼ' => '', '‘' => '', '’' => '', '`' => '\'', // turli apostrof ko‘rinishlari
        ];
        $s = strtr($s, $map);
        // Qo‘shimcha: ortiqcha bo‘shliqni 1 taga tushirish
        $s = preg_replace('/\s+/', ' ', $s);
        return trim($s);
    }
}
