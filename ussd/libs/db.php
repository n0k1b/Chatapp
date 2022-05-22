
<?php

	define('USER', 'hotelhil_arif');
	define('PASSWORD', 'a13411896');
	define('DB','hotelhil_robi');
	define('SERVER', '174.142.32.205');
     define('APP_ID', 'APP_003812');
	define('APP_PASSWORD', '46af29949ccd34bf08d7ee2366a157cd');
 
 class DB {
	 
	 var $msg = 'set a username: 
	hey <>set <> username
	to messege a friend:
	hey <> username <> message
	send to 21213';
	var $subs;
	 

	 function connectdb(){
		$conn = mysqli_connect(SERVER,USER,PASSWORD,DB);
		
		return $conn;
	}

	function ussdQuery($address){
         
        $conn = self::connectdb();
		
		$sql="SELECT * from user where  mask='$address'";
		$res=mysqli_query($conn,$sql);
		$row=mysqli_num_rows($res);
		
		file_put_contents('address.txt',$row);
		if($row==0){
		$query ="INSERT INTO user (mask,timestamp) VALUES ('$address', CURRENT_TIMESTAMP)";
		
		    mysqli_query($conn, $query);
		    $this->subs = new Subscription("https://developer.bdapps.com/subscription/send",APP_ID,APP_PASSWORD);
		$this->subs->subscribe($address);

			return $this->msg;
		}
		 else {
		   $msg = "You are already Registered\n thanks";
			return $this->msg;
		}
	}
 }
 
?>


