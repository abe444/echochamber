<?php

require_once("../conf.php");
require_once("../lib/render_post.php");

$root_url = "http" . (!empty($_SERVER['HTTPS'])?"s":"") . "://" . $_SERVER['SERVER_NAME'];

$json_data = file_get_contents('../data.json');
$data = json_decode($json_data, false);

usort($data, function($a, $b) {
    return $b->posts[0]->timestamp - $a->posts[0]->timestamp;
});

$all_posts = [];
foreach ($data as $thread) {
    $all_posts = array_merge($all_posts, $thread->posts);
}
usort($all_posts, function($a, $b) {
    return $b->timestamp - $a->timestamp;
});

// Create a new SimpleXMLElement object
$feed = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><feed xmlns="http://www.w3.org/2005/Atom"></feed>');

// Add feed metadata
$feed->addChild('title', "{$CONF['BBS_NAME']} — textboard Atom Feed");
$feed->addChild('subtitle', "All threads Atom feed of a textboard {$CONF['BBS_NAME']}");
$feed->addChild('link')->addAttribute('href', "{$root_url}/index.php");
$feed->addChild('updated', date(DATE_ATOM, $all_posts[0]->timestamp));

// Add entries
foreach ($data as $thread) {
    $op_post = $thread->posts[0];
    $op_message = $op_post->message;
    if (strlen($op_message) > 250) {
        $trimmed_op_message = preg_replace('/(\u|&#?)\d.$/', '', $op_message); // need to trim special chars if HTML encoding is cut
        $summary = substr($trimmed_op_message, 0, 250) . "...";
    }
    else
        $summary = $op_message;
    $entry = $feed->addChild('entry');
    $entry->addChild('title', $thread->topic);
    $entry->addChild('link')->addAttribute('href', "{$root_url}/thread.php?id={$thread->id}");
    $entry->addChild('id', "{$thread->id}");
    $entry->addChild('published', date(DATE_ATOM, $op_post->timestamp));
    $entry->addChild('updated', date(DATE_ATOM, end($thread->posts)->timestamp));
    $entry->addChild('author', "{$op_post->name}");
    $entry->addChild('summary', $summary);
    $entry->addChild('content', markdown_to_html($op_message))->addAttribute('type', 'html');
    $entry->addChild('category')->addAttribute('term', $thread->tag);
}


// Set the XML header and output the XML
header('Content-Type: application/xml; charset=utf-8');
echo $feed->asXML();
exit;

?>
