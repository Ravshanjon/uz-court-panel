<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TypeOfDecision extends Model
{
    protected $fillable = [
        'name',
        'job_category_id',
    ];

    public function judges()
    {
        return $this->hasMany(Judges::class);
    }

    public function reason()
    {
        return $this->hasMany(Reason::class);
    }
    public function jobCategory(): BelongsTo
    {
        return $this->belongsTo(JobCategory::class);
    }
}
