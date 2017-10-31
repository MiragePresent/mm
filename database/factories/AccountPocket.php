<?php

use Faker\Generator as Faker;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(\Pocket::class, function (Faker $faker) {
    return [
        'balance'       =>  rand(1, 999) * .01,
        'title'         =>  $faker->title,
        'description'   =>  $faker->paragraph(3),
        'has_notice'    =>  $faker->boolean(90) ? 0 : 1
    ];
});
