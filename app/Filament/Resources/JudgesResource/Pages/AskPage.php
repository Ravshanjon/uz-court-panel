<?php

namespace App\Filament\Resources\JudgesResource\Pages;

use App\Filament\Resources\JudgesResource;
use Filament\Resources\Pages\Page;

class AskPage extends Page
{
    protected static string $resource = JudgesResource::class;
    protected static ?string $routeName = 'filament.admin.pages.ask';

    protected static ?string $slug = 'ask';

}
