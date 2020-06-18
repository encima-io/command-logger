<?php

namespace Encima\CommandLogger\Tests\Feature;

use Encima\CommandLogger\Tests\TestCase;
use Encima\CommandLogger\Models\CommandModel;
use Encima\CommandLogger\Models\CommandLogEntry;

class EntryLoggingTests extends TestCase
{
    public function tearDown(): void
    {
        CommandLogEntry::all()->each->delete();
        parent::tearDown();
    }

    /**
     * @test
     * Created: 2020-06-18
     * Updated: 2020-06-18
     */
    public function it_records_a_successfull_command(): void
    {
        $this->artisan('commandlogger:success:test')->run();

        $command = CommandModel::first();
        $this->assertCount(1, $command->logEntries);
        $this->assertNotNull($command->logEntries->first()->completed_at);
        $this->assertNull($command->logEntries->first()->failed_at);

        $this->assertDatabaseHas('services_command_logger_commands', [
            'call' => 'commandlogger:success:test',
            'description' => 'This is a test command',
            'namespace' => 'Encima\CommandLogger\Tests\Commands\TestSuccessCommand',
        ]);
        $this->assertDatabaseHas('services_command_logger_log_entries', [
            'id' => 1,
            'command_id' => 1,
            'failed_at' => null,
        ]);
    }

    /**
     * @test
     * Created: 2020-06-18
     * Updated: 2020-06-18
     */
    public function it_records_a_failed_command(): void
    {
        try {
            $this->artisan('commandlogger:fail:test')->run();
        } catch (\ErrorException $e) {
            CommandLogEntry::first()->fail();
        }
        $command = CommandModel::first();
        $logEntry = CommandLogEntry::first();

        $this->assertCount(1, $command->logEntries);
        $this->assertNotNull($logEntry->started_at);
        $this->assertNull($logEntry->completed_at);
        $this->assertNotNull($logEntry->failed_at);

        $this->assertDatabaseHas('services_command_logger_commands', [
            'call' => 'commandlogger:fail:test',
            'description' => 'This is a test command',
            'namespace' => 'Encima\CommandLogger\Tests\Commands\TestFailedCommand',
        ]);
        $this->assertDatabaseHas('services_command_logger_log_entries', [
            'id' => 1,
            'command_id' => 1,
            'completed_at' => null,
        ]);
    }
}
