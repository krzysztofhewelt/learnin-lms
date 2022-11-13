<?php

return [
	// VALIDATION ERRORS RELATED WITH TASK MARKS
	'mark_points' => 'Points for student :surname :name is not valid number.',
	'mark_mark' => 'Grade for student :surname :name must by number in range [2;5].',
	'validation_empty_assignedUsers' => 'The course must have at least one user assigned to it.',

	/*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

	'accepted' => ':attribute must be accepted.',
	'accepted_if' => ':attribute musi być zaakceptowany kiedy :other jest równy :value.',
	'active_url' => ':attribute nie jest prawidłowym adresem URL.',
	'after' => ':attribute must be later than :date.',
	'after_or_equal' => ':attribute musi być później lub równy :date.',
	'alpha' => ':attribute może zawierać wyłącznie litery.',
	'alpha_dash' => 'Pole może zawierać wyłącznie litery, cyfry, myślniki oraz znaki podkreślenia',
	'alpha_num' => 'Pole może zawierać wyłącznie litery i cyfry.',
	'array' => 'Field must be array.',
	'before' => ':attribute musi być wcześniej niż :date.',
	'before_or_equal' => ':attribute musi być wcześniej lub równy :date.',
	'between' => [
		'array' => ':attribute musi zawierać się w przedziale [:min; :max].',
		'file' =>
			'Rozmiar plików :attribute musi zawierać się w przedziale [:min; :max] kilobajtów.',
		'numeric' => 'Wartość :attribute musi zawierać się w zakresie [:min; :max].',
		'string' => 'Długość :attribute musi zawierać się w przedziale [:min; :max] znaków.',
	],
	'boolean' => 'Pole musi być prawdą lub fałszem.',
	'confirmed' => 'Pole nie jest identyczne.',
	'current_password' => 'Password is invalid.',
	'date' => 'Field is not valid date.',
	'date_equals' => ':attribute nie jest równa dacie :date.',
	'date_format' => 'The :attribute does not match the format :format.',
	'declined' => 'The :attribute must be declined.',
	'declined_if' => 'The :attribute must be declined when :other is :value.',
	'different' => 'The :attribute and :other must be different.',
	'digits' => 'The :attribute must be :digits digits.',
	'digits_between' => 'The :attribute must be between :min and :max digits.',
	'dimensions' => 'The :attribute has invalid image dimensions.',
	'distinct' => 'The :attribute field has a duplicate value.',
	'email' => 'Field is not valid email address.',
	'ends_with' => 'The :attribute must end with one of the following: :values.',
	'enum' => 'The selected :attribute is invalid.',
	'exists' => 'Selected value does not exists.',
	'file' => 'Field must contain files.',
	'filled' => 'Field can\'t be blank.',
	'gt' => [
		'array' => 'The :attribute must have more than :value items.',
		'file' => 'The :attribute must be greater than :value kilobytes.',
		'numeric' => 'The :attribute must be greater than :value.',
		'string' => 'The :attribute must be greater than :value characters.',
	],
	'gte' => [
		'array' => 'The :attribute must have :value items or more.',
		'file' => 'The :attribute must be greater than or equal to :value kilobytes.',
		'numeric' => 'The :attribute must be greater than or equal to :value.',
		'string' => 'The :attribute must be greater than or equal to :value characters.',
	],
	'image' => 'The :attribute must be an image.',
	'in' => 'Selected value is invalid.',
	'in_array' => 'The :attribute field does not exist in :other.',
	'integer' => 'The :attribute must be an integer.',
	'ip' => 'The :attribute must be a valid IP address.',
	'ipv4' => 'The :attribute must be a valid IPv4 address.',
	'ipv6' => 'The :attribute must be a valid IPv6 address.',
	'json' => 'The :attribute must be a valid JSON string.',
	'lt' => [
		'array' => 'The :attribute must have less than :value items.',
		'file' => 'File size must be less than :value kilobytes.',
		'numeric' => 'The :attribute must be less than :value.',
		'string' => 'The :attribute must be less than :value characters.',
	],
	'lte' => [
		'array' => 'The :attribute must not have more than :value items.',
		'file' => 'File size must be less or equal to :value kilobytes.',
		'numeric' => 'The :attribute must be less than or equal to :value.',
		'string' => 'The :attribute must be less than or equal to :value characters.',
	],
	'mac_address' => 'The :attribute must be a valid MAC address.',
	'max' => [
		'array' => 'The :attribute must not have more than :max items.',
		'file' => 'File size :attribute must not be greater to :max kilobytes.',
		'numeric' => 'The :attribute must not be greater than :max.',
		'string' => 'This field must not be greater than :max characters.',
	],
	'mimes' => 'The :attribute must be a file of type: :values.',
	'mimetypes' => 'The :attribute must be a file of type: :values.',
	'min' => [
		'array' => 'The :attribute must have at least :min items.',
		'file' => 'The :attribute must be at least :min kilobytes.',
		'numeric' => ':attribute musi mieć co najmniej :min.',
		'string' => 'Field must be at least :min characters long.',
	],
	'multiple_of' => 'The :attribute must be a multiple of :value.',
	'not_in' => 'Selected value is invalid.',
	'not_regex' => 'Field value is not in the correct format.',
	'numeric' => 'The :attribute must be a number.',
	'present' => 'The :attribute field must be present.',
	'prohibited' => 'The :attribute field is prohibited.',
	'prohibited_if' => 'The :attribute field is prohibited when :other is :value.',
	'prohibited_unless' => 'The :attribute field is prohibited unless :other is in :values.',
	'prohibits' => 'The :attribute field prohibits :other from being present.',
	'regex' => 'The :attribute format is invalid.',
	'required' => 'Field is required.',
	'required_array_keys' => 'The :attribute field must contain entries for: :values.',
	'required_if' => 'Field :attribute is required when :other is equal to :value.',
	'required_unless' => 'The :attribute field is required unless :other is in :values.',
	'required_with' => 'The :attribute field is required when :values is present.',
	'required_with_all' => 'The :attribute field is required when :values are present.',
	'required_without' => 'The :attribute field is required when :values is not present.',
	'required_without_all' => 'The :attribute field is required when none of :values are present.',
	'same' => 'The :attribute and :other must match.',
	'size' => [
		'array' => 'The :attribute must contain :size items.',
		'file' => 'The :attribute must be :size kilobytes.',
		'numeric' => 'The :attribute must be :size.',
		'string' => 'The :attribute must be :size characters.',
	],
	'starts_with' => 'The :attribute must start with one of the following: :values.',
	'string' => 'The :attribute must be a string.',
	'timezone' => 'The :attribute must be a valid timezone.',
	'unique' => 'Field value is already taken.',
	'uploaded' => 'The :attribute failed to upload.',
	'url' => 'The :attribute must be a valid URL.',
	'uuid' => 'The :attribute must be a valid UUID.',

	/*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

	'custom' => [
		'password' => [
			'regex' =>
				'Password must contain at least one big and small letter and special character and must have at least 8 characters.',
		],

		'email' => [
			'exists' => 'A user with the specified email address does not exists.',
			'unique' => 'A user with the specified email address already exists.',
		],
	],

	/*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

	'attributes' => [],
];
