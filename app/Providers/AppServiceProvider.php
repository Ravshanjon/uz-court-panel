<?php

namespace App\Providers;


use App\Models\Appeal;
use App\Models\AppelationData;
use App\Models\AuditFirstData;
use App\Models\Bonus;
use App\Models\BonusJudges;
use App\Models\CassationData;
use App\Models\JudgeActivity;
use App\Models\Proceeding;
use App\Models\ProceedingJudge;
use App\Models\ProceedingScore;
use App\Models\service_inspection;
use App\Observers\AppealObserver;
use App\Observers\AppelationDataObserver;
use App\Observers\AuditFirstDataObserver;
use App\Observers\BonusJudgeObserver;
use App\Observers\CassationDataObserver;
use App\Observers\JudgeActivityObserver;
use App\Observers\ProceedingJudgeObserver;
use App\Observers\ProceedingObserver;
use App\Observers\ProceedingScoreObserver;
use App\Observers\ServiceInspectionObserver;
use App\Services\JudgeScoreService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        $this->app->singleton(JudgeScoreService::class, fn() => new JudgeScoreService());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Proceeding::observe(ProceedingObserver::class);
        ProceedingJudge::observe(ProceedingJudgeObserver::class);
        ProceedingScore::observe(ProceedingScoreObserver::class);
        JudgeActivity::observe(JudgeActivityObserver::class);
        service_inspection::observe(ServiceInspectionObserver::class);
        BonusJudges::observe(BonusJudgeObserver::class);
        Livewire::component('add-taftish', \App\Livewire\AddTaftish::class);
        Schema::defaultStringLength(191);
    }
}
