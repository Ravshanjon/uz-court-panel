<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\Regions;
use Filament\Resources\Pages\Page;

class RegionWiseJudges extends Page
{
    protected static string $resource = UserResource::class;
    protected static ?string $navigationIcon = 'heroicon-o-map';
    protected static ?string $navigationLabel = 'Ҳудуд бўйича судьялар';
    protected static ?int $navigationSort = 10;

    protected static bool $shouldRegisterNavigation = true;
    protected static string $view = 'filament.resources.user-resource.pages.region-wise-judges';

    public function getViewData(): array
    {
        // Har bir region bilan birga unga tegishli users (judges)ni olib kelamiz
        $regions = Regions::with(['users' => function ($query) {
            $query->whereHas('roles', fn ($q) => $q->where('name', 'judges'));
        }])->get();

        return [
            'regions' => $regions,
        ];
    }
}
