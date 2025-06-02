<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    // Define fillable attributes
    protected $fillable = [
        'judge_id',
        'service_inspection_score',
        'responsibility_score',
        'total_score'
    ];

    // Set default values when creating a new Rating
    public static function boot()
    {
        parent::boot();

        static::creating(function ($rating) {
            // Set default values if not set
            if ($rating->service_inspection_score === null) {
                $rating->service_inspection_score = 50; // Default Service Inspection Score
            }

            if ($rating->responsibility_score === null) {
                $rating->responsibility_score = 45; // Default Responsibility Score
            }

            $rating->total_score = $rating->service_inspection_score + $rating->responsibility_score;
        });

        static::saving(function ($rating) {

            $rating->total_score = $rating->service_inspection_score + $rating->responsibility_score;
        });
    }

    // Define relationship to the judge
    public function judges()
    {
        return $this->belongsTo(Judges::class);
    }
}
