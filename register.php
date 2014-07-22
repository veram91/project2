<?php
	
	session_start();
	
	$errortitle="";
	$errorname="";
	
	
	
	if(!empty($_POST['submitted'])){
		include('connect.php');
		mysql_select_db("blog_db");
		$username = $_POST['username'];
		$password = $_POST['pass'];
		$blogtitle = $_POST['title'];
		$password = crypt($password);
		$toggle=true;

		
		$get_users = mysql_query("SELECT * FROM tbl_users where username = '$username'");
		if($get_users){
			$row=mysql_fetch_array($get_users);
			if(!empty($row)){
				$errorname='Sorry, the username' . $username . ' already exists. </br>';
				$toggle=false;
			}
		}
		

		$get_blogs = mysql_query("SELECT * FROM tbl_users where blog_title = '$blogtitle'");
		if($get_blogs){
			$row=mysql_fetch_array($get_blogs);
			if(!empty($row)){
				$errortitle='Sorry, a Blog called ' . $username . ' already exists. </br>';
				$toggle=false;
			}
		}
		
		if($toggle){
			



		mysql_query("INSERT into tbl_users (f_name, l_name, username, password, blog_title) VALUES('".$_POST['fname']."','".$_POST['lname']."','".$username."','".$password."','".$blogtitle."')") or die(mysql_error());

		//remove this line if the create trigger works in mysql.
		//mysql_query("INSERT into tbl_blogs (title, username) VALUES('".$_POST['title']."','".$_POST['username']."')") or die(mysql_error());
		$result=mysql_query("SELECT blog_ID FROM tbl_blogs WHERE username='$username'");
		$row=mysql_fetch_array($result);

		$_POST['username'] = stripslashes($_POST['username']);
		$hour = time() + 3600;
		
		if(!empty($_POST['persist'])){
				setcookie('username', $username, $hour);
		}
		$_SESSION['username']=$_POST['username'];
		//closes mysql
		mysql_close($link);

		//redirects user to index page to log in
		$redirect="Location: BlogPage.php?blog_ID=".$row[0];
		header($redirect);
		exit;
		}
	}

?>	
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Registration Form</title>
	<meta name="generator" content="TextMate http://macromates.com/">
	<meta name="author" content="Vera">
	<!-- Date: 2014-07-18 -->
        <link rel="stylesheet" type="text/css" href=blog.css></link>

</head>
<body>

	<!--Registration Form-->
	<h1 class="logoh1">BlogNow...</h1>
	<h4 class="logoh4">Who will YOU influence today? Make a difference NOW.</h4>
	<?php echo $errorname ?>
	<?php echo $errortitle ?>
	<form id='register' action='register.php' method='post'
	    accept-charset='UTF-8' onsubmit="return validateFormr()">
	<fieldset >
	<legend><h3>Registration Form</h3></legend>
	<div class="formcontent">
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
	<input type='text' name='title' id='title' maxlength="50" required/><br><br>
	<label> Stay logged in?: </label>
	<input type="checkbox" name="persist"></input>
	<input type='submit' name='Submit' value='Register' />
	</div>
	</fieldset>
	</form>
	<a href="index.php"> Return to Index </a>
</body>
</html>
