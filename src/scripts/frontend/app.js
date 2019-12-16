const MASTER = "chocolatechips";

function load() {
    if (cookie_exists(MASTER)){

    }else{
        page("auth");
    }
}

function bakeUser(){
    api("scripts/backend/minicake/minicake.php", "minicake", "bake", {name: get("username").value}, (success, result)=>{

    });
}

function cookie_pull(name) {
    name += "=";
    const cookies = document.cookie.split(";");
    for (let i = 0; i < cookies.length; i++) {
        let cookie = cookies[i];
        while (cookie.charAt(0) === " ") {
            cookie = cookie.substring(1);
        }
        if (cookie.indexOf(name) === 0) {
            return decodeURIComponent(cookie.substring(name.length, cookie.length));
        }
    }
    return undefined;
}

function cookie_push(name, value) {
    const date = new Date();
    date.setTime(value !== undefined ? date.getTime() + (365 * 24 * 60 * 60 * 1000) : 0);
    document.cookie = name + "=" + encodeURIComponent(value) + ";expires=" + date.toUTCString() + ";domain=" + window.location.hostname + ";";
}

function cookie_exists(name) {
    return cookie_pull(name) !== undefined;
}