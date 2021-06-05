<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Exercise;
use App\Generate;
use App\Referentiel;
use Faker\Generator as Faker;
use App\Constants\CodeReferentiel;
use App\Constants\TypeReferentiel;

$factory->define(Exercise::class, function (Faker $faker) {
    return [
        'is_public' => rand(0, 1),
        'enonce' => [
            'data' => Generate::genExoContent($faker),
            'active' => rand(0, 1),
        ],
        'correction' => [
            'data' => Generate::genExoContent($faker),
            'active' => rand(0, 1),
        ],
        'type' => Referentiel::firstOrCreate(
            ['id' => CodeReferentiel::SYNTHESE, 'type' => TypeReferentiel::EXERCISE],
            collect(factory(Referentiel::class)->make()->toArray())->except(['id', 'type'])->all()
        )
    ];
});
