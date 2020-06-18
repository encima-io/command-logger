<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Encima\CommandLogger\Models\CommandModel;
use Encima\CommandLogger\Models\CommandLogEntry;

$factory->define(CommandLogEntry::class, function (Faker $faker) {
    return [
        'command_id' => function () {
            return create(CommandModel::class);
        },
        'started_at' => $faker->dateTime,
        'completed_at' => ($completed = $faker->boolean(90)) ? $faker->dateTime : null,
        'failed_at' => $completed ? null : $faker->dateTime,
    ];
});
