<!DOCTYPE html>
<html lang="en">
<head>
    @include('includes.head', ["title"=>"Kasutaja pole kinnitatud"])
</head>
<body>
    @include('includes.navbar')

    @include('includes.title', ["subtitle"=>"Kasutaja pole kinnitatud"])

    <div style="margin-inline: 10%">
        <p>Valimistel osalemiseks peab sinu konto olema kinnitatud. Mine <a href="{{route("profilePage")}}">profiilile</a>, et e-posti aadress kinnitada.</p>
    </div>
</body>
</html>