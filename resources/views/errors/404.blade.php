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
                $ops = ["202+202=", "2·202=", "400+4=", "4040:10=", "101·4=", "444-44+4="];

                echo $ops[array_rand($ops)];
            @endphp
        </p>
        <h1 class="code">404</h1>
        <p>SELLIST LEHEKÜLGE EI LEITUD</p>

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