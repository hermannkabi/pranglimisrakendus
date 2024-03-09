<!DOCTYPE html>
<html data-theme="dark" lang="et" translate="no" class="notranslate">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="content-language" content="et" />
        <meta name="description" content="Pranglimisrakendus on Tallinna Reaalkooli arvutusäpp"/>

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

        <noscript>
            <div style="display: flex; justify-content: center; height: 100vh;">
               <div>
                    <img style="border-radius: 8px; height: 100px;" src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/99/Unofficial_JavaScript_logo_2.svg/220px-Unofficial_JavaScript_logo_2.svg.png" alt="">
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
