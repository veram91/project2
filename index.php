<?php
<?php
include('connect.php');
session_start();
$content="";
$footer="";
//check for cookie
if(isset($_COOKIE['username'])){
  $_SESSION['username']=$_COOKIE['username'];
}


if(!empty($_POST['username'])){

	mysql_select_db("blog_db");
	$username = $_POST['username'];
	$password = $_POST['pass'];
	

	$mysql_login_info = mysql_query("SELECT password FROM tbl_users where username='$username'");
	if($mysql_login_info){
	    $row=mysql_fetch_array($mysql_login_info);
		$hash=$row['password'];
		if($hash===crypt($password, $hash)){
			$hour = time() + 3600;
			if(!empty($_POST['persist'])){
				setcookie('username', $username, $hour);
			}
			$_SESSION['username']=$username;
		}else{
		  $error="wrong password";
		}
	}else{
	  $error="Invalid Login";	
	}
}

	
	
if(!empty($_SESSION['username'])){	
	
	
	//if yes post list of blogs and a footer of "welcome" and "logout"
	$footer="<div class='loggedin'> you logged in as ".$_SESSION['username']."  Click <a href='logout.php'> here </a> to log out</div>";
		mysql_select_db("blog_db");
	$result = mysql_query("SELECT * FROM tbl_blogs");
    $content.="<ul>";
    if ($result) {
        while ($row = mysql_fetch_array($result)) {
			$blog_ID=$row['blog_ID'];
			$blogtitle=$row['title'];
			$blogauthor=$row['username'];
			$link="BlogPage.php?blog_ID=".$blog_ID;
			$content.="<li><div class='bloglink'>
						<a href='$link'>$blogtitle</a></br>
					    by:  $blogauthor
					   </div></li>
					   ";
        }
    }
	$content.="</ul>";
	
}else{ //if no, post list of blogs and a footer of log-in
	$footer='<!--Login Form-->
	<form id="login_form" method="post" action="index.php">
	<fieldset>
		<legend>Enter your username and password</Legend>
		<label for="username">Username</label>
		<input type="text" name="username" size="8">
		<label for="pass">Password</label>
		<input type="password" name="pass" size="8">
		<label> Stay logged in?: </label>
		<input type="checkbox" name="persist"></input>
		<input type="submit" value="Submit">
		<input type = "reset" value = "Reset">
		 <a href="register.php"> Create New Account </a> </br><?php echo $error ?>
	</fieldset>	
	</form>';
	mysql_select_db("blog_db");
	$result = mysql_query("SELECT * FROM tbl_blogs");
    $content.="<ul>";
    if ($result) {
        while ($row = mysql_fetch_array($result)) {
			$blog_ID=$row['blog_ID'];
			$blogtitle=$row['title'];
			$blogauthor=$row['username'];
			$link="BlogPage.php?blog_ID=".$blog_ID;
			$content.="<li><div class='bloglink'>
						<a href='$link'>$blogtitle</a></br>
					    A blog by  $blogauthor
					   </div></li>
					   ";
        }
    }
	$content.="</ul>";
}
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="blog.css">
</head>
<body>
<div id="header"><h1>This is a blog site </h1></div>
<?php echo $content ?>
<?php echo $footer ?>
</body>
</html>
