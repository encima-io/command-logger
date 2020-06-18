<?php

namespace Encima\CommandLogger\Tests;

use Illuminate\Console\Application as Artisan;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Encima\CommandLogger\CommandLoggerServiceProvider;
use Encima\CommandLogger\Tests\Commands\TestFailedCommand;
use Encima\CommandLogger\Tests\Commands\TestSuccessCommand;

class TestCase extends BaseTestCase
{
    /**
     * Setup the test case.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->loadMigrations();
        $this->withFactories(__DIR__.'/Factories');
        if (\DB::connection() instanceof \Illuminate\Database\SQLiteConnection) {
            \DB::statement(\DB::raw('PRAGMA foreign_keys=on'));
        }
        Artisan::starting(function ($artisan) {
            $artisan->resolveCommands(TestSuccessCommand::class);
            $artisan->resolveCommands(TestFailedCommand::class);
        });
    }

    protected function getPackageProviders($app)
    {
        return [
            CommandLoggerServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('services.command-logger.loggable_commands', [
            'commandlogger:success:test',
            'commandlogger:fail:test',
        ]);
    }

    /**
     * Load the migrations for the test environment.
     *
     * @return void
     */
    protected function loadMigrations()
    {
        $this->loadMigrationsFrom([
            '--database' => 'testing',
            '--path' => realpath(__DIR__.'/../database/migrations'),
            '--realpath' => true,
        ]);
    }
}
