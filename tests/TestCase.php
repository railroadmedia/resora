<?php

namespace Railroad\Resora\Tests;

use Carbon\Carbon;
use Faker\Generator;
use Illuminate\Database\DatabaseManager;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Railroad\Resora\Providers\ResoraServiceProvider;

class TestCase extends BaseTestCase
{
    /**
     * @var Generator
     */
    protected $faker;
    /**
     * @var DatabaseManager
     */
    protected $databaseManager;

    protected function setUp()
    {
        parent::setUp();

        $this->faker = $this->app->make(Generator::class);
        $this->databaseManager = $this->app->make(DatabaseManager::class);

        Carbon::setTestNow(Carbon::now());

        $this->databaseManager->connection(config('resora.default_connection_name'))
            ->statement("CREATE TABLE `resora` (`id` int(10));");
    }

    protected function tearDown()
    {
        $this->databaseManager->connection(config('resora.default_connection_name'))
            ->statement("DROP TABLE `resora`;");

        parent::tearDown();
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $defaultConfig = require(__DIR__ . '/../config/resora.php');

        foreach ($defaultConfig as $key => $value) {
            config()->set('resora.' . $key, $value);
        }

        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set(
            'database.connections.testbench',
            [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
            ]
        );

        $app->register(ResoraServiceProvider::class);
    }
}