<!DOCTYPE html>
<html lang="en">
<head>
    @include("includes.head", ["title"=>"Uus rebane"])
</head>
<body>
    @include("includes.navbar")

    @include("includes.title", ["subtitle"=>"Uus rebane"])

    @if (Session::has('success'))
        <div style="margin-inline: 10%; color: grey;">
            {{ Session::get('success') }}
        </div>
    @endif

    @error("name")
        <div style="margin-inline: 10%; color: red;">
            {{ $message }}
        </div>
    @enderror
    <form action="{{ route("valimised.addFoxPost") }}" method="post" style="margin-inline: 10%">
        @csrf

        <input name="name" type="text" placeholder="Rebase nimi *" required>
        <input name="instagram" type="text" placeholder="Instagram">
        <input name="facebook" type="text" placeholder="Facebook">

        <button type="submit">Lisa</button>
    </form>

</body>
</html>