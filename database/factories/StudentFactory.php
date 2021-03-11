<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Classe;
use App\Option;
use App\Student;
use Faker\Generator as Faker;

$factory->define(Student::class, function (Faker $faker) {
    return [
        
    ];
});

$factory->afterMaking(Student::class, function ($student, $faker) {

    $classe = factory(Classe::class)->create();   
    
    $option = factory(Option::class)->make();
    $option->classe_id = $classe->id;
    
    $option->save();  

    $student->classe = $classe->id;
    $student->option = $option->id;
});
