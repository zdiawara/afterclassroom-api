<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Matiere;
use Faker\Generator as Faker;

$factory->define(Matiere::class, function (Faker $faker) {
    return [
        "name" => $faker->word,
        "abreviation" => $faker->word,
        "id" => $faker->unique()->word,
        "position" => 0
    ];
});
