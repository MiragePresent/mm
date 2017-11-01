<?php

use Faker\Generator as Faker;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(\Category::class, function (Faker $faker) {
    return [
        'title'     => $faker->title,
        'thumb'     => $faker->imageUrl(),
        'is_common' => $faker->boolean(90) ? : 0
    ];
});
