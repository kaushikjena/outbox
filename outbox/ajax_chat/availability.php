<?php
//require_once(dirname(dirname(__FILE__))."/libs/mysql.php");
require_once(dirname(__FILE__)."/../libs/mysql.php");
// 0: Not available, 1: Private, 2: Public, 3: Custom
if(!isset($_SESSION)){session_start();}
$cuser=$_SESSION['live_chat']['cuser'];
//$_SESSION['live_chat']['myAvailability']=1;
$myAvailability=$_SESSION['live_chat']['myAvailability'];
$friendavllist=array();

updateLivestat($cuser,$myAvailability);

if($myAvailability==3){
	//Customized
	$sql="select * from mychat_customized_availability where `username`='$cuser'";
	$sql="
			SELECT c.username,c.favorite_username
			FROM `favorites` a, `favorites` b, `mychat_customized_availability` c
			WHERE a.username = '$cuser'
			AND a.username = b.favorite_username
			AND b.username = a.favorite_username
			AND a.username = c.username
			AND a.favorite_username = c.favorite_username";
		
		$friendlist=array();
		$result=mysql_query($sql);
		while($row=mysql_fetch_assoc($result))
		{
		if(strtolower($row['username'])==strtolower($cuser)){
			//$friend=array();
			$friend=$row['favorite_username'];
			//$friend['available']=0;
			if(array_search($friend,$friendlist)===false)
				array_push($friendlist,$friend);	
		}
		else{
			$friend=$row['username'];
			if(array_search($friend,$friendlist)===false)
				array_push($friendlist,$friend);			
		}
	}
	
	//print_r($friendlist);
	
	
	for($i=0;$i<count($friendlist);$i++){
		if(($case=get_user_availability($friendlist[$i],$cuser))==false){
			/*
			$friend=array();
			$friend['name']=$friendlist[$i];
			$friend['status']=0;
			array_push($friendavllist,$friend);
			*/
		}
		else{
			/*			
			$friend=array();
			$friend['name']=$friendlist[$i];
			$friend['status']=1;
			*/
			array_push($friendavllist,$friendlist[$i]);
		}
	}
//	print_r($friendavllist);
}

if($myAvailability==1){	
	$sql="SELECT * FROM mychat_online_users WHERE status=1";
	$result=mysql_query($sql);
	while($row=mysql_fetch_assoc($result)){
		array_push($friendavllist,$row['username']);	
	}
	//print_r($friendavllist);
}
sort($friendavllist);
$result=array();
$result['count']=count($friendavllist);
$result['users']=$friendavllist;
$result['customusrs']=$_SESSION['live_chat']['custavl'];
$result['custcount']=count($_SESSION['live_chat']['custavl']);
echo json_encode($result);

/*
function get_user_availability($friend,$cuser){
	$sql="	SELECT *
			FROM `mychat_online_users`
            where `username`='".$friend."' and 
			lastlogin between 
			'".date("Y-m-d H:i:s",time()-10)."' and '".date("Y-m-d H:i:s",time())."'";
	
	//echo "<br/>".$sql."<br/>";
		$result=mysql_query($sql);
	//echo mysql_error();
		if(mysql_num_rows($result)==0){ 
			return false;
		}
	
		$row=mysql_fetch_assoc($result);
		$usr_choice=$row['status'];
	
		switch($usr_choice){
			case 0: return false;
				break;
				
			case 1: 
				$sql="
				SELECT a.favorite_username as `username` 
				FROM `favorites` a, `favorites` b  
				WHERE 
					a.username=b.favorite_username 
				and 
					a.favorite_username=b.username
				and
					a.username='$cuser'
				";
				
				
				$result=mysql_query($sql);
				if(mysql_num_rows($result)>=1){
					return true;
				}					
				break;
				
				
			case 2: $sql="select * from favorites where 
						(`username`='$friend' and `favorite_username`='$cuser') 
						or 
						(`username`='$cuser' and `favorite_username`='$friend')";
				
					$result=mysql_query($sql);
				if(mysql_num_rows($result)>=1){
					return true;
				}					
				break;
			
			case 3:
			
			 	$sql="select * from `mychat_customized_availability` where 
						`username`='$friend' and `favorite_username`='$cuser'";			
				$result=mysql_query($sql);
				if(mysql_num_rows($result)>=1){
					return true;
				}
				break;
		
			default: return false;
		}
}*/
?>