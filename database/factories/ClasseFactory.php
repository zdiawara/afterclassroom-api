<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Classe;
use App\Option;
use App\Referentiel;
use Faker\Generator as Faker;

$factory->define(Classe::class, function (Faker $faker) {
    return [
        "name" => $faker->word,
        "abreviation" => $faker->unique()->word,
        "code" => $faker->unique()->word,
        "position" => $faker->randomDigit,
    ];
});

$factory->afterMaking(Classe::class, function ($classe, $faker) {
    $classe->level_id = factory(Referentiel::class)->create()->id;
});

$factory->afterCreating(Classe::class, function ($classe, $faker) {
    $option = factory(Option::class)->make();
    $option->classe_id = $classe->id;
    $option->save();
});