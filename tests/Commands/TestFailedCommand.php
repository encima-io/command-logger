<?php

namespace Encima\CommandLogger\Tests\Commands;

use Illuminate\Console\Command;

class TestFailedCommand extends Command
{
    protected $signature = 'commandlogger:fail:test';
    protected $description = 'This is a test command';

    public function handle()
    {
        $this->line((string) ['The command is failing!']);
    }
}
