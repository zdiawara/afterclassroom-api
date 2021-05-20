<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\CollegeYear;
use App\Referentiel;
use Faker\Generator as Faker;
use App\Constants\CodeReferentiel;
use App\Constants\TypeReferentiel;


$factory->define(CollegeYear::class, function (Faker $faker) {

    function createRef($code){
        return Referentiel::firstOrCreate([
            'code' => $code,
            'type' => TypeReferentiel::ETAT_COLLEGE_YEAR
        ],
            collect(factory(Referentiel::class)->make()->toArray())->except(['code','type'])->all()
        );
    }
    
    createRef(CodeReferentiel::FINISHED);
    $ref = createRef(CodeReferentiel::IN_PROGRESS);
    return [
        'name' => $faker->word,
        'year'=>$faker->year,
        'etat_id' => $ref->id
    ];
});