<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskStatsPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user, Course $course) : bool
    {
        return ($user->isTeacher() && $course->isTeacherOfCourse($user->id, $course->id)) || $user->isAdmin();
    }
}
