<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">
<?php

session_start();

if(isset($_COOKIE['username'])){
	$_SESSION['username']=$_COOKIE['username'];
}
include('connect.php');
mysql_select_db('blog_db');
$page = "";

 if(empty($_SESSION['username'])){
	echo "You must be logged in to access this page";
	echo "Click <a href='BlogPage.php'> here </a> to go back.";
	echo '<!--Login Form-->
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
		<a href="register.php"> Create New Account </a></br><?php echo $error ?>				
		</fieldset>	
		</form>';
}


	$blognum=$_GET['blog_ID'];
	$query="SELECT title FROM tbl_blogs WHERE blog_ID=$blognum";
	$result=mysql_query($query);
	$row=mysqli_fetch_array($result);
	$title=$row['title'];
	$header="<h1>$title</h1>";
	
	$entryid = $_GET['entry_ID'];
	$get_entry = "SELECT * FROM tbl_entries WHERE entry_ID = '$entryid'";
	$result = mysql_query($get_entry);
	while($row = mysql_fetch_array($result)){
		$blog_title = $row['title'];
		$content = $row['content'];
		$date = $row['data'];
		$page.="<div class = 'comments_page'>
				<div class = 'blog_title'><h2>$blog_title</h2></div>
				<div class = 'entry_content'>$content</div>
				<div class = 'entry_time'>$date</div>
				</div>
				<h1>Comments</h1>";
	
	}




	$get_comments = "SELECT * FROM tbl_comments WHERE entry_ID = '$entryid'";
	$result = mysql_query($get_comments);
	if($result){
		while($row = mysql_fetch_array($result)){
			$author = $row['author'];
			$content = $row['content'];
			$date = $row['date'];
			$page.="<div class = 'display_comments'>
					<div class = 'comment_author'><h2>".$author." said...</h2></div>
					<div class = 'comment_content'>".$content."</div>
					<div class = 'comment_time'>".$date."</div>
					</div>";
		
		}
		
	}

	else{
		$page.= "There are no comments on this entry";
	}
	
	$footer="<div class='loggedin'>you logged in as ".$_SESSION['username']."
		Click <a href='BlogPage.php?blog_ID=".$blognum."'> here </a> to go to your blog <br/>
		Click <a href='logout.php'> here </a> to log out";
	$footer.="<form name='newcomment' action='new_comment.php' method='post'>
		  <input type='hidden' name='entry_ID' value='$entryid'></input>
		  <input type='submit' value='Post Comment'></input>
		  </form></div>
		  ";
	

?>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>untitled</title>
	<meta name="generator" content="TextMate http://macromates.com/">
	<meta name="author" content="Vera">
	<!-- Date: 2014-07-20 -->
</head>
<body>
<div>
<?php echo $header; ?>
<?php echo $page; ?>	
<?php echo $footer; ?>
</div>	
</body>
</html>