<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BonusJudges extends Model
{
    protected $table = 'bonus_judge';
    protected $fillable = [
        'bonus_id',
        'judge_id',
        'text',
        'file'
    ];

    public function bonus()
    {
        return $this->belongsTo(Bonus::class, 'bonus_id');
    }

    public function judges()
    {
        return $this->belongsTo(Judges::class, 'judge_id');
    }
}
