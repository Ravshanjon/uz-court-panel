<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LegalExperience extends Model
{
    protected $table = 'legal_stage';

    protected $fillable = [
        'is_judge_stage',
        'judge_id',
        'working_place',
        'start_date',
        'end_date',
        'counter',
    ];

    public function judges()
    {
        return $this->belongsTo(Judges::class,'judge_id',  'id');
    }
}
