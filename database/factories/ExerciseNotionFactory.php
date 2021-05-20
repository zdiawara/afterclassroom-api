<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ExerciseNotion;
use Faker\Generator as Faker;

$factory->define(ExerciseNotion::class, function (Faker $faker) {
    return [
        "value" => $faker->word,
        "type" => rand(0,1)
    ];
});
