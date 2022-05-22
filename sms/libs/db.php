<?php


	/*define('SERVER_URL', 'https://developer.bdapps.com/sms/send');	
	define('APP_ID', 'APP_003812');
	define('APP_PASSWORD', '46af29949ccd34bf08d7ee2366a157cd');
    */
		
	define('SERVER_URL', 'http://localhost:7000/sms/send');	
	define('APP_ID', 'APP_000001');
	define('APP_PASSWORD', 'password');
	
	
class DB{
	
	var $msg;
	var $conn;
	var $address;
	var $sender;
	var $sender2;
	
	function connect(){
		//$this->conn = mysqli_connect("216.246.90.16","hotelhil_arif","a13411896","hotelhil_robi");
		
		$this->conn = mysqli_connect("localhost","root","","chatapp");

		$this->sender = new SMSSender( SERVER_URL, APP_ID, APP_PASSWORD);
		//$this->sender2 = new SMSSender( SERVER_URL, APP_ID, APP_PASSWORD);
		// file_put_contents("msg.txt","connected...");
	}
	
	
	
	function smsQuery($sms,$adrs){
		$this->connect();
		/*list($a) = explode(" ", $sms);
		$this->msg = trim($sms,$a);
		$this->msg = trim($this->msg);
		*/

		$a=explode(" ",$sms);
		$b=" ";
		for($i=1;$i<sizeof($a);$i++)
		{
            $b=$b.' '.$a[$i];
		}
		$this->msg=$b;
        file_put_contents('username5.txt',$sms);
		$this->msg=trim($this->msg);

		file_put_contents("trim.txt",$this->msg);
	


		$this->address = $adrs;
		
		// file_put_contents("text.txt",$this->msg);
		
		switch($a[0]){
			case "set":
			case "Set":
			case "SET":
						$this->setQuery();
						break;
			case "start":
			case "START":
			case "Start":
			
						$this->start();
						break;	

			case "unblock":
			case "Unblock":
			case "UNBLOCK":
			            
			            $this->unblock();	
			            break;		

			case "block":
			case "Block":
			case "BLOCK":
			             $this->block();
					    	break;						
						
			case "create":
			case "Create":
			case "CREATE":
						$this->createGroup();
						break;
						
			
						
			case "buy":
			case "Buy":
			case "BUY":
						break;
						
			case "add":
			case "Add":
			case "ADD":
			           $this->add_group();
						break;
						
			case "delete":
			case "Delete":
			case "DELETE":
                        $this->delete_group();
						break;

			case "leave":
			case "Leave":
			case "LEAVE":
			            $this->leave_group();
			            break;
			case "remove":
			case "REMOVE":
			case "Remove":
			            $this->remove_member_from_group();
			            break;  

			case "g":
			case "G":
				        $this->group_msg();
			         break;                      			

						
			case "help":
			            $this->help_msg();
						 break;

			case "me":
			case "Me":
			case "ME":
			       $this->my_name();
			       break;	
			 case  "limit":
			 case "Limit":
			 case "LIMIT":
			       $this->my_limit();
			       break;     		 

			default:
			    $this->byDefault();
				$this->sendMessageQuery($a[0]);
				//$this->sender->sms($this->msg,$this->address);

			
		}	
	}

  function my_limit()

  {
  	$a=$this->address;
  	$sql="SELECT * from msg_count where mask='$a'";
   	  $res=mysqli_query($this->conn,$sql);
   	  $row=mysqli_fetch_array($res);
   	  $count=$row['count'];
   	  $remain=50-$row['count'];
   	  $this->msg="Your remamining message ".' '.$remain;
   	  $this->sender->sms($this->msg,$this->address);
  }

	function my_name()
	{

		$a=$this->findUserName();
		$this->msg="Your username is ".' '.$a;
		$this->sender->sms($this->msg,$this->address);
		

	}

function help_msg()
{

	$msg2="To set username:Hey<space>set<space>abc.\n
	       To send msg:hey<space>username<space>msg.\n
	       To add group:hey<space>add<space>username<space>groupname.\n
	       To send group msg:hey<space>g<space>groupname<space>msg.\n
	       To block:hey<space>block<space>username\n
	       To unblcok:hey<space>unblock<space>username.
	        ";
	 $this->sender->sms($msg2,$this->address);
}

