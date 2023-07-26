<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Pranglimisrakendus') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="{{ URL::asset('css/master.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('css/spinner.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('css/variables.css') }}">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

        <!-- Scripts -->
        @routes
        @viteReactRefresh
        @vite(['resources/js/app.jsx', "resources/js/Pages/{$page['component']}.jsx"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        
        @inertia
        <script>window.csrfToken = "{{ csrf_token() }}";</script>

    </body>
</html>
