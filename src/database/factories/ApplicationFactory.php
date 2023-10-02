<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Application;
use Faker\Generator as Faker;
use Illuminate\Support\Str;


$factory->define(Application::class, function (Faker $faker) {
    return [
        'name'=>$faker->name,
        'definition'=>Str::random(10),
        'device_id'=>rand(1,20),
        'status'=>rand(0,3)
    ];
});
