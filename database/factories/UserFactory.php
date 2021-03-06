<?php

use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
        'email_verified_at' => now(),
    ];
});

$factory->state(App\User::class, 'unverified', function () {
    return [
        'email_verified_at' => null,
    ];
});

$factory->state(App\User::class, 'administrator', function () {
    return [
        'name' => 'admin',
    ];
});
