<?php

namespace App\Providers;


use App\Policies\AdminPolicy;
use App\Policies\CourseCategoryPolicy;
use App\Policies\CoursePolicy;
use App\Policies\FilePolicy;
use App\Policies\TaskPolicy;
use App\Policies\TaskStatsPolicy;
use App\Policies\UserPolicy;
use Faker\Core\File;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // USERS
        // we are creating gates, because policies doesn't work properly
        Gate::define('view-admin-pages', [AdminPolicy::class, 'viewAny']);
        Gate::define('manage-users', [UserPolicy::class, 'createUpdateDelete']);
        Gate::define('update-teacher', [UserPolicy::class, 'updateTeacher']);

        // COURSES
        Gate::define('create-courses', [CoursePolicy::class, 'createCourse']);
        Gate::define('show-courses', [CoursePolicy::class, 'showCourse']);
        Gate::define('manage-courses', [CoursePolicy::class, 'editDeleteCourse']);

        // COURSE CATEGORIES
        Gate::define('manage-course-categories', [CourseCategoryPolicy::class, 'createUpdateDeleteCourseCategory']);
        Gate::define('show-course-categories', [CourseCategoryPolicy::class, 'showCourseCategories']);

        // TASK STATS
        Gate::define('view-marks-statistics', [TaskStatsPolicy::class, 'viewAny']);

        // TASKS
        Gate::define('search-task', [TaskPolicy::class, 'search']);
        Gate::define('manage-task', [TaskPolicy::class, 'createUpdateDelete']);
        Gate::define('show-task', [TaskPolicy::class, 'show']);

        // FILES
        Gate::define('download-files', [FilePolicy::class, 'downloadFiles']);
        Gate::define('upload-files', [FilePolicy::class, 'uploadFiles']);
        Gate::define('delete-files', [FilePolicy::class, 'deleteFiles']);
    }
}
