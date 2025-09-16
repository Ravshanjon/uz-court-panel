<?php

namespace App\Filament\Resources;

use App\Filament\Imports\CandidatesImporter;
use App\Filament\Resources\CandidatesResource\Pages;
use App\Filament\Resources\CandidatesResource\RelationManagers;
use App\Models\Candidates_document;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Spatie\SimpleExcel\SimpleExcelReader;

class CandidatesResource extends Resource
{
    protected static ?string $model = Candidates_document::class;
    protected static ?string $pluralModelLabel = 'Номзодлар-хужжати';
    protected static ?string $navigationGroup = 'Номзодлар-хужжати ';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return Candidates_document::where('term_type', 'sessiyada')
            ->where('sent_by', auth()->id())
            ->count() ?: null;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Hidden::make('judge_id'),
                Forms\Components\Card::make()->schema([
                    Forms\Components\Grid::make(3)->schema([

                        TextInput::make('year')
                            ->numeric()
                            ->label('Йил')
                            ->default(now()->year),

                        Forms\Components\Select::make('type')
                            ->label('Масала тоифаси')
                            ->relationship('types', 'name'),

                        Forms\Components\Select::make('region_id')
                            ->relationship('region', 'name') // 'region' emas, 'regions' deb yozmang
                            ->label('Ҳудуд')
                            ->searchable()
                            ->preload()
                            ->placeholder('Ҳудудни танланг'),

                        TextInput::make('judge.codes')
                            ->label('Судья коди')
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                if (!$state || $get('judge_id')) return;

                                $judge = \App\Models\Judges::with(
                                    'establishment.position',
                                    'judges_stages'
                                )
                                    ->where('codes', $state)
                                    ->first();

                                if ($judge) {
                                    $set('judge_id', $judge->id);
                                    $set('court_type', $judge->court_type);
                                    $set('first_name', $judge->first_name);
                                    $set('last_name', $judge->last_name);
                                    $set('birth_date', $judge->birth_date);
                                    $latestStage = $judge->judges_stages->sortByDesc('start_date')->first();

                                    $set('start_date', $latestStage->start_date);
                                    $set('end_date', $latestStage->end_date);

                                    try {
                                        $birth = \Carbon\Carbon::parse($judge->birth_date);
                                        $set('retirement_date', $birth->addYears(65)->format('Y-m-d'));

                                        // ✅ Yoshni hisoblash
                                        $age = $birth->age; // yoki diffInYears(now())
                                        $set('age', intval($age));
                                    } catch (\Exception $e) {
                                        $set('retirement_date', null);
                                        $set('age', 0);
                                    }

                                    if ($judge->establishment?->position?->name) {
                                        $set('position_name', $judge->establishment->position->name);
                                        $set('court_type', $judge->establishment->court_type->name);
                                    }

                                    $fullName = "{$judge->middle_name} {$judge->first_name} {$judge->last_name}";
                                    $set('full_name', $fullName);
                                } else {
                                    \Filament\Notifications\Notification::make()
                                        ->title("Судья топилмади")
                                        ->danger()
                                        ->send();
                                }
                            }),
                        TextInput::make('full_name')->label('ФИШ'),

                        TextInput::make('position_name')
                            ->label('Эгаллаб турган лавозими'),

                        TextInput::make('court_type')
                            ->label('Суд тури'),

                        DatePicker::make('start_date')->label('Бошланиш санаси'),
                        DatePicker::make('end_date')->label('Тугаш санаси'),

                        DatePicker::make('birth_date')
                            ->label('Туғилган сана')
                            ->displayFormat('d.m.Y')
                            ->format('Y-m-d')
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    try {
                                        $birth = \Carbon\Carbon::parse($state);
                                        $retirementDate = $birth->copy()->addYears(65)->startOfDay();

                                        $set('retirement_date', $retirementDate->format('Y-m-d'));
                                    } catch (\Exception $e) {
                                        $set('retirement_date', null);
                                    }
                                } else {
                                    $set('retirement_date', null);
                                }
                            }),

