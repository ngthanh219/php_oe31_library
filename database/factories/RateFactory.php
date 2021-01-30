<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Rate;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Rate::class, function (Faker $faker) {
    return [
        'user_id' => Str::random(10),
        'book_id' => Str::random(10),
        'vote' => Str::random(5),
    ];
});
