<?php

namespace App\Filament\Imports;

use App\Models\Candidates_document;
use App\Models\Judges;
use App\Models\Regions;
use App\Models\StatusCandidates;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Carbon\Carbon;
use App\Models\SupermeJudges;
use App\Models\Types;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class CandidatesImporter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public $chunk, public $file) {}

    public function handle(): void
    {
        $typeMap = Types::all()->mapWithKeys(fn ($type) => [mb_strtolower(trim($type->name)) => $type->id]);
        $supermeJudgeMap = SupermeJudges::pluck('id', 'name')->toArray();
        $statusCandidateMap = StatusCandidates::all()->mapWithKeys(function ($item) {
            return [mb_strtolower(trim($item->name)) => $item->id];
        })->toArray();

        foreach ($this->chunk as $row) {
            try {
                $year = trim($row['Давр'] ?? null);
                $code = trim($row['Судья коди'] ?? null);
                $typeNameRaw = trim($row['Масала тоифаси'] ?? '');
                $typeId = $typeMap[mb_strtolower($typeNameRaw)] ?? null;

                $judge = null;
                $judgeId = null;

                if ($code) {
                    $judge = Judges::where('code', $code)->first();
                    $judgeId = $judge?->id;
                }

// Excel ustun nomini aniqlash (nomi har xil bo‘lishi mumkinligi uchun dinamik topiladi)
                $fullNameKey = collect(array_keys($row))->first(function ($key) {
                    $lower = mb_strtolower($key);
                    return str_contains($lower, 'фамилияси') &&
                        str_contains($lower, 'исми') &&
                        str_contains($lower, 'отасининг');
                });

                $excelFullName = trim($row[$fullNameKey] ?? '');

                $fullName = $judge
                    ? ($judge->last_name . ' ' . $judge->first_name . ' ' . $judge->middle_name)
                    : $excelFullName;


                $regionName = trim($row['Ҳудуд'] ?? '');
                $regionId = Regions::where('name', $regionName)->value('id')
                    ?? Regions::firstOrCreate(['name' => 'Олий суд'])->id;

                // Superme sudya
                $supermeName = trim($row['Кенгаш судьяси'] ?? '');
                $supermeId = $supermeJudgeMap[$supermeName] ?? null;

                // Holat (status_candidates_id)
                $statusName = mb_strtolower(trim($row['Ҳолати'] ?? ''));
                $statusId = $statusCandidateMap[$statusName] ?? null;

                if (!$statusId && $statusName !== '') {
                    Log::warning("⚠️ Ҳолати топилмади: [$statusName], default ID qo‘yilmadi.");
                }

                // Sana (renewed_date)
                $rawDate = $row['Ҳужжат келган сана'] ?? null;
                $renewedDate = null;

                try {
                    if (is_numeric($rawDate)) {
                        // Exceldagi date bo‘lsa (masalan: 44841)
                        $renewedDate = Date::excelToDateTimeObject($rawDate)->format('Y-m-d');
                    } elseif ($rawDate instanceof \DateTimeInterface) {
                        // DateTime object bo‘lsa
                        $renewedDate = $rawDate->format('Y-m-d');
                    } elseif (is_string($rawDate)) {
                        // String ko‘rinishida bo‘lsa, masalan "15.10.2022"
                        $renewedDate = \Carbon\Carbon::createFromFormat('d.m.Y', $rawDate)->format('Y-m-d');
                    }
                } catch (\Exception $e) {
                    $renewedDate = null;
                }

                // Yaratish
                Candidates_document::create([
                    'year' => is_numeric($year) ? (int)$year : null,
                    'code' => $code ?: null,
                    'judge_id' => $judgeId,
                    'full_name' => $fullName,
                    'region_id' => $regionId,
                    'type_id' => $typeId,
                    'appointment_info' => 'Вақтинчалик маълумот',
                    'superme_judges_id' => $supermeId,
                    'status_candidates_id' => $statusId,
                    'renewed_date' => $renewedDate,
                ]);

            } catch (\Throwable $e) {
                Log::error('❌ Candidates import xatoligi: ' . $e->getMessage(), [
                    'row' => $row,
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        }
    }
}
