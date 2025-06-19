<div style="margin: 48px var(--margin-inline);">
    <a href="{{ route("valimised.dashboard") }}" style="all:unset; cursor: pointer;"><h1 style="font-weight: normal; margin-bottom: 0;">Rebaste nädal 2025</h1></a>
    <h1 style="font-weight: normal; color: grey; margin-top: 0;">{{ $subtitle }}</h1>
    @if($is_test_version)
    <div style="border-radius: 4px; color: #3C72FF; background-color: transparent; border: 1px solid #3C72FF; width: min(344px, calc(100% - 16px)); padding: 8px; display: flex; flex-direction: column; align-items: start; gap: 8px">
        <span style="display: flex; flex-direction: row; align-content: center; gap: 4px"><i style="font-size: 16px" class="material-icons">info</i> <span style="font-size: 12px">Info</span></span>
        Valimiste testversioon. Sinu valik eemaldatakse lõpphääletuseks!
    </div>
    @endif

    @if (str_contains(Auth::user()->role, "valimised-admin"))
        <br>
        <div style="border-radius: 4px; color: #3C72FF; background-color: transparent; border: 1px solid #3C72FF; width: min(344px, calc(100% - 16px)); padding: 8px; display: flex; flex-direction: column; align-items: start; gap: 8px">
            <span style="display: flex; flex-direction: row; align-content: center; gap: 4px"><i style="font-size: 16px" class="material-icons">shield</i> <span style="font-size: 12px">Admin</span></span>
            <span>Lisa rebaseid <a href="{{ route("valimised.addFox") }}">siit</a></span> 
            <span>Vaata nimekirja <a href="{{ route("valimised.foxList") }}">siit</a></span> 
            <span>Halda valimisi <a href="{{ route("valimised.properties") }}">siit</a></span> 

            <br>
            <a href="{{route("valimised.admininfo")}}">Admin info</a>
        </div>
    @endif
</div>