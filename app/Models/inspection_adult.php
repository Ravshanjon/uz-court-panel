<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class inspection_adult extends Model
{
    protected $table = 'inspection_adults';
    protected $fillable = ['name'];

    public function judges()
    {
        return $this->hasMany(Judges::class);
    }
}
