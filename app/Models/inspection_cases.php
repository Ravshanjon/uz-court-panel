<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class inspection_cases extends Model
{
    protected $fillable = ['name'];
    public function judges()
    {
        return $this->hasMany(Judges::class);
    }
}
