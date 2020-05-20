<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */


use App\Group;
use App\Teacher;
use Faker\Generator as Faker;

$factory->define(Group::class, function (Faker $faker) {
    $teachers = Teacher::all();
    return [
        'teacher_id' => $teachers[random_int(0, count($teachers)-1)]->user_id,
        'group_name' => random_int(2014, 2019).'_'
                        .Str::random(1).random_int(141, 199).'_'
                        .Str::random(10)
    ];
});