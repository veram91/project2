
<?php 
session_start();
session_destroy();
$_SESSION = array();
if(isset($_COOKIE['username'])){
	setcookie('username', '', 0);
}
 $string="<br><h3 class='logoh1'>You have been successfully logged out of BlogNow.<br> Have a great day!</h3><br/>";
 $string2="<div class='return'><a style=align:'center' href='index.php'>Return to the Index Page</a></div>";
?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="blog.css">
</head>
<body>
<?php echo $string ?>
<?php echo $string2 ?>
</body>
</html>