   function unblock()
   {
      
        $name= explode(" ", $this->msg);
   	    $userName=$name[0];
        $var=$this->findUserName();
       
		  $check="SELECT * FROM block_list WHERE source='$var' and destination='$userName'" ;
		  $check1=mysqli_query($this->conn,$check);
		  $check1= mysqli_fetch_array($check1);


		  	if($check1["status"]==1){
		
		$sql1="UPDATE block_list SET status=0 where source='$var' and destination='$userName'";
		mysqli_query($this->conn,$sql1);
		 $this->msg="Successfully unblocked ".' '.$userName;
	     }



	     else
	     {

	     			 $this->msg="You did not blocked ".' '.$userName;
	     }

	
    $this->sender->sms($this->msg,$this->address);

   }


   function block()
   {      
   	    $name= explode(" ", $this->msg);
   	    $userName=$name[0];

        $var=$this->findUserName();

		  $check="SELECT * FROM block_list WHERE source='$var' and destination='$userName'" ;
		  $check1=mysqli_query($this->conn,$check);
		  $check1= mysqli_fetch_array($check1);

		

		   if($check1["id"]==NULL){

		  	

		  $sql1="INSERT INTO block_list (source,destination,status) VALUES ('$var','$userName',1)";
		   mysqli_query($this->conn,$sql1);
		    $this->msg="Successfully blocked ".' '.$userName;
	     }




	     else
	     {
	     	if($check1["status"]==0)

	     	{


               $sql1="UPDATE block_list SET status=1 where source='$var' and destination='$userName'";
		       mysqli_query($this->conn,$sql1);
		         $this->msg="Successfully blocked ".' '.$userName;
	     	 	
	     	}
	     	else
	     	    {

	     			 $this->msg="You already blocked ".' '.$userName;
	     		}
	     		
	     		
	     		
	     }


		/*if($result['username']!= null){			
			$this->msg = 'Dear '.$result['username'].' You are already registered';
			return true;
		}
		else 
			return false;
			*/


     
     
 
    $this->sender->sms($this->msg,$this->address);
    
   
   }





    function start(){
       
 
   if($this->isAlreadyRegistered()==false){
           
   $this->msg="Congratulation! To set username type hey<>set<>username  ";


   $this->byDefault();
  
	$this->sender->sms($this->msg,$this->address);
	
}
else{

   $this->msg="You are already registered";
   $this->sender->sms($this->msg,$this->address);

}
    


    }

function byDefault()
{
	  $sql = "INSERT INTO user (mask,timestamp) VALUES ('$this->address', CURRENT_TIMESTAMP);";
      $test=mysqli_query($this->conn,$sql);
     
      $this->start_count();
     


}


function isBlocked($source)


{


          
         
		  $var2=$this->findUserName();


		  $check="SELECT status FROM block_list WHERE source='$source' and destination='$var2'";
		  $check1=mysqli_query($this->conn,$check);
		  $check1= mysqli_fetch_array($check1);

		  $status=$check1['status'];
		 
		  if($status==0)
		  {
		  	return false;
		  }
		  else
		  	return true;

		  

         
}








	
	function setQuery(){
		

          $this->byDefault();

    //list($userName) = explode(" ", $this->msg);
		$a=explode(" ",$this->msg);
		$userName=$a[0];
		

		
		//Is already registered
		if($this->isAlreadyRegistered()=== true){
				$this->sender->sms($this->msg,$this->address);
		}
		else{
			if($this->isUserNameAvailable($userName)=== true ){

                $sql ="UPDATE user Set username='$userName' where mask='$this->address'";
				
				//$sql = "INSERT INTO user (username)  VALUES ('$userName');";
				if(mysqli_query($this->conn,$sql)){
					$this->msg = "Congratulation!!!".$userName.".
					Your account has been created.
					To send MSG 
					hey<>username<>msg";
					
					//$sql ="UPDATE user Set username='$userName' where mask='$this->address'";
					//mysqli_query($this->conn,$sql);
				}
				
				$this->sender->sms($this->msg,$this->address);
			}
			else {
					$this->sender->sms($this->msg,$this->address);
				}
		}




	}



	
	function createGroup(){


		$a=explode(" ",$this->msg);
		$group_name=$a[0];
  
       $username=$this->findUserName();  
       
       
     
        if($this->isGroupAvailable($group_name)==false){
		$sql = "INSERT INTO c_group(group_name,group_admin) VALUES ('$group_name','$username') ";

		if(mysqli_query($this->conn,$sql)){
			$this->msg = "Congratulation!!!.
			Your group ".$group_name." has been created.
			To add Member 
			hey<>add<>username<>msg";	
			$sql = "INSERT INTO c_group_chat(group_name,group_member_name) VALUES ('$group_name','$username') ";
			mysqli_query($this->conn,$sql);
		}
	}
	else
		$this->msg="This group name is not available.Try another one";

		$this->sender->sms($this->msg,$this->address);
	}


