<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
        $year = $this->faker->numberBetween(2018, 2022);

		return [
            'field_of_study' => $this->faker->randomElement([
                'Computer Science',
                'Electronics and telecommunication',
                'Electrotechnics',
            ]),
            'semester' => $this->faker->numberBetween(1, 7),
            'year_of_study' => $year . ' ' . ++$year,
            'mode_of_study' => $this->faker->randomElement(['stationary', 'non-stationary']),
        ];
	}
}
