<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Duration extends Model
{
    protected $fillable = [
        'name'
    ];

    public function judges()
    {
        return $this->hasMany(Judges::class,'judge_id');
    }
    public function judges_stages()
    {
        return $this->belongsTo(Judges_Stages::class);
    }
}
