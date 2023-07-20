<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseCategoryRequest;
use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CourseCategoryController extends Controller
{
	private CourseCategory $courseCategoryModel;
	private Course $courseModel;

	public function __construct()
	{
		$this->courseCategoryModel = new CourseCategory();
		$this->courseModel = new Course();
	}

	public function create(CourseCategoryRequest $request, int $courseId): JsonResponse
	{
		$course = $this->courseModel->getCourse($courseId);
		if ($course == null) {
			return response()->json(
				['error' => 'Course does not exists!'],
				Response::HTTP_NOT_FOUND,
			);
		}

		$this->authorize('manage-course-categories', $course);

		$category = $this->courseCategoryModel->create($request->validated());

		return response()->json(
			[
				'success' => 'Course category added successfully',
				'id' => $category->id,
			],
			Response::HTTP_CREATED,
		);
	}

	public function show(int $categoryId): JsonResponse
	{
		$category = $this->courseCategoryModel->getCourseCategory($categoryId);
		if ($category == null) {
			return response()->json(
				['error' => 'Category does not exists'],
				Response::HTTP_NOT_FOUND,
			);
		}

		$this->authorize('show-course-categories', $category->course);

		return response()->json($category);
	}

	public function showCourseCategories(int $courseId): JsonResponse
	{
		$courseCategories = $this->courseModel->getCourseCategories($courseId);
		if ($courseCategories == null) {
			return response()->json(
				['error' => 'Course does not exists!'],
				Response::HTTP_NOT_FOUND,
			);
		}

		$this->authorize('show-course-categories', $courseCategories);

		return response()->json($courseCategories);
	}

	public function update(CourseCategoryRequest $request, int $courseCategoryId): JsonResponse
	{
		$courseCategory = $this->courseCategoryModel->getCourseCategory($courseCategoryId);
		if ($courseCategory == null) {
			return response()->json(
				['error' => 'Course category does not exists!'],
				Response::HTTP_NOT_FOUND,
			);
		}

		$this->authorize('manage-course-categories', $courseCategory->course);

		$courseCategory->update($request->validated());

		return response()->json(['success' => 'Course category updated']);
	}

	public function delete(int $courseCategoryId): JsonResponse
	{
		$courseCategory = $this->courseCategoryModel->getCourseCategory($courseCategoryId);
		if ($courseCategory == null) {
			return response()->json(
				['error' => 'Course category does not exists!'],
				Response::HTTP_NOT_FOUND,
			);
		}

		$this->authorize('manage-course-categories', $courseCategory->course);

		$courseCategory->delete();

		return response()->json(['success' => 'Course category deleted']);
	}
}
