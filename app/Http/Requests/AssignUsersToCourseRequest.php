<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class AssignUsersToCourseRequest extends FormRequest
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

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array
	{
		return [
			'assignedUsers' => 'required|array',
			'assignedUsers.*' => 'required|exists:users,id',
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
