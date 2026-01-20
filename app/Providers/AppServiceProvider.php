<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
{
    $this->app->singleton(\App\Services\GeminiService::class, function ($app) {
        return new \App\Services\GeminiService();
    });

    $this->app->singleton(\App\Services\RecommendationService::class, function ($app) {
        return new \App\Services\RecommendationService(
            $app->make(\App\Services\GeminiService::class)
        );
    });
}

    public function boot(): void
    {
        Schema::defaultStringLength(191);
    }
}
