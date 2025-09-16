<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketReplyRelationManagerResource\RelationManagers\TicketRelationManager;
use App\Filament\Resources\TicketReplyRelationManagerResource\RelationManagers\TicketResourceRelationManager;
use App\Filament\Resources\TicketResource\Pages;
use App\Filament\Resources\TicketResource\RelationManagers;
use App\Models\Ticket;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Laravel\Pulse\Livewire\Card;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';


    public static function getNavigationBadge(): ?string
    {
        if (! auth()->user()->hasAnyRole(['admin', 'super_admin'])) {
            return null;
        }

        // panel_userlar tomonidan yuborilgan ochiq ticketlar soni
        $count = \App\Models\Ticket::whereHas('user.roles', function ($query) {
            $query->where('name', 'panel_user');
        })
            ->where('status', 'open')
            ->count();

        return $count > 0 ? (string) $count : null;
    }
    public static function getEloquentQuery(): Builder
    {
        if (auth()->user()?->hasRole('panel_user')) {
            return parent::getEloquentQuery()
                ->where('user_id', auth()->id());
        }

        return parent::getEloquentQuery();
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Card::make()->schema([
                    Forms\Components\TextInput::make('subject')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\RichEditor::make('message')
                        ->required(),


                    Select::make('status')
                        ->options([
                            'open' => 'Ochiq',
                            'in_progress' => 'Ko‘rib chiqilmoqda',
                            'closed' => 'Yopilgan',
                        ])
                        ->label('Status')
                        ->visible(fn () => !auth()->user()->hasRole('panel_user'))

                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->searchable()->label('Yuboruvchi'),
                Tables\Columns\TextColumn::make('subject')->searchable()->label('Mavzu'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Holat')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'open' => 'warning',
                        'in_progress' => 'info',
                        'closed' => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')->since()->label('Yaratilgan vaqti'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'open' => 'Ochiq',
                        'in_progress' => 'Jarayonda',
                        'closed' => 'Yopilgan',
                    ])
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

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }

}
