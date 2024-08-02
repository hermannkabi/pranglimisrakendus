<!DOCTYPE html>
<html lang="en">
<head>
    @include("includes.head", ["title"=>$name])
</head>
<body>
    @include("includes.navbar")

    @include("includes.title", ["subtitle"=>$name])

    <div style="margin-inline: 10%; font-size: 24px">

        <p style="word-wrap: anywhere">E-post: {{ Auth::user()->email}}</p>
        @if ($fox)
            <p>Sinu rebane on {{$fox->name}}</p>
        
        @else
            <p>Sul ei ole veel rebast</p>
        @endif

        <div style="height: 50px"></div>
        <form action="{{route('logout')}}">
            <button>Logi välja</button>
        </form>

    </div>
</body>
</html>