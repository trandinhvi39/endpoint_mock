<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Eloquent\Level::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word,
        'limit_project' => $faker->randomDigit
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Eloquent\User::class, function (Faker\Generator $faker) {
    static $levelIds;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt('123456'),
        'level_id' => $faker->randomElement($levelIds ?: $levelIds = \App\Eloquent\Level::pluck('id')->toArray()),
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Eloquent\Project::class, function (Faker\Generator $faker) {
    static $userIds;

    return [
        'name' => $faker->sentence(1),
        'description' => $faker->text(10),
        'token' => str_random(50),
        'user_id' => $faker->randomElement($userIds ?: $userIds = \App\Eloquent\User::pluck('id')->toArray()),
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Eloquent\Endpoint::class, function (Faker\Generator $faker) {
    static $projectIds;

    return [
        'prefix' => 'api/v1',
        'limit' => $faker->randomDigit(100),
        'format' => $faker->boolean,
        'project_id' => $faker->randomElement($projectIds ?: $projectIds = \App\Eloquent\Project::pluck('id')->toArray()),
    ];
});
