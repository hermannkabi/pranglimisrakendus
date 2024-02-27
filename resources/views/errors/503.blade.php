<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>503 - Hooldus | Pranglimisrakendus</title>
    <link rel="stylesheet" href="{{ URL::asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/master.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/404.css') }}">
</head>
<body>
    <div class="error-section">
        <p class="operation">VIGA</p>
        <h1 class="code">503</h1>
        <p>SERVER ON HOOLDUSES</p>
    </div>

    <div class="symbs">
        <span>+</span>
        <span>-</span>
        <span>+</span>
        <span>×</span>
        <span>÷</span>
        <span>÷</span>
    </div>

    <script src="{{ URL::asset('js/jquery.js') }}"></script>
    <script>
        if(window.localStorage.getItem("app-theme") == "dark"){
            document.documentElement.setAttribute('data-theme', 'dark');
        }else{
            document.documentElement.setAttribute('data-theme', 'light');
        }

        if(window.localStorage.getItem("app-primary-color") != null && window.localStorage.getItem("app-primary-color") != "default"){
            document.documentElement.style.setProperty("--primary-color", window.localStorage.getItem("app-primary-color"));
        }
    </script>
</body>
</html>