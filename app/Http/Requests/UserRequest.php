<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UserRequest extends FormRequest
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
			// user roles - for all roles
			'name' => 'required|min:3|max:45',
			'surname' => 'required|min:2|max:45',
			'identification_number' =>
				'required|max:15|unique:users,identification_number,' . $this->route('id'),
			'email' => 'required|email|unique:users,email,' . $this->route('id'),
			'password' => [
				'sometimes',
				'min:8',
				'max:255',
				'regex:/^.*(?=.{1,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
			],
			'account_role' => ['required', Rule::in(['admin', 'student', 'teacher'])],

			// student rules
			'student' => 'required_if:account_role,==,student|array',
			'student.*.field_of_study' => 'required_if:account_role,==,student|max:50',
			'student.*.semester' => 'required_if:account_role,==,student|numeric',
			'student.*.year_of_study' => 'required_if:account_role,==,student|max:10',
			'student.*.mode_of_study' => 'required_if:account_role,==,student|max:20',

			// teacher rules
			'teacher.scien_degree' => 'required_if:account_role,==,teacher|min:2|max:50',
			'teacher.business_email' => 'required_if:account_role,==,teacher|email|min:3|max:255',
			'teacher.contact_number' => 'sometimes|max:15',
			'teacher.room' => 'sometimes|max:20',
			'teacher.consultation_hours' => 'sometimes|max:255',
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
