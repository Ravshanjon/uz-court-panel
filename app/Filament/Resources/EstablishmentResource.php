<?php

namespace App\Filament\Resources;

use App\Filament\Imports\EstablishedImporter;
use App\Filament\Imports\JudgesImporter;
use App\Filament\Resources\EstablishmentResource\Pages;
use App\Filament\Resources\EstablishmentResource\RelationManagers;
use App\Models\Establishment;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;

use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Spatie\SimpleExcel\SimpleExcelReader;

class EstablishmentResource extends Resource
{

    protected static ?string $model = Establishment::class;
    protected static ?string $pluralModelLabel = 'Штат';
    protected static ?string $navigationGroup = 'Cозламалар';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    Forms\Components\Grid::make(4)->schema([
                        Select::make('court_types_id')
                            ->relationship('court_type', 'name')
                            ->label('Суд тури')
                            ->preload()
                            ->searchable()
                            ->reactive()
                            ->searchable(),

                        Select::make('provinces_districts_id')
                            ->relationship('provinces_district', 'name')
                            ->label('Вилоят / Туман')
                            ->preload()
                            ->searchable(),

                        Select::make('district_type_id')
                            ->relationship('district_types', 'name')
                            ->label('Туман тури')
                            ->preload()
                            ->searchable(),

                        Select::make('region_id')
                            ->relationship('region', 'name')
                            ->label('Вилоят')
                            ->preload()
                            ->searchable(),

                        Select::make('court_name_id')
                            ->relationship('court_names', 'name')
                            ->label('Суд номи')
                            ->preload()
                            ->searchable(),

                        Select::make('court_specialty_id')
                                ->relationship('court_specialty', 'name')
                            ->label('Суд ихтисослиги')
                            ->preload()
                            ->searchable()
                            ->reactive(),

                        Forms\Components\TextInput::make('number_state')
                            ->numeric()
                            ->unique(ignoreRecord:true)
                            ->label('Тартиб рақами'),

                        Select::make('position_id')
                            ->relationship('position', 'name')
                            ->label('Лавозим номи')
                            ->searchable()
                            ->reactive()
                            ->preload(),

                        Select::make('position_category_id')
                            ->relationship('position_category', 'name')
                            ->label('Лавозим тоифаси')
                            ->preload()
                            ->searchable(),

                        Select::make('document_type_id')
                            ->label('Ҳужжат тури')
                            ->relationship('document_type', 'name'),
                    ])
                ])
            ]);
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->orderBy('number_state'); // bu yerda har doim number_state bo‘yicha sort qilinadi
    }

    public static function table(Table $table): Table
    {

        return $table
            ->paginated([25, 50, 100, 'all'])
            ->columns([

                TextColumn::make('number_state')
                    ->label('Тартиб рақами')
                    ->searchable()
                    ->numeric(),

                TextColumn::make('region.name')->label('Ҳудуд')->wrap(40),
                Tables\Columns\TextColumn::make('court_type.name')
                    ->label('Суд тури'),
//                Tables\Columns\BadgeColumn::make('court_specialty.name')->label('Суд ихтисослиги'),
                TextColumn::make('district_types.name')->label('Вилоят/Туман'),
                TextColumn::make('provinces_district.name')->label('Туман тури'),
                TextColumn::make('court_names.name')->label('Суд ном'),
//                TextColumn::make('position_category.name')->label('Лавозим тоифаси'),
                TextColumn::make('position.name')->label('Лавозим тури')
                    ->wrap(40),
                TextColumn::make('position_category.name')->label('Лавозим тоифаси'),

            ])
            ->filters([
                SelectFilter::make('court_types_id')
                    ->relationship('court_type', 'name')
                    ->label('Суд тури')
                    ->preload()
                    ->searchable(),
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
                                EstablishedImporter::dispatch($chunk, $relativePath);
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
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListEstablishments::route('/'),
            'create' => Pages\CreateEstablishment::route('/create'),
            'edit' => Pages\EditEstablishment::route('/{record}/edit'),
        ];
    }
}
