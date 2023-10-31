<?php

if (!isset($_GET['id'])) {
    $topic_length = strlen(sanitize($_POST['topic']));
    if ($topic_length > $CONF['MAX_TOPIC_LENGTH'])
        $error = "Topic is too long. Max is {$CONF['MAX_TOPIC_LENGTH']} characters.";
    if ($topic_length < $CONF['MIN_TOPIC_LENGTH'])
        $error = "Topic is too short. Min is {$CONF['MIN_TOPIC_LENGTH']} characters.";

    $tag = sanitize($_POST['tag']);
    if (!array_key_exists($tag, $CONF['BBS_TAGS'])) {
        $error = "The tag \"{$tag}\" doesn't exist.";
    }
}
$name_length = strlen(sanitize($_POST['name']));
if ($name_length > $CONF['MAX_NAME_LENGTH'])
    $error = "Name is too long. Max is {$CONF['MAX_NAME_LENGTH']} characters.";
if (empty($name_length))
    $_POST['name'] = "Anonymous";

$email_length = strlen(sanitize($_POST['email']));
if ($email_length > $CONF['MAX_EMAIL_LENGTH'])
    $error = "Email is too long. Max is {$CONF['MAX_EMAIL_LENGTH']} characters.";

$message_length = strlen(sanitize($_POST['message']));
if ($message_length > $CONF['MAX_MESSAGE_LENGTH'])
    $error = "Message is too long. Max is {$CONF['MAX_MESSAGE_LENGTH']} characters.";
if ($message_length < $CONF['MIN_MESSAGE_LENGTH'])
    $error = "Message is too short. Min is {$CONF['MIN_MESSAGE_LENGTH']} characters.";


$message = sanitize($_POST['message']);
if ($message_length >= $CONF['MIN_MESSAGE_LENGTH'] && preg_match_all('/\p{L}/u', $message) < $CONF['MIN_MESSAGE_LENGTH'])
    $error = "Wow, hold on hacker.";


// Check if there are enormously long word
function contains_sesquipedalian(string $input, int $max_length): bool {
    $words = preg_split('/[\s[:punct:]]+/', $input);
    foreach ($words as $word) {
        if (strlen($word) > $max_length) {
            return true;
        }
    }
    return false;
}

if ($CONF['MAX_WORD_LENGTH'] > 0) {
    foreach ($_POST as $input) {
        if (contains_sesquipedalian($input, $CONF['MAX_WORD_LENGTH'])) {
            $error = "Some of the words is enormously long. Max is {$CONF['MAX_WORD_LENGTH']} characters.";
        }
    }
}

// Captcha and flood controller
if ($_POST['pulcinella'] !== "43aa950ba2689dd76e55e2596163a43b") {
    $error = "Don't be such a bot, pal.";
}

if (!empty($_POST['category']) || !empty($_POST['comment'])) {
    $error = "This website is not for robots.";
}

if (isset($_POST['captcha'])) {
    session_start();
    $userInput = trim($_POST['captcha']);
    
    if ($userInput === $_SESSION['captcha_fruit']) {
        $userInput === $_SESSION['captcha_fruit'];
    } else {
        $error = "Captcha failed.";
    }
} elseif(!isset($_POST['captcha']) || trim($_POST['captcha']) == '') {
    $error = ("Captcha failed. Input is missing.");
}else{
    $_SESSION['last_submit'] = time();
}

if (isset($_SESSION['last_submit']) && time()-$_SESSION['last_submit'] < 15)
    $error = 'Too many requests. Please wait at least 5 to 10 seconds before sending another POST request.';
else
$_SESSION['last_submit'] = time();
// Captcha and flood controller

$client_IP = $_SERVER['REMOTE_ADDR'];
$blocked_IPs = file('blocklist.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($blocked_IPs as $blocked_IP) {
    if (strpos($blocked_IP, '/')) {
        // IP range
        if (ip_in_range($client_IP, $blocked_IP)) {
            http_response_code(403);
            $error = "You are not welcome here.";
            break;
        }
    } else {
        // Individual IP address
        if ($client_IP === $blocked_IP) {
            http_response_code(403);
            $error = "You are not welcome here.";
            break;
        }
    }
}

// Render error if any
if (isset($error)) {
    include("./templates/header.php");
    echo "<hr/>";
    echo "<p style=\"color: red;\">" . $error . "</p>";
    echo "<p><nav class=\"return-nav\"><a href=\"/index.php\">Return</a></nav></p>";
    echo "<center><img src=\"static/images/stills/gondolas/2.png\" alt=\"gondola\" width=\"500\" height=\"auto\"/></center>";
    include("./templates/footer.html");
    exit();
}

?>
