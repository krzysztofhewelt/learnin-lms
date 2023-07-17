<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * StudentFile
 *
 * @mixin Builder
 */
class StudentFile extends Model
{
	use HasFactory;

	protected $table = 'users_tasks_uploads';
	protected $fillable = ['filename_original', 'course_ID'];
	protected $hidden = ['pivot', 'filename'];

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
	public function getFileForTaskAndUser(
		int $taskId,
		int $userId,
		string $filenameOriginal,
	): ?StudentFile {
		return $this->where([
			'task_ID' => $taskId,
			'filename_original' => $filenameOriginal,
			'user_ID' => $userId,
		])->first();
	}

	public function getFile(int $fileId): ?StudentFile
	{
		return $this->find($fileId);
	}

	public function getCourse(int $fileId): Course
	{
		return $this->getFile($fileId)->task->course;
	}

	public function getUploadedFilesForTask(int $userId, int $taskId): ?Collection
	{
		return $this->where([
			'user_ID' => $userId,
			'task_ID' => $taskId,
		])->get();
	}

	public function getStudentsUploadedFilesForTask(int $taskId)
	{
		// get course id for given task
		$task = new Task();
		$taskCourseId = $task->getTaskCourseId($taskId);

		// get only students from given course
		$course = new Course();
		$courseStudentsIds = trim($course->getCourseStudentsIds($taskCourseId), '[]');
		if ($courseStudentsIds == null) {
			$courseStudentsIds = 'null';
		}

		$studentFilesForTask = DB::select(
			'SELECT users.id AS user_ID,name,surname,identification_number,users_tasks_uploads.id AS file_ID,filename,filename_original,file_size,file_size_unit,created_at,updated_at
                           FROM users
                           LEFT JOIN users_tasks_uploads
                           ON users.id = users_tasks_uploads.user_ID AND task_ID=?
                           WHERE users.id IN (' .
				$courseStudentsIds .
				')
                           ORDER BY surname;',
			[$taskId],
		);

		$studentFilesForTask = json_decode(json_encode($studentFilesForTask), true);

		// let's group user files by user_id
		$studentFiles = array_reduce(
			$studentFilesForTask,
			function ($accumulator, $item) {
				$index = $item['user_ID'];

				if (!isset($accumulator[$index])) {
					$accumulator[$index] = [
						'user_ID' => $item['user_ID'],
						'identification_number' => $item['identification_number'],
						'name' => $item['name'],
						'surname' => $item['surname'],
						'files' => [],
					];
				}

				if ($item['file_ID'] != null) {
					$accumulator[$index]['files'][] = [
						'file_ID' => $item['file_ID'],
						'filename' => $item['filename'],
						'filename_original' => $item['filename_original'],
						'file_size' => $item['file_size'],
						'file_size_unit' => $item['file_size_unit'],
						'created_at' => $item['created_at'],
						'updated_at' => $item['updated_at'],
					];
				}

				return $accumulator;
			},
			[],
		);

		usort($studentFiles, function ($item1, $item2) {
			return $item1['surname'] <=> $item2['surname'];
		});

		return $studentFiles;
	}
}
