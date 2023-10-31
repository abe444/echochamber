<?php

$CONF = array(
    'BBS_NAME'                    => '&#10013;',
    'BBS_PROMO'                   => 'あなたは世界の光です',

    'META_TITLE_DELIMITER'        => ' — ', // mind the spaces
    'META_DESCRIPTION'            => 'Comfy tiny BBS.',
    'META_DESCRIPTION_MAX_LENGTH' => 150,

    'BBS_TAGS'                    => array(
                                          '&#10013;'  => 'Christ talk',
                                          'prog'  => 'Programming &amp; Programs',
                                          'sec'  => 'Cybersecurity &amp; Cyberpunk',
                                          'jp'  => 'Let the hikki out of you',
                                          'other' => 'Other Topics'),
    'DEFAULT_TAG'                 => 'other',

    // index.php
    'THREADS_DISPLAYED'           => 10,
    'THREADS_LISTED'              => 40,
    'REPLIES_DISPLAYED'           => 5,

    'STALE_THREADS_EXPIRY_HOURS'  => 24, /* hours to pass before a staled thread (i.e. the one that received no replies) is purged 
                                            0 to disable this functionality
                                         */

    // post.php
    'MAX_TOPIC_LENGTH'            => 80,
    'MAX_NAME_LENGTH'             => 20,
    'MAX_EMAIL_LENGTH'            => 40,
    'MAX_MESSAGE_LENGTH'          => 10000,

    'MIN_TOPIC_LENGTH'            => 1,
    'MIN_MESSAGE_LENGTH'          => 2,

    'MAX_WORD_LENGTH'             => 50, /* the longest word in English is "pneumonoultramicroscopicsilicovolcanoconiosis" -- 45 characters long
                                            0 to disable -- e.g. if you want wide ASCII art on board, but not recommended
                                         */
);

?>
