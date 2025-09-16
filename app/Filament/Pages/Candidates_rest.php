<?php

namespace App\Filament\Pages;

use App\Models\Candidates_document;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\View;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use http\Env\Request;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Carbon;
use Spatie\Browsershot\Browsershot;

class Candidates_rest extends Page implements HasTable
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Номзодлар-хужжати ';
    protected static ?string $navigationLabel = 'Қолдиқ';
    protected static ?int $navigationSort = 2;
    protected static string $view = 'filament.pages.candidates_rest';
    use InteractsWithTable;

    public function table(Table $table): Table
    {

        return $table
            ->query(fn () =>

            Candidates_document::query()
                ->with(['region', 'superme_judges', 'judges.judges_stages'])
                ->whereHas('status_candidates', fn($q) =>
                $q->where('name', 'Ўрганишда')
                )

            )
                ->columns([
                    Tables\Columns\TextColumn::make('index')
                        ->label('№')
                        ->rowIndex(),

                    Tables\Columns\TextColumn::make('region.name')->label('Ҳудуд')
                        ->html()
                        ->formatStateUsing(function ($state, $record) {
                            return "<span class='text-xs font-black font-normal'>{$state}</span>";
                        }),
                    Tables\Columns\TextColumn::make('full_name')
                        ->label('Ф.И.Ш.')
                        ->searchable()
                        ->html()
                        ->wrap(10)
                        ->formatStateUsing(function ($state, $record) {
                            return "<span class='text-xs font-black font-normal'>{$state}</span>";
                        }),
                    TextColumn::make('judges.judges_stages.position.name')
                        ->formatStateUsing(function ($state, $record) {
                            return "<span class='text-xs font-black font-normal'>{$state}</span>";
                        })
                        ->html()
                        ->wrap(10)
                        ->label('Эгаллаб турган лавозими'),

                    Tables\Columns\TextColumn::make('appointment_info')
                        ->label('Тавсия этилган лавозими')
                        ->formatStateUsing(function ($state, $record) {
                            return "<span class='text-xs font-black font-normal'>{$state}</span>";
                        })
                        ->html()
                        ->wrap(10)
                        ->searchable(),
//                Tables\Columns\TextColumn::make('types.name')
//                    ->formatStateUsing(function ($state, $record) {
//                        return "<span class='text-xs font-black font-normal'>{$state}</span>";
//                    })
//                    ->html()
//                    ->wrap(10)
//                    ->label('Масала тоифаси')
//                    ->searchable(),
                    Tables\Columns\TextColumn::make('renewed_date')
                        ->label("Ҳужжат\nкелган сана") // yoki shunchaki 'Ҳужжат келган сана'
                        ->extraAttributes(['class' => 'text-xs'])
                        ->html()
                        ->formatStateUsing(function ($state) {
                            if (!$state) {
                                return '—';
                            }
                            return "<span class='text-xs font-black font-normal'>" . Carbon::parse($state)->format('d.m.Y') . "</span>";
                        }),


                    Tables\Columns\TextColumn::make('judges.judges_stages')
                        ->label('Ваколати тугайдиган сана')
                        ->html()
                        ->formatStateUsing(function ($state, $record) {
                            $lastStage = $record->judges?->judges_stages?->sortByDesc('start_date')->first();

                            $end = $lastStage && $lastStage->end_date
                                ? \Carbon\Carbon::parse($lastStage->end_date)->format('d.m.Y')
                                : '—';

                            return "<span class='text-xs font-black font-normal'>{$end}</span>";
                        }),

                    Tables\Columns\TextColumn::make('conclusion')->label('ОМҲ хулосаси')
                        ->formatStateUsing(function ($state, $record) {
                            return "<span class='text-xs font-black font-normal'>{$state}</span>";
                        })
                        ->html(),

                    Tables\Columns\TextColumn::make('superme_judges.name')->label('Ижрочи')
                        ->formatStateUsing(function ($state, $record) {
                            return "<span class='text-xs font-black font-normal'>{$state}</span>";
                        })
                        ->html(),
                    Tables\Columns\TextColumn::make('term_type')
                        ->label("Ўрганиш муддати")
                        ->html()
                        ->formatStateUsing(function ($state, $record) {
                            $date = $record->renewed_date; // yoki $record->end_date

                            if (!$date) {
                                return $state ?? '—';
                            }

                            try {
                                $today = Carbon::today();
                                $selected = Carbon::parse($date);
                                $days = $today->diffInDays($selected, false);

                                $text = $state ? "<div class='font-normal'>{$state}</div>" : '';

                                if ($days > 0) {
                                    $text .= "<div class='text-green-600 text-xs'>{$days} кун қолди</div>";
                                } elseif ($days < 0) {
                                    $text .= "<div class='text-red-600 text-xs'>" . abs($days) . " кун аввал</div>";
                                } else {
                                    $text .= "<div class='text-blue-600 text-xs'>Бугун</div>";
                                }

                                return $text;
                            } catch (\Exception $e) {
                                return $state ?? '—';
                            }
                        })
                        ->extraAttributes(['class' => 'text-xs']),
                ])
                ->headerActions([
                    Tables\Actions\Action::make('download')
                        ->label('Юклаб олиш')
                        ->outlined()
                        ->size('sm')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('primary')
                        ->action(fn() => $this->downloadPdf()),
                ])
                ->filters([
                    SelectFilter::make('region_id')->label('ҳудуд')
                        ->relationship('region', 'name'),
                    SelectFilter::make('superme_judges_id')
                        ->label('Ижрочи')
                        ->relationship('superme_judges', 'name'),
                ], layout: Tables\Enums\FiltersLayout::AboveContent);

    }

    public function downloadPdf()
    {
        // Filament tablening joriy filtrlangan so‘rovini olish
        $candidates = $this->getFilteredTableQuery()->get();

        $html = view('exports.candidates_pdf', compact('candidates'))->render();
        $filename = 'qoldiq_' . now()->format('Ymd_His') . '.pdf';

        Browsershot::html($html)
            ->format('A4')
            ->landscape()
            ->margins(10, 10, 10, 10)
            ->showBackground()
            ->waitUntilNetworkIdle()
            ->save(storage_path("app/public/{$filename}"));

        return response()->download(storage_path("app/public/{$filename}"));
    }

    public static function canAccess(): bool
    {
        return !auth()->user()?->hasRole('malaka');
    }
}
