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
$comment_ID = $_POST['comment_ID'];


$query = "DELETE FROM tbl_comments WHERE comment_ID=$comment_ID";
									
mysql_query($query);
header("Location: get_comments.php?blog_ID=".$blognum."&entry_ID=".$entry_ID);



?>
