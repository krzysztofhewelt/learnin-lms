<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\TaskMark;
use Illuminate\Http\JsonResponse;
use App\Models\StudentFile;
use App\Models\Task;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
	private Course $courseModel;
	private Task $taskModel;
	private StudentFile $studentFileModel;
	private TaskMark $taskMarkModel;

	public function __construct()
	{
		$this->courseModel = new Course();
		$this->taskModel = new Task();
		$this->studentFileModel = new StudentFile();
		$this->taskMarkModel = new TaskMark();
	}

	public function searchTask(Request $request): JsonResponse
	{
		$this->authorize('search-task');

		return response()->json($this->taskModel->searchTask($request->searchString));
	}

	public function getUserTasks(): JsonResponse
	{
		$tasks = $this->taskModel->getTasksAllForUser(Auth::id());
		return response()->json($tasks);
	}

	public function getTasksForCategory($categoryId): JsonResponse
	{
		$tasks = $this->taskModel->getTasksForCategory($categoryId);
		if ($tasks == null) {
			return response()->json('');
		}

		return response()->json($tasks);
	}

	public function create(TaskRequest $request): JsonResponse
	{
		$course = $this->courseModel->getCourse($request->course_ID);
		if ($course == null) {
			return response()->json(['error' => 'Course does not exists!'], 404);
		}

		$this->authorize('manage-task', $course);

		$task = $this->taskModel->create($request->validated());

		return response()->json([
			'success' => 'Task added successfully',
			'task_ID' => $task->id,
		]);
	}

	public function show(int $taskId): JsonResponse
	{
		$task = $this->taskModel->getTaskWithCategoryAndFiles($taskId);
		if ($task == null) {
			return response()->json(['error' => 'Task does not exists!'], 404);
		}

		$this->authorize('show-task', $task->course);

		// get uploaded student files
		$studentFiles = $this->studentFileModel->getUploadedFilesForTask(Auth::id(), $taskId);

		// get mark for this task
		$studentMark = $this->taskMarkModel->getUserMarkForTask($taskId);

		return response()->json([
			'task' => $task,
			'studentFiles' => $studentFiles,
			'userMark' => $studentMark,
		]);
	}

	public function update(TaskRequest $request, int $taskId): JsonResponse
	{
		$task = $this->taskModel->getTask($taskId);
		if ($task == null) {
			return response()->json(['error' => 'Task does not exists!'], 404);
		}

		$this->authorize('manage-task', $task->course);

		$task->update($request->validated());

		return response()->json([
			'success' => 'Task updated successfully',
			'task_ID' => $task->id,
		]);
	}

	public function delete(int $taskId): JsonResponse
	{
		$task = $this->taskModel->getTask($taskId);
		if ($task == null) {
			return response()->json(['error' => 'Task does not exists!'], 404);
		}

		$this->authorize('manage-task', $task->course);

		$task->delete();

		return response()->json(['success' => 'Task deleted successfully']);
	}

	public function showStudentsUploads(int $taskId): JsonResponse
	{
		$task = $this->taskModel->getTask($taskId);
		if ($task == null) {
			return response()->json(['error' => 'Task does not exists!'], 404);
		}

		$this->authorize('manage-task', $task->course);

		return response()->json([
			'task' => $task,
			'student_files' => $this->studentFileModel->getStudentsUploadedFilesForTask($taskId),
		]);
	}
}
