<?php

include_once __DIR__ . "/../base/api.php";

const SERVER_SIGNATURE = "i6os4rz2w8qaov8h6ejf2z8nqxhwshxbps4ci7s3aa7wf7jfh3zlfhfsuin0lo3n";

const HASHING_ALGO = "sha256";

api("minicake", function ($action, $parameters) {
    if ($action === "bake") {
        if (isset($parameters->name)) {
            $hashed = hash_hmac(HASHING_ALGO, $parameters->name, SERVER_SIGNATURE);
        }
    }
}, true);