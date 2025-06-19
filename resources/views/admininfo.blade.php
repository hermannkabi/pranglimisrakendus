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
        <li>Adminid saavad rebaseid lisada (eraldi lehel) ja kustutada (punane nupp rebase kõrval avalehel)</li>
        <li>Valimiste algus- ja lõpuaega saab lisada ja muuta halduse lehel. Lisaks saab seal muuta ka valimisrežiimi: testversiooni puhul saab tulemused ühe nupuga lähtestada ja kasutajatele kuvatakse info, et tegemist on testiga. Tavarežiimis neid ei ole</li>
        <li>Samal lehel saab lisada ka need inimesed, kes saavad valida kaks rebast, kui peaks vaja minema (meil oli jumalaid vähem). </li>
        <li>Meil oli nii, et tublimad kooli panustajad said natuke varem rebase valida. Kui tahate ka seda, siis kirjuta, kes need peaksid olema ja lisan neile vastavad õigused.</li>
    </ol>

    <p>Kirjuta, kui midagi jääb segaseks!</p>
    
    </div>

</body>
</html>