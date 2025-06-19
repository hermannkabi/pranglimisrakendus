<!DOCTYPE html>
<html lang="en">
<head>
    @include('includes.head', ["title"=>"Valimised on lõppenud!"])
</head>
<body>
    @include('includes.navbar')

    @include('includes.title', ["subtitle"=>"Valimised on lõppenud!"])

    <div style="margin-inline: var(--margin-inline)">
        <p>Kui sa juba hääletasid, jäi lugema sinu viimasena antud hääl, mis on antud enne valimiste lõppu. Oma valiku leiad <a href="{{route('valimised.profile')}}">siit</a>.</p>
    </div>
</body>
</html>