	function leave_group()
       
	{
		$a=explode(" ",$this->msg);
		 
		   $group_name=$a[0];

       $username=$this->findUserName();
       if($this->isMemberofGroup($group_name,$username)==true)
       {
       	 $sql="DELETE FROM c_group_chat WHERE group_member_name='$username'";
       	 mysqli_query($this->conn,$sql);
       	 $this->msg="Succesfully leave from group";
       }
       else
       {
         $this->msg="You are not member of this group";

       }
        $this->sender->sms($this->msg,$this->address);
         



	}


	
   function remove_member_from_group()


   {
           $a=explode(" ",$this->msg);
		   $group_member_name=$a[0];
		   $group_name=$a[1];
		   
		   file_put_contents('group_name.txt',$group_name);
		   file_put_contents('group_member_name.txt',$group_member_name);

		   $username=$this->findUserName(); 
           $group_admin=$this->findAdminName($group_name);
           if($username==$group_admin)
           {    
           	  if($this->isMemberofGroup($group_name,$group_member_name)==true){

               $sql="DELETE from c_group_chat WHERE group_member_name='$group_member_name'";
               mysqli_query($this->conn,$sql);
               $this->msg="Succesfully remove nokib from group";


           }
           else{

           	$this->msg="User is not member of this group";
           }

       }
           else
           {
           	$this->msg="You are not admin of this group";
           }

        $this->sender->sms($this->msg,$this->address);


   }





	function delete_group()
	{    
		
		
           $a=explode(" ",$this->msg);
		 
		   $group_name=$a[0];

		   $username=$this->findUserName(); 
           $group_admin=$this->findAdminName($group_name);

          


         
		   
          if($this->isGroupAvailable($group_name)==true){

          	if($username==$group_admin)
          	{
         
          $sql ="DELETE FROM c_group WHERE group_name ='$group_name'";
		  $result = mysqli_query($this->conn,$sql);
		  if($result)
		  {
		  	$this->msg="Succesfully deleted your group";
		  }
       }
      else
      {
      	$this->msg="You are not admin of this group";
      }



 }
     else{

     	$this->msg="Invalid Group name";
     }

         $this->sender->sms($this->msg,$this->address);
         


	}



   function add_group()
         {
           
           $a=explode(" ",$this->msg);
		   $group_member_name=$a[0];
		   $group_name=$a[1];

		   $sql2="SELECT * FROM user WHERE username='$group_member_name'";
		   $res2=mysqli_query($this->conn,$sql2);
		   $row=mysqli_fetch_array($res2);
		   $number=$row["mask"];
		  $msg2="You are added to".' '.$group_name;

		     file_put_contents('aaa.txt',$msg2);
           file_put_contents('bbb.txt',$number);



		   $username=$this->findUserName();
          $admin_name=$this->findAdminName($group_name);

        
        if($this->isGroupAvailable($group_name)==true){

        	if($username==$admin_name)
        	{ 
        		if($this->isMemberofGroup($group_name,$group_member_name)==false)
        		{
        			if($this->isAvailable($group_member_name)==true){

        				if($this->isBlocked($group_member_name)==0){

        		
          $sql = "INSERT INTO c_group_chat(group_name,group_member_name) VALUES ('$group_name','$group_member_name') ";
		if(mysqli_query($this->conn,$sql)){
			$this->msg ="Successfully added".' '.$group_member_name.' '. "to".' '.$group_name;
			$msg2="You are added to".' '.$group_name;
			 
		

         }
     }
       else
       	  {
          
            $this->msg="You are blocked by".' '.$group_member_name;
       	  }



     }
              else{

              	$this->msg="Sorry Incorrect userName";
              }


     }

       else
       {
       	$this->msg=$group_member_name.' '."is already memeber of this group";
       }
       
      

     }
     else{

     	$this->msg="You are not admin of this group";
     }

     }
     else{

     	$this->msg="There have no group in this name. Please correct the group name";
     }

       $x=$this->sender->sms($msg2,$number);

     $y= $this->sender->sms($this->msg,$this->address);

     
     

     }
     
