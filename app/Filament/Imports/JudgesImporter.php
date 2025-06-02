<?php

namespace App\Filament\Imports;

use App\Models\Judges;
use App\Models\RatingSetting;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\SimpleExcel\SimpleExcelReader;

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
                $genderMapping = [
                    'Эркак' => 1,
                    'Аёл' => 0
                ];

                $gender = $genderMapping[$row['gender']] ?? null;

                $nationalityMapping = [
                    'Ўзбек' => 1,
                    'Қорақалпоқ' => 2,
                    'Татар' => 3,
                    'Тожик' => 4,
                    'Қозоқ' => 5,
                    'Туркман' => 6,
                    'Бошқа' => 7,
                    'Қирғиз' => 8,
                    'Рус' => 8
                ];

                $nationalityId = $nationalityMapping[$row['nationality_id']] ?? null;

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

                $birthplace = $row['birth_place'];
                $regionId = $regionMapping[$birthplace] ?? null;

                Judges::create([
                    'id' => (string)Str::uuid(),
                    'pinfl' => $row['pinfl'],
                    'birth_date' => isset($row['birth_date'])
                        ? Carbon::parse($row['birth_date'])->format('Y-m-d')
                        : null,
                    'last_name' => $row['last_name'],
                    'middle_name' => $row['middle_name'],
                    'first_name' => $row['first_name'],
                    'codes' => $row['codes'],
                    'nationality_id' => $nationalityId,
                    'university_id' => $row['university_id'],
                    'passport_name' => $row['passport_name'],
                    'birth_place' => $birthplace,
                    'region_id' => $regionId,
                    'gender' => $gender,
                    'special_education' => $row['special_education'],
                ]);

            } catch (\Throwable $e) {
                Log::error('Sudya import xatoligi: ' . $e->getMessage(), [
                    'row' => $row
                ]);
            }
        }
    }
}
