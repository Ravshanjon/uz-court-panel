<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Str;

class ViewAppealPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.view-appeal-page';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        // malaka bo'lsa URL orqali ham kira olmaydi (403)
        return ! ($user && $user->getRoleNames()->contains(
                fn ($r) => Str::lower($r) === 'malaka'
            ));
    }

}
