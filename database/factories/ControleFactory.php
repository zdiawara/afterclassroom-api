<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Controle;
use App\Generate;
use App\Referentiel;
use Faker\Generator as Faker;
use App\Constants\CodeReferentiel;
use App\Constants\TypeReferentiel;

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
        ],
        'type' => Referentiel::firstOrCreate(
            ['id' => CodeReferentiel::DEVOIR, 'type' => TypeReferentiel::CONTROLE],
            collect(factory(Referentiel::class)->make()->toArray())->except(['id', 'type'])->all()
        )->id,
        'trimestre' => Referentiel::firstOrCreate(
            ['id' => CodeReferentiel::TRIMESTRE_1, 'type' => TypeReferentiel::TRIMESTRE],
            collect(factory(Referentiel::class)->make()->toArray())->except(['id', 'type'])->all()
        )->id
    ];
});
