<?php

namespace Railroad\Resora\Providers;

use Illuminate\Database\Events\StatementPrepared;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use PDO;

class ResoraServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
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