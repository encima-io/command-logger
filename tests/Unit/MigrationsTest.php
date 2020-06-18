<?php

namespace Encima\CommandLogger\Tests\Unit;

use Encima\CommandLogger\Tests\TestCase;
use Encima\CommandLogger\Models\CommandModel;
use Encima\CommandLogger\Models\CommandLogEntry;

class MigrationsTest extends TestCase
{
    /**
     * @test
     * Created: 2020-04-22
     * Updated: 2020-04-22
     */
    public function it_runs_the_migrations()
    {
        $command = make(CommandModel::class);
        $logEntry = make(CommandLogEntry::class);

        \DB::table('services_command_logger_commands')
            ->insert($command->getAttributes());
        $this->assertDatabaseHas(
            'services_command_logger_commands',
            $command->getAttributes()
        );

        \DB::table('services_command_logger_log_entries')
            ->insert($logEntry->getAttributes());

        $this->assertDatabaseHas(
            'services_command_logger_log_entries',
            $logEntry->getAttributes()
        );
    }
}
