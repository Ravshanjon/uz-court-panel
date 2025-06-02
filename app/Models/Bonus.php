<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
    protected $table = 'bonuses';

    protected $fillable =
        [
            'judge_id',
            'name',
            'score'
    ];

    public function judge()
    {
        return $this->belongsToMany(Judges::class, 'bonus_judge', 'bonus_id', 'judge_id');
    }
}
