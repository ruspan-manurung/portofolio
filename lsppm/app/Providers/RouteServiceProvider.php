<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\AdminOrAssessorMiddleware;
use App\Http\Middleware\AssessorMiddleware;
use App\Http\Middleware\ParticipantMiddleware;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Route::aliasMiddleware('admin_or_assessor', AdminOrAssessorMiddleware::class);
        Route::aliasMiddleware('admin', AdminMiddleware::class);
        Route::aliasMiddleware('assesor', AssessorMiddleware::class);
        Route::aliasMiddleware('participant', ParticipantMiddleware::class);
    }
}