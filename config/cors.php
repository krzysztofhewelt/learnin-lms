<?php

return [
	/*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

	'paths' => ['api/*', 'sanctum/csrf-cookie'],

	'allowed_methods' => ['GET', 'POST', 'PATCH', 'DELETE'],

	'allowed_origins' => ['http://127.0.0.1:8000'],

	'allowed_origins_patterns' => [],

	'allowed_headers' => ['Content-Type', 'Authorization', 'X-Requested-With'],

	'exposed_headers' => ['Authorization', 'Content-Disposition'],

	'max_age' => 0,

	'supports_credentials' => true,
];
