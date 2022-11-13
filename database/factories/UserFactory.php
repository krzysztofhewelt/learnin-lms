<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "name" => $this->faker->firstName,
            "surname" => $this->faker->lastName,
            "identification_number" => $this->faker->unique()->randomNumber(5),
            "email" => $this->faker->unique()->safeEmail,
            "password" => bcrypt("User#12345"),
            "active" => 1,
        ];
    }
}
