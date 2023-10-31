<?php

// Get thread id then find thread data by id
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $json_data = file_get_contents('../data.json');
    $data = json_decode($json_data, false);

    foreach($data as $entry) {
        if($entry->id == $id) {
            $target_thread_data = $entry;
            break;
        }
    }
} else {
    // If no id in query parameter, then redirect to subback page
    header("Location: /subback.php");
    exit;
}

require_once("../conf.php");

$thread_dump = json_encode($target_thread_data);

// Headers to force download
header("Content-Type: application/json");
header("Content-Disposition: attachment; filename={$CONF['BBS_NAME']}-thread-{$id}.json");
header("Content-Length: " . strlen($thread_dump));

// Send file to user for download
echo $thread_dump;
?>
