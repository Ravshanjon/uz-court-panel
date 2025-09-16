<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OcrText extends Model
{
    protected $fillable = [
        'judge_id',
        'source_pdf',
        'ocr_text',
        'pages',
        'page_texts',
    ];

    protected $casts = [
        'page_texts' => 'array',
        'pages' => 'array',
    ];

    public function judges()
    {
        return $this->belongsTo(Judges::class);
    }
    public function getViolatedArticles(): array
    {
        $text = $this->ocr_text ?? '';
        $found = [];
        $moddalar = [8, 24, 74, 20];

        foreach ($moddalar as $modda) {
            if (Str::contains($text, "{$modda}-модда")) {
                $found[] = "{$modda}-модда";
            }
        }
        return $found;
    }
    public function getViolationDescriptions(): array
    {
        $text = $this->ocr_text ?? '';

        $rules = [
            '8-модда' => 'Суд ҳужжатларини ижро этиш шартлари бузилган',
            '24-модда' => 'Ижро этишга оид талаблар бузилган',
            '74-модда' => 'Судья интизомий жавобгарликка тортиладиган ҳолат',
            '20-модда' => 'Иш юритиш тартиби бузилган',
        ];

        $found = [];

        foreach ($rules as $modda => $desc) {
            if (Str::contains($text, $modda)) {
                $found[] = "{$modda} — {$desc}";
            }
        }

        return $found;
    }
}
