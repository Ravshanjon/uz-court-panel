<?php

namespace App\Console\Commands;

use App\Services\JudgeRatingCalculator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DispatchJudgeRatingJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ratings:calculate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Barcha rahbar sudyalarga o‘rtacha ballarni hisoblash';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Log::info('✅ DispatchJudgeRatingJob ishladi: ' . now());
        JudgeRatingCalculator::calculate();
    }
}
