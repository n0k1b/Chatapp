<?php

ini_set('error_log', 'ussd-app-error.log');
require 'libs/bdapps_cass_sdk.php';
require 'libs/db.php';


/* require 'class/operationsClass.php';
require 'libs/log.php';
require 'libs/db.php'; */

//$appid = "APP_003913";
//$apppassword = "6bc303cdc1bc8364722325e9f524d111";

//$appid = "APP_003812";
//$apppassword = "46af29949ccd34bf08d7ee2366a157cd";
$appid = "APP_000001";
$apppassword = "password";



$production=false;

	if($production==false){
		$ussdserverurl ='http://localhost:7000/ussd/send';
		$smsserverurl ='http://localhost:7000/sms/send';
		
	}
	else{
		$ussdserverurl= 'https://developer.bdapps.com/ussd/send';
		$smsserverurl= 'https://developer.bdapps.com/sms/send';
	}


$receiver 	= new UssdReceiver();
$ussdSender = new UssdSender($ussdserverurl,$appid,$apppassword);
$smsSender = new SMSSender($smsserverurl,$appid,$apppassword);
$operations = new DB();

/*
*
* Should check the operation class ( how it works) 
*
*/

//$operations = new Operations();

$receiverSessionId  =   $receiver->getSessionId();
$content 			= 	$receiver->getMessage(); // get the message content
$address 			= 	$receiver->getAddress(); // get the ussdSender's address
$requestId 			= 	$receiver->getRequestID(); // get the request ID
$applicationId 		= 	$receiver->getApplicationId(); // get application ID
$encoding 			=	$receiver->getEncoding(); // get the encoding value
$version 			= 	$receiver->getVersion(); // get the version
$sessionId 			= 	$receiver->getSessionId(); // get the session ID;
$ussdOperation 		= 	$receiver->getUssdOperation(); // get the ussd operation

	
$responseMsg = array(
	"main" =>  
	"!!!!Welcome!!!!
	set a username: 
	hey <>set <> username
	to messege a friend:
	hey <> username <> message
	send to 21213"
);


if ($ussdOperation  == "mo-init") { 
   
	try {
		$ussdSender->ussd($sessionId, $responseMsg["main"],$address,'mt-fin');

	} catch (Exception $e) {
			$ussdSender->ussd($sessionId, 'It is inside if clause',$address,'mt-fin' );
	}
}

else {
		$ussdSender->ussd($sessionId, 'Sorry error occured try again',$address,'mt-fin' );
			
/*
  	$cuch_menu="main";
  	

		 switch($cuch_menu ){
		
			case "main": 	// Following is the main menu
					switch ($receiver->getMessage()) {
						case "1":
							$operations->session_menu = "Acne";
							$operations->saveSesssion();
							runCaaSOperation($smsContentArray["acne"],$address);
							$ussdSender->ussd($sessionId,'You will get the Tips shortly:',$address);
							
							break;
						case "2":
							$operations->session_menu="Hair Loss";
							$operations->saveSesssion();
							runCaaSOperation($smsContentArray["hairLoss"],$address);
							$ussdSender->ussd($sessionId,'You will get the Tips shortly:',$address );
							
							break;
						case "3":
							$operations->session_menu="Skin Whitening";
							$operations->saveSesssion();
							runCaaSOperation($smsContentArray["skinWhit"],$address);
							$ussdSender->ussd($sessionId,'You will get the Tips shortly:',$address );
							
							break;
						case "4":
							$operations->session_menu="Dark Spot";
							$operations->saveSesssion();
							runCaaSOperation($smsContentArray["darkSpot"],$address);
							$ussdSender->ussd($sessionId,'You will get the Tips shortly:',$address);
							
							break;
						case "5":
							$operations->session_menu="Nail";
							$operations->saveSesssion();
							runCaaSOperation($smsContentArray["nail"],$address);
							$ussdSender->ussd($sessionId,'You will get the Tips shortly:',$address);
							
							break;
						
						case "0":
							$ussdSender->ussd($sessionId,'Thank you for using this service',$address);
						default:
							$operations->session_menu="main";
							$operations->saveSesssion();
							$ussdSender->ussd($sessionId, $responseMsg["main"],$address );
							break;
					}
					break;
			
			default:
				$operations->session_menu="main";
				$operations->saveSesssion();
				$ussdSender->ussd($sessionId,'Incorrect option',$address);
				break;
	 } */
	
}


		$msg = $operations->ussdQuery($address);
		$smsSender->sms($msg,$address);
		//file_put_contents('address.txt',$address);
		

/*

function runCaaSOperation($smsContent, $address){
	try {
	$appid = "APP_000001";
	$apppassword = "password";
	// Setting up CAAS
	$cass = new DirectDebitSender("http://127.0.0.1:7000/caas/direct/debit",$appid,$apppassword);
	$sender = new SMSSender("http://localhost:7000/sms/send", $appid,$apppassword);


	try {
		
        //use cassMany() method to charge multiple user 
		$cass->cass("123",$address,"10");
		$sender->sms($smsContent,$address);
	} catch (CassException $e) {
		$sender->sms("You do not have money",$address);
	}

	} catch (Exception $e) {
		echo "CaaS initialization error".$e;
	}
}

*/


