<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AdminController extends Controller
{
	private Course $courseModel;
	private Task $taskModel;
	private User $userModel;

	public function __construct()
	{
		$this->courseModel = new Course();
		$this->taskModel = new Task();
		$this->userModel = new User();
	}

	public function showAllCourses(): JsonResponse
	{
		$this->authorize('view-admin-pages');
		return response()->json($this->courseModel->searchCourses(request()->search));
	}

	public function showAllUsers(): JsonResponse
	{
		return response()->json($this->userModel->searchUsers(request()->search));
	}

	public function showAllTasks(): JsonResponse
	{
		$this->authorize('view-admin-pages');
		return response()->json($this->taskModel->searchTasks(request()->search));
	}
}
