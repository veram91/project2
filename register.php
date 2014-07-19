<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>untitled</title>
	<meta name="generator" content="TextMate http://macromates.com/">
	<meta name="author" content="Vera">
	<!-- Date: 2014-07-18 -->
	

</head>
<body>
	
	<!--Registration Form-->
	<h2>Registration Form</h2>
	<form id='register' action='register.php' method='post'
	    accept-charset='UTF-8' onsubmit="return validateFormr()">
	<fieldset >
	<legend>Register</legend>
	<input type='hidden' name='submitted' id='submitted' value='1'/>
	<label for='fname' >First Name*: </label>
	<input type='text' name='fname' id='fname' maxlength="50" />
	<label for='lname' >Last Name*: </label>
	<input type='text' name='lname' id='lname' maxlength="50" />
	<label for='username' >Username*: </label>
	<input type='text' name='username' id='username' maxlength="50" required />
	<label for='pass' >Password*: </label>
	<input type='password' name='pass' id='pass' maxlength="50" required/>
	<label for='title' >Blog Title*: </label>
	<input type='text' name='title' id='title' maxlength="50" required/>
	<input type='submit' name='Submit' value='Register' />
	</fieldset>
	</form>
	
	
	
	<?php
	include('connect.php');
	mysql_select_db("blog_db");
	$username = $_POST['username'];
	$password = $_POST['pass'];
	$blogtitle = $_Post['title'];
	//encrypt password at entry with crypt()
	//$password = crypt($password);
	

	$get_users = mysql_query("SELECT * FROM tlb_users where username = '$username'");
	$get_rows = mysql_affected_rows($get_users);
	if($get_rows!=0){
		die('Sorry, the username' . $username . ' already exists.');
	}


	$get_blogs = mysql_query("SELECT * FROM tlb_users where blog_title = '$blogtitle'");
	$get_rows = mysql_affected_rows($get_blogs);
	if($get_rows!=0){
		die('Sorry, the title' . $blogtitle . ' is already in use.');
	}



mysql_query("INSERT into tbl_users (f_name, l_name, username, password, blog_title) VALUES('".$_POST['fname']."','".$_POST['lname']."','".$_POST['username']."','".$_POST['pass']."','".$_POST['title']."')") or die(mysql_error());

//remove this line if the create trigger works in mysql.
mysql_query("INSERT into tbl_blogs (title, username) VALUES('".$_POST['title']."','".$_POST['username']."')") or die(mysql_error());


	$_POST['username'] = striplashes($_POST['username']);
	$hour = time() + 3600;
	setcookie('id_site', $_POST['username'], $hour);
	setcookie('key_site', $_POST['pass'], $hour);

	//closes mysql
	mysql_close($link);

	//redirects user to index page to log in
	header("Location: members.php");
	exit;


	?>	
	
</body>
</html>
