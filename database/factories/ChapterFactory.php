<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Classe;
use App\Option;
use App\Chapter;
use App\Matiere;
use App\Generate;
use App\Specialite;
//use App\Database\Factories\Generate;
use App\Referentiel;
use Faker\Generator as Faker;

$factory->define(Chapter::class, function (Faker $faker) {

    return [
        'title'=>$faker->word,
        'resume'=>$faker->text(rand(150,255)),
        'content' => Generate::genContent($faker),
        'active'=>rand(0,1),
    ];
});

$factory->afterMaking(Chapter::class, function ($chapter, $faker) {
   
});