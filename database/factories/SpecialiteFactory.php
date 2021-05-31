<?php

use App\Matiere;
use App\Specialite;
use Faker\Generator as Faker;

$factory->define(Specialite::class, function (Faker $faker) {
    return [
        "name" => $faker->word,
        "id" => $faker->unique()->word,
    ];
});

$factory->afterMaking(Specialite::class, function ($sepcialite) {
    $sepcialite->matiere_id = factory(Matiere::class)->create()->id;
});
