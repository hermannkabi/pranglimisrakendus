if(!window.localStorage.getItem("app-theme")){
    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        // dark mode
        localStorage.setItem("app-theme", "dark");
    }else if(window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches){
        //light mode
        localStorage.setItem("app-theme", "light");
    }
}else{
    if(window.localStorage.getItem("app-theme") == "dark"){
        document.documentElement.setAttribute('data-theme', 'dark');
    }else{
        document.documentElement.setAttribute('data-theme', 'light');
    }
}



if(window.localStorage.getItem("app-primary-color") != null && window.localStorage.getItem("app-primary-color") != "default"){
    document.documentElement.style.setProperty("--primary-color", window.localStorage.getItem("app-primary-color"));
}