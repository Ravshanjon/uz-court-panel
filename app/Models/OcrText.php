<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
