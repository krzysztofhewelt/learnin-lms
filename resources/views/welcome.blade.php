<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="theme-color" content="#4f46e5">

        <title>Learnin</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        @vite('resources/css/app.css')

        <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    </head>
    <body class="antialiased font-sans">
        <div id="app"></div>

        @vite('resources/js/main.js')
        <noscript>Your browser does not support JavaScript!</noscript>
    </body>
</html>