     function isMemberofGroup($group_name,$group_member_name)
     {
        
           $sql = "SELECT * FROM c_group_chat WHERE group_name ='$group_name' and group_member_name='$group_member_name'";
		    $result = mysqli_query($this->conn,$sql);
		   $result = mysqli_fetch_array($result);
            $check_group_member_name=$result["group_member_name"];
            if($check_group_member_name==NULL)
            	return false;
            else
            	return true;

     }



     function findUserName()

     {

          $sql = "SELECT username FROM user WHERE mask = '$this->address'";
		  $result = mysqli_query($this->conn,$sql);
		  $result = mysqli_fetch_array($result); 
          $var=$result['username'];
          return $var; 

     }


     function findAdminName($name)  //Parameter Group Name

     {


           $sql = "SELECT * FROM c_group WHERE group_name ='$name'";
		  $result = mysqli_query($this->conn,$sql);
		  $result = mysqli_fetch_array($result); 
          $check_admin=$result["group_admin"];
           return $check_admin;


    


     }


 
      function isGroupAvailable($name)
      {

         
           $sql = "SELECT * FROM c_group WHERE group_name ='$name'";
		  $result = mysqli_query($this->conn,$sql);
		  $result = mysqli_fetch_array($result); 
          $check_group_name=$result['group_name'];
          if($check_group_name!=NULL)
          	return true;
          else
          	return false;


      }



     function isAvailable($name)

     {
        


		$sql = "SELECT mask  FROM user WHERE username = '$name'";

		$result = mysqli_query($this->conn,$sql);
		$result = mysqli_fetch_array($result);
		if($result['mask']!= null){
			$sql = "SELECT username  FROM user WHERE mask = '$this->address';";
			$res = mysqli_query($this->conn,$sql);
			$res = mysqli_fetch_array($res);
			if($res['username']!=NULL)
				return true;
			else
				return false;
		}



     }
     


	function isAlreadyRegistered(){
		

			$sql = "SELECT username FROM user WHERE mask = '$this->address'";
		  $result = mysqli_query($this->conn,$sql);
		  $result = mysqli_fetch_array($result);
		// file_put_contents('file.txt',$sql);
		if($result['username']!= null){			
			$this->msg = 'Dear '.$result['username'].' You are already registered';
			return true;
		}
		else 
			return false;
			
	}
	
	function isUserNameAvailable($userName){
		/*$sql = "SELECT user.username as uname FROM user WHERE user.username = '$userName'";
		// file_put_contents("msg1.txt",$sql);
		$result = mysqli_query($this->conn,$sql);
		$result = mysqli_fetch_array($result);
		if($result['uname']!= null){
			$this->msg = "Sorry!!
			this User name is not available
			try another name";
			return false;
		}
		else {
			return true;
		}
		*/


         $sql = "SELECT username FROM user WHERE username = '$userName'";
		// file_put_contents("msg1.txt",$sql);
		$result = mysqli_query($this->conn,$sql);
		$result = mysqli_fetch_array($result);
		if($result['username']!= null){
			$this->msg = "Sorry!!
			this User name is not available
			try another name";
			return false;
		}
		else {
			return true;
		}
		


	}
	function send_msg($b,$a)
	{

		$this->sender->sms($b,$a);
	}

