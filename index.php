<?php

if (isset($_GET['id']))
    header("Location: /index.php");

require_once("./conf.php");
$meta_title = "Main Page";
$meta_description = $CONF['META_DESCRIPTION'];

require_once("./lib/render_post.php");
require_once("./lib/misc.php");
$data = get_data();

if (isset($_GET['tag'])) {
    $tagged_data = [];
    foreach ($data as $entry) {
        if ($entry->tag == $_GET['tag']) {
            $tagged_data[] = $entry;
        }
    }
    $data = $tagged_data;
}

usort($data, function($a, $b) {
    return $b->bump_time - $a->bump_time;
});



// Rendering of the page starts
include("./templates/header.php");

$threads_to_list = array_slice($data, 0, $CONF['THREADS_LISTED']);
echo "<nav id=\"menu\">";
foreach ($threads_to_list as $key => $thread) {
    $posts_amount = count($thread->posts);
    $thread_index = $key + 1;
    if ($thread_index <= $CONF['THREADS_DISPLAYED']) {
        echo "<span class=\"menu-link\"><a href=\"/thread.php?id={$thread->id}\">{$thread_index}: </a>";
        echo "<a href=\"#{$thread_index}\">{$thread->topic} ({$posts_amount})</a></span> ";
    } else {
        echo "<span class=\"menu-link\"><a href=\"/thread.php?id={$thread->id}\">{$thread_index}: {$thread->topic} ({$posts_amount})</a></span> ";
    }
}
echo "<p><a href=\"/subback.php\"><big>All threads</big></a></p>";
echo "</nav>";


include("./templates/form.php");

echo "<main>";
$threads_to_render = array_slice($data, 0, $CONF['THREADS_DISPLAYED']);

foreach ($threads_to_render as $key => $thread) {
    $posts_amount = count($thread->posts);
    $thread_index = $key + 1;
    $posts_to_render = $thread->posts;
    if ($posts_amount > $CONF['REPLIES_DISPLAYED'] + 1) {
        array_splice($posts_to_render, 1, -$CONF['REPLIES_DISPLAYED']);
    }

    $last_displayed_thread_number =
                                  (count($threads_to_render) < $CONF['THREADS_DISPLAYED'])
                                  ? count($threads_to_render) : $CONF['THREADS_DISPLAYED'];
    $previous_thread_number = ($key == 0) ? $last_displayed_thread_number : $key;
    $next_thread_number = ($thread_index == count($threads_to_render)) ? 1 : $thread_index + 1;

    echo "<article id=\"{$thread_index}\">";
    
    echo "<header class=\"thread-header-index\">";
    echo "<span class=\"thread-full-heading-wrapper\">";
    echo "<h2><a href=\"/thread.php?id={$thread->id}\" >{$thread->topic}</a></h2>";
    echo " ({$posts_amount})";
    echo "
<nav class=\"thread-title-nav\">
<small>
<span title=\"Copy thread ID\" onclick=\"copyThreadId('{$thread->id}')\">📋</span>
<span title=\"Copy thread URL\" onclick=\"copyThreadUrl('{$thread->id}')\">🔗</span>
— <a title=\"{$CONF['BBS_TAGS'][$thread->tag]}\" href=\"/index.php?tag={$thread->tag}\">{$thread->tag}</a>
</small>
</nav>";
    echo "</span>";
    echo " <nav class=\"thread-index-nav\">";
    echo "<medium><a title=\"Jump to thread list\" href=\"#menu\">Top</a> </medium> ";
    echo "<medium><a title=\"Jump to previous thread\" href=\"#{$previous_thread_number}\">Prev</a> </medium>";
    echo "<medium><a title=\"Jump to next thread\" href=\"#{$next_thread_number}\">Next</a></medium>";
    echo "</nav>";
    echo "</header>";
    
    foreach ($posts_to_render as $key => $post) {
        render_post($post, $thread->id);
        if (count($posts_to_render) > 1 && $key === 0)
            echo "<hr class=\"first-post-delimiter\" />";
    }
    echo "</article>";
}

echo "</main>";

include("./templates/form.php");
include("./templates/footer.html");
?>
