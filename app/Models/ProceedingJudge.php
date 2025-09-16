<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProceedingJudge extends Model
{
    protected $fillable = ['proceeding_id','judge_id','role'];
    public function proceeding() { return $this->belongsTo(Proceeding::class); }
    public function judge()      { return $this->belongsTo(Judges::class); }
}
