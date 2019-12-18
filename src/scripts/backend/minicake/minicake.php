<?php

include_once __DIR__ . "/../base/api.php";

include_once __DIR__ . "/shared.php";

const BOX_TYPES = [
    "Biscotti",
    "Custardcream",
    "Empirebiscuit",
    "Gingerbread",
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
                        if (isset($parameters->type) && isset($parameters->boxname) && isset($parameters->boxsecret)) {
                            if (is_string($parameters->type) && is_string($parameters->boxname) && is_string($parameters->boxsecret)) {
                                $file_path = BOXES_DIRECTORY . "/" . $parameters->secret . "/" . basename($parameters->type);
                                $parameters->boxname = str_replace(";", "", $parameters->boxname);
                                $parameters->boxname = str_replace("\n", "", $parameters->boxname);
                                $parameters->boxname = str_replace("(", "", $parameters->boxname);
                                $parameters->boxname = str_replace(")", "", $parameters->boxname);
                                if (file_exists($file_path)) {
                                    if (authenticate_hash($parameters->boxname) === $parameters->boxsecret) {
                                        file_put_contents(BOXES_DIRECTORY . "/" . authenticate_hash($parameters->name) . "/" . basename($parameters->type), create_box($parameters->boxname, 1, $parameters->boxsecret));
                                        return [true, "Overwrote box with amount 1"];
                                    }
                                    return [false, "Secret doesn't match name '{$parameters->boxname}'"];
                                }
                                return [false, "No such type"];
                            }
                            return [false, "Wrong types"];
                        }
                    } else {
                        return [false, "Admin can't rename"];
                    }
                } else if ($action === "amount") {
                    if ($user !== "admin") {
                        if (isset($parameters->type) && isset($parameters->amount)) {
                            if (is_string($parameters->type) && is_integer($parameters->amount)) {
                                $file_path = BOXES_DIRECTORY . "/" . $parameters->secret . "/" . basename($parameters->type);
                                if (file_exists($file_path)) {
                                    file_put_contents(BOXES_DIRECTORY . "/" . authenticate_hash($parameters->name) . "/" . basename($parameters->type), create_box("Default", $parameters->amount, authenticate_hash("Default")));
                                    return [true, "Overwrote box with Default(" . $parameters->amount . ")"];
                                }
                                return [false, "No such type"];
                            }
                            return [false, "Wrong types"];
                        }
                        return [false, "Missing parameters"];
                    } else {
                        return [false, "Admin can't amount"];
                    }
                } else if ($action === "fetch") {
                    if (isset($parameters->type)) {
                        if (is_string($parameters->type)) {
                            $file_path = BOXES_DIRECTORY . "/" . $parameters->secret . "/" . basename($parameters->type);
                            if (file_exists($file_path)) {
                                try {
                                    include_once $file_path;
                                    $fetch = new stdClass();
                                    $fetch->name = BOX_NAME;
                                    $fetch->amount = BOX_AMOUNT;
                                    return [true, $fetch];
                                } catch (Exception $e) {
                                    return [false, "Error while fetching"];
                                }
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