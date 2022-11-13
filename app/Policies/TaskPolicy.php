<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
	use HandlesAuthorization;

	public function search(User $user): bool
	{
		return $user->isTeacher() || $user->isAdmin();
	}

	// user belongs to the course and its teacher
	// course that belongs task
	public function createUpdateDelete(User $user, Course $course): bool
	{
		return ($user->isTeacher() && $course->isTeacherOfCourse($user->id, $course->id)) ||
			$user->isAdmin();
	}

	// user belongs to course
	public function show(User $user, Course $course): bool
	{
		return $course->isUserBelongsToCourse($user->id, $course->id) || $user->isAdmin();
	}
}
