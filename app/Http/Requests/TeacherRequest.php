<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class TeacherRequest extends FormRequest
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
			'scien_degree' => 'min:2|max:50',
			'business_email' => 'email|max:255',
			'contact_number' => 'sometimes|max:15',
			'room' => 'sometimes|max:20',
			'consultation_hours' => 'sometimes|max:255',
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
