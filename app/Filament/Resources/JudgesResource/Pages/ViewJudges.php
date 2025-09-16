<?php

namespace App\Filament\Resources\JudgesResource\Pages;

use App\Filament\Resources\JudgesResource;
use App\Models\Judges;
use Filament\Resources\Pages\ViewRecord;

class ViewJudges extends ViewRecord
{
    protected static string $resource = JudgesResource::class;
    public function getTitle(): string
    {
        return '';
    }

//    public function render(): \Illuminate\Contracts\View\View
//    {
//        $judge = Judges::with('serviceinspection')->findOrFail($this->record->id);
//
//        return view('components.full-rating', [
//            'qualityScore' => $judge->quality_score ?? 0,
//            'ethicsScore' => $judge->ethics_score ?? 0,
//            'extraPoints' => $judge->extra_points ?? 0, // bo‘lmasa 0 qilinadi
//            'totalRating' => $judge->rating ?? ($judge->quality_score + $judge->ethics_score + $judge->extra_points ?? 0),
//            'judge' => $judge,
//        ]);
//
//    }


}
