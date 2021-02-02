<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Book;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Book::class, function (Faker $faker) {
    return [
        'image' => $faker->imageUrl('cats') ,
        'name' => $faker->name,
        'author_id' => Str::random(10),
        'publisher_id' => Str::random(10),
        'in_stock' =>  Str::random(10),
        'total' =>  Str::random(10),
        'status' =>  Str::random(10),
        'description' =>  $faker->word,
    ];
});
