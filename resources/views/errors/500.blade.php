<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>500 - Serveripoolne viga | Reaaler</title>
    <link rel="stylesheet" href="{{ URL::asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/master.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/404.css') }}">
</head>
<body>
    <div class="error-section">
        @php
            $ops = ["555-55=", "66+6·(66+6)+(6+6):6=", "2·5·2·5·5=", "7·(77-7)+(77-7):7=", "8·8·8-(88+8):8=", "5555:55*5-5="];

            echo $ops[array_rand($ops)];
        @endphp
        <h1 class="code">500</h1>
        <p>SERVERIPOOLNE VIGA</p>
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
    <script src="{{ URL::asset('js/theme.js') }}" ></script>

</body>
</html>