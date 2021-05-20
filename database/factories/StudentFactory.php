<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Classe;
use App\Student;
use Faker\Generator as Faker;

$factory->define(Student::class, function (Faker $faker) {
    return [];
});

$factory->afterMaking(Student::class, function ($student, $faker) {
    $classe = factory(Classe::class)->create();
    $student->classe = $classe->id;
});
