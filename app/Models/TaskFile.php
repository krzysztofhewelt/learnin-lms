<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * TaskFile
 *
 * @mixin Builder
 */
class TaskFile extends Model
{
	use HasFactory;

	protected $table = 'task_referential_files';
	protected $guarded = [];
	protected $hidden = ['pivot', 'filename'];

	public function task(): BelongsTo
	{
		return $this->belongsTo(Task::class, 'task_ID', 'id');
	}

	/*
	 * Business logic
	 */
	public function getFile(int $fileId): ?TaskFile
	{
		return $this->find($fileId);
	}

	public function getCourse(int $fileId): Course
	{
		return $this->getFile($fileId)->task->course;
	}

	public function getFileForTask(int $taskId, string $filenameOriginal): ?TaskFile
	{
		return $this->where([
			'task_ID' => $taskId,
			'filename_original' => $filenameOriginal,
		])->first();
	}
}
