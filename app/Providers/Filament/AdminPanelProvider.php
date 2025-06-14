<?php

namespace App\Providers\Filament;

use App\Filament\Resources\JudgesResource\Widgets\StatOverview;
use App\Models\Judges;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Dotswan\FilamentLaravelPulse\Widgets\PulseCache;
use Dotswan\FilamentLaravelPulse\Widgets\PulseExceptions;
use Dotswan\FilamentLaravelPulse\Widgets\PulseQueues;
use Dotswan\FilamentLaravelPulse\Widgets\PulseServers;
use Dotswan\FilamentLaravelPulse\Widgets\PulseSlowOutGoingRequests;
use Dotswan\FilamentLaravelPulse\Widgets\PulseSlowQueries;
use Dotswan\FilamentLaravelPulse\Widgets\PulseSlowRequests;
use Dotswan\FilamentLaravelPulse\Widgets\PulseUsage;
use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Filament\Notifications\Notification;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;
use ShuvroRoy\FilamentSpatieLaravelBackup\FilamentSpatieLaravelBackupPlugin;
use ShuvroRoy\FilamentSpatieLaravelBackup\Pages\Backups;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {


        Filament::serving(function () {

            $user = auth()->user();
            if ($user && $user->hasRole('Malaka') && $user->pinfl) {
                $judge = \App\Models\Judges::where('pinfl', $user->pinfl)->first();

                if ($judge && request()->routeIs('filament.admin.pages.dashboard')) {
                    redirect()->route('filament.admin.resources.judges.view', ['record' => $judge->id])->send();
                    exit;
                }
            }
            if (
                $user &&
                $user->hasAnyRole(['super_admin']) &&
                !session()->has('shown_ticket_notification') // agar ko‘rsatilmagan bo‘lsa
            ) {
                $newTicketsCount = \App\Models\Ticket::whereHas('user.roles', function ($query) {
                    $query->where('name', 'panel_user');
                })
                    ->whereDate('created_at', today())
                    ->where('status', 'open')
                    ->count();

                if ($newTicketsCount > 0) {
                    Notification::make()
                        ->title('Yangi ticketlar mavjud')
                        ->body("Bugun $newTicketsCount ta yangi ticket yuborilgan.")
                        ->success()
                        ->send();

                    // Shundan keyin yana ko‘rsatilmasligi uchun flag o‘rnatamiz
                    session()->put('shown_ticket_notification', true);
                }
            }
        });
        return $panel
            ->id('admin')
            ->path('/')
            ->login()
            ->profile()
            ->colors([
                'primary' => Color::Sky,
            ])

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([

            ])
            ->favicon(asset('image/favicon.png'))
            ->collapsibleNavigationGroups(true)
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->plugins([
                FilamentApexChartsPlugin::make(),
                FilamentShieldPlugin::make()
                    ->gridColumns([
                        'default' => 1,
                        'sm' => 2,
                        'lg' => 3
                    ])
                    ->sectionColumnSpan(1)
                    ->checkboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                        'lg' => 4,
                    ])
                    ->resourceCheckboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                    ]),
                FilamentSpatieLaravelBackupPlugin::make()
                    ->usingPage(Backups::class),
                FilamentSpatieLaravelBackupPlugin::make()->authorize(fn (): bool => auth()->user()?->hasRole('super_admin'))
            ])
            ->maxContentWidth('full')
            ->brandLogo(fn() => view('filament.admin.logo'))
            ->brandName('Судьялар олий кенгаши')
            ->brandLogoHeight('50px')
            ->databaseNotifications()
            ->authMiddleware([
                Authenticate::class,
            ]);

    }


}

