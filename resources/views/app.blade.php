<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title inertia>{{ config('app.name', 'Pranglimisrakendus') }}</title>
        <link rel="shortcut icon" href="{{ URL::asset('favicon.png') }}" type="image/x-icon">

        <!-- Fonts -->
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/master.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/spinner.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/variables.css') }}">
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/icon?family=Material+Icons">

        <!-- Scripts -->
        @routes
        @viteReactRefresh
        @vite(['resources/js/app.jsx', "resources/js/Pages/{$page['component']}.jsx"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        
        @inertia
        <script>window.csrfToken = "{{ csrf_token() }}";</script>
        <script src="https://craig.global.ssl.fastly.net/js/mousetrap/mousetrap.min.js?a4098"></script> 
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> 
    </body>
</html>
