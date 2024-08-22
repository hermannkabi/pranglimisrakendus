<!DOCTYPE html>
<html lang="en">
<head>
    @include('includes.head', ["title"=>"Valimised pole alanud!"])
</head>
<body>
    @include('includes.navbar')

    @include('includes.title', ["subtitle"=>"Valimised pole alanud!"])

    @if (Auth::user()->role == "valimised-admin")
        <div style="margin-inline: var(--margin-inline); color: grey;">
            Lisa rebaseid <a href="{{route("valimised.addFox")}}">siit</a> <br> ja vaata nimekirja <a href="{{route('valimised.foxList')}}">siit</a>
        </div>
    @endif
    
    <div style="margin-inline: var(--margin-inline)">
        <p>Valimiste alguseni on <span id="time-left"></span></p>
    </div>

    <script>
        function setTime(secondsLeft){
            if(secondsLeft <= 0){
                clearInterval(interval);
                location.reload();
            }

            var timeLeft = secondsLeft + (" sekund" + (secondsLeft == 1 ? "" : "it"));

            if(secondsLeft >= 60){
                var minsLeft = Math.floor(secondsLeft / 60);
                timeLeft = minsLeft + (" minut" + (minsLeft == 1 ? " " : "it ") + "ja ") + (secondsLeft - 60*minsLeft) + (" sekund" + ((secondsLeft - 60*minsLeft) == 1 ? "" : "it"));
            }

            if(secondsLeft >= 3600){
                var hoursLeft = Math.floor(secondsLeft / 3600);
                var minsLeft = Math.floor((secondsLeft - 3600*hoursLeft) / 60);
                timeLeft = hoursLeft + (" tund" + (hoursLeft == 1 ? ", " : "i, ")) + minsLeft + (" minut" + (minsLeft == 1 ? " " : "it ") + "ja ") + (secondsLeft - 60*minsLeft - 3600*hoursLeft) + (" sekund" + ((secondsLeft - 60*minsLeft - 3600*hoursLeft) == 1 ? "" : "it"));
            }

            if(secondsLeft >= 86400){
                var daysLeft = Math.floor(secondsLeft / 86400);
                var hoursLeft = Math.floor((secondsLeft - daysLeft*86400) / 3600);
                var minsLeft = Math.floor((secondsLeft - daysLeft*86400 - 3600*hoursLeft) / 60);
                timeLeft = daysLeft + (" päev" + (daysLeft == 1 ? ", " : "a, ")) + hoursLeft + (" tund" + (hoursLeft == 1 ? ", " : "i, ")) + minsLeft + (" minut" + (minsLeft == 1 ? " " : "it ") + "ja ") + (secondsLeft - 60*minsLeft - 3600*hoursLeft - 86400*daysLeft) + (" sekund" + ((secondsLeft - 60*minsLeft - 3600*hoursLeft - 86400*daysLeft) == 1 ? "" : "it"));
            }

            document.querySelector("#time-left").innerHTML = timeLeft;
        }

        const opensAt = {{$opens_at}};
        const serverTime = {{$server_time}};
        const timeAtLoad = Math.floor(Date.now() / 1000);

        setTime(opensAt - serverTime - (Math.floor(Date.now() / 1000) -  timeAtLoad));

        var interval = setInterval(() => {
            var newTime = opensAt - serverTime - (Math.floor(Date.now() / 1000) -  timeAtLoad);
            setTime(newTime);
        }, 1000);
    </script>
</body>
</html>