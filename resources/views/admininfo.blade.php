<!DOCTYPE html>
<html lang="en">
<head>
    @include("includes.head", ["title"=>"Admin info"])
</head>
<body>
    @include("includes.navbar")

    @include("includes.title", ["subtitle"=>"Admin info"])

    <div style="margin-inline: var(--margin-inline); position: relative; width: min(360px, calc(100% - 16px));">

    <p>Loodetavasti peaks rakenduse kasutamine suhteliselt lihtne olema, aga kirjutan mõned asjad üle.</p>

    <ol>
        <li>Kasutajad näevad vaid neid rebaseid, keda veel valitud pole. Adminid näevad nimekirja lehel kõiki rebaseid ja nende jumalaid.</li>
        <li>Adminid saavad rebaseid lisada (eraldi lehel) ja kustutada (punane nupp rebase kõrval avalehel). Rebaste lisamiseks sisesta tekstikasti rebaste täisnimed, iga nimi uuel real</li>
        <li>Valimiste algus- ja lõpuaega saab lisada ja muuta halduse lehel. Lisaks saab seal muuta ka valimisrežiimi: testversiooni puhul saab valimistulemused ühe nupuga lähtestada ja kasutajatele kuvatakse info, et tegemist on testiga. NB! Taotluseid ei lähtestata, st need peavad esimese korraga saama otsustatud nii, nagu vaja!</li>
        <li>Meil oli nii, et tublimad kooliellu panustajad said natuke varem rebase valida (nn VIP valimisõigus). Kasutajad saavad seda eraldi taotleda ja admin-kontoga saab neid taotlusi lahendada. Soovitan teha nii, et kõik, kes VIP-õigust väärivad, teaksid seda, et ei tekiks mingeid segadusi sellega. Anna teada, kui palju varem VIP-valimisõigust peaks kasutada saama (meil vist oli 8 tundi).</li>
        <li>Samuti saab kasutaja taotleda võimalust valida kaks rebast. Meil läks seda vaja, kuna rebaseid oli rohkem kui jumalaid. Kui teil sama mure peaks tekkima, saab seda võimalust kasutada.</li>
        <li>NB! Et kõik oleks võimalikult usaldusväärne, on rakendusel ka <a href="{{route("valimised.log")}}">logi</a>, kust saab kasutajate toiminguid vaadata (nt kui tekib vaidlus, kumb valis rebase esimesena vms)</li>
    </ol>

    <p>Kirjuta, kui midagi jääb segaseks!</p>

    </div>

</body>
</html>