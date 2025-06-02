<?php

namespace App\Filament\Resources\JudgesResource\Pages;

use App\Filament\Resources\JudgesResource;
use App\Models\Judges;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;

class JudgeProfile extends Page
{
    protected static string $view = 'filament.resources.judges-resource.pages.judge-profile';

    public ?Judges $judge = null;

    public function mount(): void
    {
        $user = Auth::user();


        if ($user->hasRole('panel_user') && $user->pinfl) {
            $this->judge = Judges::where('pinfl', $user->pinfl)->first();
        }
    }

    public function getInfolist(string $name): ?Infolist
    {
        if (!$this->judge) {
            return null; // pinfl bo'yicha judge topilmasa hech narsa ko'rsatmaydi
        }

        return Infolist::make()
            ->record($this->judge)
            ->schema([
                TextEntry::make('first_name')->label('Ismi'),
                TextEntry::make('last_name')->label('Familiyasi'),
                TextEntry::make('middle_name')->label('Otasining ismi'),
                TextEntry::make('region.name')->label('Viloyati'),
                TextEntry::make('court_type.name')->label('Sud turi'),
            ]);
    }

}
