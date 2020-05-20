<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\GroupStudent;
use Faker\Generator as Faker;

$factory->define(GroupStudent::class, function (Faker $faker) {
    $students = Student::all();
    $groups = Group::all();
    return [
        'student_id' => $students[random_int(0, count($students)-1)]->user_id,
        'group_id' => $groups[random_int(0, count($groups)-1)]->id
    ];
});
