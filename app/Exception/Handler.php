<?php

namespace App\Exception;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $levels = [];

    protected $dontReport = [];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            Log::error('❌ Xatolik:', [
                'xabar' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        });
    }
}
