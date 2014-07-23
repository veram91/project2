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
	$row=mysql_fetch_array($result);
	$title=$row['title'];
	$header="<h1 class='logoh1'>$title</h1>";
	
	$entryid = $_GET['entry_ID'];
	$get_entry = "SELECT * FROM tbl_entries WHERE entry_ID = '$entryid'";
	$result = mysql_query($get_entry);
	while($row = mysql_fetch_array($result)){
		$blog_title = $row['title'];
		$blog_title=stripslashes($blog_title);
		$content = $row['content'];
		$content=stripcslashes($content);
		$date = $row['date'];
		$page.="<div class = 'comments_page'>
				<div class = 'blog_title'><h1 class='logoh1'>$blog_title</h1></div>
				<div class = 'entry_content'>$content</div>
				<div class = 'entry_time'>$date</div>
				</div>
				<hr class='thick'>
				<h3 class='logoh1'>Comments</h3>";
	
	}
	
	
	$username=$_SESSION['username'];
	$query="SELECT blog_ID FROM tbl_blogs WHERE username='$username'";
	$result=mysql_query($query);
   	if($result){
	$row=mysql_fetch_array($result);
	$userblog=$row['blog_ID'];
	}
	
	
	
	$get_comments = "SELECT * FROM tbl_comments WHERE entry_ID = '$entryid'";
	$result = mysql_query($get_comments);
	$get_rows = mysql_affected_rows($link);
	if($get_rows > 0){
		while($row = mysql_fetch_array($result)){
			$author = $row['author'];
			$content = $row['content'];
			$date = $row['date'];
			$comment_ID=$row['comment_ID'];
			
			//if the author of the blog is viewing the comments, show delete button. Also show delete button for the comments left by logged in user
			if($blognum===$userblog || $author ===$username){
				$page.="<div class = 'display_comments'>
						<div class = 'comment_author'><h4>".$author." said...</h4></div>
						<div class = 'comment_content'>".$content."</div>
						<div class = 'comment_time'>".$date."</div>
						<form name='delcomment' action='del_comment.php' method='post'>
							 <input type = 'hidden' name = 'blog_ID' value='$blognum'></input>
							  <input type='hidden' name='entry_ID' value='$entryid'></input>
							  <input type='hidden' name='comment_ID' value='$comment_ID'></input>
							  <input type='submit' value='Delete Comment'></input>
							  </form>
						<hr class='thin'></div>";
			
			}
			else{
					$page.="<div class = 'display_comments'>
							<div class = 'comment_author'><h4>".$author." said...</h4></div>
							<div class = 'comment_content'>".$content."</div>
							<div class = 'comment_time'>".$date."</div>
							<hr class='thin'></div>";
					
				
			}
			
			
		
		}
		
	}

	else{
		$page.= "There are no comments on this entry";
		
	}
	$form = "
<form name = 'add_comment' action = 'new_comment.php' method='post'>
<label for='comment'>Enter your comment:</label><br/>
<textarea rows = '7' cols = '50' name = 'comment' required></textarea><br/>
<input type = 'hidden' name = 'blog_ID' value='$blognum'></input>
<input type = 'hidden' name = 'entry_ID' value='$entryid'></input>
<input type = 'submit' name = 'submit' value = 'Post New Comment'>
<input type = 'reset' name = 'reset' value='Clear'>
</form>";

	$footer="<div class='loggedin'>You logged in as ".$_SESSION['username']." <br/>
		Click <a href='BlogPage.php?blog_ID=".$blognum."'> here </a> to go to your blog <br/>
		<a href='index.php'> Return to BlogNow Index</a><br/>
		<a href='logout.php'> Logout</a>";
		

	

?>
<html lang="en">
<head>
	<link rel = "stylesheet" type="text/css" href="blog.css">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Home</title>
	<meta name="generator" content="TextMate http://macromates.com/">
	<meta name="author" content="Vera">
	<!-- Date: 2014-07-20 -->
</head>
<body>
<div>
<?php echo $header; ?>
<?php echo $page; ?>
<?php echo $form; ?>
</div>
<div class="footer">
<?php echo $footer; ?>
</div>	
</body>
</html>

