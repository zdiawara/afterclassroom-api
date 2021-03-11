<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Matiere;
use App\MatiereTeacher;
use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;

$factory->define(MatiereTeacher::class, function (Faker $faker) {
    return [
        "justificatif" => UploadedFile::fake()->create("justificatif.pdf",2*1000,'application/pdf'),
        "matiere" => factory(Matiere::class)->create()->id
    ];
});
