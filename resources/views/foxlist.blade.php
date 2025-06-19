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
            <div class="name-row" status={{$item["chosen_by_email"] == null ? "notchosen" : "chosen"}}>
                <p>{{$item["fox_name"]}}</p>

                <div>
                    <p title="{{ $item["chosen_by_email"] }}" style="color:grey">{{$item["chosen_by_name"] ?? "Pole veel valitud"}}</p>
                </div>
    
            </div>
        @endforeach
    </div>

    <script src="/js/jquery.js"></script>

</body>
</html>