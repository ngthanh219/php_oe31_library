<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Request;
use Faker\Generator as Faker;

$factory->define(Request::class, function (Faker $faker) {
    return [
        'note' => $faker->title,
        'borrowed_date' => $faker->dateTime,
        'return_date' => $faker->dateTime,
        'user_id' => $faker->numberBetween(1, 6),
        'status' => $faker->numberBetween(1, 6),
    ];
});
