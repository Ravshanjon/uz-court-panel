<?php

namespace App\Filament\Widgets;

use App\Models\Establishment;
use App\Models\Judges;
use App\Models\Regions;
use Carbon\Carbon;
use Filament\Forms\Components\Grid;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class JudgesOverview extends BaseWidget
{
    protected static ?int $sort = 10;
    protected function getColumns(): int
    {
        return 4;
    }
    protected function getStats(): array
    {
        $user = auth()->user();
        $isMalaka = $user->hasRole('Malaka');
        $regionId = $user->regions_id;

        // Default query
        $judgesQuery = Judges::query();

        if ($isMalaka) {
            $regionName = Regions::find($regionId)?->name;

            if ($regionName) {
                // Tug‘ilgan joyi region nomi ichida bor sudyalarni olish
                $judgesQuery->where('birth_place', 'like', '%' . $regionName . '%');
            }
        }

        $judgesCount = $judgesQuery->count();
        $femaleJudgesCount = (clone $judgesQuery)->where('gender', 0)->count();

        $establishmentCount = $isMalaka
            ? Establishment::where('region_id', $regionId)->count()
            : Establishment::count();

        return [
            Stat::make('Умумий штатлар сони', $establishmentCount)
                ->description('Штатлар сони')
                ->descriptionIcon('heroicon-m-building-office'),

            Stat::make('Туғилган жойи шу ҳудуддаги судьялар сони', $judgesCount)
                ->description('birth_place бўйича')
                ->descriptionIcon('heroicon-m-map'),

            Stat::make('Аёл судьялар сони', $femaleJudgesCount)
                ->description('Фақат туғилган жойи бўйича')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
        ];
    }


}
