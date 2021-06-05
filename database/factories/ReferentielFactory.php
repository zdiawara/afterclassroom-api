<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Referentiel;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Referentiel::class, function (Faker $faker) {
    return [
        "name" => $faker->word,
        "id" => (string) Str::uuid(),
        "type" => $faker->word,
        "position" => 0
    ];
});
