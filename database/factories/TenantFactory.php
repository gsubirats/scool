<?php

use Faker\Generator as Faker;

$factory->define(App\Tenant::class, function (Faker $faker) {

    $subdomain = $faker->unique()->word;
    return [
        'name' => $faker->sentence,
        'subdomain' => $subdomain,
        'hostname' => 'localhost',
        'username' => $subdomain,
        'password' => 'secret',
        'database' => $subdomain
    ];
});
