<?php

namespace Encima\CommandLogger;

use Illuminate\Console\Application;
use Illuminate\Support\Facades\App;
use Illuminate\Console\Scheduling\Schedule;
use Encima\CommandLogger\Models\CommandModel;
use Illuminate\Console\Events\CommandFinished;
use Illuminate\Console\Events\CommandStarting;

class CommandRecorder
{
    protected ?Application $application;
    protected ?CommandModel $model;

    /**
     * This part gets filled by the \Illuminate\Console\Events\ArtisanStarting::class event
     */
    public function setApplication(Application $application): void
    {
        $this->application = $application;
    }

    /**
     * Create a new instance from an event
     *
     * @param \Illuminate\Console\Events\CommandStarting|\Illuminate\Console\Events\CommandFinished $event
     * @return self
     */
    public function logEvent($event): void
    {
        if (!$this->isCorrectCommandModelSet($event)) {
            $this->loadCommandModel($event);
        }
        if (is_a($event, CommandStarting::class)) {
            $this->logEntry = $this->model->startLogger();
        }
        if (is_a($event, CommandFinished::class)) {
            $this->logEntry = $this->model->stopLogger();
        }
    }

    public function isCorrectCommandModelSet($event): bool
    {
        if (!isset($this->model)) {
            return false;
        }
        if ($this->model->call == $event->command) {
            return true;
        }

        return false;
    }

    public function loadCommandModel($event): void
    {
        $command = $this->application->get($event->command);
        $this->model = CommandModel::updateOrCreate([
            'call' => $command->getName(),
        ], [
            'description' => $command->getDescription(),
            'namespace' => get_class($command),
            'interval' => static::getInterval($event),
        ]);
    }

    public static function getInterval($event): ?string
    {
        $schedule = collect(app(Schedule::class)->events());
        $event = $schedule->first(fn ($value) => $event->command == substr($value->command, strpos($value->command, 'artisan') + 9));

        return isset($event) ? $event->expression : null;
    }
}
