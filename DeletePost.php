<?php

session_start();

if(isset($_COOKIE['username'])){
	$_SESSION['username']=$_COOKIE['username'];
}


include('connect.php');
mysql_select_db('blog_db');


if(!empty($_POST['entry_ID'])){
	$entry_ID = $_POST['entry_ID'];
	$blognum=$_POST['blog_ID'];
	$string="DELETE FROM tbl_entries WHERE entry_ID=$entry_ID";
	
	mysql_query($string) or die (mysql_error());
	mysql_query("DELETE FROM tbl_comments WHERE entry_ID='$entry_ID'") or die (mysql_error()); 	
	$redirect = "Location: BlogPage.php?blog_ID=".$blognum;
	header($redirect);
}else{
	$redirect = "Location: index.php";
	header($redirect);

}


?>
