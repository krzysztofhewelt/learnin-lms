<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Task;
use App\Models\TaskMark;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    private User $userModel;
    private Course $courseModel;
    private Task $taskModel;
    private TaskMark $markModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->courseModel = new Course();
        $this->taskModel = new Task();
        $this->markModel = new TaskMark();
    }

    public function getDashboard() : JsonResponse
    {
        return response()->json([
            'user' => $this->userModel->getUserRichInfo(Auth::id()),
            'courses' => $this->courseModel->getLatestCourses(),
            'tasks' => $this->taskModel->getEndingSoonTasks(),
            'marks' => $this->markModel->getLatestUserMarks()
        ]);
    }
}
