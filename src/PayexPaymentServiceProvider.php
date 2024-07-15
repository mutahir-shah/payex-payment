<?php

namespace Mutahirshah\PayexPayment;

use Illuminate\Support\ServiceProvider;

class PayexPaymentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config files
        $this->publishes([
            __DIR__ . '/../config/payex.php' => config_path('payex.php'),
        ], 'config');
        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        // Load views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'payex');
        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
