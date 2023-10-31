<?php header('Content-Type: application/xhtml+xml; charset=utf-8'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $CONF['BBS_NAME'] . $CONF['META_TITLE_DELIMITER'] . $meta_title ?></title>
    <meta name="description" content="<?php echo $meta_description ?>" />
    <meta name="og:title" content="<?php echo $CONF['BBS_NAME'] . $CONF['META_TITLE_DELIMITER'] . $meta_title ?>" />
    <meta name="og:description" content="<?php echo $meta_description ?>" />

    <link rel="alternative" type="application/atom+xml"
     title="<?php echo $CONF['BBS_NAME']?> textboard Atom feed" href="/util/atom.php" />
    <link rel="stylesheet" href="./static/style.css" />
    <link rel="stylesheet" href="./static/prism/prism.css" />
    <link rel="icon" type="image/png" href="/favicon.ico" />
  </head>
  <body>
    <header id="top">
      <?php
        if (isset($_GET['id'])
            || $_SERVER['PHP_SELF'] !== "/index.php"):
            echo "<center><h1><span class='glory'>{$CONF['BBS_NAME']}</span></h1></center>";
        else:
            echo "<center><h1><span class='glory'>{$CONF['BBS_NAME']}</span></h1></center>";
        endif;
        echo "<center><pre>{$CONF['BBS_PROMO']}</pre></center>";
        echo "<nav id=\"tag-list\">[ ";
        echo "<a title=\"All Topics\" href=\"/index.php\">all</a> ";
        foreach ($CONF['BBS_TAGS'] as $tag => $tag_title):
            echo " | <a title=\"{$tag_title}\" href=\"/index.php?tag={$tag}\">{$tag}</a>";
        endforeach;

        echo " ] [ <a title=\"Atom Feed\" href=\"/util/atom.php\" target=\"_blank\">atom</a> |
                   <a title=\"About {$CONF['BBS_NAME']}\" href=\"/about.php\">about</a> ]</nav>";
      ?>
    </header>
