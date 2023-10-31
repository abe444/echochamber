<?php

if ($_SERVER['REQUEST_METHOD'] != 'POST')
    header("Location: /index.php");

require_once("./conf.php");
require_once("./lib/render_post.php");
require_once("./lib/tripcode.php");
require_once("./lib/sitemap.php");
require_once("./lib/misc.php");
require_once("./lib/purge_stale.php");

// Handle too long or too short fields
include("./templates/error.php");

$name = sanitize($_POST['name']);

if (strpos($name, '#')) {
    $name = provide_tripcode($name);
}

$data = 
    purge_stale_threads(
        get_data(true), 
        $CONF['STALE_THREADS_EXPIRY_HOURS']
    );

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $timestamp = time();
    foreach ($data as $key => $entry) {
        if ($entry['id'] == $id) {
            $last_post = end($data[$key]['posts']);
            $data[$key]['posts'][] = array(
                'name' => $name,
                'email' => sanitize($_POST['email']),
                'message' => sanitize($_POST['message']),
                'timestamp' => $timestamp,
                'number' => $last_post['number'] + 1
            );
            break;
        }
    }

    if ($_POST['email'] != "sage" && $_POST['email'] != "nonokosage")
        $data[$key]['bump_time'] = $timestamp;

    $final_json_data = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents('data.json', $final_json_data);

    if ($_POST['email'] == "nonoko" || $_POST['email'] == "nonokosage")
        header("Location: /index.php");
    else
        header("Location: /thread.php?id=" . $id);

} else {
    $id = uniqid();
    $entry = array(
        'topic' => sanitize($_POST['topic']),
        'bump_time' => time(),
        'id' => $id,
        'tag' => sanitize($_POST['tag']),
        'posts' => [ array(
            'name' => $name,
            'email' => sanitize($_POST['email']),
            'message' => sanitize($_POST['message']),
            'timestamp' => time(),
            'number' => 1
        ) ]
    );

    $data[] = $entry;
    $final_json_data = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents('data.json', $final_json_data);

    if ($_POST['email'] == "nonoko" || $_POST['email'] == "nonokosage")
        header("Location: /index.php");
    else
        header("Location: /thread.php?id=" . $id);
}

generate_sitemap();

exit;
?>
