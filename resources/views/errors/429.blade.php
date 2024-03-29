<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>404 - Ei leitud | Reaaler</title>
    <link rel="stylesheet" href="{{ URL::asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/master.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/404.css') }}">
</head>
<body>
    <div class="error-section">
        <p class="operation">
            @php
                $ops = ["400+20+9=", "136,624·3,14=", "3·11·13=", "429429:1001=", "(999-99·9)·4-3=", "(1+1+1)·(11+1+1+1)·11="];

                echo $ops[array_rand($ops)];
            @endphp
        </p>
        <h1 class="code">429</h1>
        <p>LIIGA PALJU PÄRINGUID</p>

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
    <script src="{{ URL::asset('js/settings.js') }}" ></script>
    
</body>
</html>