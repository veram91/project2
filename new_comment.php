<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<?php
session_start();
include('connect.php');
mysql_select_db('blog_db');

if(empty($_POST['entry_ID'])){
	header("Location: index.php");
	die();
}
else{
	$entryid = $_POST['entry_ID'];
	$blognum = $_POST['blog_ID'];
}

$form = "
<form name = 'add_comment' action = 'new_comment.php' method='post'>
<label for='comment'>Enter your comment:</label><br/>
<textarea rows = '7' cols = '50' name = 'comment' required></textarea><br/>
<input type = 'hidden' name = 'entry_ID' value='$entryid'></input>
<input type = 'submit' name = 'submit' value = 'Post New Comment'>
<input type = 'reset' name = 'reset' value='Clear'>
</form>";

	$username = $_SESSION['username'];
	$content = $_POST['comment'];

	mysql_query("INSERT INTO tbl_comments(entry_ID, author, content, date) 
				VALUES('$entryid', '$username', '$content', now()) ") or die (mysql_error()); 
	$redirect = "Location: get_comments.php?blog_ID=".$blognum."&entry_ID=".$entryid;
	header($redirect);



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
<?php echo $form ?>
</div>
</body>
</html>
