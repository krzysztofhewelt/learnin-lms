<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class TaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() : bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'name' => 'required|min:5|max:100',
            'description' => 'required|min:5|max:255',
            'available_from' => 'required|date',
            'available_to' => 'nullable|date|after:available_from',
            'max_points' => 'required|numeric',
            'course_ID' => 'required',
            'course_category' => [
                'required',
                Rule::exists('course_categories', 'id')->where(function($q) {
                    $q->where(['course_ID' => request()->course_ID, 'id' => request()->course_category]);
                })
            ]
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        $errors = $validator->errors();

        throw new HttpResponseException(response()->json([
            'errors' => $errors
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
