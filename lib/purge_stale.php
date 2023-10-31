<?php

require_once("./lib/misc.php");

function purge_stale_threads(array $data, int $expiry_hours = 24): array {
    if ($expiry_hours === 0)
        return $data;

    $expiry_time = $expiry_hours * 60 * 60;
    $current_time = time();
    $filtered_threads = [];

    foreach ($data as $thread) {
        $last_reply_time = $thread['bump_time'];
        $is_expired = ($current_time - $last_reply_time) >= $expiry_time;

        if (count($thread['posts']) > 1 || !$is_expired ) {
            $filtered_threads[] = $thread;
        }
    }

    return $filtered_threads;

}

?>