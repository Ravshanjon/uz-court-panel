<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PositionCategories extends Model
{
    protected $fillable = [
        'name',
    ];

    public function judges()
    {
        return $this->belongsTo(Judges::class);
    }
    public function establishments()
    {
        return $this->belongsTo(Establishment::class);
    }
    public function users(){
        return $this->belongsTo(User::class);
    }
}
