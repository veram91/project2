<?php 



session_start();

if(isset($_COOKIE['username'])){
	$_SESSION['username']=$_COOKIE['username'];
}


$db_host='localhost';
$db_user='root';
$db_pass='99goner99';
$db_db='blog_db';
$page="";
$footer="";
$header="";
$userblog="";

function createFoot($blog_ID){

	global $footer;
	if ($blog_ID>0){
	$string="Click <a href='BlogPage.php?blog_ID=".$blog_ID."'> here </a> to go to your blog";
	}else{
		$string="<a href='index.php'>Return to Blog Index</a></br>";
	}

	$footer="<div class='loggedin'>You logged in as ".$_SESSION['username'].$string.
		"Click <a href='logout.php'> here </a> to log out.</div>";
}

function countComments($entry_ID, $con){
					$commentQuery="SELECT COUNT(*) AS total FROM tbl_comments WHERE entry_ID='$entry_ID'";
					$commentResult=mysqli_query($con,$commentQuery);
					$total=mysqli_fetch_assoc($commentResult);
					$numCom=$total['total'];
					if(!empty($total['total'])){
					    if($numCom==1){
							$commentString="View $numCom comment.";
						}else{
							$commentString="View $numCom comments.";
						}
					}else{
						   $commentString="Click to add a comment.";
					}
					return $commentString;
}





