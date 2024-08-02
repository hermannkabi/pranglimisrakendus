<div class="appbar" style="text-align: end; margin: 8px;">
    <script>
        function logout(){
            window.location.href = "{{route('valimised.profile')}}";
        }
    </script>
    <a style="all:unset; cursor: pointer; display: flex; margin-top: 16px; text-transform: capitalize; justify-content: end; align-items: center"  href="{{route('valimised.profile')}}">{{Auth::user()->eesnimi}} {{Auth::user()->perenimi}} &nbsp; @if(Auth::user()->profile_pic != "/assets/logo.png") <img src="{{Auth::user()->profile_pic}}" style="height: 30px; border-radius: 50%; aspect-ratio: 1; object-fit: cover" alt=""> @else <i class="material-icons">person</i> @endif</a>
</div>