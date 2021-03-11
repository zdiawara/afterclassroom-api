<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Classe;
use App\Matiere;
use App\Specialite;
use App\Referentiel;
use Faker\Generator as Faker;

$factory->define(Specialite::class, function (Faker $faker) {
    return [
        "name" => $faker->word,
        "code" => $faker->unique()->word,
    ];
});

$factory->afterMaking(Specialite::class, function ($sepcialite, $faker) {
    //$sepcialite->level_id = factory(Referentiel::class)->create()->id;
    $sepcialite->matiere_id = factory(Matiere::class)->create()->id;
});
