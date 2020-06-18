<?php

namespace Encima\CommandLogger\Tests\Unit;

use Encima\CommandLogger\Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;
use Encima\CommandLogger\Models\CommandModel;
use Encima\CommandLogger\Models\CommandLogEntry;

class CommandTest extends TestCase
{
    /**
     * @test
     * Created: 2020-04-22
     * Updated: 2020-04-22
     */
    public function it_has_log_entries(): void
    {
        $command = create(CommandModel::class);
        $logEntries = create(CommandLogEntry::class, [
            'command_id' => $command->id,
        ], 10);

        $this->assertCount(10, $command->logEntries);
        $this->assertInstanceOf(Collection::class, $command->logEntries);
        $this->assertInstanceOf(CommandLogEntry::class, $command->logEntries->random());

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
