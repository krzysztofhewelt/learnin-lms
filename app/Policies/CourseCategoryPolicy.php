<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CourseCategoryPolicy
{
	use HandlesAuthorization;

	public function createUpdateDeleteCourseCategory(User $user, Course $course): bool
	{
		// check if user is teacher of course
		return ($user->isTeacher() && $course->isTeacherOfCourse($user->id, $course->id)) ||
			$user->isAdmin();
	}

	public function showCourseCategories(User $user, Course $course): bool
	{
		// check if user belongs to course
		return $course->isUserBelongsToCourse($user->id, $course->id) || $user->isAdmin();
	}
}
