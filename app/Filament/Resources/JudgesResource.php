<?php

namespace App\Filament\Resources;

use App\Filament\Imports\JudgesImporter;
use App\Filament\Resources\JudgesResource\Pages;
use App\Filament\Resources\JudgesResource\RelationManagers\BonusRelationManager;
use App\Models\Establishment;
use App\Models\JudgeRatingHistory;
use App\Models\Judges_Stages;
use App\Models\RatingSetting;
use Carbon\Carbon;
use Closure;
use Filament\Forms\Components\Placeholder;
use Filament\Infolists;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Infolist;
use App\Models\Judges;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Navigation\NavigationItem;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\HtmlString;
use League\CommonMark\Extension\Table\TableSection;
use PrintFilament\Print\Infolists\Components\PrintComponent;
use Spatie\SimpleExcel\SimpleExcelReader;
use function Termwind\style;

class JudgesResource extends Resource
{

    protected static ?string $model = Judges::class;
    protected static ?string $pluralModelLabel = "Судьялар";
    protected static ?int $navigationSort = 1;

    protected static string $route = 'judges'; // Bu yerda resurs nomi bo'lishi kerak

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();

        $regionId = $user->regions_id;
        $courtTypeId = $user->judge?->establishment?->court_type_id;
        $positionCategoryId = $user->judge?->establishment?->position_category_id;
        $courtSpecialtyId = $user->judge?->establishment?->court_specialty_id;
        $typeOfUserId = $user->type_of_users_id;
        $judgeId = $user->judge_id;

        // HAR DOIM JOINLAR BO‘LSIN
        $query = parent::getEloquentQuery()

            ->leftJoin('establishments', 'judges.establishment_id', '=', 'establishments.id')
            ->select('judges.*', 'establishments.number_state as est_number')
            ->orderByRaw('COALESCE(establishments.number_state, 999999) ASC');

        if ($user->hasRole('malaka')) {
            return $query->where('establishments.region_id', $regionId);
        }
        // 👨‍⚖️ Faqat sudyalar uchun
        if ($user->hasRole('judges')) {
            $courtTypeId = $user->judge?->establishment?->court_type_id;
            $courtSpecialtyId = $user->judge?->establishment?->court_specialty_id;
            $positionCategoryId = $user->position_categories_id;
            $regionId = $user->regions_id;
//
            if (is_null($regionId) && $courtTypeId == 1 && $positionCategoryId === 1) {
                return $query
                    ->where('establishments.court_type_id', 1);
            }

            // ✅ OLIY SUD RAIS O‘RINBOSARI – yo‘nalish (Жиноят, Фуқаролик...) bo‘yicha filtrlaydi
            if (is_null($regionId) && $courtTypeId == 1 && $positionCategoryId === 3 && $courtSpecialtyId) {

                return $query
                    ->where('establishments.court_type_id', 1)
                    ->where('establishments.court_specialty_id', $courtSpecialtyId);
            }

            // Rais
            if ($positionCategoryId === 1 && $typeOfUserId == 2 && $regionId) {

                return $query
                    ->where('establishments.region_id', $regionId);
            }

            // Rais o‘rinbosari
            if ($positionCategoryId === 3 && $regionId) {
                $query = $query->where('establishments.region_id', $regionId);

                if ($courtSpecialtyId) {
                    $query = $query->where('establishments.court_specialty_id', $courtSpecialtyId);
                }

                return $query;
            }

            return $query->where('judges.id', $judgeId);
        }

