<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Referentiel;
use Faker\Generator as Faker;

$factory->define(Referentiel::class, function (Faker $faker) {
    return [
        "name" => $faker->word,
        "code" => $faker->unique()->word,
        "type" => $faker->unique()->word,
        "position" => 0
    ];
});
