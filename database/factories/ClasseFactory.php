<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Classe;
use App\Referentiel;
use Faker\Generator as Faker;

$factory->define(Classe::class, function (Faker $faker) {
    return [
        "name" => $faker->word,
        "abreviation" => $faker->unique()->word,
        "id" => $faker->unique()->word,
        "position" => $faker->randomDigit,
    ];
});

$factory->afterMaking(Classe::class, function ($classe) {
    $classe->level_id = factory(Referentiel::class)->create()->id;
});
