<?php 



session_start();

if(isset($_COOKIE['username'])){
	$_SESSION['username']=$_COOKIE['username'];
}


$db_host='localhost';
$db_user='root';
$db_pass='';
$db_db='blog_db';
$page="";
$footer="";
$header="";
$userblog="";



if(!empty($_GET['blog_ID'])){ 
	$blognum=$_GET['blog_ID'];
	$con=mysqli_connect($db_host, $db_user, $db_pass, $db_db);
	if(mysqli_connect_errno()){
		$success="Failed to connect to database: ".mysqli_connect_error();
	}
	$query="SELECT title FROM tbl_blogs WHERE blog_ID=$blognum";
	$result=mysqli_query($con,$query);
	$row=mysqli_fetch_array($result);
	$title=$row['title'];
	$header="<h1>$title</h1>";
   if(empty($_SESSION['username'])){ //if person looking isnt logged in
			$query="SELECT * FROM tbl_entries WHERE blog_ID=$blognum ORDER BY data ASC";
			$result=mysqli_query($con,$query);
			if($result){
				while($row=mysqli_fetch_array($result)){
					$c=$row['title'];
					$b=$row['content'];
				    $a=$row['data'];
			     	$page.="<div class='entry'>
						<div class='etitle'><h2>$c</h2></div>
						<div class='etext'>$b</div></br>
						<div class='eauthor'>$a</div><div text='ecomments'> log in to view comments </div>
						</div>";
				}
				$footer='<!--Login Form-->
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
				</fieldset>	
				</form> <a href="register.php"> Create New Account </a></br><?php echo $error ?>';

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
			$query="SELECT * FROM tbl_entries WHERE blog_ID='$blognum' ORDER BY data ASC";
			$result=mysqli_query($con,$query);
			if($result){
				while($row=mysqli_fetch_array($result)){
					$c=$row['title'];
					$b=$row['content'];
					$a=$row['data'];
					$page.="<div class='entry'>
						<div class='etitle'><h2>$c</h2></div>
						<div class='etext'>$b</div></br>
						<div class='eauthor'>$a</div><div text='ecomments'> comments section link here </div>
						</div>";
				}
				$footer.="<form name='newpost' action='NewPost.php' method='post'>
					  <input type='hidden' name='blog_ID' value='$userblog'></input>
					  <input type='submit' value='New Post'></input>
					  </form>
					 ";
			}else{
	             $page.="There is no content on this blog"; //blank blog
				 		$footer.="<form name='newpost' action='NewPost.php' method='post'>
					  <input type='hidden' name='blog_ID' value='$userblog'></input>
					  <input type='submit' value='New Post'></input>
					  </form>
					 ";
			}
		}else{ //viewing someone elses blog
			$query="SELECT * FROM tbl_entries WHERE blog_ID='$blognum' ORDER BY data ASC";
			$result=mysqli_query($con,$query);
			if($result){
				while($row=mysqli_fetch_array($result)){
					$c=$row['title'];
					$b=$row['content'];
					$a=$row['data'];
					$page.="<div class='entry'>
						<div class='etitle'><h2>$c</h2></div>
						<div class='etext'>$b</div></br>
						<div class='eauthor'>$a</div><div text='ecomments'> comments section link here </div>
						</div>";
				}
				$username=$_SESSION['username'];

				$query="SELECT blog_ID from tbl_blogs where username='$username'";
				$result=mysqli_query($con,$query);
				$row=mysqli_fetch_array($result);
				$blog_ID=$row[0];
				$footer="<h2>you logged in as ".$_SESSION['username']."
				</br> Click <a href='BlogPage.php?blog_ID=".$blog_ID."'> here </a> to go to your blog
				</br> Click <a href='logout.php'> here </a> to log out</h2>";
			}else{
				$page.="There is no content on this blog"; //blank blog
			}
		}

				mysqli_close($con);
	}
}elseif(!empty($_SESSION['username'])){
	$username=$_SESSION['username'];
	$con=mysqli_connect($db_host, $db_user, $db_pass, $db_db);
	if(mysqli_connect_errno()){
		$success="Failed to connect to database: ".mysqli_connect_error();
	}
	$query="SELECT blog_ID,title FROM tbl_blogs WHERE username=$username";
	$result=mysqli_query($con,$query);
	$row=mysqli_fetch_array($result);
	$userblog=$row['blog_ID'];
	$title=$row['title'];
	$header="<h1>$title</h1>";
	$query="SELECT * FROM tbl_entries WHERE blog_ID=$blognum ORDER BY data ASC";
	$result=mysqli_query($con,$query);
	if($result){
		while($row=mysqli_fetch_array($result)){
					$c=$row['title'];
					$b=$row['content'];
				    $a=$row['data'];
			     	$page.="<div class='entry'>
						<div class='etitle'><h2>$c</h2></div>
						<div class='etext'>$b</div></br>
						<div class='eauthor'>$a</div><div text='ecomments'> comments section link here </div>
						</div>";
		}
		$footer.="<form name='newpost' action='NewPost.php' method='post'>
					  <input type='hidden' name='blog_ID' value='$userblog'></input>
					  <input type='submit' value='New Post'></input>
					  </form>
					 ";
	}else{
	    $page.="There is no content on this blog"; //blank blog
		$footer.="<form name='newpost' action='NewPost.php' method='post'>
					  <input type='hidden' name='blog_ID' value='$userblog'></input>
					  <input type='submit' value='New Post'></input>
					  </form>
					 ";
	}
  
}else{ //not logged in, not looking at a bookmarked page

	header("Location: index.php");
}

?>
<html>
<head>
<!--css and java links-->
</head>
<body>
<div>
<?php echo $header ?>
<?php echo $page ?>
<?php echo $footer ?>
</div>
</body>
</html>
