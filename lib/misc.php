<?php

function get_data(?bool $is_associative = false): array {
    $existing_json_data = file_get_contents('data.json');
    $data = json_decode($existing_json_data, $is_associative);

    return $data;
}


function ip_in_range($ip, $range) {
    list($subnet, $bits) = explode('/', $range);
    $ip_decimal = ip2long($ip);
    $subnet_decimal = ip2long($subnet);
    $mask = -1 << (32 - $bits);

    return ($ip_decimal & $mask) === ($subnet_decimal & $mask);
}

?>