<?php

namespace App\Providers;


use App\Models\service_inspection;
use App\Observers\ServiceInspectionObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use PhpParser\Builder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        service_inspection::observe(ServiceInspectionObserver::class);
        Schema::defaultStringLength(191);

    }
}