	function group_msg()
	{
           
           $a=explode(" ",$this->msg);
		   $group_name=$a[0];
		  
           $d=$this->address;

		   $b=" ";
		for($i=1;$i<sizeof($a);$i++)
		{
            $b=$b.' '.$a[$i];
		}
		$this->msg=$b;
		$this->msg=trim($this->msg);
		$this->msg=$group_name.':  '.$this->msg;

          $username=$this->findUserName();

          if($this->isGroupAvailable($group_name)){

          	if($this->msg_alert($d)==true){


          
 
          if($this->isMemberofGroup($group_name,$username)==true || $this->findAdminName($group_name)==$username)
          {    




            $sql="SELECT * FROM c_group_chat WHERE group_name='$group_name'";
            $result=mysqli_query($this->conn,$sql);
            
            while($row=mysqli_fetch_array($result)){
            	   
            $name=$row["group_member_name"];

            if($name!=$username){

            $sql1="SELECT mask FROM user where username='$name'";
            $res=mysqli_query($this->conn,$sql1);
            $res=mysqli_fetch_array($res);
            $c=$c." ".$res["mask"];

            //$c=$res["mask"];
            
        }
           

            }
            $this->msg_count($d);
            $c=trim($c);
            $c=explode(" ",$c);
           
             file_put_contents('c.txt',$c);
            

          }


          else{
             $this->msg="You are not member of this group";
              $this->sender->sms($this->msg,$this->address);

          }
      }

      else{

  		$this->msg="Sorry your daily limit reached";
      	  $this->sender->sms($this->msg,$this->address);
  }

  }
  

      else{

      	$this->msg="Invalid Group name";
      	  $this->sender->sms($this->msg,$this->address);
      }
             
       $this->sender->sms($this->msg,$c);






	}

    function start_count()
    {
    	$sql="INSERT INTO msg_count(mask) VALUES ('$this->address')";
    	mysqli_query($this->conn,$sql);
    }

   function msg_count($a)
   {  
   	  

   	  $sql="SELECT * from msg_count where mask='$a'";
   	  $res=mysqli_query($this->conn,$sql);
   	  $row=mysqli_fetch_array($res);
   	  $count=$row['count'];
   	  //file_put_contents('msg_count.txt',$count);

   	  $u_count=$count+1;
   	  $sql1="UPDATE msg_count Set count=$u_count where mask='$a'";
   	  mysqli_query($this->conn,$sql1);
   	  
   	  //file_put_contents('msg_count.txt',$a);
   	 
   }
   function msg_alert($a)
   {
      
   	  $sql="SELECT * from msg_count where mask='$a'";
   	  $res=mysqli_query($this->conn,$sql);
   	  $row=mysqli_fetch_array($res);
   	  $count=$row['count'];

   	  file_put_contents('msg_count.txt',$count);
   	  
   	  if($count>=50)
   	  {
   	  	return false;

   	  }
   	  else{
   	  	return true;
   	  }
   }
	
	
	function sendMessageQuery($userName){
         
		  $var=$this->findUserName();
		  $sql1="SELECT * from user where username='$var'";
		  $res1=mysqli_query($this->conn,$sql1);
		  $row1=mysqli_fetch_array($res1);
		  $sub=$row1['isUnsub'];


		  file_put_contents('var.txt', $row1["isUnsub"]);

		  /*$check="SELECT status FROM block_list WHERE source='$userName' and destination='$var'";
		  $check1=mysqli_query($this->conn,$check);
		  $check1= mysqli_fetch_array($check1);
		  */
           $b=$this->address;

           if($sub==1){

		  if($this->isBlocked($userName)==false){

        
          //file_put_contents('status.txt', $check1["status"]);
         

		$sql = "SELECT mask  FROM user WHERE username = '$userName'";

		$result = mysqli_query($this->conn,$sql);
		$result = mysqli_fetch_array($result);
		if($result['mask']!= null){
			$sql = "SELECT username  FROM user WHERE mask = '$this->address';";
			$res = mysqli_query($this->conn,$sql);
			$res = mysqli_fetch_array($res);

			if($res['username']!= null ){


				if($this->msg_alert($b)==true){
				$this->msg = $res['username'].':'.$this->msg;
				$this->address = $result['mask'];
				$this->msg_count($b);
			}
			else{
				$this->msg="Sorry,Your daily limit reached";
			}
			}
			else{
				$this->msg = "Please dial USSD *213*439# to send message";
			}
		}
		else {
			$this->msg = "Sorry!!!
					write the correct user name";
		}
	}
	else{

		$this->msg = " Sorry you are blocked by".' '.$userName ;
	}
}
else{

		$this->msg = "Sorry you are not subscribed " ;
	}

	
	$this->sender->sms($this->msg,$this->address);

}
	
}

?>