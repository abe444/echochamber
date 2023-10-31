<?php

require_once("./conf.php");
$meta_title = "About";
$meta_description = $CONF['META_DESCRIPTION'];

$last_modified = filemtime(__FILE__);


include("./templates/header.php");

echo "<hr/>";

// Navigate bottom
echo '<nav class="thread_vertical_nav"><big><a title="Jump to bottom" href="#bottom">Bottom</a></big></nav>';
?>
<main>
  <article class="about">
    <header>
      <center><h1>About <?php echo $CONF['BBS_NAME'] ?></h1></center>
      
    </header>

      <p>You info goes here.</p>

      <h2>Subtitle</h2>
      <p>More info.</p>
    <footer>
      <center>
        <small><?php echo "Last modified on " . date("Y-m-d H:i:s", $last_modified) . " (UTC)"; ?></small>
      </center>
    </footer>
  </article>
</main>
<?php
echo "<nav class=\"return-nav\"><a href=\"/index.php\" title=\"Go to index page\">Return</a></nav>";

// Navigate top
echo '<nav class="thread_vertical_nav"><big><a title="Jump to top" href="#top">Top</a></big></nav>';
    
include("./templates/footer.html");
?>
