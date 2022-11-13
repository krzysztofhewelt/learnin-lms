<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\CourseFile;
use App\Models\StudentFile;
use App\Models\TaskFile;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FilePolicy
{
	use HandlesAuthorization;

	// course file -> check if its teacher of given course
	// task ref file -> check if its teacher of given course
	// student upload file -> check if its student belongs to given course and check if task is not ended or its after open task date

	public function deleteFiles(
		User $user,
		string $fileType,
		Course $course,
		CourseFile|TaskFile|StudentFile $file,
	): bool {
		if ($fileType == 'student_upload') {
			return $user->isStudent() &&
				$course->isUserBelongsToCourse($user->id, $course->id) &&
				$file->user_ID == $user->id;
		} else {
			return ($user->isTeacher() && $course->isTeacherOfCourse($user->id, $course->id)) ||
				$user->isAdmin();
		}
	}

	public function downloadFiles(
		User $user,
		string $fileType,
		Course $course,
		CourseFile|TaskFile|StudentFile|null $file,
	): bool {
		if ($fileType == 'student_upload') {
			return ($user->isStudent() &&
				$course->isUserBelongsToCourse($user->id, $course->id) &&
				$file->user_ID == $user->id) ||
				($user->isTeacher() && $course->isUserBelongsToCourse($user->id, $course->id)) ||
				$user->isAdmin();
		} else {
			return $course->isUserBelongsToCourse($user->id, $course->id) || $user->isAdmin();
		}
	}

	public function uploadFiles(User $user, string $fileType, Course $course): bool
	{
		if ($fileType == 'student_upload') {
			return $user->isStudent() && $course->isUserBelongsToCourse($user->id, $course->id);
		} else {
			return ($user->isTeacher() && $course->isTeacherOfCourse($user->id, $course->id)) ||
				$user->isAdmin();
		}
	}
}
