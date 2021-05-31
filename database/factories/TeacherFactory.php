<?php

use App\User;
use App\Matiere;
use App\Teacher;
use App\Referentiel;
use App\Constants\CodeReferentiel;
use App\Constants\TypeReferentiel;

$factory->define(Teacher::class, function () {

    Referentiel::firstOrCreate(
        ["id" => CodeReferentiel::VALIDATING, "type" => TypeReferentiel::ETAT],
        collect(factory(Referentiel::class)->make()->toArray())->except(['id', 'type'])->all()
    );

    factory(Matiere::class, rand(2, 3))->create();

    $level = Referentiel::firstOrCreate(
        ["id" => CodeReferentiel::LYCEE, "type" => TypeReferentiel::LEVEL],
        collect(factory(Referentiel::class)->make()->toArray())->except(['id', 'type'])->all()
    );

    return [
        'matieres' => Matiere::all()->map(function ($matiere) use ($level) {
            return ['id' => $matiere->id, 'level' => $level->id];
        })->all()
    ];
});

$factory->afterCreating(Teacher::class, function ($teacher) {
    $teacher->user()->save(factory(User::class)->make());

    Referentiel::firstOrCreate(
        ["code" => CodeReferentiel::VALIDATED, "type" => TypeReferentiel::ETAT],
        collect(factory(Referentiel::class)->make()->toArray())->except(['code', 'type'])->all()
    );
});
