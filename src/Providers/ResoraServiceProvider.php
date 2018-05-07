<?php

namespace Railroad\Resora;

class ResoraServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $this->loadMigrationsFrom(__DIR__ . '/../migrations');
        $this->publishes(
            [
                __DIR__ . '/../config/resora.php' => config_path('resora.php'),
            ]
        );
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
}