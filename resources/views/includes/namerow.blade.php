<div class="name-row" style="min-height: 75px; font-weight: {{$chosen_id == Auth::id() ? "bold" : "normal"}}">
    <div style="display: flex; gap: 16px; align-items: center">
        <div>
            <p style="margin-bottom: 0" class="name">{{ $name }}</p>  
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

        @if (str_contains(Auth::user()->role, "valimised-admin"))
                {{-- @if ($chosen_id)
                    <form action="{{ route("valimised.foxClear") }}" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ $id }}">
                        <button style="padding-inline: 16px" type="submit"><i style="font-size: 16px; " class="material-icons">close</i></button>
                    </form>
                @endif --}}
            <form action="{{ route("valimised.foxDelete") }}" method="post">
                @csrf
                <input type="hidden" name="id" value="{{ $id }}">
                <button style="padding-inline: 16px" class="red" type="submit"><i style="font-size: 16px; " class="material-icons">delete</i></button>
            </form>
        @endif
    </div>
</div>