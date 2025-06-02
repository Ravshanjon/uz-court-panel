<?php

namespace App\Filament\Widgets;

use App\Models\Establishment;
use App\Models\Judges;
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
        $oldestJudge = Judges::whereNotNull('birth_date')
            ->orderBy('birth_date', 'asc')
            ->first();

        return [
            Stat::make('Umumiy shtatlar soni',Establishment::count())
                ->description('32k increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),

            Stat::make('Ishlab turgan sudyalar soni', Judges::count())
                ->description('32k increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),

            Stat::make('Ayol sudyalar soni', Judges::where('gender', 0)->count())
                ->description('3% increase') // You can calculate this dynamically too
                ->descriptionIcon('heroicon-m-arrow-trending-up'),

        ];
    }
}
