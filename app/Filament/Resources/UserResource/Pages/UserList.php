<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\Page;
//use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use App\Models\User;
class UserList extends Page
{
    protected static string $resource = UserResource::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static string $view = 'filament.pages.user-list';
    public function table(Table $table): Table
    {
        return $table
            ->query(User::query()) // E'tibor bering: bu yerda model querysi kerak!
            ->columns([
                Tables\Columns\TextColumn::make('region.name')
                    ->label('Вилоят')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('ФИШ')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('roles.name')
                    ->label('Роллар'),
            ])
            ->filters([
                // Filtrlar shu yerda
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
}
