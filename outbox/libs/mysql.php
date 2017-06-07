<?php
//Default Timezone
//date_default_timezone_set("Pacific Time (US & Canada)");
date_default_timezone_set("Asia/Calcutta");
include(dirname(__FILE__).'/../includes/class.Main.php');
//Object initialization
$dbf = new User();

function checkfornewmsg($cuser,$lmid,$fload=0){
	$data=array();
	if(!isset($_SESSION)){session_start();}
		
	$sql="Select * from mychat_messages where `to_user`='".$cuser."' and `unread`=1 order by `from_user`";
	if($result=mysql_query($sql))
	{
		if($result=mysql_query($sql))
		{
			$data['count']=mysql_num_rows($result);
			$data['messages']=array();
			$i=0;
			while($row=mysql_fetch_assoc($result)){
				if(!isset($_SESSION['live_chat']['sfmsg']))
					$_SESSION['live_chat']['sfmsg']=$row['id'];
				
				$dateofmsg=date("Y-m-d",$row['datetime']);
				$datetoday=date("Y-m-d",time());
				$timeofmsg="";
				
				if($dateofmsg==$datetoday)
					$timeofmsg=date("h:i a",$row['datetime']);
				else
					$timeofmsg=date("d-m h:i a",$row['datetime']);
			
				$row['datetime']=$timeofmsg;
				$data['messages'][$i++]=$row;
				
				mark_readed($row['id']);
				$data['lmid']=$row['id'];
			}
			return $data;
		}
		else
			return mysql_error().($sql);
	}
}

function getMessages($cuser,$lmid,$fload=0){
	$data=array();
	if(!isset($_SESSION)){session_start();}
		//$sfmsg=$_SESSION['live_chat']['sfmsg'];
		
	if($fload=1){
		$sql="Select * from mychat_messages where `to_user`='".$cuser."' and `unread`=1 order by `from_user`";	
	}else{	
		$sql="Select * from mychat_messages where `to_user`='".$cuser."' and (`unread`=1 or `datetime`>=".(time()-30).") order by `id`";
	}
	if($result=mysql_query($sql)){
		$data['count']= mysql_num_rows($result);
		$data['messages']=array();
		$i=0;
		while($row=mysql_fetch_assoc($result)){
			if(!isset($_SESSION['live_chat']['sfmsg']))
				$_SESSION['live_chat']['sfmsg']=$row['id'];
				//$timestmtoday=date("Y-m-d");
				$dateofmsg=date("Y-m-d",$row['datetime']);
				$datetoday=date("Y-m-d",time());
				
			$timeofmsg="";
			if($dateofmsg==$datetoday)
				$timeofmsg=date("h:i a",$row['datetime']);
			else
				$timeofmsg=date("d-m h:i a",$row['datetime']);
				
			//print_r($row);
			$row['datetime']=$timeofmsg;
			$data['messages'][$i++]=$row;
			//print_r($row);
			//only mark it as read if the other end user already read it
			if($row['from_user']!=$cuser)
				mark_readed($row['id']);
				
			$data['lmid']=$row['id'];
		}
		return $data;
	}
	else
		return mysql_error().($sql);
}

function getMessages_byFriend($cuser,$friend,$firstmsgid,$isHistoric=1){
	if($isHistoric==1){
		if($firstmsgid==0)
			$sql="Select * from mychat_messages where (`to_user`='".$cuser."' and `from_user`='".$friend."')  or (`to_user`='".$friend."' and `from_user`='".$cuser."') order by `datetime` desc limit 10";
		else
			$sql="Select * from mychat_messages where ((`to_user`='".$cuser."' and `from_user`='".$friend."')  or (`to_user`='".$friend."' and `from_user`='".$cuser."')) and  `id` < ".$firstmsgid." order by `datetime` desc limit 10";
	}else{
		$sql="Select * from mychat_messages where ((`to_user`='".$cuser."' and `from_user`='".$friend."')  or (`to_user`='".$friend."' and `from_user`='".$cuser."')) and  `id` > ".$firstmsgid." order by `datetime`";
	}
	//return $sql;
	if($result=mysql_query($sql)){
		$data=array();
		$data['count']=mysql_num_rows($result);
		$data['messages']=array();
		$i=0;
		while($row=mysql_fetch_assoc($result)){
			$dateofmsg=date("Y-m-d",$row['datetime']);
			$datetoday=date("Y-m-d",time());
				
			$timeofmsg="";
			if($dateofmsg==$datetoday)
				$timeofmsg=date("h:i a",$row['datetime']);
			else
				$timeofmsg=date("d-m h:i a",$row['datetime']);
				
			//print_r($row);
			$row['datetime']=$timeofmsg;
			$data['messages'][$i++]=$row;
		//	mark_readed($row['id']);
		}
		return $data;
	}else
		return mysql_error().($sql);
}

