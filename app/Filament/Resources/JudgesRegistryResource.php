<?php

namespace App\Filament\Resources;

use App\Filament\Imports\CandidatesImporter;
use App\Filament\Imports\JudgesRegisterImporter;
use App\Filament\Resources\JudgesRegistryResource\Pages;
use App\Filament\Resources\JudgesRegistryResource\RelationManagers;
use App\Models\JudgesRegistry;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Spatie\SimpleExcel\SimpleExcelReader;

class JudgesRegistryResource extends Resource
{
    protected static ?string $model = JudgesRegistry::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $modelLabel = 'Реестр';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    Forms\Components\Grid::make()->schema([
                        Select::make('region_id')
                            ->label('Ҳудуд')
                            ->relationship('region', 'name') // 👈 method nomi region()
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('code')->label('Коди'),
                        Forms\Components\TextInput::make('full_name')->label('ФИШ'),
                        Forms\Components\DatePicker::make('brith_day')
                            ->date('d.m.Y')
                            ->suffixIcon('heroicon-o-calendar')
                            ->label('Туғилган санаси'),
                        Forms\Components\DatePicker::make('judges_anouncment')
                            ->native(true)
                            ->date()
                            ->displayFormat('d.m.Y')
                            ->label('Дастлаб судьяликка тайинланган йили'),
                    ])
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')->label('№')->rowIndex(),
                Tables\Columns\TextColumn::make('code')->label('Коди'),
                Tables\Columns\TextColumn::make('region.name')->label('Ҳудуд'),
                Tables\Columns\TextColumn::make('full_name')->label('ФИШ'),
                Tables\Columns\TextColumn::make('brith_day')
                    ->date('d.m.Y')
                    ->label('Туғилган санаси'),
                Tables\Columns\TextColumn::make('judges_anouncment')
                    ->date('d.m.Y')
                    ->label('Дастлаб судьяликка тайинланган йили')

            ])
            ->headerActions([
                Tables\Actions\Action::make('Import Applications')
                    ->label('Импорт')
                    ->visible(fn () => auth()->user()?->hasRole('super_admin'))
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
                                JudgesRegisterImporter::dispatch($chunk, $relativePath);
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
            ->filters([
                //
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
            'index' => Pages\ListJudgesRegistries::route('/'),
            'create' => Pages\CreateJudgesRegistry::route('/create'),
            'edit' => Pages\EditJudgesRegistry::route('/{record}/edit'),
        ];
    }
}
