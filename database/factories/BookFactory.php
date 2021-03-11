<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Book;
use App\Model;
use App\Classe;
use App\Option;
use App\Category;
use App\Generate;
use App\Specialite;
use App\Referentiel;
use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;
use App\Constants\CodeReferentiel;
use App\Constants\TypeReferentiel;

$factory->define(Book::class, function (Faker $faker) {
    return [
        'title'=>$faker->text(rand(20,50)),
        'resume'=>$faker->text(rand(150,255)),
        'price'=>$faker->randomNumber(3),
        'content' => [
            "data" => Generate::genContent($faker),
            'active'=>rand(0,1),
        ],
        "cover" => UploadedFile::fake()->image("cover.png")
    ];
});


$factory->afterMaking(Book::class, function ($book, $faker) {
 
});
