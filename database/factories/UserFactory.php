<?php

namespace Database\Factories;

use App\Enums\TwoFactorProvider;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'avatar' => $this->faker->imageUrl(640, 480),
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => $this->faker->dateTime(),
            'cellphone' => $this->faker->unique()->e164PhoneNumber,
            'cellphone_verified_at' => $this->faker->dateTime(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'status' => new UserStatus($this->faker->randomElement(UserStatus::toArray())),
            'role_id' => $this->faker->numberBetween(1, 2),
            'two_factor_provider' => new TwoFactorProvider($this->faker->randomElement(TwoFactorProvider::toArray())),
        ];
    }
}
