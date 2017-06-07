<?php
//Default Timezone
date_default_timezone_set("Asia/Calcutta");
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();
echo date("d-m-YY H:i:s").'<br>';
echo date('d-m-YY H:i:s','1416574242').'<br/>';
$ctime =time()-360;
echo $sql="Select * from mychat_messages where `to_user`='Administrator123A' and (`unread`=1 or `datetime`>=".$ctime.") order by `id`";exit;
if($result=mysql_query($sql)){
		echo $data['count']= mysql_num_rows($result);exit;
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
		print_r($data);
	}
?>