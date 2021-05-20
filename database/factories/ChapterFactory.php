<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Chapter;
use App\Generate;
use Faker\Generator as Faker;

$factory->define(Chapter::class, function (Faker $faker) {

    return [
        'title' => $faker->word,
        'resume' => $faker->text(rand(150, 255)),
        'content' => Generate::genContent($faker),
        'is_active' => rand(0, 1),
        'is_public' => rand(0, 1),
    ];
});

$factory->afterMaking(Chapter::class, function ($chapter, $faker) {
});
