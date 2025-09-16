<?php

namespace App\Models;

use Database\Seeders\CourtSpecialty;
use Illuminate\Database\Eloquent\Model;

class Candidates_document extends Model
{
    protected $fillable = [
        'year',
        'type_id',
        'judge_id',
        'region_id',
        'superme_judges_id',
        'status_candidates_id',
        'court_specialty_id',
        'position',
        'code',
        'number',
        'full_name',
        'appointment_info',
        'start_date',
        'end_date',
        'renewed_date',
        'term_type',
        'court_type',
        'judge_level',
        'suitability',
        'decision_date',
        'transferred_to',
        'inspector_name',
        'discussion_status',
        'final_date',
        'final_result',
        'final_region',
        'final_position',
        'term_length',
        'final_court_type',
        'final_approval_date',
        'document_number',
        'is_sent',
        'sent_by',
        'sent_at',
    ];

    public function region()
    {
        return $this->belongsTo(Regions::class, 'region_id');
    }

    public function types()
    {
        return $this->belongsTo(Types::class, 'type_id');
    }

    public function judges()
    {
        return $this->belongsTo(Judges::class, 'judge_id');
    }

    public function establishment()
    {
        return $this->belongsTo(Establishment::class, 'establishment_id');
    }

    public function court_specialty()
    {
        return $this->belongsTo(CourtSpeciality::class, 'court_specialty_id');
    }
    public function superme_judges()
    {
        return $this->belongsTo(SupermeJudges::class, 'superme_judges_id');
    }
    public function status_candidates()
    {
        return $this->belongsTo(StatusCandidates::class, 'status_candidates_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
