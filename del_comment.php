<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<?php
session_start();
if(isset($_COOKIE['username'])){
	$_SESSION['username']=$_COOKIE['username'];
}
include('connect.php');
mysql_select_db('blog_db');
$blognum = $_POST['blog_ID'];
$entry_ID = $_POST['entry_ID'];
$author = $_POST['c_author'];
$content = $_POST['c_content'];
$date = $_POST['c_date'];

$query = "DELETE FROM tbl_comments WHERE entry_ID = '$entry_ID' 
									AND author = '$author' 
									AND content = '$content' 
									AND date = '$date'";
									
mysql_query($query);
header("Location: get_comments.php?blog_ID=".$blognum."&entry_ID=".$entryid);



?>
