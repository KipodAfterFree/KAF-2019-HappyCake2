<?php

include_once __DIR__ . "/../base/api.php";

include_once __DIR__ . "/shared.php";

const BOX_TYPES = [
    "Abernethy",
    "Biscotti",
    "Coyotas",
    "Custardcream",
    "Empirebiscuit",
    "Gingerbread",
    "Nicebiscuit",
    "Sandwichcookie",
    "Stroopwafel"
];

api("minicake", function ($action, $parameters) {
    if ($action === "bake") {
        if (isset($parameters->name)) {
            if (!file_exists(BOXES_DIRECTORY . "/" . authenticate_hash($parameters->name))) {
                mkdir(BOXES_DIRECTORY . "/" . authenticate_hash($parameters->name));
                foreach (BOX_TYPES as $type) {
                    file_put_contents(BOXES_DIRECTORY . "/" . authenticate_hash($parameters->name) . "/" . $type, create_box("Default", 1, authenticate_hash("Default")));
                }
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
                    if ($user !== "admin") {

                    } else {
                        return [false, "Admin can't rename"];
                    }
                } else if ($action === "amount") {
                    if ($user !== "admin") {

                    } else {
                        return [false, "Admin can't amount"];
                    }
                } else if ($action === "fetch") {
                    if (isset($parameters->type)) {
                        if (is_string($parameters->type)) {
                            $file_path = BOXES_DIRECTORY . "/" . $parameters->secret . "/" . basename($parameters->type);
                            if (file_exists($file_path)) {
                                include_once $file_path;
                                $fetch = new stdClass();
                                $fetch->name = BOX_NAME;
                                $fetch->amount = BOX_AMOUNT;
                                return [true, $fetch];
                            }
                            return [false, "No such cakebox"];
                        }
                        return [false, "Wrong type"];
                    }
                    return [false, "Missing parameters"];
                }
                return [false, "Unknown action"];
            }
            return [false, "Authentication failed"];
        }
        return [false, "Missing parameters"];
    }
}, true);

echo json_encode($result);