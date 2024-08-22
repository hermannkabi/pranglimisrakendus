<!DOCTYPE html>
<html lang="en">
<head>
    @include("includes.head", ["title"=>"Logi sisse"])
</head>
<body>
    <script>
        function googleLogin(){
            console.log("Here");
            window.location.href = "{{route('google.redirect')}}";
        }
    </script>
    @include("includes.title", ["subtitle"=>"Logi sisse"])
    @if (Session::has('error'))
        <div style="margin-inline: var(--margin-inline); color: red;">
            {{ Session::get('error') }}
        </div>
    @endif
    <button onclick="googleLogin()" class="google-btn"><span style="display: flex; flex-direction: row; align-items: center"><span>Sisene</span>&nbsp;<img height="16px" src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2f/Google_2015_logo.svg/800px-Google_2015_logo.svg.png" alt="" style="margin-top: 4px">&nbsp;<span>kaudu</span></span></button>
</body>
</html>