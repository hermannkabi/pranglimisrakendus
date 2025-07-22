<!DOCTYPE html>
<html lang="en">
<head>
    @include("includes.head", ["title"=>"Uus rebane"])
</head>
<body>
    @include("includes.navbar")

    @include("includes.title", ["subtitle"=>"Uus rebane"])

    @if (Session::has('success'))
        <div style="margin-inline: var(--margin-inline); color: grey;">
            {{ Session::get('success') }}
        </div>
    @endif

    @error("name")
        <div style="margin-inline: var(--margin-inline); color: red;">
            {{ $message }}
        </div>
    @enderror
    <form action="{{ route("valimised.addFoxPost") }}" method="post" style="margin-inline: var(--margin-inline)">
        @csrf

        <textarea name="foxnames" id="" style="width: min(350px, calc(100% - 16px)); height: 300px; background-color: var(--background-color); color: var(--color)" placeholder="Sisesta või kopeeri rebaste nimed siia (üks nimi rea kohta)"></textarea>

        <button type="submit">Lisa</button>
    </form>

</body>
</html>