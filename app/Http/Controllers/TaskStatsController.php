<?php

namespace App\Http\Controllers;

use App\Models\CourseCategory;
use App\Models\Task;
use App\Models\TaskMark;
use Illuminate\Http\JsonResponse;

class TaskStatsController extends Controller
{
	// stats for given task
	// stats for whole course category

	private Task $taskModel;
	private TaskMark $taskMarkModel;
	private CourseCategory $courseCategoryModel;

	public function __construct()
	{
		$this->taskModel = new Task();
		$this->taskMarkModel = new TaskMark();
		$this->courseCategoryModel = new CourseCategory();
	}

	// if course category doesnt exists, course also (relations)
	public function computeAvgMarksForCourseCategory(
		int $courseId,
		int $courseCategoryId,
	): JsonResponse {
		$courseCategory = $this->courseCategoryModel->getCourseCategory($courseCategoryId);
		if ($courseCategory == null) {
			return response()->json(['error' => 'Course category does not exists'], 404);
		}

		// check if given course belongs to course category
		if ($courseCategory->course_ID != $courseId) {
			return response()->json([
				'error' => 'This course category does not belongs to this course',
			]);
		}

		$this->authorize('view-marks-statistics', $courseCategory->course);

		return response()->json(
			$this->taskMarkModel->getMarksPointsAvgForCourseCategory($courseCategoryId, $courseId),
		);
	}

	public function computeMarksForTask(int $taskId): JsonResponse
	{
		$task = $this->taskModel->getTask($taskId);
		if ($task == null) {
			return response()->json(['error' => 'Task does not exists!'], 404);
		}

		$this->authorize('view-marks-statistics', $task->course);

		return response()->json($this->taskMarkModel->getMarksForTaskAvg($taskId));
	}
}
