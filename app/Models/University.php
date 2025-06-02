<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class University extends Model
{
    protected $table = 'universities';
    protected $fillable = ['name'];

    public function judges()
    {
        return $this->belongsTo(Judges::class);
    }
}
