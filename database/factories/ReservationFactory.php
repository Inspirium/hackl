<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Reservation;
use Faker\Generator as Faker;

$factory->define(Reservation::class, function (Faker $faker) {
    return [
        'court_id' => $faker->randomDigit,
        'from' => $faker->dateTimeBetween('now', 'tomorrow'),
        'to' => $faker->dateTimeBetween('now', 'tomorrow'),
        'price' => 30,
    ];
});
