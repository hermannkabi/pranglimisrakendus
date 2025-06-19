<!DOCTYPE html>
<html lang="en">
<head>
    @include("includes.head", ["title"=>"Halda valimisi"])
</head>
<body>
    @include("includes.navbar")

    @include("includes.title", ["subtitle"=>"Halda valimisi"])


    <form action="{{ route("valimised.propertiesPost") }}" method="post" style="margin-inline: var(--margin-inline)">
        @csrf

        <label for="opens_at">Valimiste algusaeg</label>
        <input value="{{$opens_at}}" type="datetime-local" name="opens_at" id="">

        <label for="closes_at">Valimiste lõpuaeg</label>
        <input value="{{ $closes_at }}" type="datetime-local" name="closes_at" id="">

        <label for="second_fox_allowed">Kahe rebase valimise õigus (semikooloniga eraldatud e-postid)</label>
        <input value="{{$second_fox_allowed}}" type="text" name="second_fox_allowed" id="">

        <input style="display: inline" {{$test ? "checked" : ""}} type="checkbox" name="test" id="" value="1"> <label for="test">Valimiste testversioon</label><br><br>

        <button type="submit">Salvesta</button>
        
    </form>

   @if ($test)
        <form action="{{route("valimised.clearResults")}}" method="POST" style="margin-inline: var(--margin-inline)">
            @csrf
            <br>
            <button class="outlined" type="submit">Lähtesta valimistulemused</button>
        </form>
    @endif

</body>
</html>