@if ($record)
    @livewire(
    \App\Filament\Resources\JudgesResource\RelationManagers\AppealsRelationManager::class,
    [
    'ownerRecord' => $record,
    'pageClass' => \App\Filament\Resources\JudgesResource\Pages\EditJudges::class,
    ]
    )
@endif
