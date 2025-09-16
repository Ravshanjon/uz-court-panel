<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RatingSetting extends Model
{
    protected $fillable = [
        'quality_score',
        'etiquette_score',
        'adding_rating',
        'ethics_score',
        'foreign_language_bonus',
    ];
}
