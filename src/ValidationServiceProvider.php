<?php

namespace Larammerce\Validation;

use Illuminate\Support\ServiceProvider;

/**
 * @author Arash Khajelou
 * @link https://github.com/a-khajelou
 * @package Larammerce\Validation
 */
class ValidationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/larammerce/validation.php' => config_path('larammerce/validation.php'),
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/larammerce/validation.php', 'larammerce.validation'
        );
    }
}
