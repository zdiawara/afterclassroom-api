<?php

use App\CollegeYear;
use Faker\Generator as Faker;

$factory->define(CollegeYear::class, function (Faker $faker) {

    return [
        'name' => $faker->word,
        'id' => $faker->year,
        'started_at' => (new DateTime('2000-01-01')),
        'finished_at' => (new DateTime('2000-01-01'))->add(new DateInterval('P1Y'))
    ];
});
