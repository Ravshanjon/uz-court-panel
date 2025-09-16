<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourtType extends Model
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
    public function appeal()
    {
        return $this->belongsTo(Appeal::class);
    }


}
