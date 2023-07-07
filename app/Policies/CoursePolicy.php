<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoursePolicy
{
	use HandlesAuthorization;

	public function createCourse(User $user): bool
	{
		return $user->isTeacher() || $user->isAdmin();
	}

	public function editDeleteCourse(User $user, Course $course): bool
	{
		// check if user is teacher of course
		return ($user->isTeacher() && $course->isTeacherOfCourse($user->id, $course->id)) ||
			$user->isAdmin();
	}

	public function showCourse(User $user, Course $course): bool
	{
		// check if user belongs to course or is admin
		return $course->isUserBelongsToCourse($user->id, $course->id) || $user->isAdmin();
	}
}
