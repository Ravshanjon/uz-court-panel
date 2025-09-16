<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistrictType extends Model
{
    protected $fillable = ['name'];

    public function judges()
    {
        return $this->hasMany(Judges::class);
    }

    public function establishments()
    {
        return $this->hasMany(Establishment::class);
    }

}
