<?php

namespace App\Filament\Pages;

use App\Filament\Resources\JudgesResource\Pages\EditJudges;
use App\Models\Candidates_document;
use App\Models\Judges;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
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
                TextColumn::make('establishment.position.name')->label('Иш жойи')->wrap(30),
                TextColumn::make('establishment.court_specialty.name')->label('Суд ихтисослиги'),
                TextColumn::make('judges_stages.end_date')
                    ->sortable()
                    ->date('d.m.Y')
                    ->label('Муддати тугайдиган сана'),

                TextColumn::make('remaining_days')
                    ->label('Қолган кунлар')
                    ->badge()
                    ->color(function ($record) {
                        $endDate = $record->judges_stages()
                            ->whereNotNull('end_date')
                            ->orderBy('end_date')
                            ->first()?->end_date;

                        if (!$endDate) return 'gray';

                        $end = \Carbon\Carbon::parse($endDate)->startOfDay();
                        $today = now()->startOfDay();

                        if ($end->lt($today)) return 'danger';

                        $days = $today->diffInDays($end);
                        return $days <= 3 ? 'warning' : 'primary';
                    })
                    ->getStateUsing(function ($record) {
                        $endDate = $record->judges_stages()
                            ->whereNotNull('end_date')
                            ->orderBy('end_date')
                            ->first()?->end_date;

                        if (!$endDate) return 'Маълум эмас';

                        $end = \Carbon\Carbon::parse($endDate)->startOfDay();
                        $today = now()->startOfDay();

                        if ($end->lt($today)) return 'Муддати тугаган';

                        $days = $today->diffInDays($end);

                        return match (true) {
                            $days === 0 => 'Бугун тугайди',
                            $days === 1 => '1 кун қолди',
                            default => "$days кун қолди",
                        };
                    })
            ])
            ->actions([
                Action::make('send')
                    ->label('Юбориш')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->label(fn($record) => \App\Models\Candidates_document::where('judge_id', $record->id)
                        ->where('is_sent', true)
                        ->exists() ? 'Юборилган' : 'Юбориш'
                    )
                    ->icon('heroicon-o-paper-airplane')
                    ->color(fn($record) => \App\Models\Candidates_document::where('judge_id', $record->id)
                        ->where('is_sent', true)
                        ->exists() ? 'gray' : 'success'
                    )
                    ->disabled(fn($record) => \App\Models\Candidates_document::where('judge_id', $record->id)
                        ->where('is_sent', true)
                        ->exists()
                    )
                    ->form(function ($record) {
                        return [
                            Grid::make(3)->schema([
//                               TextInput::make('codes')
//                                   ->label('Судья коди')
//                                   ->default($record->codes)
//                                   ->disabled(),

//                                Select::make('type_id')
//                                    ->label('Масала тоифаси')
//                                    ->relationship('types', 'name') // 'types' is the relationship method name
//                                    ->searchable()
//                                    ->required(),

                                TextInput::make('full_name')
                                    ->label('Ф.И.Ш')
                                    ->default($record->last_name . ' ' . $record->first_name . ' ' . $record->middle_name)
                                    ->disabled()
                                    ->columnSpan(1),

                                TextInput::make('lavozim')
                                    ->label('Ҳозирги лавозими')
                                    ->default(fn($record) => optional(
                                        $record->judges_stages()
                                            ->where(function ($query) {
                                                $query->whereNull('end_date')
                                                    ->orWhere('end_date', '>', now());
                                            })
                                            ->latest('start_date')
                                            ->first()
                                    )->working_place ?? 'Маълумот йўқ')
                                    ->disabled()->columnSpan(2),

                                TextInput::make('vakolat_tugash_sanasi')
                                    ->label('Ваколат муддати тугайдиган сана')
                                    ->default(fn($record) => optional($record->judges_stages()->latest('end_date')->first())->end_date
                                        ? Carbon::parse($record->judges_stages()->latest('end_date')->first()->end_date)->format('d.m.Y')
                                        : 'Маълумот йўқ'
                                    )
                                    ->disabled()
                                    ->columnSpan(1),


                                Select::make('regions_id')
                                    ->relationship('region', 'name')
                                    ->label('Ҳудуд'),



                                TextInput::make('appointment_info')
                                    ->label('Тавсия этилган лавозими')
                                    ->default($record->appointment_info ?? '')
                                    ->required(),

                                Select::make('court_specialty_id')
                                    ->label('Тавсия этилган суд ихтисослиги')
                                    ->relationship('court_specialty', 'name')
                                    ->default(fn($record) => $record->court_specialty_id)
                                    ->required(),
                            ])
                        ];
                    })
                    ->requiresConfirmation()
                    ->action(function (array $data, $record) {
                        // Sudya allaqachon yuborilganmi?
                        $alreadySent = Candidates_document::where('judge_id', $record->id)
                            ->where('is_sent', true)
                            ->exists();

                        if ($alreadySent) {
                            Notification::make()
                                ->title('❗Аллақачон юборилган!')
                                ->danger()
                                ->send();
                            return;
                        }

                        Candidates_document::create([
                            'judge_id' => $record->id,
                            'appointment_info' => $data['appointment_info'],
                            'full_name' => $record->last_name . ' ' . $record->first_name . ' ' . $record->middle_name,
                            'code' => $record->code,
                            'region_id' => $record->region_id,
                            'position' => optional($record->establishment->position)->name,
                            'start_date' => now(),
                            'is_sent' => true,
                            'sent_by' => auth()->id(),
                            'sent_at' => now(),
                        ]);

                        Notification::make()
                            ->title('✅ Юборилди!')
                            ->success()
                            ->send();

                    })->modalWidth('4xl')
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('judges_stages')
            ])
            ->query(function () {
                $startDate = now();
                $endDate = now()->addMonth();

                $query = Judges::query()
                    ->whereHas('judges_stages', fn($q) =>
                    $q->whereBetween('end_date', [$startDate, $endDate])
                    )
                    ->with(['judges_stages' => fn($q) =>
                    $q->whereBetween('end_date', [$startDate, $endDate])
                        ->orderBy('end_date')
                    ]);

                $user = auth()->user();

                if ($user?->hasRole('malaka') && $user->regions_id) {
                    $query->whereHas('establishment', fn($q) =>
                    $q->where('region_id', $user->regions_id)
                    );
                }

                return $query;
            });
    }

}
