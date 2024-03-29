<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileDownloadRequest;
use App\Models\CourseFile;
use App\Models\StudentFile;
use App\Models\Task;
use App\Models\TaskFile;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use ZipArchive;

class DownloadController extends Controller
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

	public function downloadResources(FileDownloadRequest $request, int $resourceId)
	{
		$fileType = $request->fileType;
		$resourceFile = $this->getInstanceOfFileObject($fileType, $resourceId);

		$resourceCourse = $resourceFile->getCourse($resourceId);

		$this->authorize('download-files', [$fileType, $resourceCourse, $resourceFile]);

		if ($resourceFile != null) {
			$resourceFileDir = $this->getFileDir(
				$fileType,
				$resourceFile->filename,
				$resourceCourse->id,
			);

			if (Storage::exists($resourceFileDir)) {
				return Storage::download($resourceFileDir, $resourceFile->filename_original);
			} else {
				return response()->json(
					['error' => 'File does not exists!'],
					Response::HTTP_NOT_FOUND,
				);
			}
		} else {
			return response()->json(['error' => 'File does not exists!'], Response::HTTP_NOT_FOUND);
		}
	}

	// generate zip and download
	public function generateZipAndDownload(int $taskId)
	{
		$task = $this->taskModel->getTask($taskId);
		$studentFiles = $this->studentFileModel->getStudentsUploadedFilesForTask($taskId);

		$this->authorize('download-files', ['taskStudentZip', $task->course, null]);

		Storage::makeDirectory('zips'); // without this, ZipArchive can't create directory and can't create .zip

		$zipName = 'task - ' . $taskId . ' student files.zip';
		$zipDir = Storage::path('zips/' . $zipName);

		$zip = new ZipArchive();

		if ($zip->open($zipDir, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
			foreach ($studentFiles as $student) {
				$studentDirName =
					$student['surname'] .
					' ' .
					$student['name'] .
					' - ' .
					$student['identification_number'];

				$zip->addEmptyDir($studentDirName);

				foreach ($student['files'] as $uploadedFiles) {
					$filePath = $this->getFileDir(
						'student_upload',
						$uploadedFiles['filename'],
						$task['course_ID'],
					);

					if (Storage::exists($filePath)) {
						$zip->addFile(
							Storage::path($filePath),
							$studentDirName . '/' . $uploadedFiles['filename_original'],
						);
					}
				}
			}

			$zip->close();

			$headers = [
				'Content-Type' => 'application/zip',
			];

			return response()->download($zipDir, $zipName, $headers);
		} else {
			return response()->json(
				['error' => 'Can\'t generate zip right now!'],
				Response::HTTP_INTERNAL_SERVER_ERROR,
			);
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
