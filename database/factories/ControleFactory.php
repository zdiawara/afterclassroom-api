<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Controle;
use App\Generate;
use Faker\Generator as Faker;

$factory->define(Controle::class, function (Faker $faker) {
    return [
        'year' => $faker->year,
        'is_public' => rand(0, 1),
        'enonce' => [
            'data' => Generate::genExoContent($faker),
            'active' => rand(0, 1),
        ],
        'correction' => [
            'data' => Generate::genExoContent($faker),
            'active' => rand(0, 1),
        ]
    ];
});
