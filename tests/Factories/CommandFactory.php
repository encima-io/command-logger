<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Encima\CommandLogger\Models\CommandModel;

$factory->define(CommandModel::class, function (Faker $faker) {
    $faker->addProvider(new \CronExpressionGenerator\FakerProvider($faker));

    return [
        'call' => 'eos:update:'.$faker->word,
        'description' => $faker->sentence,
        'namespace' => 'App\Console\\'.$faker->word,
        'interval' => $faker->cron(),
    ];
});
