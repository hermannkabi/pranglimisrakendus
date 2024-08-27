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
        <link rel="shortcut icon" href="{{ URL::asset('favicon.png') }}" type="image/x-icon">

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/master.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/layout.css') }}">

        <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/spinner.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/variables.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/material-icons.css') }}">
        <!-- Scripts -->
        @routes
        @viteReactRefresh
        @vite(['resources/js/app.jsx', "resources/js/Pages/{$page['component']}.jsx"])
        @inertiaHead
    </head>
    <body translate="no" class="font-sans antialiased" >

        <noscript>
            <div style="display: flex; justify-content: center; height: 100vh;">
               <div>
                    <h1>JavaScript on keelatud</h1>
                    <p>Selle lehe jaoks on vaja lubada brauseris JavaScript</p>
                    <p>Kui sa seda ei oska, vaata <a href="https://enable-javascript.com">sellele lingile</a></p>
                </div> 
            </div>
            
        </noscript>
        
        @inertia
        <script>window.csrfToken = "{{ csrf_token() }}";</script>
        <script src="{{ URL::asset('js/mousetrap.js') }}"></script> 
        <script src="{{ URL::asset('js/jquery.js') }}"></script>
        <script src="{{ URL::asset('js/settings.js') }}"></script> 
    </body>
</html>
