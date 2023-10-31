<?php

require_once("./lib/render_post.php");

function provide_tripcode(string $name): string {
    $parts = explode("#", $name);
    $tripcode_input = implode("#", array_slice($parts, 1));
    return sanitize($parts[0]) . "&#33;" . sanitize(generate_tripcode($tripcode_input));
}

function generate_tripcode(string $password): string {
    $salt = substr($password . "H.", 1, 2);
    $salt = preg_replace("/[^\.-z]/", ".", $salt);
    $salt = strtr($salt, ":;<=>?@[\\]^_`", "ABCDEFGabcdef");
    return substr(crypt($password, $salt), -10);
}

?>
