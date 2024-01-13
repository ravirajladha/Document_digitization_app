<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\DocumentTableService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(DocumentTableService::class, function ($app) {
            // return new instance of service
            return new DocumentTableService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
