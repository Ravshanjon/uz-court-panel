<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    protected $fillable = [
        'judge_id',
        'father_name',
        'father_brith_date',
        'father_lives_place',
        'mother_name',
        'mother_brith_date',
        'father_live_place',
        'wife_name',
        'wife_brith_date',
        'wife_live_place',
        'kids_name',
    ];

    public function judges()
    {
        return $this->belongsToMany(Judges::class);
    }
}
