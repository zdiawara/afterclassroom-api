<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */


use App\User;
use App\Matiere;
use App\Teacher;
use App\Specialite;
use App\Referentiel;
use Faker\Generator as Faker;
use App\Constants\CodeReferentiel;
use App\Constants\TypeReferentiel;

$factory->define(Teacher::class, function (Faker $faker) {

    Referentiel::firstOrCreate(
        ["code" => CodeReferentiel::VALIDATING, "type" => TypeReferentiel::ETAT],
        collect(factory(Referentiel::class)->make()->toArray())->except(['code', 'type'])->all()
    );

    factory(Matiere::class, rand(2, 3))->create();

    $level = Referentiel::firstOrCreate(
        ["code" => CodeReferentiel::LYCEE, "type" => TypeReferentiel::LEVEL],
        collect(factory(Referentiel::class)->make()->toArray())->except(['code', 'type'])->all()
    );

    return [
        'matieres' => Matiere::all()->map(function ($matiere) use ($level) {
            return ['code' => $matiere->code, 'level' => $level->code];
        })->all()
    ];
});

$factory->afterCreating(Teacher::class, function ($teacher, $faker) {
    $teacher->user()->save(factory(User::class)->make());

    $refEtat = Referentiel::firstOrCreate(
        ["code" => CodeReferentiel::VALIDATED, "type" => TypeReferentiel::ETAT],
        collect(factory(Referentiel::class)->make()->toArray())->except(['code', 'type'])->all()
    );

    /*factory(Matiere::class,rand(2,3))->create()->each(function($matiere)use($teacher,$refEtat,$faker){
        $specialite = factory(Specialite::class)->make();
        $specialite->matiere_id = $matiere->id;
        $specialite->save();
        $teacher->matieres()->attach($matiere->id,[
            'etat_id' => $refEtat->id,
            'justificatif' => $faker->imageUrl(550, 320, 'nature' , true, 'Faker')
        ]);
    });*/
});
