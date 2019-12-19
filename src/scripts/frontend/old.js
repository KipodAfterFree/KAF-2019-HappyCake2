const SERVER_SIGNATURE = "----------------------------------------------------------------";

const TYPES = [
    "Biscotti",
    "Custardcream",
    "Empirebiscuit",
    "Gingerbread",
    "Sandwichcookie",
    "Stroopwafel",
    //"Flag" - only for admin
];

function loadBoxes() {
    for (let t of TYPES) {
        fetch("files/boxes/?name=" + ckpull("chocolatechip") + "&secret=" + ckpull("chocolatechips") + "&type=" + t).then(resp => {
            resp.text().then(res => {
                let box = make("p");
                input(box);
                box.style.margin = "1vh";
                let name = res.match("const BOX_NAME = \".+\";")[0].replace("const BOX_NAME = \"", "").replace("\";", "");
                let amount = res.match("const BOX_AMOUNT = .+;")[0].replace("const BOX_AMOUNT = ", "").replace(";", "");
                box.innerText = t + " (" + name + ") has " + amount + " mini cakes";
                get("list").appendChild(box);
            });
        });
    }
}

function renameBox(type, name, secret) {
    api("scripts/backend/minicake/minicake.php", "minicake", "rename", {
        name: ckpull("chocolatechip"),
        secret: ckpull("chocolatechips"),
        type: type,
        boxname: name,
        boxsecret: secret
    }, (success, result) => {
        if (success) {
            load();
        } else {
            popup(result);
        }
    });
}

function amountBox(type, amount) {
    api("scripts/backend/minicake/minicake.php", "minicake", "amount", {
        name: cookie_pull(MASTER_NAME),
        secret: cookie_pull(MASTER_SECRET),
        type: type,
        amount: amount
    }, (success, result) => {
        if (success) {
            load();
        } else {
            popup(result);
        }
    });
}

function ckpull(name) {
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

function ckpush(name, value) {
    const date = new Date();
    date.setTime(value !== undefined ? date.getTime() + (365 * 24 * 60 * 60 * 1000) : 0);
    document.cookie = name + "=" + encodeURIComponent(value) + ";expires=" + date.toUTCString() + ";domain=" + window.location.hostname + ";";
}

function ckext(name) {
    return ckpull(name) !== undefined;
}