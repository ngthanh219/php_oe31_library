<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Author;
use Faker\Generator as Faker;

$factory->define(Author::class, function (Faker $faker) {
    $date_of_born = $faker->dateTimeBetween('-20 years', now());
    $date_of_death = $faker->dateTimeBetween(now());

    return [
        'image' => $faker->imageUrl('cats'),
        'name' => $faker->name,
        'date_of_born' => $date_of_born,
        'date_of_death' => $date_of_death,
        'description' => $faker->word,
    ];
});
