<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskMark;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class TaskMarkController extends Controller
{
	private TaskMark $taskMarkModel;
	private Task $taskModel;

	public function __construct()
	{
		$this->taskMarkModel = new TaskMark();
		$this->taskModel = new Task();
	}

	public function getUserMarks(): JsonResponse
	{
		return response()->json($this->taskMarkModel->getUserMarks(Auth::id()));
	}

	public function getStudentsMarksForTask(int $taskId): JsonResponse
	{
		$task = $this->taskModel->getTask($taskId);
		if ($task == null) {
			return response()->json(['error' => 'Task does not exists!'], 404);
		}

		$this->authorize('manage-task', $task->course);

		// get students marks
		$studentsMarks = $this->taskMarkModel->getMarksForTask($taskId);

		return response()->json([
			'task' => $task,
			'student_marks' => $studentsMarks,
		]);
	}

	public function updateStudentsMarksForTask(Request $request, int $taskId): JsonResponse
	{
		// check if tasks exists
		$task = $this->taskModel->getTask($taskId);
		if ($task == null) {
			return response()->json(['error' => 'Task does not exists!'], 404);
		}

		$this->authorize('manage-task', $task->course);

		// build sync array from request
		$syncArray = [];
		foreach ($request->marks as $mark) {
			if ($mark['points'] != null || $mark['mark'] != null || $mark['description'] != null) {
				// mark, points -> required
				// description -> optional
				$studentMark = $mark['mark'];
				$points = $mark['points'];
				$description = $mark['description'];

				// check if mark can
				if ($studentMark != (float) $studentMark || $studentMark < 2 || $studentMark > 5) {
					return response()->json(
						[
							'error' => __('validation.mark_mark', [
								'surname' => $mark['surname'],
								'name' => $mark['name'],
							]),
						],
						422,
					);
				}

				// check if points can be parsed to float
				if ($points != (float) $points) {
					return response()->json(
						[
							'error' => __('validation.mark_points', [
								'surname' => $mark['surname'],
								'name' => $mark['name'],
							]),
						],
						422,
					);
				}

				// build the sync array
				$syncArray[$mark['user_ID']] = [
					'points' => floatval($points),
					'mark' => $studentMark,
					'description' => $description,
					'updated_at' => now(),
				];
			}
		}

		$task->userMarks()->sync($syncArray);

		return response()->json(['success' => 'Marks saved successfully']);
	}
}
