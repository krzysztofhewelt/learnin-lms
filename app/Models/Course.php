<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

/**
 * Course
 *
 * @mixin Builder
 */
class Course extends Model
{
    use HasFactory;

    protected $table = 'courses';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'available_from',
        'available_to',
    ];

    //protected $primaryKey = 'id';

    protected $hidden = ['pivot'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'course_enrollments', 'course_ID', 'user_ID')
            ->orderByDesc('account_role')
			->orderBy('surname');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'course_ID', 'id');
    }

    public function categories(): HasMany
    {
        return $this->hasMany(CourseCategory::class, 'course_ID', 'id');
    }

    public function courseFiles(): HasMany
    {
        return $this->hasMany(CourseFile::class, 'course_ID', 'id')->orderByDesc('updated_at');
    }

    /*
     * Business logic
     */
    public function getLatestCourses() : ?Collection
    {
        return (new User)->find(Auth::id())
            ->courses()
            ->orderBy('available_from')
            ->with(['users' => function($q) {
                $q->where('users.account_role', '=', 'teacher')
                    ->select(['name', 'surname', 'account_role']);
            }])
            ->limit(3)
            ->get();
    }

    public function isUserBelongsToCourse(int $userId, int $courseId) : bool
    {
        return $this->find($courseId)
                ->users()
                ->wherePivot('user_ID', $userId)
                ->count() > 0;
    }

    public function isTeacherOfCourse(int $userId, int $courseId) : bool
    {
        return $this->find($courseId)
                ->users()
                ->wherePivot('user_ID', $userId)
                ->where('account_role', 'teacher')
                ->count() > 0;
    }

    public function getAllCourses() : LengthAwarePaginator
    {
        return $this->paginate(15);
    }

    public function getCourse(int $courseId) : ?Course
    {
        return $this->find($courseId);
    }

    public function getUserCourses(int $userId) : ?Collection
    {
        return (new User)->find($userId)
            ->courses()
            ->with(['users' => function($q) {
                $q->where('users.account_role', '=', 'teacher')
                    ->select(['name', 'surname', 'account_role']);
            }])
            ->get();
    }

    public function getUserCoursesId(int $userId) : ?Collection
    {
        return (new User)->find($userId)
            ->courses()
            ->get()
            ->pluck('id');
    }

    public function getCourseCategories(int $courseId) : ?Course
    {
        return $this->with('categories')
            ->select(['id', 'name'])
            ->find($courseId);
    }

    public function getCourseDetails(int $courseId) : ?Course
    {
        return $this->with(['users:id,surname,name,account_role', 'categories', 'courseFiles'])
			->find($courseId);
    }

    public function getCourseParticipantsIds(int $courseId) : ?Array
    {
        return $this->where('id', $courseId)
            ->with('users:id')
            ->get()
            ->pluck('users.*.id', 'id')[$courseId];
    }

    public function getCourseStudentsIds(int $courseId): ?Collection
    {
        return (new User)->select('id')
            ->where('account_role', 'student')
            ->whereIn('users.id', $this->getCourseParticipantsIds($courseId))
            ->get()
            ->pluck('id');
    }

    public function searchCourse(string $searchString) : LengthAwarePaginator
    {
        return $this
            ->where('name', 'like', '%' . $searchString . '%')
            ->paginate(15);
    }
}
