<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */


use App\User;
use App\Identify;
use App\Referentiel;
use Faker\Generator as Faker;
use App\Constants\CodeReferentiel;
use App\Constants\TypeReferentiel;

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
            ["id" => CodeReferentiel::HOMME, "type" => TypeReferentiel::GENDER],
            collect(factory(Referentiel::class)->make()->toArray())->except(['id', 'type'])->all()
        )->id
    ];
});
