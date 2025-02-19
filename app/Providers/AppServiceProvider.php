<?php

namespace App\Providers;

use App\Models\Subscription;
use App\Services\EmailService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(EmailService::class, function ($app) {
            return new EmailService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Registrazione del componente
        Blade::component('app-frontend', 'App\View\Components\AppFrontend');
        Blade::component('app-frontend-whistleblowing-page', 'App\View\Components\AppFrontendWhistleblowingPages');

        /* DB::listen(function ($query) {
            Log::info("Query: {$query->sql}, Bindings: " . json_encode($query->bindings) . ", Time: {$query->time}ms");
        }); */
    }
}
