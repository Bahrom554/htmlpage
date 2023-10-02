<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Device;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Device::class, function (Faker $faker) {
    return [
        'ram'=>json_encode(["key"=>"value","keyr"=>"value"]),
        'hdd'=>json_encode(["key"=>"value"]),
        'ssd'=>json_encode(["key"=>"value"]),
        'cpu'=>json_encode(["key"=>"value"]),
        'architecture'=>json_encode(["key"=>"value"]),
        'power'=>json_encode(["key"=>"value"]),
        'os'=>json_encode(["key"=>"value"]),
        'version'=>json_encode(["key"=>"value"]),
        'case'=>json_encode(["key"=>"value"]),
        'type'=>json_encode(["key"=>"value"]),
        'slot'=>json_encode(["key"=>"value"]),
        'definition'=>Str::random(100),
    ];
});
