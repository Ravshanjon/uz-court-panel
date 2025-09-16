<?php

namespace App\Filament\Imports;

use App\Models\Candidates_document;
use App\Models\Judges;
use App\Models\Regions;
use App\Models\StatusCandidates;
use App\Models\SupermeJudges;
use App\Models\Types;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class JudgesRegisterImporter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public $chunk, public $file) {

    }

    public function handle(): void
    {
        foreach ($this->chunk as $row) {
            $code = trim((string)($row['Судьянинг коди'] ?? $row[0] ?? ''));
            $regionName = trim((string)($row['Ҳудуд'] ?? $row[1] ?? ''));
            $fullName = trim((string)($row['ФИШ'] ?? $row[2] ?? ''));

            $birthYearRaw = $row['Туғилган йили'] ?? $row[3] ?? '';
            if ($birthYearRaw instanceof \DateTimeInterface) {
                $birthYear = $birthYearRaw->format('Y');
            } else {
                $birthYear = preg_replace('/\D/', '', trim((string)$birthYearRaw));
            }

            $judgesAnnouncementRaw = $row['Дастлаб судьяликка тайинланган йили'] ?? $row[4] ?? '';
            if ($judgesAnnouncementRaw instanceof \DateTimeInterface) {
                $judgesAnnouncement = $judgesAnnouncementRaw->format('Y');
            } else {
                $judgesAnnouncement = preg_replace('/\D/', '', trim((string)$judgesAnnouncementRaw));
            }

            $region = \App\Models\Regions::where('name', 'like', "%{$regionName}%")->first();
            logger('Importing row', [
                'code' => $code,
                'regionName' => $regionName,
                'fullName' => $fullName,
                'birthYearRaw' => $birthYearRaw,
                'birthYear' => $birthYear ?? 'NULL',
                'judgesAnnouncement' => $judgesAnnouncement ?? 'NULL',
                'regionFound' => $region ? $region->id : 'NOT FOUND',
            ]);
            if (!$region) continue;

            \App\Models\JudgesRegistry::create([
                'code'                => $code,
                'region_id'           => $region->id,
                'full_name'           => $fullName,
                'birth_day'           => is_numeric($birthYear) ? (int)$birthYear : null,
                'judges_announcement' => is_numeric($judgesAnnouncement) ? (int)$judgesAnnouncement : null,
            ]);
        }
    }
}
