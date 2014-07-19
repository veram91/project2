<?php
$host = 'localhost';
$user = 'root';
$pass = 'root';
$db = 'blog_db';


$link = mysql_connect($host, $user, $pass, $db);
if (!$link) {
    die('Could not connect: ' . mysql_error());
}

?>
