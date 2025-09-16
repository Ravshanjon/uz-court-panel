<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditFirstData extends Model
{
    use HasFactory;

    protected $table = 'audit_first_data';

    protected $fillable = [
        'appeal_id','taftish_1_date','first_instance_reason_id','first_instance_score',
        'taftish_1_speaker_judge_id','taftish_1_presiding_judge_id','taftish_1_jury_judge_id',
        'penalty_speaker','penalty_presiding','penalty_jury','applied_at',
    ];

    protected $casts = [
        'taftish_1_date' => 'date',
        'applied_at' => 'datetime',
        'snap_a_score' => 'float',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Asosiy Appeal
    public function appeal()
    {
        return $this->belongsTo(Appeal::class);
    }

    public function firstReason()
    {
        return $this->belongsTo(Reason::class, 'first_instance_reason_id');
    }
}
