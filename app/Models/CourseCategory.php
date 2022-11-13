<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * CourseCategory
 *
 * @mixin Builder
 */
class CourseCategory extends Model
{
	use HasFactory;

	protected $table = 'course_categories';
	public $timestamps = false;
	protected $guarded = [];
	protected $hidden = ['pivot'];

	public function course(): BelongsTo
	{
		return $this->belongsTo(Course::class, 'course_ID', 'id');
	}

	public function tasks(): HasMany
	{
		return $this->hasMany(Task::class, 'course_ID', 'id');
	}

	/*
	 * Business logic
	 */
	public function getCourseCategory(int $courseCategoryId): ?CourseCategory
	{
		return $this->find($courseCategoryId);
	}
}
