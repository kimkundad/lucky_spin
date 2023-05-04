<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>SPA Authentication using Laravel 9 Sanctum, Vue 3 and Vite - TechvBlogs</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Kanit" rel="stylesheet">
        <link href="{{ url('style.css') }}" rel="stylesheet">

        @vite(['resources/js/app.js'])
    </head>
    <body>
        <div class="chakra-container-600">
            <div id="app">
                <router-view></router-view>
            </div>
        </div>
    </body>
</html>