<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parents extends Model
{
    protected $fillable = ['name'];

    public function family()
    {
        return $this->hasMany(Family::class);
    }
}
