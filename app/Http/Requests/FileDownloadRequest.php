<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class FileDownloadRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(): bool
	{
		return true;
	}

	public function prepareForValidation(): void
	{
		$this->merge(['fileType' => $this->route('fileType')]);
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array
	{
		return [
			'fileType' => ['required', Rule::in(['student_upload', 'task_ref', 'course_file'])],
		];
	}

	protected function failedValidation(Validator $validator): void
	{
		$errors = $validator->errors();

		throw new HttpResponseException(
			response()->json(
				[
					'errors' => $errors,
				],
				Response::HTTP_UNPROCESSABLE_ENTITY,
			),
		);
	}
}
