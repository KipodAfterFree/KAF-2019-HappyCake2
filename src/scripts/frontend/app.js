const MASTER_NAME = "chocolatechip";
const MASTER_SECRET = "chocolatechips";

const TYPES = [
    "Abernethy",
    "Biscotti",
    "Coyotas",
    "Custardcream",
    "Empirebiscuit",
    "Gingerbread",
    "Nicebiscuit",
    "Sandwichcookie",
    "Stroopwafel",
    // "Flag"
];

function load() {
    if (cookie_exists(MASTER_NAME) && cookie_exists(MASTER_SECRET)) {
        page("home");
        loadBoxes();
    } else {
        page("auth");
    }
}

function bakeUser() {
    api("scripts/backend/minicake/minicake.php", "minicake", "bake", {name: get("username").value}, (success, result) => {
        if (success) {
            cookie_push(MASTER_NAME, get("username").value);
            cookie_push(MASTER_SECRET, result);
            load();
        } else {
            popup(result);
        }
    });
}

function loadBoxes() {
    clear("list");
    for (let t of TYPES) {
        api("scripts/backend/minicake/minicake.php", "minicake", "fetch", {
            name: cookie_pull(MASTER_NAME),
            secret: cookie_pull(MASTER_SECRET),
            type: t
        }, (success, result) => {
            let box = make("div");
            let box_text = make("p");
            let box_add = make("button");
            input(box);
            row(box);
            box_text.innerText = result.name + " has " + result.amount + " " + t + " mini cakes";
            box_add.innerText = "Add 1 mini cake";
            box_add.onclick = function () {
                api("scripts/backend/minicake/minicake.php", "minicake", "amount", {
                    name: cookie_pull(MASTER_NAME),
                    secret: cookie_pull(MASTER_SECRET),
                    type: t,
                    amount: result.amount + 1
                }, (success, result) => {
                    if (success) {
                        load();
                    } else {
                        popup(result);
                    }
                });
            };
            box.appendChild(box_text);
            box.appendChild(box_add);
            get("list").appendChild(box);
        });
    }
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