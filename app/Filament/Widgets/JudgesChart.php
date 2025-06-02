<?php

namespace App\Filament\Widgets;

use App\Models\Judges;
use App\Models\Regions;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class JudgesChart extends ApexChartWidget
{

    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'judgesChart';
    protected static ?int $sort = 15;


    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Туғилган жойи бўйича маълумот';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        // Step 1: Get counts of judges grouped by birth_place
        $birthPlaceCounts = Judges::selectRaw('birth_place, COUNT(*) as count')
            ->groupBy('birth_place')
            ->pluck('count', 'birth_place');

        // Step 2: Get region names using region_id from Judges (not from birth_place)
        $regionIds = Judges::whereIn('birth_place', $birthPlaceCounts->keys())
            ->pluck('region_id', 'birth_place');

        // Step 3: Fetch region names for those region_ids
        $regionNames = Regions::whereIn('id', $regionIds->values())
            ->pluck('name', 'id');
        $judgesCount = Judges::selectRaw('birth_place, COUNT(*) as count')
            ->groupBy('birth_place')
            ->orderByDesc('count')
            ->limit(10)
            ->pluck('count', 'birth_place');

        // Step 4: Prepare x-axis labels using region_id linked to each birth_place
        $labels = $birthPlaceCounts->keys()->map(function ($birthPlaceId) use ($regionIds, $regionNames) {
            $regionId = $regionIds[$birthPlaceId] ?? null;
            return $regionNames[$regionId] ?? 'Бошқа';
        })->toArray();

        return [
            'chart' => [
                'type' => 'area',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'Туғилганлик жой бўйича', // "By Birthplace"
                    'data' => $birthPlaceCounts->values()->toArray(),
                ],
            ],
            'xaxis' => [
                'categories' => $labels,
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'colors' => ['#2563eb'],
            'stroke' => [
                'curve' => 'smooth',
            ],
            'dataLabels' => [
                'enabled' => false,
            ],
        ];
    }

}
