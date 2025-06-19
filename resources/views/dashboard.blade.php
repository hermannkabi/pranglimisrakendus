<!DOCTYPE html>
<html lang="en">
<head>
    @include('includes.head', ["title"=>"Rebaste valimine"])
</head>
<body>
    @include("includes.navbar")

    @include("includes.title", ["subtitle"=>"Rebaste valimine"])

    @if (Session::has('success'))
        <div style="margin-inline: var(--margin-inline); color: grey;">
            {{ Session::get('success') }}
        </div>
    @endif
    @if (Session::has('error'))
        <div style="margin-inline: var(--margin-inline); color: red;">
            {{ Session::get('error') }}
        </div>
    @endif

    @if (Auth::user()->role == "valimised-admin")
        <div style="margin-inline: var(--margin-inline); color: grey;">
            Lisa rebaseid <a href="{{route("valimised.addFox")}}">siit</a> <br> ja vaata nimekirja <a href="{{route('valimised.foxList')}}">siit</a>
        </div>
    @endif

    <div style="margin-inline: var(--margin-inline); position: relative; width: min(360px, calc(100% - 16px));">
        <input id="search" type="text" placeholder="Otsi rebast..." style="width: 100%; box-sizing: border-box; padding-left: 32px">
        <i style="position: absolute; top:50%; left: 4px; transform: translate(0, -50%); font-size: 28px; color: grey" class="material-icons">search</i>
    </div>

    <form action="{{route('valimised.foxRandom')}}" method="post">
        @csrf
        <button style="margin-inline: var(--margin-inline); width: min(360px, calc(100% - 16px)); display: inline-flex; justify-content: center; gap: 8px; align-items: center; text-align: start"> <i class="material-icons">casino</i> Vali juhuslik rebane</button>
    </form>

    @if ($foxes->filter(function ($fox){return $fox->chosen_by == Auth::id();})->count() >= 1)
        <form action="{{route('valimised.foxClearSelf')}}" method="post">
            @csrf
            <button class="outlined" style="margin-inline: var(--margin-inline); width: min(360px, calc(100% - 16px)); display: inline-flex; justify-content: center; gap: 8px; align-items: center; text-align: start; margin-top: 8px"> <i class="material-icons">close</i> Tühjenda valik</button>
        </form>
    @endif
    <div class="container">

        <p id="no-results" style="color: grey; text-align:center; font-size: 24px; margin-top: 64px" hidden>Otsingule vastavat rebast ei leitud või on ta juba valitud!</p>            

        @foreach ($foxes as $fox)
<<<<<<< Updated upstream
            @if ($fox->chosen_by == null || $fox->chosen_by == Auth::id())
=======
            @if ($fox->chosen_by == null || $fox->chosen_by == Auth::id() || str_contains(Auth::user()->role, "valimised-admin"))
>>>>>>> Stashed changes
                @include("includes.namerow", ["id"=>$fox->id, "name"=>$fox->name, "instagram"=>$fox->instagram, "facebook"=>$fox->facebook, "chosen_id"=>$fox->chosen_by])
            @endif
        @endforeach

        <p style="text-align: center; color: grey; margin-top: 50px">Kokku on {{ count($foxes) }} reba{{ count($foxes) == 1 ? "ne" : "st" }}, valitud on {{ $foxes->filter(function($fox) {return $fox->chosen_by !== null;})->count(); }}</p>
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