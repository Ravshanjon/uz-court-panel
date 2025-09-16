<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelatedJudgeMatch extends Model
{
    protected $fillable = [
        'judge_a_id',
        'judge_b_id',
        'relation_type',
        'relative_name',
        'match_percent',
    ];

    public function judgeA()
    {
        return $this->belongsTo(Judges::class, 'judge_a_id');
    }

    public function judgeB()
    {
        return $this->belongsTo(Judges::class, 'judge_b_id');
    }
}