function mark_readed($msgID){
	$sql="UPDATE mychat_messages SET unread=0 where `id`=".$msgID;
	if($result=mysql_query($sql)){
		return 1;
	}else
		return mysql_error().($sql);
}

function postMessage($cuser,$to,$message){
	
	$sql="INSERT INTO mychat_messages (datetime,from_user,to_user,messagebody,unread) VALUES ('".time()."','".$cuser."','".$to."','".mysql_escape_string($message)."',1)";
	if($result=mysql_query($sql))
		return 1;
	else
		return mysql_error().($sql);
}

function updateLivestat($cuser,$myAvailability){
	if(!isset($_SESSION))session_start();
	$sql="REPLACE INTO mychat_online_users(username,user_type,status,lastlogin) VALUES ('$cuser','$_SESSION[usertype]','$myAvailability','".date("Y-m-d H:i:s",time())."')"; 
	if($result=mysql_query($sql))
		return 1;
	else
		return mysql_error().("(".$sql.")");
}

function updatewriting($cuser,$to_user){
	$sql="REPLACE INTO writtingstatus (msgFrom ,msgTo ,time) VALUES ('$cuser', '$to_user',	".time().")";
	//echo $sql;
	if($result=mysql_query($sql))
		return 1;
	else
		return mysql_error().("(".$sql.")");
}

function getavailablefriends($cuser){
	
	$sql="select `mychat_online_users`.`username` from `friendlist`,`mychat_online_users` where `mychat_online_users`.`username`=`friendlist`.`user_name` 
			and `mychat_online_users`.`lastlogin` between '".date("Y-m-d H:i:s",time()-10)."' and '".date("Y-m-d H:i:s",time())."'";
	
	$user_arr=array();
	$retval=array();
	$i=0;
	if($result=mysql_query($sql))	{
		while($row=mysql_fetch_assoc($result)){
			array_push($user_arr,$row['username']);
			$i++;
		}		
		$retval['count']=$i;
		$retval['users']=$user_arr;		
		return json_encode($retval);
	}
	else
		return false;
}

function getavailabilitybyname($user){
	$sql="select `tcf_users`.`tcf_username` from `tcf_users` where `tcf_username`='$user' and `tcf_users`.`tcf_logindate` between '".date("Y-m-d H:i:s",time()-10)."' and '".date("Y-m-d H:i:s",time())."'";	
	if($result=mysql_query($sql))	{
		if(mysql_num_rows($result)>=1)
			return true;
		else
			return false;
	}
	else
		return false;
}

function getwritingstatus($cuser){
	$sql="select msgFrom from writtingstatus where msgTo ='$cuser' and time between '".(time()-3)."' and '".time()."'";
	$user_arr=array();
	$retval=array();
	$i=0;
	if($result=mysql_query($sql)){
		while($row=mysql_fetch_assoc($result)){
			array_push($user_arr,$row['msgFrom']);
			$i++;
		}		
		$retval['count']=$i;
		$retval['users']=$user_arr;
		
		return json_encode($retval);
	}
	else
		return false;
}
function emailnotification($username,$msg,$msgto){
	$email=file_get_contents(dirname(__FILE__)."/"."email_template.php");
	$time_of_msg=date("Y-m-d h:i:s a",time());
	$email=str_replace('MYCHAT_USERNAME',$username,$email);
	$email=str_replace('MYCHAT_TIME_OF_MESSAGE',$time_of_msg,$email);
	$email=str_replace('MYCHAT_MESSAGE_BODY',$msg,$email);	
	global $emailsender;
	global $websitename;
	
	$sql="select `tcf_email` from `tcf_users` where `tcf_username`='$msgto'";
	
	if($result=mysql_query($sql)){
		if($row=mysql_fetch_assoc($result)){
			$emailId=$row['tcf_email'];
//			$emailId="rajibdeb.slg@gmail.com";
			$subject=$websitename.": You have unread messages from $username";
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

			// Additional headers
			$headers .= 'From: '.$emailsender. "\r\n";

			return "Email:(".mail($emailId, $subject ,$email,$headers).")";
		}
	}
	return $email;
}
?>