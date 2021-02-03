<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Book;
use Faker\Generator as Faker;

$factory->define(Book::class, function (Faker $faker) {
    return [
        'image' => $faker->imageUrl('cats'),
        'name' => $faker->title,
        'author_id' => $faker->numberBetween(1, 10),
        'publisher_id' => $faker->numberBetween(1, 10),
        'in_stock' => $faker->numberBetween(20, 50),
        'total' => $faker->numberBetween(20, 50),
        'status' => $faker->numberBetween(1, 2),
        'description' => $faker->word,
    ];
});
