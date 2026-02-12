<?php

namespace App\Providers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Foundation\MaintenanceMode as MaintenanceModeContract;
use Illuminate\Foundation\MaintenanceModeManager;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind the legacy "files" service name expected by some packages (e.g. Sanctum)
        // to the Illuminate Filesystem instance. This keeps older packages compatible
        // with the Laravel 11 application container.
        $this->app->singleton('files', function () {
            return new Filesystem();
        });

        // Explicitly bind the MaintenanceMode contract for Laravel 11 HTTP middleware.
        $this->app->singleton(MaintenanceModeContract::class, function ($app) {
            return new MaintenanceModeManager($app);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
    }
}

