<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * TaskMark
 *
 * @mixin Builder
 */
class TaskMark extends Model
{
	use HasFactory;

	protected $table = 'tasks_marks';
	protected $hidden = ['pivot'];

	public function task(): BelongsTo
	{
		return $this->belongsTo(Task::class, 'task_ID', 'id');
	}

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class, 'user_ID', 'id');
	}

	/*
	 * Business logic
	 */
	public function getLatestUserMarks(): ?Collection
	{
		return $this->where('user_ID', Auth::id())
			->with('task', 'task.course', 'task.category')
			->orderBy('updated_at', 'desc')
			->limit(6)
			->get();
	}

	public function getUserMarks(int $userId): LengthAwarePaginator
	{
		return $this->where('user_ID', $userId)
			->with('task', 'task.course', 'task.category')
			->orderBy('updated_at', 'desc')
			->paginate(15);
	}

	public function getUserMarkForTask(int $taskId): ?TaskMark
	{
		return $this->where([
			'user_ID' => Auth::id(),
			'task_ID' => $taskId,
		])->first();
	}

	public function getMarksForTask(int $taskId): array
	{
		$task = new Task();
		$courseId = $task->getTaskCourseId($taskId);

		// get all students ids from given course
		$courses = new Course();
		$courseStudentsIds = trim($courses->getCourseStudentsIds($courseId), '[]');
		if ($courseStudentsIds == null) {
			$courseStudentsIds = 'null';
		}

		return DB::select(
			'SELECT users.id AS user_ID,name,surname,identification_number,tasks_marks.id AS mark_ID,points,mark,description,updated_at FROM users
                   LEFT JOIN tasks_marks ON users.id = tasks_marks.user_ID AND task_ID=?
                   WHERE users.id IN (' .
				$courseStudentsIds .
				')
                   ORDER BY surname;',
			[$taskId],
		);
	}

	public function getMarksPointsAvgForCourseCategory(int $courseCategoryId, int $courseId): array
	{
		// get tasks ids for which we want avg
		$tasks = new Task();
		$tasksIds = $tasks->getTasksIdsForCategory($courseCategoryId);
		$tasksCount = count($tasksIds);
		if ($tasksCount == 0) {
			return [];
		}

		$tasksIds = trim($tasksIds, '[]');
		if ($tasksIds == null) {
			$tasksIds = 'null';
		}

		// get all students ids from given course
		$courses = new Course();
		$courseStudentsIds = trim($courses->getCourseStudentsIds($courseId), '[]');
		if ($courseStudentsIds == null) {
			$courseStudentsIds = 'null';
		}

		// perform raw query to maximize performance -> we want name, surname, ... for show avg marks and points for whole category
		return DB::select(
			'SELECT users.id,name,surname,identification_number,COALESCE(SUM(mark)/?) AS \'mark\', COALESCE(SUM(points)/?) AS \'points\'
                          FROM users
                          LEFT JOIN tasks_marks ON tasks_marks.user_ID = users.id AND task_ID IN (?)
                          WHERE users.id IN (' .
				$courseStudentsIds .
				')
                          GROUP BY users.id,name,surname,identification_number
                          ORDER BY surname;',
			[$tasksCount, $tasksCount, $tasksIds],
		);
	}

	public function getMarksForTaskAvg(int $taskId): array
	{
		$task = new Task();
		$taskCourseId = $task->getTaskCourseId($taskId);

		$course = new Course();
		$courseStudentsIds = trim($course->getCourseStudentsIds($taskCourseId), '[]');

		// get overall avg from task
		$taskAvg = DB::select(
			'SELECT COALESCE(AVG(mark), \'n/a\') AS \'avg(mark)\', COALESCE(AVG(points), \'n/a\') AS \'avg(points)\' FROM tasks_marks WHERE task_ID = ?;',
			[$taskId],
		);

		// get student marks for task
		$studentsMarks = DB::select(
			'SELECT users.id,name,surname,identification_number,COALESCE(mark, \'n/a\') AS \'mark\', COALESCE(points, \'n/a\') AS \'points\'
                           FROM users
                           LEFT JOIN tasks_marks ON tasks_marks.user_ID = users.id AND task_ID IN (?)
                           WHERE users.id IN (' .
				$courseStudentsIds .
				')
                           GROUP BY users.id,name,surname,identification_number,mark,points
                           ORDER BY surname;',
			[$taskId],
		);

		return [
			[
				'taskAvg' => $taskAvg,
				'studentMarks' => $studentsMarks,
			],
		];
	}
}
