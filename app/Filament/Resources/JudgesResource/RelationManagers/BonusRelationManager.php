<?php

namespace App\Filament\Resources\JudgesResource\RelationManagers;

use App\Models\Bonus;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Components\Tab;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Log;

class BonusRelationManager extends RelationManager
{
    protected static string $relationship = 'bonusAssignments';
    public function getTableHeading(): string
    {
        return 'Бонус баллар';
    }
    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Tabs::make('bonus')->tabs([
                    Forms\Components\Tabs\Tab::make('bonus')->schema([
                        Select::make('bonus_id')
                            ->label('Бонус')
                            ->relationship('bonus', 'name')
                            ->required()
                            ->label('Бонус тури')
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, $livewire, $old) {
                                $judge = $livewire->getOwnerRecord();

                                $oldBonus = \App\Models\Bonus::find($old);
                                $newBonus = \App\Models\Bonus::find($state);

                                $oldScore = $oldBonus?->score ?? 0;
                                $newScore = $newBonus?->score ?? 0;

                                // Agar old bonus bor bo'lsa, olib tashlaymiz
                                if ($oldScore > 0) {
                                    $judge->adding_rating = max(0, ($judge->adding_rating ?? 0) - $oldScore);
                                }

                                if ($newScore > 0) {
                                    $judge->adding_rating = ($judge->adding_rating ?? 0) + $newScore;
                                }

                                // Barcha ballarni hisoblab ratingni yangilaymiz
                                $judge->rating =
                                    ($judge->adding_rating ?? 0);


                                $judge->save();
                                $judge->refresh();
                            }),
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\Textarea::make('title')->label('Номланиши')->rows(3),
                            Forms\Components\FileUpload::make('bonus')->label('Файл бриктириш')->maxSize(2048),
                        ]),
                    ])->label('Бонус баллар'),

                    Forms\Components\Tabs\Tab::make('activities')->schema([

                    ])->label('Фаолиги')

                ])->columnSpanFull()

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('bonus')
            ->columns([
                Tables\Columns\TextColumn::make('bonus.name')->label('Бонус номи'),
                Tables\Columns\TextColumn::make('bonus.score')->label('Балл'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('+')
                    ->color('gray')
                    ->size('sm')
                    ->modalHeading('')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
