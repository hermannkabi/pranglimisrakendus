<div class="name-row" style="min-height: 75px; font-weight: {{$chosen_id == Auth::id() ? "bold" : "normal"}}">
    <div style="display: flex; gap: 16px; align-items: center">
        {{-- <img src="https://hermannkabi.com/assets/hermann.jpg" style="height: 50px; border-radius: 4px;"> --}}
        <div>
            <p style="margin-bottom: 0" class="name">{{ $name }}</p>  
            @if ( $instagram || $facebook )
                <div style="margin-top: 8px; display: {{ $instagram || $facebook ? "block" : "none" }}">
                    @if ( $instagram )
                        <a target="_blank" href="{{ $instagram }}"><img src="{{asset("assets/logos/instagram.png")}}" alt="" height="24px" width="24px"></a>
                    @endif
                    @if ($facebook)
                        <a target="_blank" href="{{ $facebook }}"><img src="{{asset("assets/logos/facebook.png")}}" alt="" height="24px" width="24px"></a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <div style="display: flex; flex-direction: row; gap: 4px; align-items: center">
        @if ($chosen_id)
            <p style="color: grey">{{$chosen_id == Auth::id() ? "Minu rebane" : "Juba valitud"}}</p>
        @else
            <form action="{{ route("valimised.chooseFox") }}" method="post">
                @csrf
                <input type="hidden" name="id" value="{{ $id }}">
                <button type="submit">Vali</button>
            </form>
        @endif

        @if (Auth::user()->role == "valimised-admin")
            <form action="{{ route("valimised.foxDelete") }}" method="post">
                @csrf
                <input type="hidden" name="id" value="{{ $id }}">
                <button style="padding-inline: 16px" class="red" type="submit"><i style="font-size: 16px; " class="material-icons">delete</i></button>
            </form>
        @endif
    </div>
</div>