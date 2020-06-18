<?php

namespace Encima\CommandLogger;

use Illuminate\Support\Str;
use Illuminate\Console\Application;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Events\ArtisanStarting;
use Illuminate\Console\Events\CommandFinished;
use Illuminate\Console\Events\CommandStarting;

class CommandLoggerServiceProvider extends ServiceProvider
{
    public function shouldLogCommand(string $command = null): bool
    {
        if ($command === null) {
            return false;
        }
        $commandList = config('services.command-logger.loggable_commands') ?? [];
        foreach ($commandList as $recordableCommand) {
            if (Str::is($recordableCommand, $command)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CommandRecorder::class, function () {
            return new CommandRecorder();
        });
    }

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'command-logger-migrations');
            $this->publishes([
                __DIR__.'/../config/command-logger.php' => config_path('services/command-logger.php'), ], 'command-logger-config');
        }
        $this->loadMigrationsFrom(realpath(__DIR__.'/../database/migrations'));
        $this->loadFactoriesFrom(realpath(__DIR__.'/../tests/Factories'));

        Event::listen(ArtisanStarting::class, function (ArtisanStarting $event) {
            app(CommandRecorder::class)->setApplication($event->artisan);
        });
        Event::listen(CommandStarting::class, function (CommandStarting $event) {
            if ($this->shouldLogCommand($event->command)) {
                app(CommandRecorder::class)->logEvent($event);
            }
        });
        Event::listen(CommandFinished::class, function (CommandFinished $event) {
            if ($this->shouldLogCommand($event->command)) {
                app(CommandRecorder::class)->logEvent($event);
            }
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            CommandRecorder::class,
        ];
    }
}
