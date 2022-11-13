<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "scien_degree" => $this->faker->randomElement([
                "dr",
                "mgr",
                "prof.",
                "dr hab.",
                "inż.",
                "lic.",
            ]),
            "business_email" => $this->faker->unique()->safeEmail,
            "contact_number" => $this->faker->phoneNumber,
            "room" =>
                $this->faker->randomLetter .
                "-" .
                $this->faker->randomNumber(3),
            "consultation_hours" =>
                $this->faker->dayOfWeek .
                " " .
                $this->faker->numberBetween(9, 15) .
                ":00",
        ];
    }
}
