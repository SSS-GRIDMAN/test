<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Assignment;
use App\File;
use App\Group;
use Faker\Generator as Faker;

$factory->define(Assignment::class, function (Faker $faker) {
    $teachers = Teacher::all();
        
    return [
        'group_id' => $teachers[random_int(0, count($teachers)-1)]->groups[0],
        'file_id' => factory(File::class)->create()->id,
        'deadline' => now(),
        'publicity' => false,
        'name' => $faker->name
    ];
});
