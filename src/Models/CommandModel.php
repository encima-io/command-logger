<?php

namespace Encima\CommandLogger\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CommandModel extends Model
{
    protected CommandLogEntry $currentLogEntry;
    protected $dateFormat = 'Y-m-d H:i:sO';
    protected $guarded = ['id'];
    protected $table = 'services_command_logger_commands';

    public function logEntries(): ?HasMany
    {
        return $this->hasMany(CommandLogEntry::class, 'command_id')
            ->orderBy('started_at', 'desc');
    }

    public function startLogger(): bool
    {
        $this->currentLogEntry = $this->logEntries()->create([
            'started_at' => now(),
        ]);

        return true;
    }

    public function stopLogger(): bool
    {
        return $this->currentLogEntry->stop();
    }

    public function getLastRunAttribute()
    {
        return $this->logEntries()
            ->whereNotNull('completed_at')
            ->latest()
            ->first();
    }
}
