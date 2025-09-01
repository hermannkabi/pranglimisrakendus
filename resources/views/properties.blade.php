<!DOCTYPE html>
<html lang="en">
<head>
    @include("includes.head", ["title"=>"Halda valimisi"])
</head>
<body>
    @include("includes.navbar")

    @include("includes.title", ["subtitle"=>"Halda valimisi"])


    <div style="margin-inline: var(--margin-inline)">
        <h2 style="margin-bottom: 0">Valimisõiguse taotlused ({{$applications->count()}})</h2>
        <p style="color: grey; margin-top: 4px">Siin kuvatakse veel lahendamata taotlused</p>
        <br>
        @forelse ($applications as $application)
        <div style="margin-block: 4px; border-radius: 4px; background-color: transparent; border: 1px solid var(--color); width: min(344px, calc(100% - 16px)); padding: 8px;">
            <h3 style="margin-block: 0">{{ $application->application_type == "valimised-basic" ? "Tavalise valimisõiguse" : ($application->application_type == "valimised-vip" ? "VIP valimisõiguse" : ($application->application_type == "valimised-twofox" ? "Kahe rebase valimise" : ("Muu (" . $application->application_type . ")"))) }} taotlus</h3>
            <p style="margin-top: 4px; color: grey">{{$application->created_at->format('d.m.Y')}}</p>
            <p style="margin-bottom: 0">Taotleja: {{ $application->applicantUser->eesnimi }} {{ $application->applicantUser->perenimi }}</p>
            <p style="color: grey; margin-top: 4px;">{{ $application->applicantUser->email }}</p>
            <br>
            <div>
                <button onclick="decideApplication('{{$application->id}}', 'granted')" style="margin-block: 4px">Rahulda</button>
                <button onclick="decideApplication('{{$application->id}}', 'denied')" style="margin-block: 4px" class="outlined">Lükka tagasi</button>
            </div>
        </div>
        @empty
        <p>Otsust ootavaid taotlusi ei leitud</p>
        @endforelse
    </div>
    <br><br>
    <form action="{{ route("valimised.propertiesPost") }}" method="post" style="margin-inline: var(--margin-inline)">
        <h2>Muud valikud</h2>
        @csrf

        <label for="opens_at">Valimiste algusaeg</label>
        <input value="{{$opens_at}}" type="datetime-local" name="opens_at" id="">

        <label for="closes_at">Valimiste lõpuaeg</label>
        <input value="{{ $closes_at }}" type="datetime-local" name="closes_at" id="">

        <input style="display: inline" {{$test ? "checked" : ""}} type="checkbox" name="test" id="" value="1"> <label for="test">Valimiste testversioon</label><br><br>

        <button type="submit">Salvesta</button>
        
    </form>

   @if ($test)
        <form action="{{route("valimised.clearResults")}}" method="POST" style="margin-inline: var(--margin-inline)">
            @csrf
            <br>
            <button class="outlined" type="submit">Lähtesta valimistulemused</button>
        </form>
    @endif


    <script>        
        function decideApplication(id, status){
            var message = null;

            if(status == "denied"){
                message = prompt("Palun lisa põhjendus, miks taotlus rahuldamata jääb", "Teil pole õigust sel aastal valimisel osaleda");
            }

            fetch("/valimised/application/"+id+"/decide", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}", 
                },
                body: JSON.stringify({ decision: status, message: message}),
            })
            .then(data => {
                window.location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
</body>
</html>