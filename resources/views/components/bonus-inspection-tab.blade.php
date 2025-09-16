@if ($record)
    @livewire(
    \App\Filament\Resources\JudgesResource\RelationManagers\BonusRelationManager::class,
    [
    'ownerRecord' => $record,
    'pageClass' => \App\Filament\Resources\JudgesResource\Pages\EditJudges::class,
    ]
    )
@endif
