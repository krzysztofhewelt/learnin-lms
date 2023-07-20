<?php

use App\Http\Controllers\CourseCategoryController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskMarkController;
use App\Http\Controllers\TaskStatsController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChangeSelfPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\LocaleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// login user
Route::post('login', [AuthController::class, 'login'])->middleware(['localization']);

// refresh the JWT token
Route::get('refresh', [AuthController::class, 'refresh']);

// change user's locale
Route::post('locale', [LocaleController::class, 'setLocale']);

// sites for authenticated users
Route::middleware(['auth:api', 'localization'])->group(function () {
	// dashboard (home)
	Route::get('dashboard', [DashboardController::class, 'getDashboard']);

	// admin section
	Route::prefix('admin')->group(function () {
		Route::get('users', [AdminController::class, 'showAllUsers']);
		Route::get('courses', [AdminController::class, 'showAllCourses']);
		Route::get('tasks', [AdminController::class, 'showAllTasks']);
	});

	// user section
	Route::prefix('users')->group(function () {
		Route::patch('update-teacher/{id}', [UserController::class, 'updateTeacher']);
		Route::put('create-or-edit/{id}', [UserController::class, 'createOrUpdate']);
		Route::get('show/{id}', [UserController::class, 'getUserProfile']);
		Route::delete('delete/{id}', [UserController::class, 'delete']);
		Route::get('courses/{id}', [CourseController::class, 'showUserCourses']);
		Route::get('teachers', [UserController::class, 'getAllTeachers']);
	});

	// personal account
	Route::get('profile', [UserController::class, 'getUserProfile']);

	// change password controller
	Route::post('change-password', [ChangeSelfPasswordController::class, 'changePassword']);

	// courses section
	Route::prefix('courses')->group(function () {
		Route::get('', [CourseController::class, 'showUserCourses']);
		Route::post('create', [CourseController::class, 'create']);
		Route::get('details/{id}', [CourseController::class, 'show']);
		Route::patch('edit/{id}', [CourseController::class, 'update']);
		Route::delete('delete/{id}', [CourseController::class, 'delete']);

		// assign users to the course
		Route::post('assign/{id}', [CourseController::class, 'assignUsers']);
	});

	// course categories section
	Route::prefix('categories')->group(function () {
		Route::get('show/{id}', [CourseCategoryController::class, 'show']);
		Route::get('course/{id}', [CourseCategoryController::class, 'showCourseCategories']);
		Route::patch('edit/{id}', [CourseCategoryController::class, 'update']);
		Route::post('create/course/{id}', [CourseCategoryController::class, 'create']);
		Route::delete('delete/{id}', [CourseCategoryController::class, 'delete']);
	});

	// tasks section
	Route::prefix('tasks')->group(function () {
		Route::get('', [TaskController::class, 'getUserTasks']);
		Route::get('show/category/{id}', [TaskController::class, 'getTasksForCategory']);
		Route::post('create', [TaskController::class, 'create']);
		Route::patch('edit/{id}', [TaskController::class, 'update']);
		Route::get('show/{id}', [TaskController::class, 'show']);
		Route::delete('delete/{id}', [TaskController::class, 'delete']);

		// get student uploaded files
		Route::get('students_uploads/{id}', [TaskController::class, 'showStudentsUploads']);

		// make zip and download from student uploaded files
		Route::get('students_uploads/{id}/zip', [
			DownloadController::class,
			'generateZipAndDownload',
		]);
	});

	// task marks section
	Route::prefix('marks')->group(function () {
		Route::get('', [TaskMarkController::class, 'getUserMarks']);
		Route::get('task/{id}', [TaskMarkController::class, 'getStudentsMarksForTask']);
		Route::post('task/{id}', [TaskMarkController::class, 'updateStudentsMarksForTask']);
	});

	// statistics section
	Route::prefix('statistics')->group(function () {
		Route::get('task/{id}', [TaskStatsController::class, 'computeMarksForTask']);
		Route::get('course/{course}/{categoryId}', [
			TaskStatsController::class,
			'computeAvgMarksForCourseCategory',
		]);
	});

	// upload section
	Route::post('upload/{id}/{fileType}', [UploadController::class, 'uploadResources']);

	// download section
	Route::get('download/{id}/{fileType}', [DownloadController::class, 'downloadResources']);

	// delete resources section
	Route::delete('delete-resource/{id}/{fileType}', [FileController::class, 'deleteResources']);

	// logout user
	Route::post('logout', [AuthController::class, 'logout']);
});
