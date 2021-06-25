<?php

$lang = $_GET['lang'];
$back_uri = isset($_GET['back_uri']) ? trim($_GET['back_uri']) : 'index.php';
if ($lang == "id" || $lang == "en") {
session_name('IndoWapBlog');
session_start();
$_SESSION['language'] = $lang;
header('Location: '.str_replace('&amp;','&',htmlentities(urldecode($back_uri))));
}
else {
header('Location: '.str_replace('&amp;','&',htmlentities(urldecode($back_uri))));
}
?>