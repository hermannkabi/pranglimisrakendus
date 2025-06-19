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

<<<<<<< Updated upstream
        <input style="width: min(360px, calc(100% - 16px));" name="name" type="text" placeholder="Rebase nimi *" required>
        <input style="width: min(360px, calc(100% - 16px));" name="instagram" type="text" placeholder="Instagram">
        <input style="width: min(360px, calc(100% - 16px));" name="facebook" type="text" placeholder="Facebook">
=======
        <input style="width: min(360px, calc(100% - 16px));" name="first_name" type="text" placeholder="Rebase eesnimi *" required>
        <input style="width: min(360px, calc(100% - 16px));" name="last_name" type="text" placeholder="Rebase perekonnanimi *">
>>>>>>> Stashed changes

        <button type="submit">Lisa</button>
    </form>

</body>
</html>