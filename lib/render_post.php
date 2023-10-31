<?php

function render_post(object $current_post, string $thread_id) {
    $date = date("Y-m-d H:i:s", $current_post->timestamp);
    $date_iso8601 = date("Y-m-d\TH:i:s", $current_post->timestamp);
    $formatted_message = markdown_to_html(
        link_reference($current_post->message, $thread_id)
    );
    if (empty($current_post->email)) {
        $name_chunk = $current_post->name;
    } else {
        $name_chunk = "<a href=\"mailto:{$current_post->email}\">{$current_post->name}</a>";
    }

    if (isset($_GET['id'])) {
        $post_heading_level = '2';
        $post_onclick = "onclick=\"referencePost({$current_post->number}); return false;\"";
    } else {
        $post_heading_level = '3';
        $post_onclick = "";
        $formatted_message = truncate_long_post($formatted_message,
                                                $thread_id, $current_post->number);
    }
    echo "<section>";
    echo "<header>";
    echo "<h{$post_heading_level}>";
    echo "<a rel=\"nofollow\" {$post_onclick} href=\"/thread.php?id={$thread_id}&amp;p={$current_post->number}\">{$current_post->number}</a>";
    echo "</h{$post_heading_level}>";
    echo " <small>{$name_chunk}: <time datetime=\"{$date_iso8601}\">{$date}</time></small>";
    echo "</header>";
    echo "<p>{$formatted_message}</p>";
    echo "</section>";
}

function sanitize(string $input): string {
    // remove format chars
    $input = preg_replace('/\p{Cf}/u', '', $input);
    // Trim, sanitize HTML and return formatted string
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
};

function link_reference (string $message, string $thread_id): string {
    // https://textboard.lol/thread.php?id=646a7fe0991da becomes a short link
    $message = preg_replace("|(https?://)?{$_SERVER['SERVER_NAME']}/thread\.php\?id=([0-9a-f]{13})(\s)|", '@$2$3', $message);
    // @6431cf2cd1b31 becomes a link to thread 6431cf2cd1b31
    $message = preg_replace('/@([0-9a-f]{13})([^a-z0-9\-]|$)/', '<a href="/thread.php?id=$1">@$1</a>$2', $message);
    // @6431cf2cd1b31->2 becomes a link to thread's 6431cf2cd1b31 second post
    $message = preg_replace('/@([0-9a-f]{13})-&gt;(\d+)/',
                            "<a rel=\"nofollow\" href=\"/thread.php?id=$1&amp;p=$2\">@$1-&gt;$2</a>",
                            $message);
    // >>3 becomes link to third post of the thread
    return preg_replace('/&gt;&gt;(\d+)/', "<a rel=\"nofollow\" href=\"/thread.php?id={$thread_id}&amp;p=$1\">&gt;&gt;$1</a>", $message);
    // check for non-existing post?
}

function markdown_to_html(string $text): string {
    // >quote
    $text = preg_replace('/(^|\r\n|\n|\r)&gt;(.*?)($|\r\n|\n|\r(?!\n))(?!(\r\n)?&gt;)(\r\n|\n|\r)*/s',
                         '<blockquote>&gt;$2</blockquote>', $text);

    /*
      ```bash
      echo "code snippet"
      ```
    */
    $text = preg_replace('/```([a-z0-9-]{0,20})(\n|\r|\r\n)(.*?)\2```\2?/s', '<pre><code class="language-$1">$3</code></pre>', $text);

    /* replaces that depend on programmatic newlines
       should be put before this conversion
     */
    // newline
    $text = nl2br($text);
    
    // **bold**
    $text = preg_replace('/(\*\*)(?!\*)([^*]*?)(?<!\*)\1/', '<b>$2</b>', $text);

    // *italic*
    $text = preg_replace('/(\*)(?!\*)([^*]*?)(?<!\*)\1/', '<i>$2</i>', $text);

    // ~~strikethrough~~
    $text = preg_replace('/(~~)(.*?)\1/', '<del>$2</del>', $text);

    // __underscore__
    $text = preg_replace('/(__)(.*?)\1/', '<u>$2</u>', $text);

    // %%spoiler%%
    $text = preg_replace('/(%%)(.*?)\1/', '<span class="spoiler">$2</span>', $text);

    // teletype -- inline code
    $text = preg_replace('/`(?!`)(.*?)`/', '<code>$1</code>', $text);

    // ==Red text== 
    $text = preg_replace('/(\=\=)(.*?)\1/', '<span class="redtext">$2</span>', $text);

    // $$shaketext$$ 
    $text = preg_replace('/(\$\$)(.*?)\1/', '<span class="shaketext">$2</span>', $text);

    // !!GLOW text!! 
    $text = preg_replace('/(\!\!)(.*?)\1/', '<span class="glow">$2</span>', $text);

    // $rainbowtext$ 
    $text = preg_replace('/(\$)(.*?)\1/', '<span class="rainbowtext">$2</span>', $text);

    // [[BUTTON text]] 
    $text = preg_replace('/(\|\|)(.*?)\1/', '<button>$2</button>', $text);

    // ^^3D text^^ 
    $text = preg_replace('/(\^\^)(.*?)\1/', '<span class="threedtext">$2</span>', $text);

    return $text;
}

function truncate_long_post(string $html, string $thread_id, string $post_number): string {
    $MAX_LINES = 10;

    $lines = explode('<br />', $html);
    if (count($lines) > $MAX_LINES) {
        $lines = array_slice($lines, 0, $MAX_LINES);
        $truncated_html = implode('<br />', $lines);
        $truncated_html = preg_replace('/<pre><code(.*?)>(.*)$/s', '', $truncated_html);
        $truncated_html = preg_replace('/<blockquote>((?:(?!<\/blockquote>).)*)$/s', '<blockquote>$1</blockquote>', $truncated_html);
        $truncated_html .=
                        "<br /><br />"
                        .
                        "<i>(<a rel=\"nofollow\" href=\"/thread.php?id={$thread_id}&amp;p={$post_number}\">Post truncated</a>)</i>";
        return $truncated_html;
    }

    // Return the original HTML
    return $html;
}

?>
