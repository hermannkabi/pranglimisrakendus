<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
<link rel="shortcut icon" href="/favicon.png" type="image/png">

<title>{{ $title }} | Rebaste nädal 2025</title>

<script>    
    if(!window.localStorage.getItem("app-theme")){
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            // dark mode
            localStorage.setItem("app-theme", "dark");
        }else if(window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches){
            //light mode
            localStorage.setItem("app-theme", "light");
        }
    }

    if(window.localStorage.getItem("app-theme") == "dark"){
        document.documentElement.setAttribute('data-theme', 'dark');
    }else{
        document.documentElement.setAttribute('data-theme', 'light');
    }
</script>