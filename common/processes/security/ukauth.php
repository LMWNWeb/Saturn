<?php

function generate_uka_key()
{
    try {
        $key = random_int(1000000000, 9999999999);
        $hash = hash(SECURITY_DEFAULT_HASH, $key);
    } catch (Exception $e) {
        echo 'Exception: '.$e;
        exit;
    }

    return $hash;
}
