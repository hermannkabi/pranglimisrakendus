var cookies = `; ${document.cookie}`;

function getCookieFromName(name) {
    const value = cookies;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
}

var settings = JSON.parse(decodeURIComponent(getCookieFromName("settings")));

console.log(settings);

if(settings){
    if("theme" in settings){
        window.localStorage.setItem("app-theme", settings.theme);
    }
    if("color" in settings){
        window.localStorage.setItem("app-primary-color", settings.color);
    }
    if("timer-visibility" in settings){
        window.localStorage.setItem("timer-visibility", settings["timer-visibility"]);
    }
    if("points-animation" in settings){
        window.localStorage.setItem("points-animation", settings["points-animation"]);
    }
    if("default-time" in settings){
        window.localStorage.setItem("default-time", settings["default-time"]);
    }

}


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



if(window.localStorage.getItem("app-primary-color") != null && window.localStorage.getItem("app-primary-color") != "default"){
    document.documentElement.style.setProperty("--primary-color", window.localStorage.getItem("app-primary-color"));
}