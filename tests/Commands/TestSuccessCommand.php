<?php

namespace Encima\CommandLogger\Tests\Commands;

use Illuminate\Console\Command;

class TestSuccessCommand extends Command
{
    protected $signature = 'commandlogger:success:test';
    protected $description = 'This is a test command';

    public function handle()
    {
        $this->line('The command is run!');
    }
}
