<?php

namespace Larammerce\Validation;

use Illuminate\Support\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/larammerce_validation.php' => config_path('larammerce_validation.php'),
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->config->get('larammerce_validation') === null) {
            $this->app->config->set('larammerce_validation', require __DIR__ . '/config/larammerce_validation.php');
        }
    }
}
