<?php
require_once 'global-functions.php';

$instagram = strpos($_SERVER['HTTP_USER_AGENT'], "Instagram");
if ($instagram) {
    echo 'Instagram';
    echo '<br><br>';
}

$facebook = strpos($_SERVER['HTTP_USER_AGENT'], "FBAN");
$facebook = strpos($_SERVER['HTTP_USER_AGENT'], "FBIOS");
$facebook = strpos($_SERVER['HTTP_USER_AGENT'], "FBDV");

if ($facebook) {
    echo 'Facebook';
    echo '<br><br>';
}

$chrome = strpos($_SERVER['HTTP_USER_AGENT'], "Chrome");

if ($chrome) {
    echo 'Google Chrome';
    echo '<br><br>';
}
