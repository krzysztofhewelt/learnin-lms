<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignUsersToCourseRequest;
use App\Http\Requests\CourseRequest;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    private Course $courseModel;

    public function __construct()
    {
        $this->courseModel = new Course();
    }

    public function assignUsers(AssignUsersToCourseRequest $request, int $courseId): JsonResponse
    {
        $course = $this->courseModel->getCourse($courseId);
        if($course == null)
            return response()->json(['error' => 'Course does not exists!'], 404);

        $this->authorize('manage-courses', $course);

        $assignedUsers = $request->assignedUsers;
        $course->users()->sync($assignedUsers);

        return response()->json(['success' => 'Users has been assigned to this course']);
    }

    public function searchCourse(Request $request) : JsonResponse
    {
        $this->authorize('create-courses');

        return response()->json($this->courseModel->searchCourse($request->searchString));
    }

    public function showUserCourses(int $userId = null): JsonResponse
    {
        if($userId == null)
            $userId = Auth::id();

        return response()->json($this->courseModel->getUserCourses($userId));
    }

    public function create(CourseRequest $request): JsonResponse
    {
        $this->authorize('create-courses');

        $course = $this->courseModel->create($request->validated());

        // attach current user to course
        $course->users()->attach(Auth::id());

        return response()->json(['success' => 'Course created successfully', 'course_ID' => $course->id]);
    }

    public function show(int $courseId): JsonResponse
    {
        $course = $this->courseModel->getCourseDetails($courseId);
        if($course == null)
            return response()->json(['error' => 'Course does not exists!'], 404);

        $this->authorize('show-courses', $course);

        return response()->json($course);
    }

    public function update(CourseRequest $request, int $courseId): JsonResponse
    {
        $course = $this->courseModel->getCourse($courseId);
        if($course == null)
            return response()->json(['error' => trans('general.save')], 404);

        $this->authorize('manage-courses', $course);

        $course->update($request->validated());

        return response()->json(['success' => 'Course updated successfully', 'course_ID' => $course->id]);
    }


    public function delete(int $courseId): JsonResponse
    {
        $course = $this->courseModel->getCourse($courseId);
        if($course == null)
            return response()->json(['error' => 'Course does not exists!'], 404);

        $this->authorize('manage-courses', $course);

        // delete course from database and all related files in storage directory
        if($course->delete())
            if(Storage::exists('/courses/' . $courseId))
                Storage::delete('/courses/' . $courseId);

        return response()->json(['success' => 'Course deleted successfully']);
    }
}
