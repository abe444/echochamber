<?php

require_once("./lib/misc.php");

// Get thread id then find thread data by id
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $data = get_data();

    foreach($data as $entry) {
        if($entry->id == $id) {
            $current_thread_data = $entry;
            break;
        }
    }
    if (!isset($current_thread_data)) {
        header("Location: /index.php");
        exit;
    }
} else {
    // If no id in query parameter, then redirect to main page
    header("Location: /index.php");
    exit;
}

require_once("./conf.php");

// Handle meta-data for SEO passed to header.php
$meta_title = $current_thread_data->topic;

$formatted_op_message = htmlspecialchars_decode(str_replace(["\r\n", "\n", "\r"], ' ', $current_thread_data->posts[0]->message));
if (strlen($formatted_op_message) > $CONF['META_DESCRIPTION_MAX_LENGTH']) {
    $substring = substr($formatted_op_message, 0, $CONF['META_DESCRIPTION_MAX_LENGTH']);
    $meta_description = htmlspecialchars($substring . "...", ENT_QUOTES, 'UTF-8');
} else {
    $meta_description = htmlspecialchars($formatted_op_message, ENT_QUOTES, 'UTF-8');
}

// Get tail parameter with thread id for form.php to post into thread
$post_tail = "?id=" . $current_thread_data->id;

include("./templates/header.php");
echo "<hr/>";
include("./templates/form.php");

// Count all posts in thread
$posts_amount = count($current_thread_data->posts);

require_once("./lib/render_post.php");

// Navigate bottom
echo '<nav class="thread_vertical_nav"><big><a title="Jump to bottom" href="#bottom">Bottom</a></big></nav>';

/*
foreach ($data as $key => $post) {
$post_date = date('Y-m-d g:i e', $post['datetime']);
$post_title = $post['title'];
$post_content = $post['content'];
$last_bumped = time() - $post['bump_stamp'];

if ($last_bumped < 60) {
    $timeLabel = 'last bumped ' . $last_bumped . ' seconds ago';
}elseif ($last_bumped < 3600) {
    $timeLabel = 'last bumped ' . floor($last_bumped / 60) . ' minutes ago';
} else {
    $timeLabel = 'last bumped ' . floor($last_bumped / 3600) . ' hours ago';
}

if ($last_bumped >= 172800) {
    $daysPassed = floor($last_bumped / 86400);
    $timeLabel = 'last bumped ' . $daysPassed . ' days ago';
}
}
*/

// Add link to go to entire thread only when one post is shown
if (isset($_GET['p']))
    $entire_thread_link = " <a href=\"/thread.php?id={$id}\" title=\"Show entire thread\">Entire thread</a>";
else
    $entire_thread_link = "";
$navigation_links = "<nav class=\"return-nav\"><a href=\"/index.php\" title=\"Go to index page\">Return</a>"
                  . $entire_thread_link . "</nav>";

// Navigation links
echo $navigation_links;

echo "<main>";

echo "<article>";

echo "<header>";
// Title
echo "

<h1>{$current_thread_data->topic}</h1> ({$posts_amount})";
// Tag
echo "
<nav class=\"thread-title-nav\">
<small>
<span title=\"Copy thread ID\" onclick=\"copyThreadId('{$current_thread_data->id}')\">📋</span>
<span title=\"Copy thread URL\" onclick=\"copyThreadUrl('{$current_thread_data->id}')\">🔗</span>
— <a title=\"{$CONF['BBS_TAGS'][$current_thread_data->tag]}\" href=\"/index.php?tag={$current_thread_data->tag}\">{$current_thread_data->tag}</a>
</small>
</nav>";
echo "</header>";

// If post number (p parameter) is defined we show only the defined post
if (isset($_GET['p'])) {
    $post_number = $_GET['p'];
    foreach ($current_thread_data->posts as $post) {
        if ($post->number == $post_number) {
            $current_post = $post;
            break;
        }
    }

    if (isset($current_post)) {
        render_post($current_post, $id);
    }
} else {
    // Otherwise, default behavior -- we show all posts of the thread
    foreach ($current_thread_data->posts as $post):
        render_post($post, $id);
    endforeach;
}
echo "</article>";

echo "</main>";

// Navigation links
echo $navigation_links;

// Navigate top
echo '<nav class="thread_vertical_nav"><big><a title="Jump to top" href="#top">Top</a></big></nav>';

include("./templates/form.php");
include("./templates/footer.html");
?>
