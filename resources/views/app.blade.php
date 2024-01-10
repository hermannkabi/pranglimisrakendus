<!DOCTYPE html>
<html data-theme="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title inertia>{{ config('app.name', 'Pranglimisrakendus') }}</title>
        <link rel="shortcut icon" href="{{ URL::asset('favicon.png') }}" type="image/x-icon">

        <!-- Fonts -->
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/master.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/spinner.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/variables.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/material-icons.css') }}">

        <!-- Scripts -->
        @routes
        @viteReactRefresh
        @vite(['resources/js/app.jsx', "resources/js/Pages/{$page['component']}.jsx"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased" >
        
        @inertia
        <script>window.csrfToken = "{{ csrf_token() }}";</script>
        <script src="{{ URL::asset('js/mousetrap.js') }}"></script> 
        <script src="{{ URL::asset('js/jquery.js') }}"></script> 
        <script>
            if(window.localStorage.getItem("app-theme") == "dark"){
                document.documentElement.setAttribute('data-theme', 'dark');
            }else{
                document.documentElement.setAttribute('data-theme', 'light');
            }
        </script>
    </body>
</html>
