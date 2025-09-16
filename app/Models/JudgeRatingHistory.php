<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JudgeRatingHistory extends Model
{
    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->recorded_at)) {
                $model->recorded_at = now()->toDateString();
            }
        });
    }

    protected $fillable = ['judge_id', 'rating', 'recorded_at'];

    public function judge()
    {
        return $this->belongsTo(Judges::class, 'judge_id');
    }

}
