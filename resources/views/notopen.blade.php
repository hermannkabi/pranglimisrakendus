<!DOCTYPE html>
<html lang="en">
<head>
    @include('includes.head', ["title"=>"Valimised on lõppenud!"])
</head>
<body>
    @include('includes.navbar')

    @include('includes.title', ["subtitle"=>"Valimised on lõppenud!"])

    <div style="margin-inline: var(--margin-inline)">
<<<<<<< Updated upstream
        <p>Kui sa juba hääletasid, jäi lugema sinu viimasena antud hääl, mis on antud enne valimiste lõppu. Kui sa valima ei jõudnud, pole see kahjuks enam võimalik. Oma valiku leiad <a href="{{route('valimised.profile')}}">siit</a>.</p>
=======
        <p>Kui sa juba hääletasid, jäi lugema sinu viimasena antud hääl, mis on antud enne valimiste lõppu. Oma valiku leiad <a href="{{route('valimised.profile')}}">siit</a>.</p>
>>>>>>> Stashed changes
    </div>
</body>
</html>