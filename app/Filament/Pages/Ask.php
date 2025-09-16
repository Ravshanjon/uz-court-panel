<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Ask extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';
    protected static ?string $title = 'AI';

    protected static string $view = 'filament.pages.ask';
    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->hasRole('super_admin');
    }
}
