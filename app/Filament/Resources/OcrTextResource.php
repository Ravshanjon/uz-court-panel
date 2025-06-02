<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OcrTextResource\Pages;
use App\Filament\Resources\OcrTextResource\RelationManagers;
use App\Models\OcrText;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class OcrTextResource extends Resource
{
    protected static ?string $model = OcrText::class;

    protected static ?string $relationship = 'ocrTexts';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('judge_id'),
                TextColumn::make('pages')
                    ->label('Sahifalar')
                    ->formatStateUsing(function ($state, $record) {
                        if (!is_array($state)) return '-';

                        return new HtmlString(
                            collect($state)->map(function ($page) use ($record) {
                                return "<a href='#'
                    class='text-primary-600 underline text-sm'
                    onclick='window.dispatchEvent(new CustomEvent(\"open-ocr-modal\", { detail: { page: $page, text: `" . e($record->page_texts[$page - 1]['text'] ?? '') . "` } }))'>
                    $page
                </a>";
                            })->implode(' | ')
                        );
                    })
                    ->html()
            ])
            ->filters([
                //
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListOcrTexts::route('/'),
            'create' => Pages\CreateOcrText::route('/create'),
//            'edit' => Pages\EditOcrText::route('/{record}/edit'),
        ];
    }
    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->hasRole('super_admin');
    }
}
