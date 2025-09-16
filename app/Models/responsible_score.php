<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class responsible_score extends Model
{
    protected $fillable = ['judge_id', 'score'];

    protected static function booted()
    {
        static::creating(function ($responsibilityScore) {
            if (is_null($responsibilityScore->score)) {
                $responsibilityScore->score = 50;
            }
        });
    }

    // Relationship to Judge
    public function judges()
    {
        return $this->belongsTo(Judges::class);
    }
}
