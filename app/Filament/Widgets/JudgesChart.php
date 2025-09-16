<?php

namespace App\Filament\Widgets;

use App\Models\Judges;
use Illuminate\Support\Str;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class JudgesChart extends ApexChartWidget
{
    protected static ?string $chartId = 'judgesChart';
    protected static ?int $sort = 15;
    protected static ?string $heading = 'Вилоятлар бўйича туғилганлик маълумоти';

    protected function getOptions(): array
    {
        // JOIN orqali viloyat nomlarini olish
        $rows = Judges::query()
            ->join('regions', 'regions.id', '=', 'judges.region_id')
            ->selectRaw('regions.name as region, COUNT(*) as cnt')
            ->groupBy('regions.id', 'regions.name')
            ->orderByDesc('cnt')
            ->limit(20) // xohlasa cheklovni o‘zgartir
            ->get();

        if ($rows->isEmpty()) {
            return [
                'chart' => ['type' => 'bar', 'height' => 300],
                'series' => [['name' => 'Жами', 'data' => [0]]],
                'xaxis' => ['categories' => ['Маълумот йўқ']],
                'dataLabels' => ['enabled' => false],
            ];
        }

        // Labels = viloyat nomlari
        $labels = $rows->pluck('region')->toArray();
        $data   = $rows->pluck('cnt')->map(fn ($v) => (int) $v)->toArray();

        return [
            'chart' => [
                'type' => 'bar', // ko‘p labelda bar yaxshi
                'height' => 350,
            ],
            'series' => [
                [
                    'name' => 'Судьялар сони',
                    'data' => $data,
                ],
            ],
            'xaxis' => [
                'categories' => $labels,
                'labels' => [
                    'rotate' => -15,
                    'style' => ['fontFamily' => 'inherit'],
                ],
            ],
            'yaxis' => [
                'labels' => ['style' => ['fontFamily' => 'inherit']],
            ],
            'dataLabels' => ['enabled' => false],
            'grid' => ['strokeDashArray' => 4],
            'stroke' => ['show' => true, 'width' => 2],
            'colors' => ['#2563eb'],
        ];
    }
    public static function canView(): bool
    {
        $user = auth()->user();

        // malaka bo‘lsa ko‘rinmasin
        return !($user && $user->getRoleNames()->contains(
                fn ($r) => Str::lower($r) === 'malaka'
            ));
    }
}
