<?php

namespace App\Models;

use App\Services\JudgeRatingCalculator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

class Appeal extends Model
{

    protected $fillable = [
        'judge_id',
        'region_id',
        'reasons_id',
        'group_id',
        'score',
        'instances_id',
        'establishment_id',
        'job_category_id',
        'court_specialty_id',
        'court_name_id',
        'court_type_id',
        'case_type',
        'type_of_decision_id',
        'sides',
        'content',
        'file',
        'appeal_date',
        'appeal_reason',
        'first_instance_decision',
        'cassation',
        'repeat_cassation',
    ];

    public function judges(): BelongsTo
    {
        return $this->belongsTo(Judges::class);
    }

    public function judges_stages(): BelongsTo
    {
        return $this->belongsTo(Judges_Stages::class);
    }

    public function courtName(): BelongsTo
    {
        return $this->belongsTo(CourtName::class);
    }

    public function courtSpecialty(): BelongsTo
    {
        return $this->belongsTo(CourtSpeciality::class, 'court_specialty_id');
    }

    public function courtType(): BelongsTo
    {
        return $this->belongsTo(CourtType::class);
    }

    public function typeOfDecision(): BelongsTo
    {
        return $this->belongsTo(TypeOfDecision::class, 'type_of_decision_id');
    }

    public function jobCategory()
    {
        return $this->belongsTo(JobCategory::class, 'job_category_id');
    }

    public function region()
    {
        return $this->belongsTo(Regions::class, 'region_id');
    }


    public function reason()
    {
        return $this->belongsTo(\App\Models\Reason::class, 'reasons_id');
    }

}
