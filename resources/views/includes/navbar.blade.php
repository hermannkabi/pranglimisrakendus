<div class="appbar" style="display: flex; justify-content: space-between; align-items: center; margin: 8px;">
    <script>
        function logout(){
            window.location.href = "{{route('valimised.profile')}}";
        }

        function timeStampToTime(timestamp){
            var date = new Date(timestamp * 1000);

            // Hours part from the timestamp
            var hours = "0" + date.getHours();

            // Minutes part from the timestamp
            var minutes = "0" + date.getMinutes();

            // Seconds part from the timestamp
            var seconds = "0" + date.getSeconds();

            // Will display time in 10:30:23 format
            var formattedTime = hours.substr(-2) + ':' + minutes.substr(-2) + ':' + seconds.substr(-2);

            return formattedTime;
        }

        var time = parseInt("{{ $server_time }}");
        const localTime = Math.floor(Date.now() / 1000);

        function manageServerClock(){
            const newTime = time + ((Math.floor(Date.now() / 1000)) - localTime);
            document.querySelector("#clock").innerText = timeStampToTime(newTime);
        }        

        setInterval(() => {
            manageServerClock();
        }, 1000);
    </script>
    <p style="color: grey">Serveri kell: <span id="clock"></span> </p>
    <a style="all:unset; cursor: pointer; display: flex; text-transform: capitalize; justify-content: end; align-items: center"  href="{{route('valimised.profile')}}">{{Auth::user()->eesnimi}} {{Auth::user()->perenimi}} &nbsp; @if(Auth::user()->profile_pic != "/assets/logo.png") <img src="{{Auth::user()->profile_pic}}" style="height: 30px; border-radius: 50%; aspect-ratio: 1; object-fit: cover" alt=""> @else <i class="material-icons">person</i> @endif</a>

    <script>document.querySelector("#clock").innerText = timeStampToTime(time);</script>

</div>