<?php

use Faker\Generator as Faker;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(\Account::class, function (Faker $faker) {
    return [
        'title' =>  $faker->title,
    ];
});
