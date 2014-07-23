<?php 
session_start();
session_destroy();
$_SESSION = array();
if(isset($_COOKIE['username'])){
	setcookie('username', '', 0);
}
 $string="<br> you are logged out successfully!<br/><a href='index.php'>Return to the Index Page</a>";
?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="blog.css">
</head>
<body>
<? echo $string ?>
</body>
</html>
