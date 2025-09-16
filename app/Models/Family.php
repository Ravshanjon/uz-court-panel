<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    protected static function booted()
    {
        static::saving(function ($family) {
            $family->name = self::normalizeFullName($family->name);
        });
    }

    public static function normalizeFullName($name): string
    {
        // 1. Ortiqcha bo‘sh joylarni tozalaymiz
        $name = trim(preg_replace('/\s+/', ' ', $name));

        // 2. Katta harf bilan boshlanuvchi joylarda probel qo‘shamiz
        $name = preg_replace('/(?<=\p{Cyrillic})(?=\p{Lu}\p{Ll})/u', ' ', $name);

        return $name;
    }

    protected $fillable = [
        'judge_id',
        'parents_id',
        'name',
        'birth_date',
        'birth_place',
        'working_place',
        'live_place',
        'is_deceased',
        'relation',
        'death_note',
        'marriage_annulled',
        'annulment_note',
    ];

    public function judge()
    {
        return $this->belongsTo(Judges::class);
    }
    public function parents()
    {
        return $this->belongsTo( Parents::class,'parents_id');
    }
}
