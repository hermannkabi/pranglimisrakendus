<!DOCTYPE html>
<html lang="en">
<head>
    @include("includes.head", ["title"=>"Taotle valimisõigust"])
</head>
<body>
    @include("includes.navbar")

    @include("includes.title", ["subtitle"=>"Taotle valimisõigust"])

    <div style="margin-inline: var(--margin-inline); position: relative; width: min(360px, calc(100% - 16px));">
        <p>Sujuva ja turvalise valimisprotsessi nimel tuleb valimisõigust eraldi taotleda. Siin lehel saadki seda teha. Kui oled juba taotluse esitanud, näed siin selle seisu.</p>
        
        @foreach ($applications as $application)
        @php
            $color = ["pending"=>"#3C72FF", "denied"=>"red", "granted"=>"lime"][$application->status];
        @endphp
        <div style="margin-block: 8px; border-radius: 4px; background-color: transparent; border: 1px solid var(--color); width: min(344px, calc(100% - 16px)); padding: 8px;">
            <h3 style="margin-block: 0">{{ $application->application_type == "valimised-basic" ? "Tavalise valimisõiguse" : ($application->application_type == "valimised-vip" ? "VIP valimisõiguse" : ($application->application_type == "valimised-twofox" ? "Kahe rebase valimise" : ("Muu (" . $application->application_type . ")"))) }} taotlus</h3>
            <p style="margin-top: 4px; color: grey">{{$application->created_at->format('d.m.Y')}} {{ in_array($application->status, ["denied", "granted"]) ? ("· Lahendatud ". $application->updated_at->format('d.m.Y')) : "" }}</p>
            @if ($application->message)
                <p>{{ $application->message }}</p>
            @else
                <br>
            @endif
            <div style="border-radius: 4px; background-color: transparent; border: 1px solid {{$color}}; color: {{$color}}; width: fit-content; padding: 2px 4px; font-size: 12px; display: flex; flex-direction:row; align-items:center; gap: 4px;"> <i style="font-size: 16px" class="material-icons">{{["denied"=>"close", "pending"=>"schedule", "granted"=>"check"][$application->status]}}</i> <span>{{["denied"=>"TAGASI LÜKATUD", "pending"=>"OOTEL", "granted"=>"RAHULDATUD"][$application->status]}}</span></div>
        </div>
        @endforeach

        @if (!($applications->contains('application_type', 'valimised-basic') && $applications->contains('application_type', 'valimised-vip') && $applications->contains('application_type', 'valimised-twofox')))
        <h2 style="margin-bottom: 0">Uus taotlus</h2>
        <p style="color: grey; margin-top: 4px">VIP valimisõiguse saavad kooliellu enimpanustanud õpilased valimiste korraldaja kaalutlusel</p>
        @endif

        @if (!$applications->contains('application_type', 'valimised-basic'))
        <form action="{{route('valimised.apply')}}" method="POST">
            @csrf
            <input type="hidden" name="type" value="valimised-basic">
            <button>Taotlen tavalist valimisõigust</button><br><br>
        </form>
        @endif

        @if (!$applications->contains('application_type', 'valimised-vip'))
        <form action="{{route('valimised.apply')}}" method="POST">
            @csrf
            <input type="hidden" name="type" value="valimised-vip">
            <button class="outlined">Taotlen VIP valimisõigust</button><br><br>
        </form>
        @endif

        @if (!$applications->contains('application_type', 'valimised-twofox'))
        <form action="{{route('valimised.apply')}}" method="POST">
            @csrf
            <input type="hidden" name="type" value="valimised-twofox">
            <button class="outlined">Taotlen kahe rebase valimisõigust</button>
        </form>
        @endif

    </div>

</body>
</html>