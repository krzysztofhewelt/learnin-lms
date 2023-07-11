<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class ChangeSelfPasswordRequest extends FormRequest
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
			'current_password' => [
				'required',
				function ($attribute, $value, $fail) {
					if (!Hash::check($value, User::find(Auth::id())->password)) {
						$fail(trans('auth.incorrect_password'));
					}
				},
			],
			'new_password' => [
				'required',
				'regex:/^.*(?=.{1,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).{8,255}$/',
				'different:current_password',
			],
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
