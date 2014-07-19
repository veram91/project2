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
	//test login against database
	mysql_select_db("blog_db");
	$username = $_POST['username'];
	$password = $_POST['pass'];

	$mysql_login_info = mysql_query("SELECT username, password FROM tbl_users where username='$username' and password='$password'");
	$get_rows = mysql_affected_rows($link);

	#If username/pasword is correct, it will update the session to include their username.
	if ($get_rows >= 1) {
		$username = stripslashes($_POST['username']);
		$hour = time() + 3600;
		if(!empty($_POST['persist'])){
			setcookie('username', $username, $hour);
		}
	    $_SESSION['username']=$_POST['username'];
	}else{
		$error="Invalid Login";
	}
}

	
	
if(!empty($_SESSION['username'])){	
	
	
	//if yes post list of blogs and a footer of "welcome" and "logout"
	$footer="<h2>you logged in as ".$_SESSION['username']." </br> Click <a href='logout.php'> here </a> to log out</h2>";
		mysql_select_db("blog_db");
	$result = mysql_query("SELECT * FROM tbl_blogs");
    $content.="<ol>";
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
	$content.="</ol>";
	
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
	</fieldset>	
	</form> <a href="register.php"> Create New Account </a> </br><?php echo $error ?>';
	mysql_select_db("blog_db");
	$result = mysql_query("SELECT * FROM tbl_blogs");
    $content.="<ol>";
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
	$content.="</ol>";
}
?>
<html>
<head>
</head>
<body>
<?php echo $content ?>
<?php echo $footer ?>
</body>
</html>
