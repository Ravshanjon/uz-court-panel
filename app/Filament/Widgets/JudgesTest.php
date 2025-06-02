<?php

namespace App\Filament\Widgets;

use App\Models\Judges;
use App\Models\National;
use App\Models\Nationality;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class JudgesTest extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'judgesTest';
    protected static ?int $sort = 15;


    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Миллати';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        $nationalityCounts = Judges::whereNotNull('nationality_id')
            ->selectRaw('nationality_id, COUNT(*) as count')
            ->groupBy('nationality_id')
            ->pluck('count', 'nationality_id')
            ->slice(0, 9);  // Limit to top 4 nationalities

        $labels = Nationality::whereIn('id', $nationalityCounts->keys())->pluck('name')->toArray();
        $data = $nationalityCounts->values()->toArray();

        return [
            'chart' => [
                'type' => 'radialBar',
                'height' => 300,


            ],
            'series' => $data,
            'plotOptions' => [
                'radialBar' => [
                    'hollow' => [
                        'size' => '20%',
                    ],
                    'dataLabels' => [
                        'show' => true,
                        'name' => [
                            'show' => true,
                        ],
                        'total' => [
                            'show' => true,
                            'label' => 'Умумий',
                            'fontSize' => '20px',
                            'color' => '#f59e0b',
                        ],
                        'value' => [
                            'show' => true,
                            'fontFamily' => 'inherit',
                            'fontWeight' => 600,
                            'fontSize' => '20px',
                        ],
                    ],
                ],
            ],
            'stroke' => [
                'lineCap' => '',
            ],
            'labels' => $labels,
            'colors' => ['#f59e0b', '#34d399', '#3b82f6', '#f87171'], // Adjust colors if necessary
        ];
    }
}
