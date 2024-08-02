<!DOCTYPE html>
<html lang="en">
<head>
    @include('includes.head', ["title"=>"Valimised on lõppenud!"])
</head>
<body>
    @include('includes.navbar')

    @include('includes.title', ["subtitle"=>"Valimised on lõppenud!"])

    <div style="margin-inline: 10%">
        <p>Kui sa juba hääletasid, jäi lugema sinu viimasena antud hääl, mis on antud enne valimiste lõppu. Kui sa valima ei jõudnud, pole see kahjuks enam võimalik. Oma valiku leiad <a href="{{route('valimised.profile')}}">siit</a>.</p>
    </div>
</body>
</html>