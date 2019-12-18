<?php

include_once __DIR__ . "/../base/api.php";

include_once __DIR__ . "/shared.php";

const BOX_TYPES = [

];



api("minicake", function ($action, $parameters) {
    if ($action === "bake") {
        if (isset($parameters->name)) {
            if (!file_exists(BOXES_DIRECTORY . "/" . authenticate_hash($parameters->name))) {
                // todo Make boxes

                return [true, authenticate_hash($parameters->name)];
            }
            return [false, "User already exists"];
        }
        return [false, "Missing parameters"];
    } else {
        if (isset($parameters->name) && isset($parameters->secret)) {
            if (authenticate($parameters->name, $parameters->secret)) {
                $user = $parameters->name;
                if ($action === "rename") {
                    if ($user !== "admin"){

                    }else{
                        return [false, "Admin can't rename"];
                    }
                } else if ($action === "amount") {
                    if ($user !== "admin"){

                    }else{
                        return [false, "Admin can't amount"];
                    }
                } else if ($action === "fetch") {

                }
                return [false, "Unknown action"];
            }
            return [false, "Authentication failed"];
        }
        return [false, "Missing parameters"];
    }
}, true);