        return $query;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Умумий маълумотлари')
                    ->tabs([
                        Tab::make('Судья маълумотлари')->schema([
                            Grid::make(4)->schema([
                                FileUpload::make('image')
                                    ->acceptedFileTypes(['application/pdf', 'image/png', 'image/jpeg'])
                                    ->extraAttributes(['class' => 'w-18'])
                                    ->columnSpan(1)
                                    ->label('Расм'),

                                TextInput::make('middle_name')
                                    ->dehydrated()
                                    ->label('Фамилияси'),

                                TextInput::make('first_name')
                                    ->dehydrated()
                                    ->label('Исми'),

                                TextInput::make('last_name')
                                    ->dehydrated()
                                    ->label('Отасининг исми'),

                                TextInput::make('codes')
                                    ->label('Code')
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(4)
                                    ->live()
                                    ->dehydrated()
                                    ->rule('digits:4'),

                                Forms\Components\TextInput::make('pinfl')
                                    ->label('ПИНФЛ')
                                    ->numeric()
                                    ->required()
                                    ->dehydrated()
                                    ->live()
                                    ->rule('digits:14')
                                    ->unique(ignoreRecord: true)
                                    ->maxWidth(14),

                                Select::make('birth_place')
                                    ->relationship('region', 'name')
                                    ->label('Туғилган жойи')
                                    ->preload()
                                    ->dehydrated()
                                    ->searchable(),

                                TextInput::make('passport_name')
                                    ->label('Паспорт бўйича Ф.И.Ш.')
                                    ->dehydrated()
                                    ->extraInputAttributes(['onChange' => 'this.value = this.value.toUpperCase()'])
                                    ->extraAttributes(['class' => 'uppercase']),

                                DatePicker::make('birth_date')
                                    ->label('Туғилган сана')
                                    ->format('Y-m-d')
                                    ->dehydrated()
                                    ->icon('heroicon-o-calendar')
                                    ->native(false)
                                    ->dehydrated() // yoki false qiling
                                    ->displayFormat('d.m.Y')
                                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                        if ($state) {
                                            try {
                                                $birthDate = Carbon::parse($state);
                                                $age = $birthDate->diffInYears(now());
                                                $set('age', intval($age));
                                            } catch (\Exception $e) {
                                                $set('age', 0);
                                            }
                                        }
                                    }),

                                TextInput::make('age')
                                    ->disabled()
                                    ->default(0)
                                    ->dehydrated(true),

                                TextInput::make('address')
                                    ->label('Адрес'),


                                //                            Select::make('threety_region_id')
                                //                                ->relationship('threetyRegion', 'name')
                                //                                ->label('Доимий яшаш жойи')
                                //                                ->preload()
                                //                                ->searchable(),

                                Select::make('gender')
                                    ->label('Жинси')
                                    ->options([
                                        1 => 'Эркак',
                                        2 => 'Аёл',
                                    ])
                                    ->required()
                                    ->dehydrated()
                                    ->default(true),

                                Select::make('nationality_id')
                                    ->label('Миллати')
                                    ->required()
                                    ->dehydrated()
                                    ->relationship('nationality', 'name'),

                                TextInput::make('legal_experience')
                                    ->dehydrated()
                                    ->label('Маълумоти'),

                                Select::make('university_id')
                                    ->searchable()
                                    ->required()
                                    ->preload()
                                    ->dehydrated()
                                    ->label('Битирган олийгохи')
                                    ->relationship('university', 'name'),


                                DatePicker::make('graduation_year')
                                    ->label('Қачон битирган (йили)')
                                    ->format('Y-m-d')
                                    ->native(false)
                                    ->dehydrated()
                                    ->displayFormat('d.m.Y'),

                                TextInput::make('special_education')
                                    ->dehydrated()
                                    ->label('Махсус ўқув юртлари'),

                                Select::make('leadership_experience')
                                    ->dehydrated()
                                    ->label('Лидерлик тажрибаси')
                                    ->options([
                                        1 => 'Ҳа',
                                        0 => 'Йўқ',
                                    ])
                                    ->default(0),


                                Select::make('leadership_reserve')
                                    ->label('Лидерлик захираси')
                                    ->dehydrated()
                                    ->options([
                                        1 => 'Ҳа',
                                        0 => 'Йўқ',
                                    ])
                                    ->default(0),


                            ]),
                        ])
                            ->icon('heroicon-o-user-circle')
                        ,
                        Tab::make('Меҳнат фаолияти')->schema([
                            Placeholder::make('judges-stages')
                                ->label('')
                                ->content(function ($record) {
                                    if (!$record || !$record->exists) {
                                        return new HtmlString('
                <div class="rounded-lg border bg-primary-500 border-red-700 text-white px-4 py-4 text-sm shadow-sm flex items-start space-x-3">
                    <div>
                        <div class="font-semibold text-base mb-1">Илтимос, аввал судьяни сақланг.</div>
                        <p>Бу бўлимни фаол қилиш учун, аввал <strong>“Судья маълумотлари”</strong> тўлдиринг ва сақланг.</p>
                    </div>
                </div>
            ');
                                    }

                                    return view('components.judges-stages-tab', ['record' => $record]);
                                }),

//                            Placeholder::make('judges-stages')
//                                ->content(fn($record) => view('components.judges-stages-tab', ['record' => $record])),

                            Placeholder::make('activity_heatmap')
                                ->label('')
                                ->content(fn($record) => view('components.judges-stages-chart', ['record' => $record]))

                        ])
                            ->icon('heroicon-o-square-3-stack-3d'),
                        Tab::make('Оилавий Ҳолати')->schema([

                        ])
                            ->icon('heroicon-o-identification'),

                        Tab::make('Рейтинг')->schema([
                            Tabs::make('Asosiy bo‘limlar')
                                ->tabs([
                                    Tab::make('Сифати')->schema([
                                        Placeholder::make('judges-stages')
                                            ->label('')
                                            ->content(fn($record) => view('components.appeal', ['record' => $record])),
                                    ])->icon('heroicon-o-book-open')
                                        ->visible(fn($record) => $record?->establisatehment?->position_cgory?->name !== 'Суд раиси')
                                        ->badge(function ($record) {

                                        })
                                        ->label('Суд қарорларининг сифати'),

                                    Tab::make('Хизмат текшируви')->schema([
                                        Placeholder::make('judges-stages')
                                            ->label('')
                                            ->content(fn($record) => view('components.service-inspection-tab', ['record' => $record]))
                                    ])->badge(function ($record) {


                                    })
                                        ->visible(fn($record) => $record?->establishment?->position_category?->name !== 'Суд раиси')
                                        ->icon('heroicon-o-fire'),
                                    Tab::make('Чет тили')->schema([

                                    ])->icon('heroicon-o-language'),

                                    Tab::make('Қўшимча баллар')->schema([
                                        Placeholder::make('bonus')
                                            ->label('')
                                            ->content(fn($record) => view('components.bonus-inspection-tab', ['record' => $record])),
//
                                    ])->icon('heroicon-o-plus'),

                                    Tab::make('Хусусий ажрим')->schema([
                                        Placeholder::make('private_awards')
                                            ->label('')
                                            ->content(fn($record) => view('components.private_awards', ['record' => $record])),
//
                                    ])->icon('heroicon-o-clipboard-document'),

                                ])
                        ])->icon('heroicon-o-chart-pie'),
                    ])->columnSpan(['lg' => 2])
            ]);
    }

    public static function table(Table $table): Table
    {
        $user = auth()->user();
        $filters = [];

        if ($user->hasRole('judges')) {
            $judge = Judges::where('pinfl', $user->pinfl)->first();

            if ($judge && $judge->position_category_id === 3) {
                $filters[] = Tables\Filters\SelectFilter::make('gender')
                    ->label('Жинс бўйича фильтр')
                    ->options([
                        0 => 'Аёл',
                        1 => 'Эркак',
                    ]);
            }
        }
        return $table
            ->paginated([25])
            ->columns([
                TextColumn::make('establishment.number_state')
                    ->label('№'),
                TextColumn::make('codes')
                    ->label('ID')
                    ->badge()
                    ->searchable(),

//                ImageColumn::make('image')
//                    ->circular()
//                    ->label('Расм')
//                    ->alignment('center')
//                    ->getStateUsing(function ($record) {
//                        return $record->image
//
//                            ? asset('storage/' . $record->image)
//                            : asset('image/default.jpg'); // default avatar yo‘li
//                    }),

                TextColumn::make('middle_name')->label('Фамилияси')->searchable(),
                TextColumn::make('first_name')->label('Исми')->searchable(),
                TextColumn::make('last_name')->label('Отасининг исми')->searchable(),

                TextColumn::make('faol_ish_joyi')
                    ->wrap(10)
                    ->label('Лавозими')
                    ->getStateUsing(function ($record) {
                        $stage = $record->judges_stages()
                            ->where(function ($query) {
                                $query->whereNull('end_date')
                                    ->orWhere('end_date', '>', now());
                            })
                            ->latest('start_date')
                            ->first();

                        if (!$stage) {
                            return '—';
                        }

                        $start = \Carbon\Carbon::parse($stage->start_date)->format('d.m.Y');
                        $end = $stage->end_date
                            ? \Carbon\Carbon::parse($stage->end_date)->format('d.m.Y')
                            : 'ҳозиргача';

                        return "{$stage->working_place}";
                    }),

                Tables\Columns\TextColumn::make('birth_date')
                    ->label('Туғилган сана')
                    ->alignCenter('center')
                    ->searchable()
                    ->sortable()
                    ->getStateUsing(function ($record) {
                        $birthDate = $record->birth_date ? \Carbon\Carbon::parse($record->birth_date)->format('d.m.Y') : '';
                        $regionName = $record->region ? $record->region->name : '';
                        return "{$birthDate} <br> {$regionName}";
                    })
                    ->html()
                    ->extraAttributes(['class' => 'text-center'])  // Center the text horizontally
                    ->columnSpanFull(),

                TextColumn::make('rating')
                    ->label('Рейтинг')
                    ->icon('heroicon-o-chart-bar')
                    ->sortable(),

//                TextColumn::make('judges_stages.counter')
//                    ->label('Судьялик стажи')

            ])
            ->filters([
                SelectFilter::make('by_rating')
                    ->label('Рейтинг бўйича')
                    ->options([
                        'best' => 'Энг яхши кўрсатгич',
                        'worst' => 'Энг ёмон кўрсатгич',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $value = $data['value'] ?? null;

                        return match ($value) {
                            'best' => $query->where('rating', '>=', 95),
                            'worst' => $query->where('rating', '<=', 56),
                            default => $query,
                        };
                    }),
                Tables\Filters\SelectFilter::make('regions_id')
                    ->relationship('region', 'name')
                    ->label('Туғилган жойи бўйича')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->placeholder('Ҳудудни танланг'),


                Tables\Filters\SelectFilter::make('nationality_id')
                    ->relationship('nationality', 'name')
                    ->label('Миллати')
                    ->placeholder('Миллатни танланг'),

                Tables\Filters\SelectFilter::make('university_id')
                    ->relationship('university', 'name')
                    ->label('Тамомлаган олийгоҳи')
                    ->searchable()
                    ->preload()
                    ->placeholder('Тамомлаган олийгоҳи'),

                Tables\Filters\SelectFilter::make('position_id')
                    ->label('Лавозим')
                    ->relationship('position', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->placeholder('Лавозимни танланг'),

                SelectFilter::make('establishment.region_id')
                    ->columnSpan(2)
                    ->relationship('establishment.region', 'name')
                    ->label('Ҳудуд бўйича (жойлашув)')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->placeholder('Ҳудудни танланг'),

            ], layout: Tables\Enums\FiltersLayout::AboveContent)->searchable()
            ->actions([
                Action::make('download')
                    ->label('')
                    ->icon('heroicon-o-arrow-down-on-square-stack')
                    ->color('danger')
                    ->url(fn($record) => route('judges.download-pdf', $record)) // Dynamic route
                    ->openUrlInNewTab(),
                Action::make('view_cv')
                    ->label('Объективка ')
                    ->icon('heroicon-o-paper-clip')
                    ->color('primary')
                    ->modalContent(function ($record) {
                        $judges_stages = DB::table('judges_stages')
                            ->where('judge_id', $record->id)
                            ->get();

                        // Get unique position and document type IDs from the stages
                        $positionIds = $judges_stages->pluck('position_id')->filter()->unique()->toArray();
                        $documentTypeIds = $judges_stages->pluck('document_type_id')->filter()->unique()->toArray();

                        // Fetch [id => name] pairs
                        $positions = \App\Models\Positions::whereIn('id', $positionIds)->pluck('name', 'id');
                        $documentTypes = \App\Models\DocumentType::whereIn('id', $documentTypeIds)->pluck('name', 'id');

                        // Find the first stage where end_date is in the future
                        $currentStage = $judges_stages->first(function ($stage) {
                            return $stage->end_date && \Carbon\Carbon::parse($stage->end_date)->isFuture();
                        });

                        // Get active position name (if applicable)
                        $activePositionName = $currentStage && isset($positions[$currentStage->position_id])
                            ? $positions[$currentStage->position_id]
                            : null;

                        // Get document type name from current stage
                        $documentTypeName = $currentStage && isset($documentTypes[$currentStage->document_type_id])
                            ? $documentTypes[$currentStage->document_type_id]
                            : null;

                        return view('filament.resources.judges.view_cv', [
                            'judge' => $record,
                            'judges_stages' => $judges_stages,
                            'positions' => $positions,
                            'document_types' => $documentTypes,
                            'is_judge_stage' => $judges_stages->isNotEmpty() ? 1 : 0,
                            'active_position_name' => $activePositionName,
                            'active_document_type_name' => $documentTypeName,
                        ]);
                    })
                    ->modalSubmitAction(false)
                    ->modalCloseButton(false)
                    ->modalCancelAction(false)
                    ->modalHeading(false)
                    ->modalCancelActionLabel(function () {
                        return null;
                    }),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()->label('Таҳрирлаш')->slideOver(),
                    Tables\Actions\ViewAction::make()->label('Кўриш'),
                ])
            ])
            ->headerActions([
                Tables\Actions\Action::make('Import Applications')
                    ->label('Импорт')
                    ->color('warning')
                    ->icon('heroicon-o-arrow-down-on-square-stack')
                    ->form([
                        FileUpload::make('xlsxFile')
                            ->label('XLSX File')
                            ->required()
                            ->directory('imports')
                            ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', '.xlsx']),
                    ])
                    ->visible(fn() => auth()->user()?->name === 'admin')
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
                                JudgesImporter::dispatch($chunk, $relativePath);
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
                    Tables\Actions\DeleteBulkAction::make()->label('Танланганлари ўчириш'),
                ])->label('Ўчириш'),
            ]);


    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Grid::make(3)->schema([
                    Section::make()->schema([

                        Infolists\Components\Tabs::make()->schema([
                            Infolists\Components\Tabs\Tab::make('Судья хақида маьлумотлар')->schema([
                                Infolists\Components\Grid::make(6)->schema([
                                    Fieldset::make('Маьлумот')->schema([
                                        Infolists\Components\Grid::make(1)->schema([
                                            ImageEntry::make('image')
                                                ->label('')
                                                ->width(150)
                                                ->height(150)
                                                ->circular()
                                                ->getStateUsing(function ($record) {
                                                    return $record->image
                                                        ? asset('storage/' . $record->image)
                                                        : asset('image/default.jpg');
                                                })->alignCenter(),


                                            TextEntry::make('full_name_and_details')
                                                ->label('')
                                                ->getStateUsing(function ($record) {
                                                    return "<b class='text-md'>{$record->middle_name} {$record->first_name} {$record->last_name}</b> ";
                                                })
                                                ->html()
                                                ->alignCenter()
                                                ->columnSpanFull(),

                                            TextEntry::make('faol_ish_joyi')
                                                ->label('')
                                                ->getStateUsing(function ($record) {
                                                    $stage = $record->judges_stages()
                                                        ->where(function ($query) {
                                                            $query->whereNull('end_date')
                                                                ->orWhere('end_date', '>', now());
                                                        })
                                                        ->latest('start_date')
                                                        ->first();

                                                    if (!$stage) {
                                                        return '—';
                                                    }

                                                    $start = \Carbon\Carbon::parse($stage->start_date)->format('d.m.Y');
                                                    $end = $stage->end_date
                                                        ? \Carbon\Carbon::parse($stage->end_date)->format('d.m.Y')
                                                        : 'ҳозиргача';

                                                    return "{$stage->working_place} ({$start} – {$end})";


                                                }),

                                            ViewEntry::make('lavozim_tugashi')
                                                ->label('Lavozim tugashiga qolgan vaqt')
                                                ->view('components.countdown', [
                                                    'start' => $infolist->record->latestStage()?->start_date,
                                                    'end' => $infolist->record->latestStage()?->end_date,
                                                ])
                                                ->columnSpanFull(),

                                        ])->extraAttributes(['class' => 'text-center']),

                                    ])->columnSpan(1),
                                    Fieldset::make('Умумий маълумотлар')->schema([
                                        TextEntry::make('download_pdf')
                                            ->label('')
                                            ->getStateUsing(function ($record) {
                                                $url = route('judges.download-pdf', $record->id);
                                                return "<a href='{$url}' target='_blank' class='text-blue-500'>Юклаш PDF</a>";
                                            })
                                            ->badge()
                                            ->icon('heroicon-o-arrow-down-on-square-stack')
                                            ->alignRight()
                                            ->columnSpanFull()
                                            ->html(),

                                        Infolists\Components\Grid::make(3)->schema([
                                            TextEntry::make('nationality.name')->columns(1)
                                                ->label('Миллати:')->columns(),
                                            TextEntry::make('birth_date')->columns(1)
                                                ->label('Туғилган санаси:')
                                                ->date('d.m.Y'),
                                            TextEntry::make('region.name')->columns(1)
                                                ->label('Туғилган жойи:'),
                                            TextEntry::make('address')->columns(1)
                                                ->label('Яшаш манзили:'),

                                            TextEntry::make('legal_experience')->columns(1)
                                                ->label('Маълумоти:'),

                                            TextEntry::make('university.name')
                                                ->label('Тамомлаган олийгоҳи:')
                                                ->columns(1),
                                            TextEntry::make('gender')
                                                ->label('Жинси:')
                                                ->columns(1)
                                                ->getStateUsing(function ($record) {
                                                    return $record->gender == 1 ? 'Эркак' : 'Аёл';
                                                }),
                                            TextEntry::make('leadership_experience')
                                                ->label('Давлат томонидан берилган мукофотлари:')
                                                ->columns(1)
                                                ->columnSpanFull()
                                                ->getStateUsing(function ($record) {
                                                    return $record->gender == 1 ? 'Ҳа' : 'Йўқ';
                                                }),
                                            TextEntry::make('special_education')->label('Махсус ўқув юртлари')->columnSpanFull(),

                                            TextEntry::make('gender')->columns(1)
                                                ->label('Давлат томонидан берилган мукофотлари:')->columnSpanFull(),
                                        ])
                                    ])->columnSpan(3),
                                    Fieldset::make('Рейтинг')->schema([
                                        ViewEntry::make('chart')
                                            ->view('components.rating', [
                                                'rating' => $infolist->record->rating,
                                            ])->columnSpanFull(),

                                    ])->columnSpan(2),

                                ])
                            ])->icon('heroicon-o-user-circle'),
                            Infolists\Components\Tabs\Tab::make('Меҳнат фаолияти')->schema([
                                Infolists\Components\Grid::make(5)->schema([
                                    ViewEntry::make('qrcode')
                                        ->view('components.qrcode',
                                            ['qrcode' => $infolist->record->qrcode]),
                                    TextEntry::make('full_name')
                                        ->label('')
                                        ->getStateUsing(function ($record) {
                                            return "<span class='text-3xl '>{$record->middle_name} {$record->first_name}  {$record->last_name} </span>";
                                        })
                                        ->columnStart(2)
                                        ->columnSpan(1)
                                        ->html(),
                                    TextEntry::make('overall_duration')
                                        ->label('Юридик стаж тўғрисида маълумот')
                                        ->getStateUsing(function ($record) {
                                            $judgeId = $record->id;

                                            // Initialize variables for the total durations of both Judge and Legal stages
                                            $totalJudgeYears = 0;
                                            $totalJudgeMonths = 0;
                                            $totalJudgeDays = 0;

                                            // Get all durations for Judge Stages (is_judge_stage = 1) for this judge
                                            $allDurationsJudgeStage = Judges_Stages::where('judge_id', $judgeId)
                                                ->where('is_judge_stage', 1)
                                                ->pluck('counter')
                                                ->toArray();

                                            foreach ($allDurationsJudgeStage as $durationString) {
                                                if (is_string($durationString)) {
                                                    // Extract years, months, and days from the duration string using regex
                                                    preg_match('/(\d+)\s+йил/', $durationString, $yearsMatch);
                                                    preg_match('/(\d+)\s+ой/', $durationString, $monthsMatch);
                                                    preg_match('/(\d+)\s+кун/', $durationString, $daysMatch);

                                                    // Add extracted years, months, and days to the total for Judge Stages
                                                    $totalJudgeYears += (int)($yearsMatch[1] ?? 0);
                                                    $totalJudgeMonths += (int)($monthsMatch[1] ?? 0);
                                                    $totalJudgeDays += (int)($daysMatch[1] ?? 0);
                                                }
                                            }

                                            // Normalize months and days for Judge Stages
                                            $totalJudgeYears += floor($totalJudgeMonths / 12);
                                            $totalJudgeMonths %= 12;
                                            $totalJudgeYears += floor($totalJudgeDays / 365);  // Approximate days to years (adjust if needed)
                                            $totalJudgeDays %= 365;

                                            // Variables for total durations for Legal Stages (is_judge_stage = 0)
                                            $totalLegalYears = 0;
                                            $totalLegalMonths = 0;
                                            $totalLegalDays = 0;

                                            // Get all durations for Legal Stages (is_judge_stage = 0) for this judge
                                            $allDurationsLegalStage = Judges_Stages::where('judge_id', $judgeId)
                                                ->where('is_judge_stage', 0)
                                                ->pluck('counter')
                                                ->toArray();

                                            foreach ($allDurationsLegalStage as $durationString) {
                                                if (is_string($durationString)) {
                                                    // Extract years, months, and days from the duration string using regex
                                                    preg_match('/(\d+)\s+йил/', $durationString, $yearsMatch);
                                                    preg_match('/(\d+)\s+ой/', $durationString, $monthsMatch);
                                                    preg_match('/(\d+)\s+кун/', $durationString, $daysMatch);

                                                    // Add extracted years, months, and days to the total for Legal Stages
                                                    $totalLegalYears += (int)($yearsMatch[1] ?? 0);
                                                    $totalLegalMonths += (int)($monthsMatch[1] ?? 0);
                                                    $totalLegalDays += (int)($daysMatch[1] ?? 0);
                                                }
                                            }

                                            // Normalize months and days for Legal Stages
                                            $totalLegalYears += floor($totalLegalMonths / 12);
                                            $totalLegalMonths %= 12;
                                            $totalLegalYears += floor($totalLegalDays / 365);  // Approximate days to years (adjust if needed)
                                            $totalLegalDays %= 365;

                                            // Calculate the overall duration (sum of Judge and Legal stages)
                                            $overallYears = $totalJudgeYears + $totalLegalYears;
                                            $overallMonths = $totalJudgeMonths + $totalLegalMonths;
                                            $overallDays = $totalJudgeDays + $totalLegalDays;

                                            // Normalize the overall total months and days
                                            $overallYears += floor($overallMonths / 12);
                                            $overallMonths %= 12;

                                            // Adjust days into months if they exceed 30
                                            $additionalMonths = floor($overallDays / 30);
                                            $overallMonths += $additionalMonths;
                                            $overallDays %= 30;

                                            // Format the results with "йил", "ой", "кун"
                                            $formattedOverall = '';
                                            if ($overallYears > 0) {
                                                $formattedOverall .= sprintf('%d йил ', $overallYears);
                                            }
                                            if ($overallMonths > 0) {
                                                $formattedOverall .= sprintf('%d ой ', $overallMonths);
                                            }
                                            $formattedOverall .= sprintf('%d кун', $overallDays);

                                            // Format Judge Stage duration
                                            $formattedJudge = '';
                                            if ($totalJudgeYears > 0) {
                                                $formattedJudge .= sprintf('%d йил ', $totalJudgeYears);
                                            }
                                            if ($totalJudgeMonths > 0) {
                                                $formattedJudge .= sprintf('%d ой ', $totalJudgeMonths);
                                            }
                                            $formattedJudge .= sprintf('%d кун', $totalJudgeDays);

                                            // Format Legal Stage duration
                                            $formattedLegal = '';
                                            if ($totalLegalYears > 0) {
                                                $formattedLegal .= sprintf('%d йил ', $totalLegalYears);
                                            }
                                            if ($totalLegalMonths > 0) {
                                                $formattedLegal .= sprintf('%d ой ', $totalLegalMonths);
                                            }
                                            $formattedLegal .= sprintf('%d кун', $totalLegalDays);

                                            // Return the formatted output for both Judge and Legal stages
                                            return sprintf(
                                                '<span class="">Умумий юридик стажи:<span/> %s<br>' .
                                                '<span class="">Судьялик стажи:<span/> %s<br>',
                                                $formattedOverall,
                                                $formattedJudge,
                                            );
                                        })
                                        ->html()
                                        ->columnSpan(2),

                                    TextEntry::make('download_pdf')
                                        ->label('')
                                        ->icon('heroicon-o-arrow-down-circle')
                                        ->getStateUsing(function ($record) {
                                            $url = route('judges.profile-pdf', $record->id);
                                            return "<a href='{$url}' target='_blank' class='text-blue-500'>Юклаш PDF</a>";
                                        })
                                        ->badge()
                                        ->alignRight()
                                        ->html()
                                        ->columns(1),


                                    Infolists\Components\ViewEntry::make('get_login_link')
                                        ->view('components.experienceFor-table',
                                            ['judges_stages' => $infolist->record->judges_stages])
                                        ->columnSpanFull(),

                                ])
                            ]),
                            Infolists\Components\Tabs\Tab::make('Оилавий ҳолати')->schema([
                                Infolists\Components\Grid::make(4)->schema([


                                ]),
                                TextEntry::make('family.kids_name'),
                                Infolists\Components\ViewEntry::make('family')
                                    ->view('components.family',
                                        ['family' => $infolist->record->family])
                                    ->columnSpanFull()

                            ]),
                            Infolists\Components\Tabs\Tab::make('Рейтинг')->schema([

                                ViewEntry::make('rating-view')
                                    ->view('components.rating-page')
                                    ->viewData([
                                        'record' => $infolist->record->load([
                                            'appeals.reason.instances',
                                            'appeals.reason.typeOfDecision',
                                        ]),
                                        'ratingSetting' => RatingSetting::first(),
                                    ])
                                    ->columnSpanFull(),


                                ViewEntry::make('rating_chart')
                                    ->label('Рейтинг графиги')
                                    ->view('components.profile-ratings')
                                    ->viewData(function ($record) {
                                        $ratings = JudgeRatingHistory::where('judge_id', $record->id)
                                            ->orderBy('created_at')
                                            ->get(['recorded_at', 'created_at', 'rating']); // ⚠️ created_at ham kerak

                                        return [
                                            'ratings' => $ratings,
                                        ];
                                    })
                            ]),
                        ]),

                    ])
                ])
            ]);
    }

    public static function getRelations(): array
    {
        return [
//              ServiceinspectionRelationManager::class,
//              JudgesStagesRelationManager::class
//            BonusRelationManager::class


        ];

    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJudges::route('/'),
            'create' => Pages\CreateJudges::route('/create'),
            'view' => Pages\ViewJudges::route('/{record}'),
            'edit' => Pages\EditJudges::route('/{record}/edit'),
        ];
    }

    public static function calculateExperience(callable $get, callable $set): void
    {
        $startDate = $get('start_date');
        $endDate = $get('end_date');

        if (!$startDate) {
            $set('counter', null);
            return;
        }

        try {
            $start = Carbon::parse($startDate);
            $end = $endDate ? Carbon::parse($endDate) : now();

            // if end date is in the future or null → use now()
            if (!$endDate || $end->isFuture()) {
                $end = now();
            }

            if ($start->greaterThan($end)) {
                $set('counter', 'Хатолик: бошланиш санаси тугаш санасидан кейин.');
                return;
            }

            $diff = $start->diff($end);
            $years = $diff->y;
            $months = $diff->m;
            $days = $diff->d;

            $set('counter', "{$years} йил, {$months} ой, {$days} кун");

        } catch (\Exception $e) {
            $set('counter', 'Хато');
        }

    }

    public static function mutateFormDataBeforeSave(array $data): array
    {
        Log::info('📤 Form data:', $data);

        return $data;
    }


}

