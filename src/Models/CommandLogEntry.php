<?php

namespace Encima\CommandLogger\Models;

use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommandLogEntry extends Model
{
    protected $dateFormat = 'Y-m-d H:i:sO';
    protected $guarded = ['id'];
    protected $dates = [
        'started_at',
        'completed_at',
        'failed_at',
    ];

    protected $table = 'services_command_logger_log_entries';

    public function command(): ?BelongsTo
    {
        return $this->belongsTo(CommandModel::class);
    }

    public function stop()
    {
        return $this->update([
            'completed_at' => now(),
        ]);
    }

    public function fail(): void
    {
        if ($this->completed_at === null && $this->failed_at === null) {
            $this->update(['failed_at' => now()]);
        }
    }

    public function getRuntimeAttribute()
    {
        if ($this->completed_at && $this->started_at) {
            return $this->completed_at->diff($this->started_at);
        }

        return null;
    }

    public function getRuntimeStringAttribute(): ? string
    {
        if ($this->runtime) {
            $string = CarbonInterval::fromString(
                $this->runtime->format('%yy %mmo %dd %hh %im %ss')
            )->cascade()->forHumans();

            if ($string == '') {
                return '0 sekunder';
            }

            return $string;
        }

        return null;
    }

    public function __destruct()
    {
        try {
            $this->fail();
        } catch (\Illuminate\Database\QueryException $e) {
        }
    }
}
