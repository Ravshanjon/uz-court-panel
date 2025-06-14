<?php

namespace App\Filament\Widgets;

use App\Models\Judges;
use Filament\Widgets\Widget;

class JudgeAwardChart extends Widget
{
    protected static string $view = 'filament.widgets.judge-award-chart';
    protected static ?string $heading = 'Судьялар бўйича мукофотлар статистикаси';

    protected function getData(): array
    {
        $topJudges = Judges::withCount('private_awards')
            ->orderByDesc('private_awards_count')
            ->limit(10)
            ->get();

        return [
            'labels' => $topJudges->pluck('last_name'),
            'datasets' => [
                [
                    'label' => 'Мукофотлар сони',
                    'data' => $topJudges->pluck('private_awards_count'),
                ],
            ],
        ];
    }
}
