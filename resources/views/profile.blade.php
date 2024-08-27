<!DOCTYPE html>
<html lang="en">
<head>
    @include("includes.head", ["title"=>$name])
</head>
<body>
    @include("includes.navbar")

    @include("includes.title", ["subtitle"=>$name])

    <div style="margin-inline: var(--margin-inline); font-size: 24px">

        <p style="word-wrap: anywhere">E-post: {{ Auth::user()->email}}</p>
        @if (count($fox) >= 1)
            <p>Sinu reba{{count($fox) == 1 ? "ne" : "sed"}} on {{$fox->first()->name}} {{count($fox) > 1 ? (" ja " . $fox->get(1)->name) : ""}} </p>
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