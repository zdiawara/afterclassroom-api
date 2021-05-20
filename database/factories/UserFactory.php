<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Identify;
use App\Referentiel;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;
use App\Constants\CodeReferentiel;
use App\Constants\TypeReferentiel;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    Identify::firstOrCreate([
        'tranche' => 10,
        "current" => 0
    ]);
    return [
        'firstname' => $faker->firstName,
        'lastname' => $faker->lastName,
        'username' => $faker->userName,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => bcrypt('secret'),
        "avatar" => 'test.png', //UploadedFile::fake()->image("cover.png"),
        'gender' => Referentiel::firstOrCreate(
            ["code" => CodeReferentiel::HOMME, "type" => TypeReferentiel::GENDER],
            collect(factory(Referentiel::class)->make()->toArray())->except(['code', 'type'])->all()
        )->id
    ];
});
