<?php
$db_hostname = "localhost";
$db_database = "saara";
$db_user="root";
$db_password="";
$name=$_POST['name'];
$comment=$_POST['comment'];
$submit=$_POST['submit'];
$con=mysqli_connect($db_hostname, $db_user, $db_password,$db_database) or die("Cannot connect");


if($submit)
{
      if($name&&$comment)
	     {
		 $sql="INSERT INTO mycomments (name, comment)
		 VALUES ('$name','$comment')";
              mysqli_query($con,$sql);
			 }
			else
			{
				echo "Please fill out all of the fields";
			}
}mysqli_close($con);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtl1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Comment Box</title>

</head>
<body>
<form name="trial" action="comments.php" method="post">
<table>
<tr><td colspan="2">Name: </td><td><input id="name" type="text" name="name"></td></tr>
<tr><td colspan="2">Comment: </td></tr>
<tr><td colspan="6"><textarea id="comment" name="comment" rows="5" col="100"></textarea></td></tr>
<tr><td colspan="2"><input type="submit"  name="submit" value="Submit"></td></tr>
<tr><td colspan="2"><input type="reset" name="reset" value="Reset"></td></tr>
</table>
</form>
<?php
$getquery=mysqli_query("SELECT * FROM mycomments ORDER BY id DESC");
while($rows=mysqli_fetch_assoc(getquery));
{
	$id=$rows['id'];
	$name=$rows['name'];
	$comment=$rows['comment'];
	$delete="<a href=""
	echo $name . '<br>' . '<br>' . $comment . '<br />' . '<br />' . '<hr />'
;}
?>
</body>
</html>
