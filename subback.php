<?php

require_once("./conf.php");
$meta_title = "All Threads List";
$meta_description = $CONF['META_DESCRIPTION'];

function format_size_units($bytes)
{
    $units = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB');
    $i = 0;
    while ($bytes >= 1024) {
        $bytes /= 1024;
        $i++;
    }
    return round($bytes, 2) . $units[$i];
}


require_once("./lib/misc.php");
$data = get_data();
usort($data, function($a, $b) {
    return $b->bump_time - $a->bump_time;
});

include("./templates/header.php");

echo "<hr/>";

echo "<center><h1>All Threads List</h1></center>";

// Navigate bottom
echo '<nav class="thread_vertical_nav"><big><a title="Jump to bottom" href="#bottom">Bottom</a></big></nav>';

$total_threads = count($data);
$all_posts = [];
foreach ($data as $thread) {
    $all_posts = array_merge($all_posts, $thread->posts);
}
$total_posts = count($all_posts);

$total_replies = $total_posts - $total_threads;

$total_data_in_bytes = strlen(file_get_contents('data.json'));
$total_data = format_size_units($total_data_in_bytes);

echo "
<fieldset>
<legend><strong>Textboard Stats</strong></legend>
<ul>
<li><b>Total threads:</b> {$total_threads}</li>
<li><b>Total posts:</b> {$total_posts}</li>
<li><b>Total replies:</b> {$total_replies}</li>
<li><b>Total data:</b> {$total_data}</li>
</ul>
</fieldset>
";

echo "<table class=\"subback\">";
echo "<thead><tr><th>Num</th> <th>Topic</th> <th>Tag</th> <th>Posts</th> <th>Last post</th> <th>Data</th></tr></thead>";

echo "<tbody>";
foreach ($data as $key => $thread) {
    $thread_index = $key + 1;
    $posts_amount = count($thread->posts);

    $last_post = end($data[$key]->posts);
    $last_post_date = date("Y-m-d H:i:s", $last_post->timestamp);

    $thread_dump = json_encode($thread);
    $thread_size_in_bytes = strlen($thread_dump);
    $thread_size = format_size_units($thread_size_in_bytes);

    echo "<tr align=\"center\">";
    echo "<td align=\"right\">{$thread_index}:</td>";
    echo "<td align=\"left\"><a href=\"/thread.php?id={$thread->id}\">{$thread->topic}</a></td>";
    echo "<td><a href=\"/index.php?tag={$thread->tag}\">{$thread->tag}</a></td>";
    echo "<td>{$posts_amount}</td>";
    echo "<td><small>{$last_post_date}</small></td>";
    echo "<td><a href=\"/util/dump.php?id={$thread->id}\" title=\"Download thread data\">{$thread_size}</a></td>";
    echo "</tr>";
}
echo "</tbody>";

echo "</table>";

echo "<nav class=\"return-nav\"><a href=\"/index.php\" title=\"Go to index page\">Return</a></nav>";

// Navigate top
echo '<nav class="thread_vertical_nav"><big><a title="Jump to top" href="#top">Top</a></big></nav>';

include("./templates/footer.html");

?>
