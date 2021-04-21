<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Enums\TwoFactorProvider;
use App\Enums\UserStatus;
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(User::class, function (Faker $faker) {
    return [
        'avatar' => $faker->imageUrl($width = 640, $height = 480),
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => $faker->dateTime(),
        'cellphone' => $faker->unique()->e164PhoneNumber,
        'cellphone_verified_at' => $faker->dateTime(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
        'status' => new UserStatus($faker->randomElement(UserStatus::toArray())),
        'role_id' => $faker->numberBetween( $min = 1, $max = 2),
        'two_factor_provider' => new TwoFactorProvider($faker->randomElement(TwoFactorProvider::toArray(), null)),
    ];
});
