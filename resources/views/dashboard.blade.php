<!DOCTYPE html>
<html lang="en">
<head>
    @include('includes.head', ["title"=>"Rebaste valimine"])
</head>
<body>
    @include("includes.navbar")

    @include("includes.title", ["subtitle"=>"Rebaste valimine"])

    @if (Session::has('success'))
        <div style="margin-inline: 10%; color: grey;">
            {{ Session::get('success') }}
        </div>
    @endif
    @if (Session::has('error'))
        <div style="margin-inline: 10%; color: red;">
            {{ Session::get('error') }}
        </div>
    @endif

    @if (Auth::user()->role == "valimised-admin")
        <div style="margin-inline: 10%; color: grey;">
            Lisa rebaseid <a href="{{route("valimised.addFox")}}">siit</a> <br> ja vaata nimekirja <a href="{{route('valimised.foxList')}}">siit</a>
        </div>
    @endif

    <input id="search" type="text" placeholder="Otsi rebast..." style="margin-inline: 10%; width: min(80%, 360px); box-sizing: border-box">

    <form action="{{route('valimised.foxRandom')}}" method="post">
        @csrf
        <button style="margin-inline: 10%; width: min(80%, 360px); display: inline-flex; justify-content: center; gap: 8px"> <i class="material-icons">casino</i> Vali juhuslik rebane</button>
    </form>
    <div class="container">

        <p id="no-results" style="color: grey; text-align:center; font-size: 24px; margin-top: 64px" hidden>Tingimustele vastavaid rebaseid ei leitud!</p>            

        @foreach ($foxes as $fox)
            @include("includes.namerow", ["id"=>$fox->id, "name"=>$fox->name, "instagram"=>$fox->instagram, "facebook"=>$fox->facebook, "chosen_id"=>$fox->chosen_by])
        @endforeach

        <p style="text-align: center; color: grey; margin-top: 50px">Kokku on {{ count($foxes) }} rebast</p>
    </div>


    <script src="/js/jquery.js"></script>
    <script>

        function tweakSearchString(query){
            return query.toLowerCase().replaceAll("ä", "a").replaceAll("õ", "o").replaceAll("ö", "o").replaceAll("ü", "u").replaceAll("š", "sh").replaceAll("ž", "z").replaceAll(" ", "").replaceAll("-", "");
        }

        function filterFoxes(){
            $("#no-results").hide();
            $(".name-row").each(function (){
                $(this).show();
                if(!(tweakSearchString($(" .name", this).text()).includes(tweakSearchString($("#search").val())))){
                    $(this).hide();
                }
            });

            if($('.name-row:visible').length == 0){
                $("#no-results").show();
            }
        }

        $("#search").on("input", filterFoxes);

        filterFoxes();
    </script>
</body>
</html>