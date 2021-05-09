<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Userdata::class, function (Faker $faker) {
    return [
        'user_id'=>$faker->in(),
        'user_data'=>$faker->text(200),
        'status'=>$faker->text(50),
        'delete'=>$faker->text(50)
    ];
});
