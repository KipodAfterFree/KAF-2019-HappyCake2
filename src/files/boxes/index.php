<?php

include __DIR__ . "/../../scripts/backend/minicake/shared.php";

if (isset($_GET["name"]) && isset($_GET["secret"]) && isset($_GET["type"])) {
    if (authenticate($_GET["name"], $_GET["secret"])) {
        if (is_string($_GET["type"])) {
            $user_hash = authenticate_hash($_GET["name"]);
            $file_path = BOXES_DIRECTORY . "/" . $user_hash . "/" . basename($_GET["type"]);
            if (file_exists($file_path)) {
                echo file_get_contents($file_path);
            }
        } else {
            echo "Box retrieval failed!";
        }
    } else {
        echo "Authentication failed!";
    }
} else {
    echo "Missing parameters!";
}

