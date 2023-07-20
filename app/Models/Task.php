<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Task
 *
 * @mixin Builder
 */
class Task extends Model
{
	use HasFactory;

	public $timestamps = false;
	protected $guarded = [];
	protected $hidden = ['pivot'];

	public function course(): BelongsTo
	{
		return $this->belongsTo(Course::class, 'course_ID', 'id');
	}

	public function category(): BelongsTo
	{
		return $this->belongsTo(CourseCategory::class, 'course_category', 'id');
	}

	public function taskFiles(): HasMany
	{
		return $this->hasMany(TaskFile::class, 'task_ID', 'id')->orderByDesc('updated_at');
	}

	public function studentFiles(): HasMany
	{
		return $this->hasMany(StudentFile::class, 'task_ID', 'id')->orderByDesc('updated_at');
	}

	public function userMarks(): BelongsToMany
	{
		return $this->belongsToMany(User::class, 'tasks_marks', 'task_ID', 'user_ID')->withPivot(
			'points',
			'mark',
			'description',
		);
	}

	/*
	 * Business logic
	 */
	public function getEndingSoonTasks(): ?Collection
	{
		$coursesIds = (new Course())->getUserCoursesId(Auth::id());

		return $this->whereIn('course_ID', $coursesIds)
			->with('course', 'category')
			->where('available_to', '>=', now())
			->orderBy('available_to')
			->limit(6)
			->get();
	}

	public function getAllTasksWithCourseAndCategory(): LengthAwarePaginator
	{
		return $this->with('course', 'category')->paginate(15);
	}

	public function getTask(int $taskId): ?Task
	{
		return $this->find($taskId);
	}

	public function getTaskCourseId(int $taskId): int
	{
		return $this->where('id', $taskId)->pluck('course_ID')[0];
	}

	public function getTaskWithCategoryAndFiles(int $taskId): ?Task
	{
		return $this->with('course:id,name', 'category:id,name', 'taskFiles')->find($taskId);
	}

	public function getTasksAllForUser(int $userId): ?Collection
	{
		$coursesIds = (new Course())->getUserCoursesId($userId);

		return $this->whereIn('course_ID', $coursesIds)
			->with([
				'course:id,name',
				'category',
				'userMarks' => function ($q) use ($userId) {
					$q->where('users.id', '=', $userId)->select('mark', 'points');
				},
			])
			->get();
	}

	public function getTasksForCategory(int $categoryId): ?Collection
	{
		return $this->where('course_category', $categoryId)
			->with([
				'course:id,name',
				'category',
				'userMarks' => function ($q) {
					$q->where('users.id', '=', Auth::id())->select('mark', 'points');
				},
			])
			->get();
	}

	public function getTasksIdsForCategory(int $categoryId): ?Collection
	{
		return $this->where('course_category', $categoryId)
			->get()
			->pluck('id');
	}

	public function searchTasks(string $searchString = null): LengthAwarePaginator
	{
		return $searchString == null
			? $this->getAllTasksWithCourseAndCategory()
			: $this->where(function ($q) use ($searchString) {
				$q->orWhere('name', 'like', '%' . $searchString . '%')
					->orWhere('max_points', 'like', '%' . $searchString . '%')
					->orWhere('available_from', 'like', '%' . $searchString . '%')
					->orWhere('available_to', 'like', '%' . $searchString . '%')
					->orWhereHas('course', function ($query) use ($searchString) {
						$query->where('name', 'like', '%' . $searchString . '%');
					})
					->orWhereHas('category', function ($query) use ($searchString) {
						$query->where('name', 'like', '%' . $searchString . '%');
					});
			})
				->with('course', 'category')
				->paginate(15);
	}
}
