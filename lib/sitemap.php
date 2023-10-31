<?php

function generate_sitemap() {
    $root_url = "http" . (!empty($_SERVER['HTTPS'])?"s":"") . "://" . $_SERVER['SERVER_NAME'];
    
    $urls = [
        $root_url,
        $root_url . '/about.php',
        $root_url . '/subback.php'
    ];

    $json_data = file_get_contents('./data.json');
    $data = json_decode($json_data, false);

    foreach ($data as $thread) {
        $urls[] = $root_url . "/thread.php?id=" . $thread->id;
    }

    $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');

    foreach ($urls as $url) {
        $url_element = $xml->addChild('url');
        $url_element->addChild('loc', $url);
        $url_element->addChild('changefreq', 'daily');
    }

    $xml->asXML('sitemap.xml');
}

?>
