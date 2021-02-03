<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Request;
use Faker\Generator as Faker;

$factory->define(Request::class, function (Faker $faker) {
    $borrowed_date = $faker->dateTimeBetween('-5 days', now());
    $return_date = $faker->dateTimeBetween(now());

    return [
        'note' => $faker->word,
        'borrowed_date' => $borrowed_date,
        'return_date' => $return_date,
        'user_id' => 5,
        'status' => 0,
    ];
});
