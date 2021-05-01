<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Generate;
use App\Question;
use Faker\Generator as Faker;

$factory->define(Question::class, function (Faker $faker) {

    return [
        'title' => $faker->text(rand(30, 50)),
        'content' => Generate::genContent($faker),
        'is_active' => rand(0, 1),
        'is_public' => rand(0, 1),
    ];
});
