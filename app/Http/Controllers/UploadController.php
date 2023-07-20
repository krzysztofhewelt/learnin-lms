<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseFile;
use App\Models\StudentFile;
use App\Models\Task;
use App\Models\TaskFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class UploadController extends Controller
{
	private StudentFile $studentFileModel;
	private TaskFile $taskFileModel;
	private CourseFile $courseFileModel;
	private Task $taskModel;
	private Course $courseModel;

	public function __construct()
	{
		$this->studentFileModel = new StudentFile();
		$this->taskFileModel = new TaskFile();
		$this->courseFileModel = new CourseFile();
		$this->taskModel = new Task();
		$this->courseModel = new Course();
	}

	public function uploadResources(Request $request, int $resourceId): JsonResponse
	{
		$fileType = $request->fileType;
		$files = $request->file('files');

		$this->authorize('upload-files', [
			$fileType,
			$this->getCourseForResource($fileType, $resourceId),
		]);

		foreach ($files as $file) {
			// we are checking if file exists
			$fileToUpload = $this->getFileByName(
				$file->getClientOriginalName(),
				$fileType,
				$resourceId,
			);
			if ($fileToUpload != null) {
				$oldFileDir = $this->getFileDir($fileType, $resourceId, $fileToUpload->filename);
				if (Storage::exists($oldFileDir)) {
					Storage::delete($oldFileDir);
				}
			} else {
				$fileToUpload = $this->getInstanceOfNewFileObject($fileType);
			}

			if ($fileType == 'student_upload') {
				$fileToUpload->user_ID = Auth::id();
				$fileToUpload->task_ID = $resourceId;
			} elseif ($fileType == 'task_ref') {
				$fileToUpload->task_ID = $resourceId;
			} elseif ($fileType == 'course_file') {
				$fileToUpload->course_ID = $resourceId;
			}

			// fix for .exe files and nginx.:
			$filenameHashed =
				pathinfo($file->hashName(), PATHINFO_FILENAME) .
				'.' .
				$file->getClientOriginalExtension();
			$fileToUpload->filename = $filenameHashed;
			$fileToUpload->filename_original = $file->getClientOriginalName();

			$fileDir = $this->getFileDir(
				$fileType,
				$this->getCourseIdForResource($fileType, $resourceId),
			);
			Storage::putFileAs($fileDir, $file, $filenameHashed);

			$filesize = $this->filesize_conversion($file->getSize());
			$convertedFilesizeSplitted = explode(' ', $filesize);

			$fileToUpload->file_size = $convertedFilesizeSplitted[0];
			$fileToUpload->file_size_unit = $convertedFilesizeSplitted[1];
			$fileToUpload->save();
		}

		return response()->json([
			'success' => 'Files uploaded successfully',
		]);
	}

	private function getInstanceOfNewFileObject(
		string $fileType,
	): StudentFile|TaskFile|CourseFile|null {
		return match ($fileType) {
			'student_upload' => new StudentFile(),
			'task_ref' => new TaskFile(),
			'course_file' => new CourseFile(),
		};
	}

	private function getCourseForResource(string $fileType, int $resourceId)
	{
		if ($fileType == 'task_ref' || $fileType == 'student_upload') {
			return $this->taskModel
				->getTask($resourceId)
				->course()
				->first();
		} elseif ($fileType == 'course_file') {
			return $this->courseModel->getCourse($resourceId);
		}

		return null;
	}

	private function getCourseIdForResource(string $fileType, int $resourceId): int
	{
		if ($fileType == 'task_ref' || $fileType == 'student_upload') {
			return $this->taskModel->getTaskCourseId($resourceId);
		} elseif ($fileType == 'course_file') {
			return $resourceId;
		}

		return -1;
	}

	// get directories for given file type
	private function getFileDir(string $fileType, int $courseId, string $filename = null): string
	{
		return match ($fileType) {
			'student_upload' => '/courses/' . $courseId . '/tasks/students/' . $filename,
			'task_ref' => '/courses/' . $courseId . '/tasks/ref/' . $filename,
			'course_file' => '/courses/' . $courseId . '/files/' . $filename,
		};
	}

	private function getFileByName(
		string $filenameOriginal,
		string $fileType,
		int $resourceId,
	): StudentFile|TaskFile|CourseFile|null {
		return match ($fileType) {
			'student_upload' => $this->studentFileModel->getFileForTaskAndUser(
				$resourceId,
				Auth::id(),
				$filenameOriginal,
			),
			'task_ref' => $this->taskFileModel->getFileForTask($resourceId, $filenameOriginal),
			'course_file' => $this->courseFileModel->getFileForCourse(
				$resourceId,
				$filenameOriginal,
			),
		};
	}

	/*
	 * File size conversion and give correct unit
	 */
	private function filesize_conversion(float $size): string
	{
		$units = ['B', 'KB', 'MB', 'GB'];
		$power = $size > 0 ? floor(log($size, 1024)) : 0;
		return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
	}
}
