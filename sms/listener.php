
<?php

ini_set('error_log', 'sms-app-error.log');
require 'libs/bdapps_cass_sdk.php';
require 'libs/db.php';


$logger = new Logger();

try{

	// Creating a receiver and intialze it with the incomming data
	$receiver = new SMSReceiver(file_get_contents('php://input'));
	
	//Creating a sender
	
	$message = $receiver->getMessage(); // Get the message sent to the app
	$address = $receiver->getAddress();	// Get the phone no from which the message was sent 
      $a = explode(" ", $message);
      $b=" ";
		for($i=1;$i<sizeof($a);$i++)
		{
            $b=$b.' '.$a[$i];
		}
	$message =$b;
    $message = trim($message);
	$db = new DB();
	$db->smsQuery($message,$address);
	//$logger->WriteLog($receiver->getAddress());

		// 	Send a SMS to a particular user
		//	$response=$sender->sms('This message is sent only to one user', $address);

}catch(SMSServiceException $e){
	$logger->WriteLog($e->getErrorCode().' '.$e->getErrorMessage());
}

?>

