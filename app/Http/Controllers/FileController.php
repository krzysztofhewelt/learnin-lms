<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileDownloadRequest;
use App\Models\CourseFile;
use App\Models\StudentFile;
use App\Models\Task;
use App\Models\TaskFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
	private StudentFile $studentFileModel;
	private TaskFile $taskFileModel;
	private CourseFile $courseFileModel;
	private Task $taskModel;

	public function __construct()
	{
		$this->studentFileModel = new StudentFile();
		$this->taskFileModel = new TaskFile();
		$this->courseFileModel = new CourseFile();
		$this->taskModel = new Task();
	}

	public function deleteResources(FileDownloadRequest $request, int $resourceId): JsonResponse
	{
		$fileType = $request->file_type;
		$resourceFile = $this->getInstanceOfFileObject($fileType, $resourceId);
		$resourceCourse = $resourceFile->getCourse($resourceId);

		$this->authorize('delete-files', [$fileType, $resourceCourse, $resourceFile]);

		if ($resourceFile != null) {
			$resourceFileDir = $this->getFileDir(
				$fileType,
				$resourceFile->filename,
				$resourceCourse->id,
			);

			if (Storage::exists($resourceFileDir)) {
				Storage::delete($resourceFileDir);
			}

			$resourceFile->delete();

			return response()->json(['success' => 'File deleted successfully']);
		} else {
			return response()->json(['error' => 'File does not exists!'], 404);
		}
	}

	// get instance of file type
	// StudentFile, TaskFile, CourseFile
	private function getInstanceOfFileObject(
		string $fileType,
		int $resourceId,
	): StudentFile|TaskFile|CourseFile|null {
		return match ($fileType) {
			'student_upload' => $this->studentFileModel->getFile($resourceId),
			'task_ref' => $this->taskFileModel->getFile($resourceId),
			'course_file' => $this->courseFileModel->getFile($resourceId),
		};
	}

	private function getFileDir(string $fileType, string $filename, int $courseId): string
	{
		return match ($fileType) {
			'student_upload' => '/courses/' . $courseId . '/tasks/students/' . $filename,
			'task_ref' => '/courses/' . $courseId . '/tasks/ref/' . $filename,
			'course_file' => 'courses/' . $courseId . '/files/' . $filename,
		};
	}
}
