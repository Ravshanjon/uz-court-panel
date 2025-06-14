<?php

namespace App\Filament\Pages;

use App\Filament\Resources\JudgesResource\Pages\EditJudges;
use App\Models\Judges;
use Filament\Forms\Components\Select;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;

class ExpiringStagesPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $title = 'Муддати тугайдиган судьялар';


    protected static string $view = 'filament.pages.expiring-stages-page';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('first_name')
                    ->label('Ф.И.О')
                    ->formatStateUsing(function ($state, $record) {
                        return implode(' ', array_filter([
                            $record->last_name,
                            $record->first_name,
                            $record->middle_name,
                        ]));
                    })
                    ->url(fn($record) => route('filament.admin.resources.judges.edit', $record->getKey()))
                    ->openUrlInNewTab(false),
                TextColumn::make('establishment.position.name')->label('Иш жойи'),
                TextColumn::make('establishment.court_specialty.name')->label('Суд ихтисослиги'),
                TextColumn::make('judges_stages.end_date')
                    ->sortable()
                    ->date('d.m.Y')
                    ->label('Муддати тугайдиган сана'),

                TextColumn::make('remaining_days')
                    ->label('Қолган кунлар')
                    ->badge()
                    ->color(function ($record) {
                        $endDate = optional($record->judges_stages)->sortBy('end_date')->first()?->end_date;

                        if (!$endDate) return 'gray';

                        $end = \Carbon\Carbon::parse($endDate)->startOfDay();
                        $now = now()->startOfDay();

                        if ($end->isPast()) return 'danger';

                        $days = $now->diffInDays($end);
                        return $days <= 3 ? 'warning' : 'primary';
                    })
                    ->getStateUsing(function ($record) {
                        $endDate = optional($record->judges_stages)->sortBy('end_date')->first()?->end_date;

                        if (!$endDate) return 'Маълум эмас';

                        $end = \Carbon\Carbon::parse($endDate)->startOfDay();
                        $now = now()->startOfDay();

                        if ($end->isPast()) return 'Муддати тугаган';

                        $days = $now->diffInDays($end);
                        return match (true) {
                            $days === 0 => 'Бугун тугайди',
                            $days === 1 => '1 кун қолди',
                            default => "$days кун қолди",
                        };
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('judges_stages')
            ])
            ->query(function () {
                $query = Judges::query()
                    ->whereHas('judges_stages', function ($q) {
                        $q->whereBetween('end_date', [now(), now()->addMonth()]);
                    })
                    ->with(['judges_stages' => function ($q) {
                        $q->whereBetween('end_date', [now(), now()->addMonth()])
                            ->orderBy('end_date');
                    }]);

                if (auth()->user()?->hasRole('malaka')) {
                    $regionId = auth()->user()?->regions_id;

                    if ($regionId) {
                        $query->whereHas('establishment', function ($q) use ($regionId) {
                            $q->where('region_id', $regionId);
                        });
                    }
                }

                return $query;
            });
    }
}
