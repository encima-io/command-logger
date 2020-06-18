<?php

namespace Encima\CommandLogger\Tests\Unit;

use Encima\CommandLogger\Tests\TestCase;
use Encima\CommandLogger\Models\CommandModel;
use Encima\CommandLogger\Models\CommandLogEntry;

class LogEntryTest extends TestCase
{
    /**
     * @test
     * Created: 2020-04-22
     * Updated: 2020-04-22
     */
    public function it_has_belongsto_a_command(): void
    {
        $command = create(CommandModel::class);
        $logEntries = create(CommandLogEntry::class, [
            'command_id' => $command->id,
        ], 10);

        $this->assertInstanceOf(CommandModel::class, $logEntries->random()->command);

        $this->assertDatabaseHas(
            'services_command_logger_commands',
            $command->getAttributes()
        );
        $this->assertDatabaseHas(
            'services_command_logger_log_entries',
            $logEntries->random()->getAttributes()
        );
    }
}
