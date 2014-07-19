<?php 


if(!isset($_SESSION)){
	session_start();
}
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


//IF the user goes to a bookmarked page or uses a link from the login or index,
//the link will have BlogPage?blog_ID=###
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
   if(empty($_SESSION['username'])){   //if person looking isnt logged in
			$query="SELECT * FROM tbl_entries WHERE blog_ID=$blognum ORDER BY data ASC";
			$result=mysqli_query($con,$query);
			if($result){
				while($row=mysqli_fetch_array($result)){
					$c=$row['title'];
					$b=$row['content'];
				    $a=$row['data'];
			     	$page.="<div>
			     			<h2>$c</h2>
							<div>$b</div></br>
							<div>$c</div><div> log in to view comments </div>
							</div>";
				}
				//$footer=link to log in page
		   }else{
			   //header back to index, they are trying to look at a blank blog
			 }
    }else{ //person is logged in, check the username
		$username=$_SESSION['username'];
		$query="SELECT blog_ID FROM tbl_blogs WHERE username=$username";
		$result=mysqli_query($con,$query);
    	$row=mysqli_fetch_array($result);
		$userblog=$row['blog_ID'];
		$blognum=$_GET['blog_ID'];
		if($blognum===$userblog){        //if this is the users blog
			$query="SELECT * FROM tbl_entries WHERE blog_ID=$blognum ORDER BY data ASC";
			$result=mysqli_query($con,$query);
			if($result){
				while($row=mysqli_fetch_array($result)){
					$c=$row['title'];
					$b=$row['content'];
					$a=$row['data'];
					$page.="<div>
						<h2>$c</h2>
						<div>$b</div></br>
						<div>$c</div><div> comments section </div>
						</div>";
				}
				$footer.='<form name="newpost" action="NewPost.php" method="post">
					  <input type="hidden" name="blog_ID" value="$userblog"></input>
					  <input type="submit" value="New Post"></input>
					  </form>
					 ';
			}else{  
	          $page.="There is no content on this blog"; //blank blog
	          $footer.='<form name="newpost" action="NewPost.php" method="post">
					  <input type="hidden" name="blog_ID" value="$userblog"></input>
					  <input type="submit" value="New Post"></input>
					  </form>
					 ';
			}
		}else{       //viewing someone elses blog, don't create the New Post form in the footer
			$query="SELECT * FROM tbl_entries WHERE blog_ID=$blognum ORDER BY data ASC";
			$result=mysqli_query($con,$query);
			if($result){
				while($row=mysqli_fetch_array($result)){
					$c=$row['title'];
					$b=$row['content'];
					$a=$row['data'];
					$page.="<div>
						<h2>$c</h2>
						<div>$b</div></br>
						<div>$c</div><div> comments section </div>
						</div>";
				}
				//$footer=log out link and link to home blog
			}else{
				$page.="There is no content on this blog"; //blank blog
			}
		}

				mysqli_close($con);
	}
	 //if the link goes straight to BlogPage.php without the ?blog_ID=###
	 //and the user is logged in, redirect them to their page.
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
	$redirect="Location: BlogPage.php?blog_ID=".$userblog;
	header($redirect);
}else{ //not logged in, not looking at a bookmarked page, redirect to index

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
