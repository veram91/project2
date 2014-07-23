<?php 
session_start();
session_destroy();
$_SESSION = array();
if(isset($_COOKIE['username'])){
	setcookie('username', '', 0);
}
 $string="<br><h3 class='logoh1'>You are logged out of BlogNow successfully! have a great day.</h3><br/><a href='index.php'>Return to the Index Page</a>";
?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="blog.css">
</head>
<body>
<?php echo $string ?>
</body>
</html>
