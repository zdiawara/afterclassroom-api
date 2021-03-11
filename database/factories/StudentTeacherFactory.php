<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Classe;
use App\Teacher;
use App\CollegeYear;
use App\Referentiel;
use App\StudentTeacher;
use Faker\Generator as Faker;
use App\Constants\CodeReferentiel;
use App\Constants\TypeReferentiel;


$factory->define(StudentTeacher::class, function (Faker $faker) {

    function createCollegeYear($year){
        return CollegeYear::firstOrCreate([
            'year' => $year
        ],
            collect(factory(CollegeYear::class)->make()->toArray())->except(['year'])->all()
        );
        
    }
    
    $teacher = factory(Teacher::class)->create();
    
    $year = date('Y', strtotime(now()));

    createCollegeYear($year);
    
    return [
        "teacher" => $teacher->id,
        "classe" => factory(Classe::class)->create()->id,
        "matiere" => $teacher->matieres()->first()->id
    ];
});
