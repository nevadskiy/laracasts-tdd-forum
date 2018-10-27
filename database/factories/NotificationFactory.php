<?php

use App\Notifications\ThreadWasUpdated;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Notifications\DatabaseNotification;
use Ramsey\Uuid\Uuid;

$factory->define(DatabaseNotification::class, function (Faker $faker) {
    return [
        'id' => Uuid::uuid4()->toString(),
        'type' => ThreadWasUpdated::class,
        'notifiable_id' => function () {
            return auth()->id() ?: factory(User::class)->create()->id;
        },
        'notifiable_type' => User::class,
        'data' => ['foo' => 'bar'],
    ];
});
