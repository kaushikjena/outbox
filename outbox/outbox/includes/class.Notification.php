<?php
error_reporting(0);
include_once "GCMPushMessage.php";
//Involves Any User operations************************************************************
class Notification extends GCMPushMessage{	
	function Notification(){
		//$this->parent($api_key);
	}
	#################################################################################################
	################################## SEND IOS NOTIFICATION ########################################
	#################################################################################################
	function send_ios_notification($deviceToken,$message,$badges){
		//echo "here===ios function calling";exit;
		$badges=(int)$badges;
		$passphrase = 'blet';
		//$passphrase = 'boxware';
		$ctx = stream_context_create();
		//Development Environment
		//stream_context_set_option($ctx, 'ssl', 'local_cert', './pushNotification/ck.pem');
		//Production Environment
		//stream_context_set_option($ctx, 'ssl', 'local_cert', './pushNotification/dv.pem');
		stream_context_set_option($ctx, 'ssl', 'local_cert', './pushnotificationlive/box.pem');
		stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
		try{
			// Open a connection to the APNS server
			//Development Environment
			$fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err,$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
			//Production Environment
			//$fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err,$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
			
			if ($fp){
				// Create the payload body
				$body['aps'] = array('alert' => $message,'sound' => 'default','badge' => $badges);
				// Encode the payload as JSON
				$payload = json_encode($body);
				// Build the binary notification
				$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
				// Send it to the server
				$result = fwrite($fp, $msg, strlen($msg));
				/*if (!$result)
				echo 'Message not delivered' . PHP_EOL;
				else
				echo 'Message successfully delivered' . PHP_EOL;*/
			}
			/*if (!$fp)
			exit("Failed to connect: $err $errstr" . PHP_EOL);
			echo 'Connected to APNS' . PHP_EOL;*/
			// Close the connection to the server
			fclose($fp);
		}catch(Exception $e){
			//echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
		return true;
	}
	#################################################################################################
	################################## SEND ANDROID NOTIFICATION ####################################
	#################################################################################################
	function send_android_notification($registration_key,$message){
		//include_once "GCMPushMessage.php";
		if($registration_key!=''){
			//$api_key="AIzaSyALkfhARmZi06HNmAwCPFDWMbInVl-PjtQ";
			$api_key="AIzaSyCtVnoyCW7HhIL2Vi6za7daafxJKDFZmMc";	//$registration_key="APA91bFtacxnUdle0EI-BJetPTCFtLMe7iFTcAgH3ejopBZ4QT2JhPgLd_I16l6dOQ0xwjjOzFy6GDf0Mnp_71Nba5IJ6QtGisdBZi5EdXCxjuGuWnssA_Xruy26jhIne_jDksC9oC_U";
			//$an = new GCMPushMessage($api_key);
			//$this->parent($api_key);
			$this->GCMPushMessage($api_key);
			$this->setDevices(array($registration_key));
			$this->send($message);
			//$an = GCMPushMessage($api_key);
			//$an->setDevices(array($registration_key));
			//$response = $an->send($message);
		}
		return true;
	}
}
?>