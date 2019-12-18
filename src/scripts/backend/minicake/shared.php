<?php

const BOXES_DIRECTORY = __DIR__ . "/../../../files/boxes/private";

const SERVER_SIGNATURE = "i6os4rz2w8qaov8h6ejf2z8nqxhwshxbps4ci7s3aa7wf7jfh3zlfhfsuin0lo3n";

const HASHING_ALGO = "sha256";

function authenticate($name, $secret)
{
    if (is_string($name) && is_string($secret)) {
        return authenticate_hash($name) === $secret;
    }
    return false;
}

function authenticate_hash($name)
{
    return hash_hmac(HASHING_ALGO, $name, SERVER_SIGNATURE);
}

function create_box($name, $amount, $hash)
{
    $amount = intval($amount);
    $contents = "<?php\nconst BOX_NAME = \"$name\";\nconst BOX_AMOUNT = $amount;\nconst BOX_NAME_HASH = \"$hash\";";
    return $contents;
}