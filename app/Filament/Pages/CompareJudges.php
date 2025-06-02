<?php

namespace App\Filament\Pages;

use App\Models\Judges;
use Filament\Forms\Components\Select;
use Filament\Pages\Page;

class CompareJudges extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.compare-judges';
    protected static ?string $navigationLabel = 'Sudya solishtirish';

    public ?string $judgeA = null;
    public ?string $judgeB = null;

    // 🔹 Form schema (2 ta Select)
    protected function getFormSchema(): array
    {
        return [
            Select::make('judgeA')
                ->label('Birinchi sudya')
                ->options($this->getJudgesList())
                ->searchable()
                ->reactive(),

            Select::make('judgeB')
                ->label('Ikkinchi sudya')
                ->options(fn () => $this->getJudgesList()
                    ->filter(fn ($label, $id) => $id != $this->judgeA))
                ->searchable()
                ->disabled(fn () => !$this->judgeA),
        ];
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    protected function getJudgesList()
    {
        return Judges::all()->mapWithKeys(fn ($j) => [
            $j->id => "{$j->last_name} {$j->first_name} {$j->middle_name}"
        ]);
    }

    public function getJudgeAData()
    {
        return $this->judgeA ? Judges::find($this->judgeA) : null;
    }

    public function getJudgeBData()
    {
        return $this->judgeB ? Judges::find($this->judgeB) : null;
    }

    public function compareJudges()
    {
        $this->dispatch('comparison-ready');
    }

    // 🔹 Spider chart uchun property
    public function getSpiderChartDataProperty(): array
    {
        $judgeA = $this->getJudgeAData();
        $judgeB = $this->getJudgeBData();

        return [
            'labels' => [
                'Сифат',
                'Одоб',
                'Хизмат',
                'Тил',
                'Қўшимча',
                'Бошқа',
            ],
            'judgeA' => [
                $judgeA?->quality_score ?? 0,
                $judgeA?->etiquette_score ?? 0,
                $judgeA?->ethics_score ?? 0,
                $judgeA?->foreign_language_bonus ?? 0,
                $judgeA?->adding_rating ?? 0,
                $judgeA?->rating ?? 0,
            ],
            'judgeB' => [
                $judgeB?->quality_score ?? 0,
                $judgeB?->etiquette_score ?? 0,
                $judgeB?->ethics_score ?? 0,
                $judgeB?->foreign_language_bonus ?? 0,
                $judgeB?->adding_rating ?? 0,
                $judgeB?->rating ?? 0,
            ],
        ];
    }

    // 🔹 Foiz progress va g‘olib aniqlovchi property
    public function getComparisonStatsProperty(): array
    {
        $judgeA = $this->getJudgeAData();
        $judgeB = $this->getJudgeBData();

        if (!$judgeA || !$judgeB) {
            return [
                'judgeA' => ['percent' => 0],
                'judgeB' => ['percent' => 0],
                'winner' => null,
            ];
        }

        $scoreA = ($judgeA->quality_score ?? 0) +
            ($judgeA->etiquette_score ?? 0) +
            ($judgeA->ethics_score ?? 0) +
            ($judgeA->foreign_language_bonus ?? 0) +
            ($judgeA->adding_rating ?? 0);

        $scoreB = ($judgeB->quality_score ?? 0) +
            ($judgeB->etiquette_score ?? 0) +
            ($judgeB->ethics_score ?? 0) +
            ($judgeB->foreign_language_bonus ?? 0) +
            ($judgeB->adding_rating ?? 0);

        return [
            'judgeA' => ['percent' => round($scoreA)],
            'judgeB' => ['percent' => round($scoreB)],
            'winner' => $scoreA > $scoreB ? 'A' : ($scoreB > $scoreA ? 'B' : 'draw'),
        ];
    }
}