if(!empty($_GET['blog_ID'])){ 
	$blognum=$_GET['blog_ID'];
	$con=mysqli_connect($db_host, $db_user, $db_pass, $db_db);
	if(mysqli_connect_errno()){
		$success="Failed to connect to datebase: ".mysqli_connect_error();
	}
	$query="SELECT title,username FROM tbl_blogs WHERE blog_ID=$blognum";
	$result=mysqli_query($con,$query);
	$row=mysqli_fetch_array($result);
	$title=$row['title'];
    $username = $row['username'];
	
	$query = "SELECT f_name, l_name FROM tbl_users WHERE username = '$username'";
	$result = mysqli_query($con,$query);
	$row=mysqli_fetch_array($result);
	$firstname = $row['f_name'];
	$lastname = $row['l_name'];
	
	$header="<h1 class='logoh1'>$title</h1><h4 class='logoh4'>A BlogNow by: $firstname $lastname</h4><hr class='thick'>";
    if(empty($_SESSION['username'])){ //if person looking isnt logged in
			$query="SELECT * FROM tbl_entries WHERE blog_ID='$blognum' ORDER BY date ASC";
			$result=mysqli_query($con,$query);
			if($result){
				while($row=mysqli_fetch_array($result)){
					$title=$row['title'];
					$title=stripslashes($title);
					$content=$row['content'];
					$content=stripslashes($content);
					$mysqldate=strtotime($row['date']);
					$date="posted on ".date("m/d/y",$mysqldate)." at ".date("g:i a",$mysqldate);
					$page.="<div class='entry'>
						<div class='etitle'><h2>$title</h2></div>
						<div class='etext'>$content</div></br>
						<div class='etime'>$date</div><div class='ecomments'> Log in to view comments. </div>
						</div>";
				}
				$footer='<!--Login Form-->
				<form id="login_form" method="post" action="index.php">
				<fieldset>
				<legend><h3>Enter your username and password</h3></Legend>
				<label for="username">Username</label>
				<input type="text" name="username" size="8">
				<label for="pass">Password</label>
				<input type="password" name="pass" size="8">
				<label> Stay logged in?: </label>
				<input type="checkbox" name="persist"></input>
				<input type="submit" value="Submit">
				<input type = "reset" value = "Reset">
				<a href="register.php"> Create New Account </a></br><?php echo $error ?>
				<a href="index.php">Return to Blog Index</a></br>
				</fieldset>	
				</form> ';

		     }else{

				header("Location: index.php");
			 }
    }else{
		$username=$_SESSION['username'];
		$query="SELECT blog_ID FROM tbl_blogs WHERE username='$username'";
		$result=mysqli_query($con,$query);
    	if($result){
		$row=mysqli_fetch_array($result);
		$userblog=$row['blog_ID'];
		}
		if($blognum===$userblog){ //if this is the users blog
			$query="SELECT * FROM tbl_entries WHERE blog_ID='$blognum' ORDER BY date ASC";
			$result=mysqli_query($con,$query);
			if($result){
				while($row=mysqli_fetch_array($result)){
					$title=$row['title'];
					$title=stripslashes($title);
					$content=$row['content'];
					$content=stripslashes($content);
					$mysqldate=strtotime($row['date']);
					$date="Posted on ".date("m/d/y",$mysqldate)." at ".date("g:i a",$mysqldate).".";
					$entry_ID=$row['entry_ID'];
					$commentString=countComments($entry_ID, $con);
					$page.="<div class='entry'>
						<div class='etitle'><h2>$title</h2></div>
						<div class='etext'>$content</div></br>
						<div class='etime'>$date</div><div class='ecomments'><a href='get_comments.php?blog_ID=".$blognum."&entry_ID=".$entry_ID."'>$commentString</a> </div>
						<div class='deletebutton'>
						<form name='deletepost' action='DeletePost.php' method='post'>
						<hr class='thin'>
						<input type='hidden' name ='blog_ID' value='$blognum'></input>
						<input type='hidden' name ='entry_ID' value='$entry_ID'></input>
						<input type='submit' name='submit' value='Delete Post'></input>
						</form>
						</div>";
				}
		 		createFoot(0);
				$footer.="<form name='newpost' action='NewPost.php' method='post'>
					  <input type='hidden' name='blog_ID' value='$userblog'></input>
					  <input type='submit' value='New Post'></input>
					  </form></div>
					  ";
			}else{
	             $page.="There is no content on this blog."; //blank blog

		 		createFoot($blognum);
				$footer.="<form name='newpost' action='NewPost.php' method='post'>
					  <input type='hidden' name='blog_ID' value='$userblog'></input>
					  <input type='submit' value='New Post'></input>
					  </form></div>
					  ";
			}
		}else{ //viewing someone elses blog
			$query="SELECT * FROM tbl_entries WHERE blog_ID='$blognum' ORDER BY date ASC";
			$result=mysqli_query($con,$query);
			if($result){
				while($row=mysqli_fetch_array($result)){
					$title=$row['title'];
					$title=stripslashes($title);
					$content=$row['content'];
					$content=stripslashes($content);
					$mysqldate=strtotime($row['date']);
					$date="posted on ".date("m/d/y",$mysqldate)." at ".date("g:i a",$mysqldate);
					$entry_ID=$row['entry_ID'];
					
					$commentString=countComments($entry_ID, $con);
					
					$page.="<div class='entry'>
						<div class='etitle'><h2>$title</h2></div>
						<div class='etext'>$content</div></br>
						<div class='etime'>$date</div><div class='ecomments'><a href='get_comments.php?blog_ID=".$blognum."&entry_ID=".$entry_ID."'>$commentString</a> </div>
						</div>";
				}
				$username=$_SESSION['username'];

				$query="SELECT blog_ID from tbl_blogs where username='$username'";
				$result=mysqli_query($con,$query);
				$row=mysqli_fetch_array($result);
				$blog_ID=$row[0];
				createFoot($blog_ID);
			}else{
				$page.="There is no content on this blog."; //blank blog
				createFoot($blog_ID);
			}
		}

				mysqli_close($con);
	}
}elseif(!empty($_SESSION['username'])){
	$username=$_SESSION['username'];
	$con=mysqli_connect($db_host, $db_user, $db_pass, $db_db);
	if(mysqli_connect_errno()){
		$success="Failed to connect to datebase: ".mysqli_connect_error();
	}
	$query="SELECT blog_ID,title FROM tbl_blogs WHERE username='$username'";
	$result=mysqli_query($con,$query);
	$row=mysqli_fetch_array($result);
	$userblog=$row['blog_ID'];
	$redirect="Location: BlogPage.php?blog_ID=$userblog";
	header($redirect);

  
}else{ //not logged in, not looking at a bookmarked page

	header("Location: index.php");
}

?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="blog.css">
</head>
<body>
<div>
<?php echo $header ?>
<?php echo $page ?>
<?php echo $footer ?>
</div>
</body>
</html>


