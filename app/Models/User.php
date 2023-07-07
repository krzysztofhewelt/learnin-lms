<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Collection;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * User
 *
 * @mixin Builder
 */
class User extends Authenticatable implements JWTSubject
{
	use HasApiTokens, HasFactory, Notifiable;

	public $timestamps = false;

	protected $guarded = ['account_role'];

	protected $hidden = ['password', 'remember_token', 'pivot'];

	protected $casts = [
		'email_verified_at' => 'datetime',
		'last_success_login' => 'datetime',
		'last_wrong_login' => 'datetime',
	];

	/*
	 * JWT
	 */

	public function getJWTIdentifier()
	{
		return $this->getKey();
	}

	public function getJWTCustomClaims()
	{
		return [];
	}

	/*
	 * Relations
	 */

	public function courses(): BelongsToMany
	{
		return $this->belongsToMany(Course::class, 'course_enrollments', 'user_ID', 'course_ID');
	}

	public function taskMarks(): BelongsToMany
	{
		return $this->belongsToMany(Task::class, 'tasks_marks', 'user_ID', 'task_ID')->withPivot([
			'points',
			'mark',
			'description',
		]);
	}

	public function student(): HasMany
	{
		return $this->hasMany(Student::class, 'user_ID', 'id');
	}

	public function teacher(): HasOne
	{
		return $this->hasOne(Teacher::class, 'user_ID', 'id');
	}

	public function taskUploads(): HasMany
	{
		return $this->hasMany(StudentFile::class, 'user_ID', 'id');
	}

	/*
	 * Business logic
	 */
	public function isAdmin(): bool
	{
		return $this->account_role == 'admin';
	}

	public function isStudent(): bool
	{
		return $this->account_role == 'student';
	}

	public function isTeacher(): bool
	{
		return $this->account_role == 'teacher';
	}

	public function isUserActive(): bool
	{
		return $this->active == 1;
	}

	public function getUser($userId): ?User
	{
		return $this->find($userId);
	}

	public function getAllUsers(): LengthAwarePaginator
	{
		return $this->orderBy('id')->paginate(15);
	}

	public function getTeachers($allTeachers): LengthAwarePaginator|Collection
	{
		return match ($allTeachers) {
			'all' => $this->where('account_role', 'teacher')
				->with('teacher')
				->get(),
			default => $this->where('account_role', 'teacher')
				->with('teacher')
				->paginate(15),
		};
	}

	public function getUserByEmail(string $email): ?User
	{
		return $this->where('email', $email)->first();
	}

	public function getUserRichInfo(int $userId): ?User
	{
		return $this->with('teacher', 'student')->find($userId);
	}

	public function searchUser(string $searchString): LengthAwarePaginator
	{
		return $this->where(function ($q) use ($searchString) {
			$q->orWhere('id', $searchString)
				->orWhere('name', 'like', '%' . $searchString . '%')
				->orWhere('surname', 'like', '%' . $searchString . '%')
				->orWhere('identification_number', 'like', '%' . $searchString . '%')
				->orWhere('email', 'like', '%' . $searchString . '%')
				->orWhere('account_role', 'like', '%' . $searchString . '%');
		})->paginate(25);
	}
}
