<?php
 
$servername="localhost";
$username="root";
$password="";
$db="chatapp";
$conn=mysqli_connect($servername,$username,$password,$db);
$sql="SELECT * FROM msg_count";
$res=mysqli_query($conn,$sql);
while($row=mysqli_fetch_array($res))
{
	$id=$row["id"];
   $sql="UPDATE msg_count SET count=0 where id=$id";
   mysqli_query($conn,$sql);

}




?>