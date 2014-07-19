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
	<!--Login Form-->
	<h2>Login Form</h2>
	<form id="login_form" method="post" action="login.php">
	<fieldset>
		<legend>Enter your username and password</Legend>
		<label for="username">Username</label>
		<input type="text" name="username" size="8">
		<label for="pass">Password</label>
		<input type="password" name="pass" size="8">
		<input type="submit" value="Submit">
		<input type = "reset" value = "Reset">	
	</fieldset>	
	</form>	
	
<?php
include('connect.php');

if (isset($_COOKIE['id_site'])){
	header("Location: members.php");
}

mysql_select_db("blog_db");
$username = $_POST['username'];
$password = $_POST['pass'];

$mysql_login_info = mysql_query("SELECT username, password FROM tbl_users where username='$username' and password='$password'");
$get_rows = mysql_affected_rows($link);

#If username/pasword is correct, it will redirect the user to his/her unique home page
if ($get_rows >= 1) {
	$_POST['username'] = striplashes($_POST['username']);
	$hour = time() + 3600;
	setcookie('id_site', $_POST['username'], $hour);
	setcookie('key_site', $_POST['pass'], $hour);
    header("Location: members.php");
    } else {
        die('WRONG username/password combination');
    }
    
//alternate password check if the password is encrypted at registration
/*$mysql_login_info = mysql_query("SELECT password FROM tbl_users where username='$username'");
  
  if($mysql_login_info){
	if($row['password']=== crypt($pass, $row['password'])){
	   	$_POST['username'] = striplashes($_POST['username']);
	        $hour = time() + 3600;
         	setcookie('id_site', $_POST['username'], $hour);
         	setcookie('key_site', $_POST['pass'], $hour);
                header("Location: members.php");
	 }else{
	   die('WRONG username/password combination');
	 }
      }else{
	   die('username not found');
       }	   
  */  
    ?>

?>
</body>
</html>
