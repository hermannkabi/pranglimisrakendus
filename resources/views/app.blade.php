<!DOCTYPE html>
<html data-theme="dark" lang="et" translate="no" class="notranslate">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="content-language" content="et" />
        <meta name="description" content="Reaaler muudab matemaatika säravaks. Reealer on Tallinna Reaalkooli arvutusäpp, millega õpilane saab peastarvutamist õppida moodsalt, mugavalt ja motiveeritult."/>
        <meta name="keywords" content="pranglimine, reaaler, arvutusäpp, Tallinna Reaalkool, reaalkool">
        <meta property="og:image" content="https://reaaler.real.edu.ee/logo.png" />
        <meta property="og:title" content="Reaaler">
        <meta property="og:description" content="Reealer on Tallinna Reaalkooli arvutusäpp, millega saad peastarvutamist õppida moodsalt, mugavalt ja motiveeritult">
        <meta property="og:url" content="https://reaaler.real.edu.ee">
        <meta property="og:type" content="website">
        
        <title inertia>{{ config('app.name', 'Reaaler') }}</title>
        <link rel="icon" href="{{ URL::asset('favicon.ico') }}">
        <!-- Fonts -->
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/material-icons.css?v=2') }}">
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/master.css?v=2') }}">
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/layout.css?v=2') }}">

        <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/spinner.css?v=2') }}">
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/variables.css?v=2') }}">
        <!-- Scripts -->
        @routes
        @viteReactRefresh
        @vite(['resources/js/app.jsx', "resources/js/Pages/{$page['component']}.jsx"])
        @inertiaHead
    </head>
    <body translate="no" class="font-sans antialiased" >        
        @inertia
        <script>window.csrfToken = "{{ csrf_token() }}";</script>
        <script src="{{ URL::asset('js/mousetrap.js') }}"></script> 
        <script src="{{ URL::asset('js/jquery.js') }}"></script>
        <script src="{{ URL::asset('js/settings.js') }}"></script> 
    </body>
</html>
