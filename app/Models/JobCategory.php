<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobCategory extends Model
{
    protected $fillable = ['name'];

    public function typeOfDecisions()
    {
        return $this->hasMany(TypeOfDecision::class);
    }
}
