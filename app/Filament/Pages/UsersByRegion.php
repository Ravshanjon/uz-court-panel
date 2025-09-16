<?php

namespace App\Filament\Pages;

use App\Models\Regions;
use Filament\Pages\Page;
use Illuminate\Support\Str;

class UsersByRegion extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.users-by-region';

    public $groupedUsers;

    public function mount(): void
    {
        $this->groupedUsers = Regions::with('users')->get();
    }
    public static function canAccess(): bool
    {
        $user = auth()->user();

        // malaka bo'lsa URL orqali ham kira olmaydi (403)
        return ! ($user && $user->getRoleNames()->contains(
                fn ($r) => Str::lower($r) === 'malaka'
            ));
    }
}
