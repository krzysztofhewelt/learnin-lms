<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Learnin</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
        </style>

        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <link rel="icon" href="favicon.ico">

    </head>
    <body class="antialiased font-sans">

        <div id="app"></div>
        <script src="{{ asset('/js/main.js') }}"></script>
        <noscript>Your browser does not support JavaScript!</noscript>
    </body>
</html>
