<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourtSpeciality extends Model
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
    public function judges_stage(){
        return $this->hasMany(Judges_Stages::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
