<?php
include('connect.php');
session_start();
date_default_timezone_set("America/New_York");
$content="";
$footer="";
//check for cookie
if(isset($_COOKIE['username'])){
  $_SESSION['username']=$_COOKIE['username'];
}


//test the password
if(!empty($_POST['username'])){
	//test login against database
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

//finds the date of the last blog post in the blog
function lastPost($blog_ID){ 
	$commentQuery="SELECT date FROM tbl_entries WHERE blog_ID='$blog_ID' ORDER BY date DESC";
	$commentResult=mysql_query($commentQuery);
	$row=mysql_fetch_array($commentResult);
	$mysqldate=strtotime($row['date']);
	$date="Last updated on ".date("m/d/y",$mysqldate)." at ".date("g:i a",$mysqldate);
	if(!empty($mysqldate)){
		return $date;
	}else{
		return "No posts"; 
	}
}

//print out the list of blogs
function printBlogs(){
	mysql_select_db("blog_db");
	$result = mysql_query("SELECT * FROM tbl_blogs");
    	$content="<ul class='curly'>";
	if ($result) {
	 while ($row = mysql_fetch_array($result)) {
		$blog_ID=$row['blog_ID'];
		$blogtitle=$row['title'];
		$blogauthor=$row['username'];
		$link="BlogPage.php?blog_ID=".$blog_ID;
		$update=lastPost($blog_ID);
		$content.="<li><div>
		           <a class='bloglink' href='$link'>$blogtitle</a></br>
			    <div class='owner'>A blog by  $blogauthor</div>
				<div class='updated'>$update</div>
			   </div></li><hr class='thin'>";
        }
    }
	$content.="</ul>";
	
	return $content;
}
	
if(!empty($_SESSION['username'])){	
	
	//if yes post list of blogs and a footer of "welcome" and "logout"
	$footer="<div class='loggedin'> You logged in as ".$_SESSION['username'].".  Click <a href='logout.php'> here </a> to log out.</div>";
	$content=printBlogs();
	
}else{ //if no, post list of blogs and a footer of log-in
	$footer='<!--Login Form-->
	<form id="login_form" method="post" action="index.php">
	<fieldset>
		<legend><h3>Enter your username and password</h3></Legend>
		<div class="formcontent">
		<label for="username">Username</label>
		<input type="text" name="username" size="8">
		<label for="pass">Password</label>
		<input type="password" name="pass" size="8">
		<label> Stay logged in?: </label>
		<input type="checkbox" name="persist"></input>
		<input type="submit" value="Submit">
		<input type = "reset" value = "Reset">
		 <a href="register.php"> Create New Account </a> </br><?php echo $error ?>
		 </div>
	</fieldset>	
	</form>';
	$content=printBlogs();
}

?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="blog.css">
</head>
<body>
<div id="header">
<h1 class="logoh1">BlogNow...</h1><h4 class="logoh4">Who will YOU influence today? Make a difference NOW.</h4>
</div>
<div>
<?php echo $content ?>
</div>
<div class="footer">
<?php echo $footer ?>
</div>
</body>
</html>
