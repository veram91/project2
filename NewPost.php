<?php


session_start();
$db_host='localhost';
$db_user='root';
$db_pass='';
$db_db='blog_db';


//if this page is loaded from a bookmark, redirect to the index
if(empty($_POST['blog_ID'])){
 header ("Location: index.php");
 die();
}else{
$blog_ID=$_POST['blog_ID'];
}
$form="
<form name='newpost' action='NewPost.php' method='post'>
<label>Post Title:</label></br>
<input type='text' name='title'></input></br>
<label>Post Content:</label></br>
<textarea rows='10' cols='70' name='content'></textarea></br>
<input type='hidden' name='blog_ID' value='$blog_ID'></input>
<input type='submit' name='submit' value='Publish Post'>
<input type='reset' name='reset' value='Clear'>
</form>
";

if(!empty($_POST['title'])){
	
	//test form for completeness.
    $con=mysqli_connect($db_host, $db_user, $db_pass, $db_db);
	$username = mysqli_real_escape_string($con, $_SESSION['username']);
	$entrytitle= mysqli_real_escape_string($con, $_POST['title']);
	$entry= mysqli_real_escape_string($con, $_POST['content']);
		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		
	$query="INSERT INTO tbl_entries(blog_ID, title, content, data) SELECT blog_ID, '$entrytitle', '$entry', now() FROM tbl_blogs WHERE blog_ID='$blog_ID'";
	if (!mysqli_query($con,$query)) {
		die('Error: ' . mysqli_error($con));
	}
}
?>
<html>
<head>
<!--css and java links-->
</head>
<body>
<div>
<?php echo $form ?>
</div>
</body>
</html>