//                        Select::make('court_specialty_id')
//                            ->relationship('court_specialty', 'name')
//                            ->label('Тавсия этилган суд ихтисослиги')
//                            ->preload()
//                            ->searchable()
//                            ->reactive(),

                        Select::make('superme_judges_id')
                            ->relationship('superme_judges', 'name')
                            ->label('Кенгаш судьяси'),

                        Select::make('status_candidates_id')
                            ->relationship('status_candidates', 'name')
                            ->label('Ҳолати'),

                        DatePicker::make('retirement_date')
                            ->label('65 ёшга тўладиган санаси')
                            ->displayFormat('d.m.Y')
                            ->format('Y-m-d')
                            ->readOnly() // faqat ko‘rsatish uchun
                            ->dehydrated(true), // bazaga saqlash uchun, kerak bo‘lsa

                        TextInput::make('number')->numeric()->label('Рақам'),

                        TextInput::make('appointment_info')->label('Тавсия этилган лавозими'),

                        DatePicker::make('renewed_date')
                            ->label('Келиб тушган сана')
                            ->displayFormat('d.m.Y')
                            ->native(false)
                            ->suffixIcon('heroicon-o-calendar')
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    try {
                                        $today = Carbon::today();
                                        $selected = Carbon::parse($state);

                                        $days = $today->diffInDays($selected, false); // true: absolute; false: with sign

                                        if ($days > 0) {
                                            $set('days_count', "{$days} кун қолди");
                                        } elseif ($days < 0) {
                                            $set('days_count', abs($days) . ' кун аввал');
                                        } else {
                                            $set('days_count', "Бугун");
                                        }

                                    } catch (\Exception $e) {
                                        $set('days_count', 'Хатолик');
                                    }
                                } else {
                                    $set('days_count', null);
                                }
                            }),

                        TextInput::make('term_type')->label('Муддат ҳолати'),
                        TextInput::make('court_type')->label('Суд тури'),
                        TextInput::make('judge_level')->label('Судья даражаси'),
                        TextInput::make('suitability')->label('Муносиблик'),
                        DatePicker::make('decision_date')->label('Қарор санаси'),
                        TextInput::make('transferred_to')->label('Кимга ўтган'),
                        TextInput::make('inspector_name')->label('Инспектор'),
                        TextInput::make('discussion_status')->label('Муҳокама ҳолати'),

                        DatePicker::make('final_date')->label('Якун санаси'),
                        TextInput::make('final_result')->label('Хулоса'),
                        TextInput::make('final_region')->label('Якун вилоят'),

                        Textarea::make('final_position')->label('Якун лавозим'),

                        TextInput::make('term_length')->label('Муддат (йил)'),
                        TextInput::make('final_court_type')->label('Якун суд тури'),
                        DatePicker::make('final_approval_date')->label('Тасдиқ санаси'),
                        TextInput::make('document_number')->label('Ҳужжат рақами'),
                    ])
                ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginated([100])
            ->columns([
                TextColumn::make('index')->label('№')->rowIndex(),
                TextColumn::make('year')
                    ->label(new HtmlString('<span class="text-xs">Давр</span>'))
                    ->html()
                    ->getStateUsing(fn($record) => '<span class="text-xs">' . e($record->year) . '</span>'),

                TextColumn::make('types.name')
                    ->label('')
                    ->wrap(10)
                    ->label(new HtmlString('<span class="text-xs">Масала <br> тоифаси</span>'))
                    ->html()
                    ->getStateUsing(fn($record) => '<span class="text-xs">' . e($record->types?->name) . '</span>')
                    ->searchable(),

                TextColumn::make('region.name')
                    ->label(new HtmlString('<span class="text-xs">Ҳудуд</span>'))
                    ->wrap(10)
                    ->html()
                    ->getStateUsing(fn($record) => '<span class="text-xs">' . e($record->region?->name) . '</span>')
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('code')
                    ->label(new HtmlString('<span class="text-xs">Судья<br>коди</span>'))
                    ->html()
                    ->getStateUsing(fn($record) => '<span class="text-xs">' . e($record->code) . '</span>')
                    ->searchable(),

                TextColumn::make('full_name')
                    ->label(new HtmlString('<span class="text-xs">Фамилияси, исми ва<br>отасининг исми </span>'))
                    ->html()
                    ->getStateUsing(fn($record) => '<span class="text-xs">' . e($record->full_name) . '</span>')
                    ->description(function ($record) {
                        $count = \App\Models\Candidates_document::where('full_name', $record->full_name)->count();
                        return new HtmlString(
                            "<span style='color: #ea9373; font-weight: normal; font-size: 12px;'>Ҳужжатлар сони: {$count}</span>"
                        );
                    }),

                TextColumn::make('start_date')
                    ->label(new HtmlString('<span class="text-xs">Судьялик <br> ваколати</span>'))
                    ->formatStateUsing(function ($record) {
                        try {
                            $start = \Carbon\Carbon::parse($record->start_date)->format('d.m.Y');
                            $end = \Carbon\Carbon::parse($record->end_date)->format('d.m.Y');
                            return "<span class='text-xs'>$start</span><br><span class='text-xs'>$end</span>";
                        } catch (\Exception $e) {
                            return null;
                        }
                    })
                    ->html(),
                TextColumn::make('end_date')
                    ->label(new HtmlString('<span class="text-xs">Ваколат <br> тугаш санаси</span>'))
                    ->formatStateUsing(fn($state) => "<span class='text-xs'>" . \Carbon\Carbon::parse($state)->format('d.m.Y') . "</span>")
                    ->html(),
                TextColumn::make('appointment_info')
                    ->label(new HtmlString('<span class="text-xs">Тавсия <br> этилган лавозими</span>'))
                    ->html()
                    ->formatStateUsing(fn($state) => "<span class='text-xs'>" . e($state) . "</span>"),


                TextColumn::make('superme_judges.name')
                    ->html()
                    ->label(new HtmlString('<span class="text-xs">Кенгаш <br>судьяси</span>'))
                    ->formatStateUsing(fn($state) => "<span class='text-xs'>" . e($state) . "</span>"),

                TextColumn::make('status_candidates.name')
                    ->wrap(10)
                    ->label(new HtmlString('<span class="text-xs">Ҳолати</span>'))
                    ->html()
                    ->formatStateUsing(fn($state) => "<span class='text-xs'>" . $state . "</span>"),

                TextColumn::make('renewed_date')
                    ->label(new HtmlString('<span class="text-xs">Ҳужжат <br> келган </br> сана</span>'))
                    ->html()
                    ->formatStateUsing(fn($state) => $state
                        ? "<span class='text-xs'>" . Carbon::parse($state)->format('d.m.Y') . "</span>"
                        : ''
                    ),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('year')
                    ->label('Давр'),

                Tables\Filters\SelectFilter::make('type_id')
                    ->label('Масала тоифаси')
                    ->relationship('types', 'name'),

                Tables\Filters\SelectFilter::make('status_candidates_id')
                    ->label('Ҳолати')
                    ->relationship('status_candidates', 'name'),

            ], layout: Tables\Enums\FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\EditAction::make()
            ])
            ->headerActions([
                Tables\Actions\Action::make('Import Applications')
                    ->label('Импорт')
                    ->visible(fn() => auth()->user()?->hasRole('super_admin'))
                    ->color('warning')
                    ->icon('heroicon-o-arrow-down-on-square-stack')
                    ->form([
                        FileUpload::make('xlsxFile')
                            ->label('XLSX File')
                            ->required()
                            ->directory('imports')
                            ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', '.xlsx']),
                    ])
                    ->action(function (array $data) {
                        $relativePath = $data['xlsxFile'];
                        $fullPath = storage_path("app/public/{$relativePath}"); // Note "public" here

                        // Check if the file exists
                        if (!file_exists($fullPath)) {
                            Notification::make()
                                ->title("File not found: {$fullPath}")
                                ->danger()
                                ->send();
                            return;
                        }


                        try {
                            $rows = SimpleExcelReader::create($fullPath)->getRows()->toArray();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title("Error reading the file: {$e->getMessage()}")
                                ->danger()
                                ->send();
                            return;
                        }

                        $chunks = array_chunk($rows, 500);
                        $totalChunks = count($chunks);

                        foreach ($chunks as $index => $chunk) {
                            try {
                                CandidatesImporter::dispatch($chunk, $relativePath);
                                $chunkIndex = $index + 1; // Human-readable chunk number
                                Notification::make()
                                    ->title("Chunk " . $chunkIndex . " of " . $totalChunks . " import started.") // Concatenate using .
                                    ->success()
                                    ->send();
                            } catch (\Exception $e) {
                                Notification::make()
                                    ->title("Error dispatching chunk " . ($index + 1) . ": " . $e->getMessage()) // Concatenate using .
                                    ->danger()
                                    ->send();
                            }
                        }

                        Notification::make()
                            ->title('Import Process Started')
                            ->success()
                            ->send();
                    })
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ])
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCandidates::route('/'),
            'create' => Pages\CreateCandidates::route('/create'),
            'edit' => Pages\EditCandidates::route('/{record}/edit'),
        ];
    }

    public static function canEdit(Model $record): bool
    {
        return !auth()->user()?->hasRole('malaka');
    }

    public static function canDelete(Model $record): bool
    {
        return !auth()->user()?->hasRole('malaka');
    }
}
