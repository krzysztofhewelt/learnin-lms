<?php

return [
	// VALIDATION ERRORS RELATED WITH TASK MARKS
	'mark_points' => 'Liczba punktów dla studenta :surname :name nie jest prawidłową liczbą.',
	'mark_mark' =>
		'Ocena studenta :surname :name musi być liczbą rzeczywistą i zawierać się w przedziale [2;5].',
	'validation_empty_assignedUsers' =>
		'Kurs musi mieć przypisanego co najmniej jednego użytkownika',

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

	'accepted' => ':attribute musi być zaakceptowany.',
	'accepted_if' => ':attribute musi być zaakceptowany kiedy :other jest równy :value.',
	'active_url' => ':attribute nie jest prawidłowym adresem URL.',
	'after' => 'Data musi być późniejsza niż data rozpoczęcia.',
	'after_or_equal' => ':attribute musi być później lub równy :date.',
	'alpha' => ':attribute może zawierać wyłącznie litery.',
	'alpha_dash' => 'Pole może zawierać wyłącznie litery, cyfry, myślniki oraz znaki podkreślenia',
	'alpha_num' => 'Pole może zawierać wyłącznie litery i cyfry.',
	'array' => 'Pole musi być tablicą.',
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
	'current_password' => 'Podane hasło jest niepoprawne.',
	'date' => 'Pole nie jest prawidłową datą.',
	'date_equals' => ':attribute nie jest równa dacie :date.',
	'date_format' => 'The :attribute does not match the format :format.',
	'declined' => 'The :attribute must be declined.',
	'declined_if' => 'The :attribute must be declined when :other is :value.',
	'different' => 'The :attribute and :other must be different.',
	'digits' => 'The :attribute must be :digits digits.',
	'digits_between' => 'The :attribute must be between :min and :max digits.',
	'dimensions' => 'The :attribute has invalid image dimensions.',
	'distinct' => 'The :attribute field has a duplicate value.',
	'email' => 'Podano nieprawidłowy adres email.',
	'ends_with' => 'The :attribute must end with one of the following: :values.',
	'enum' => 'The selected :attribute is invalid.',
	'exists' => 'Zaznaczona wartość nie istnieje.',
	'file' => 'Pole musi zawierać pliki.',
	'filled' => 'Pole nie może być puste.',
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
	'in' => 'Zaznaczona wartość nie jest prawidłowa.',
	'in_array' => 'The :attribute field does not exist in :other.',
	'integer' => 'The :attribute must be an integer.',
	'ip' => 'The :attribute must be a valid IP address.',
	'ipv4' => 'The :attribute must be a valid IPv4 address.',
	'ipv6' => 'The :attribute must be a valid IPv6 address.',
	'json' => 'The :attribute must be a valid JSON string.',
	'lt' => [
		'array' => 'The :attribute must have less than :value items.',
		'file' => 'Rozmiar każdego z plików musi być mniejszy niż :value kilobajtów.',
		'numeric' => 'The :attribute must be less than :value.',
		'string' => 'The :attribute must be less than :value characters.',
	],
	'lte' => [
		'array' => 'The :attribute must not have more than :value items.',
		'file' => 'Rozmiar pliku :attribute musi być mniejszy bądź równy :value kilobajtów.',
		'numeric' => 'The :attribute must be less than or equal to :value.',
		'string' => 'The :attribute must be less than or equal to :value characters.',
	],
	'mac_address' => 'The :attribute must be a valid MAC address.',
	'max' => [
		'array' => 'The :attribute must not have more than :max items.',
		'file' => 'Rozmiar pliku :attribute nie może być wiekszy niż :max kilobajtów.',
		'numeric' => 'The :attribute must not be greater than :max.',
		'string' => 'Pole nie może zawierać więcej niż :max znaków.',
	],
	'mimes' => 'The :attribute must be a file of type: :values.',
	'mimetypes' => 'The :attribute must be a file of type: :values.',
	'min' => [
		'array' => 'The :attribute must have at least :min items.',
		'file' => 'The :attribute must be at least :min kilobytes.',
		'numeric' => ':attribute musi mieć co najmniej :min.',
		'string' => 'Pole musi zawierać co najmniej :min znaków.',
	],
	'multiple_of' => 'The :attribute must be a multiple of :value.',
	'not_in' => 'Zaznaczona wartość nie jest prawidłowa.',
	'not_regex' => 'Podana wartość pola nie jest w prawidłowym formacie.',
	'numeric' => 'The :attribute must be a number.',
	'present' => 'The :attribute field must be present.',
	'prohibited' => 'The :attribute field is prohibited.',
	'prohibited_if' => 'The :attribute field is prohibited when :other is :value.',
	'prohibited_unless' => 'The :attribute field is prohibited unless :other is in :values.',
	'prohibits' => 'The :attribute field prohibits :other from being present.',
	'regex' => 'The :attribute format is invalid.',
	'required' => 'Pole jest wymagane.',
	'required_array_keys' => 'The :attribute field must contain entries for: :values.',
	'required_if' => 'Pole :attribute jest wymagane kiedy :other jest równy :value.',
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
	'unique' => 'The :attribute jest już zajęte.',
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
				'Hasło musi zawierać co najmniej jedną wielką i małą literę oraz znak specjalny oraz musi mieć co najmniej 8 znaków.',
		],

		'current_password' => [
			'regex' =>
				'Hasło musi zawierać co najmniej jedną wielką i małą literę oraz znak specjalny oraz musi mieć co najmniej 8 znaków.',
		],

		'new_password' => [
			'regex' =>
				'Hasło musi zawierać co najmniej jedną wielką i małą literę oraz znak specjalny oraz musi mieć co najmniej 8 znaków.',
			'different' => 'Hasło musi różnić się od obecnego hasła.',
		],

		'email' => [
			'exists' => 'Użytkownik o podanym adresie email nie istnieje.',
			'unique' => 'Użytkownik o podanym adresie email już istnieje.',
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
