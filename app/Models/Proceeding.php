<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Proceeding extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
       'id', 'judge_id', 'type', 'parent_id', 'root_first_id', 'issued_at', 'meta'
    ];
    protected $casts = ['meta' => 'array', 'issued_at' => 'datetime'];

    public function judges()
    {
        return $this->belongsTo(Judges::class, 'judge_id');
    }

    public function parent()
    {
        return $this->belongsTo(Proceeding::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Proceeding::class, 'parent_id');
    }

    public function panel()
    {
        return $this->hasMany(ProceedingJudge::class);
    }

    public function scores()
    {
        return $this->hasMany(ProceedingScore::class);
    }

    public function applied()
    {
        return $this->hasMany(AppliedPenalty::class);
    }

    // helper: is first?
    public function isFirst(): bool
    {
        return $this->type === 'first';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (! $model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }
}
