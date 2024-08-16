<!DOCTYPE html>
<html lang="en">
<head>
    @include('includes.head', ["title"=>"Rebaste nimekiri"])
</head>
<body>
    @include("includes.navbar")

    @include("includes.title", ["subtitle"=>"Rebaste nimekiri"])

    <div>
        @foreach ($data as $item)
            <div class="name-row">
                <p>{{$item["fox_name"]}}</p>

                <div>
                    <p title="{{ $item["chosen_by_email"] }}" style="color:grey">{{$item["chosen_by_name"] ?? "Pole veel valitud"}}</p>
                </div>
    
            </div>

        @endforeach
    </div>

</body>
</html>