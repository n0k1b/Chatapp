<?php
 require 'libs/bdapps_cass_sdk.php';

 define('USER', 'hotelhil_arif');
	define('PASSWORD', 'a13411896');
	define('DB','hotelhil_robi');
	define('SERVER', '174.142.32.205');
     define('APP_ID', 'APP_003812');
	define('APP_PASSWORD', '46af29949ccd34bf08d7ee2366a157cd');



$servername="174.142.32.205";
$username="hotelhil_arif";
$password="a13411896";
$db="hotelhil_robi";
$conn=mysqli_connect($servername,$username,$password,$db);
$sql="SELECT * FROM msg_count";
$res=mysqli_query($conn,$sql);
while($row=mysqli_fetch_array($res))
{
	$id=$row["id"];
   $sql="UPDATE msg_count SET count=0 where id=$id";
   mysqli_query($conn,$sql);

}

 $this->sub = new Subscription("https://developer.bdapps.com/subscription/send",APP_ID,APP_PASSWORD);
 $t=$this->sub->getStatus("tel:B%3C4vVZP+SXIKrf6NQIA1zV/jrUjNPe18RKGXRwv7PMmeF0/xKn63nAt0ooW59wOuK7rxRdWpUMv6mbxnYrup1NRSA==");

 file_put_contents('ttt.txt',$t);





?>