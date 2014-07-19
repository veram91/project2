<?php 
session_start();
session_destroy();
$_SESSION = array();
if(isset($_COOKIE['username'])){
	setcookie('username', '', 0);
}
 echo "<br> you are logged out successfully!";
 echo "<br/><a href='index.php'>Return to the Index Page</a>";
?>
