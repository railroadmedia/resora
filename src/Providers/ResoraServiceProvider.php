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

        // return all results as assoc array for all resora database calls
        Event::listen(
            StatementPrepared::class,
            function (StatementPrepared $event) {
                if (strpos($event->connection->getName(), 'resora_connection_mask_') !== false) {
                    $event->statement->setFetchMode(PDO::FETCH_ASSOC);
                }
            }
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