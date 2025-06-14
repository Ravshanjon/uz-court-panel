<?php
namespace App\Filament\Imports;

use App\Models\Judges;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class JudgesImporter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public $chunk, public $file)
    {
    }

    public function handle(): void
    {
        foreach ($this->chunk as $row) {
            try {
                $gender = match(trim($row['gender'] ?? '')) {
                    'Эркак' => 1,
                    'Аёл' => 0,
                    default => null,
                };

                $nationalityMapping = [
                    'Ўзбек' => 1,
                    'Қорақалпоқ' => 2,
                    'Татар' => 3,
                    'Тожик' => 4,
                    'Қозоқ' => 5,
                    'Туркман' => 6,
                    'Бошқа' => 7,
                    'Қирғиз' => 8,
                    'Рус' => 8,
                ];
                $nationalityId = $nationalityMapping[$row['nationality_id'] ?? ''] ?? null;

                $regionMapping = [
                    'Фарғона вилояти' => 3,
                    'Тошкент вилояти' => 14,
                    'Тошкент шаҳри' => 13,
                    'Самарқанд вилояти' => 10,
                    'Қашқадарё вилояти' => 8,
                    'Сурхондарё вилояти' => 12,
                    'Жиззах вилояти' => 4,
                    'Андижон вилояти' => 1,
                    'Бухоро вилояти' => 2,
                    'Қорақалпоғистон Республикаси' => 9,
                    'Навоий вилояти' => 7,
                    'Сирдарё вилояти' => 11,
                    'Наманган вилояти' => 6,
                    'Хоразм вилояти' => 5,
                ];

                $birthplace = trim($row['birth_place'] ?? '');
                $regionId = $regionMapping[$birthplace] ?? null;

                Judges::create([
                    'id' => (string) Str::uuid(),
                    'pinfl' => $row['pinfl'] ?? null,
                    'birth_date' => isset($row['birth_date']) ? Carbon::parse($row['birth_date'])->format('Y-m-d') : null,
                    'last_name' => $row['last_name'] ?? null,
                    'middle_name' => $row['middle_name'] ?? null,
                    'first_name' => $row['first_name'] ?? null,
                    'codes' => $row['codes'] ?? null,
                    'nationality_id' => $nationalityId,
                    'university_id' => $row['university_id'] ?? null,
                    'passport_name' => $row['passport_name'] ?? null,
                    'birth_place' => $birthplace,
                    'region_id' => $regionId,
                    'gender' => $gender,
                    'special_education' => $row['special_education'] ?? null,
                ]);

            } catch (\Throwable $e) {
                Log::error('Sudya import xatoligi: ' . $e->getMessage(), [
                    'row' => $row,
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        }
    }
}

