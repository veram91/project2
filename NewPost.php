<?php


session_start();
$db_host='localhost';
$db_user='root';
$db_pass='';
$db_db='blog_db';
$preview="";
$form="";

//if this page is loaded from a bookmark, redirect to the index
if(empty($_POST['blog_ID'])){
 header ("Location: index.php");
 die();
}else{
$blog_ID=$_POST['blog_ID'];
}

//redirect if quit button is pressed
if(!empty($_POST['quit'])){
 header ("Location: BlogPage.php?blog_ID=$blog_ID");
}

//
if(!empty($_POST['post'])){
	$con=mysqli_connect($db_host, $db_user, $db_pass, $db_db);
	$username = mysqli_real_escape_string($con, $_SESSION['username']);
	$entrytitle= mysqli_real_escape_string($con, $_POST['title']);
	$entry= mysqli_real_escape_string($con, $_POST['content']);
		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		
	$query="INSERT INTO tbl_entries(blog_ID, title, content, date) SELECT blog_ID, '$entrytitle', '$entry', now() FROM tbl_blogs WHERE blog_ID='$blog_ID'";
	if (!mysqli_query($con,$query)) {
		die('Error: ' . mysqli_error($con));
	}
	$redirect="Location: BlogPage.php?blog_ID=".$blog_ID;
	header($redirect);
}


if(!empty($_POST['title'])&&!empty($_POST['content'])){
	if(empty($_POST['rewrite'])){ //code to show a preview of the post
	$title=$_POST['title'];
	$content=$_POST['content'];
	$content=nl2br($content);

	$preview="<div class='entry'>
		<div class='etitle'><h2>$title</h2></div>
		<div class='etext'>$content</div></br></div>";

	$form="
	<form name='newpost' action='NewPost.php' method='post'>
	<input type='hidden' name='blog_ID' value='$blog_ID'></input>
	<input type='hidden' name='title' value='$title'></input>
	<input type='hidden' name='content' value='$content'></input>
	<input type='submit' name='post' value='Publish post'>
	<input type='submit' name='rewrite' value='Rewrite'>
	<input type='submit' name='quit' value='Quit without posting'>
	</form>";
	}else{ //code to edit the post after preview
	$title=$_POST['title'];
	$content=$_POST['content'];
	$content=str_replace("<br />", "", $content);
	$form="
	<form name='newpost' action='NewPost.php' method='post'>
	<label>Post Title:</label></br>
	<input type='text' name='title' value='$title'></input></br>
	<label>Post Content:</label></br>
	<textarea rows='10' cols='70' name='content'>$content</textarea></br>
	<input type='hidden' name='blog_ID' value='$blog_ID'></input>
	<input type='submit' name='preview' value='Preview Post'>
	<input type='reset' name='reset' value='Clear'>
	<input type='submit' name='quit' value='Quit without posting'>
	</form>";
	}
}else{    //this code prints the blank form

	$form="
	<form name='newpost' action='NewPost.php' method='post'>
	<label>Post Title:</label></br>
	<input type='text' name='title'></input></br>
	<label>Post Content:</label></br>	
	<textarea rows='10' cols='70' name='content'></textarea></br>
	<input type='hidden' name='blog_ID' value='$blog_ID'></input>
	<input type='submit' name='preview' value='Preview Post'>
	<input type='reset' name='reset' value='Clear'>
	<input type='submit' name='quit' value='Quit without posting'>
	</form>";
}

?>



<html>
<head>
<link rel="stylesheet" type="text/css" href="blog.css"></link>
</head>
<body>
<div>
<div id="content">
<?php echo $preview ?>
<div id="formarea">
<?php echo $form ?>
</div>
</div>
</body>
</html>
