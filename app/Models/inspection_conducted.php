<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class inspection_conducted extends Model
{
    protected $table = 'inspection_conducted';
    protected $fillable = ['name'];

    public function judges()
    {
        return $this->hasMany(Judges::class);
    }
}
