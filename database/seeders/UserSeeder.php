<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run(): void
	{
		// default admin account
		//		User::create([
		//			'account_role' => 'admin',
		//			'name' => 'Jan',
		//			'surname' => 'Kowalski',
		//			'identification_number' => '12345',
		//			'email' => 'email@email.com',
		//			'password' => bcrypt('Admin#12345'),
		//			'active' => 1,
		//		]);

		// teachers
		User::factory(5)
			->sequence(
				fn($sequence) => [
					'email' => 'teacher' . $sequence->index + 1 . '@email.com',
				],
			)
			->create([
				'account_role' => 'teacher',
			])
			->each(function ($user) {
				Teacher::factory(1)->create(['user_ID' => $user->id]);
			});

		// students
		User::factory(15)
			->sequence(
				fn($sequence) => [
					'email' => 'student' . $sequence->index + 1 . '@email.com',
				],
			)
			->create([
				'account_role' => 'student',
			])
			->each(function ($user) {
				Student::factory(1)->create(['user_ID' => $user->id]);
			});
	}
}
