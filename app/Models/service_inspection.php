<?php

namespace App\Models;

use App\Services\JudgeRatingCalculator;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;


class service_inspection extends Model
{
    protected $casts = [
        'inspection_qualification_dates' => 'date',
        'study_started_at'               => 'datetime',
        'study_finished_at'              => 'datetime',
    ];
    protected $fillable =
        [
            'judge_fullname_snapshot',
            'judge_region_snapshot',
            'judge_workplace_snapshot',
            'mistakes_id',
            'report_qualification_judgement',
            'date_case',
            'judge_id',
            'region_id',
            'file',
            'date_referred',
            'inspection_conducted_id',
            'inspection_adults_id',
            'inspection_offices_id',
            'inspection_cases_id',
            'inspection_regulations_id',
            'inspection_qualification_dates',
            'under_study',
            'study_started_at',
            'study_finished_at',
        ];


    protected static function booted(): void
    {
        static::saving(function ($inspection) {
            if ($inspection->isDirty('prision_type_id') && $inspection->prision_type_id && $inspection->judge_id) {
                $new = \App\Models\Prision_Type::find($inspection->prision_type_id);
                $oldId = $inspection->getOriginal('prision_type_id');
                $old = $oldId ? \App\Models\Prision_Type::find($oldId) : null;

                $judge = \App\Models\Judges::find($inspection->judge_id);
                if ($judge) {
                    $oldScore = $old?->score ?? 0;
                    $newScore = $new?->score ?? 0;

                    $judge->ethics_score = max(0, $judge->ethics_score + $oldScore - $newScore);
                    $judge->rating = $judge->ethics_score;
                    $judge->save();
                    $inspection->score_applied = true;

                }
            }
            JudgeRatingCalculator::calculate();
        });

        static::deleted(function ($inspection) {
            if ($inspection->score_applied && $inspection->prision_type_id && $inspection->judge_id) {
                $prision = \App\Models\Prision_Type::find($inspection->prision_type_id);
                $judge = \App\Models\Judges::find($inspection->judge_id);

                if ($prision && $judge) {
                    $judge->ethics_score += $prision->score;
                    $judge->rating = $judge->ethics_score;
                    $judge->save();
                }

            }
            JudgeRatingCalculator::calculate();
        });
    }

    public function judges()
    {
        return $this->belongsTo(Judges::class, 'judge_id');
    }

    public function inspectionConducted()
    {
        return $this->belongsTo(inspection_conducted::class, 'inspection_conducted_id');
    }

    public function inspectionAdult()
    {
        return $this->belongsTo(inspection_adult::class, 'inspection_adults_id');
    }

    public function inspectionOffice()
    {
        return $this->belongsTo(inspection_office::class, 'inspection_offices_id');
    }

    public function inspectionCase()
    {
        return $this->belongsTo(inspection_cases::class, 'inspection_cases_id');
    }

    public function inspectionRegulation()
    {
        return $this->belongsTo(inspection_regulation::class, 'inspection_regulations_id');
    }

    public function inspectionQualificationDate()
    {
        return $this->belongsTo(inspection_qualification_date::class);
    }

    public function region()
    {
        return $this->belongsTo(Regions::class);
    }

    public function mistake()
    {
        return $this->belongsTo(\App\Models\Mistake::class);
    }

    public function prision_type()
    {
        return $this->belongsTo(Prision_Type::class, 'prision_type_id');
    }
    public function getRemainingDaysAttribute(): string
    {
        if (!$this->date_case) {
            return '—';
        }

        $startDate = Carbon::parse($this->date_case);
        $endDate = $startDate->copy()->addMonths(6);
        $remaining = intval(now()->diffInDays($endDate, false));

        if ($remaining < 0) {
            return 'Муддат тугаган';
        }
        return $remaining . ' кун қолди';
    }
}
