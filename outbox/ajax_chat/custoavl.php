<?php
ob_start();
require_once(dirname(dirname(__FILE__))."/libs/mysql.php");
if(!isset($_SESSION)){session_start();}

//print_r($_REQUEST);
//print_r($_POST);

$cuser=$_SESSION['live_chat']['cuser'];
$_SESSION['live_chat']['custavl']=$_POST['chkbox_users'];

$sql="delete from `mychat_customized_availability`
	  where username='$cuser'";
mysql_query($sql);
$sql="Insert into `mychat_customized_availability`(`username`,`favorite_username`)
	  values";
	  
//echo count($_POST['chkbox_users']);
//die();	  
for($i=0;$i<count($_POST['chkbox_users']);$i++){
	$sql.="('$cuser','".$_POST['chkbox_users'][$i]."'),";
}	
$sql=trim($sql,",");
if($i!=0){
	if(!mysql_query($sql))
		echo mysql_error();
	else{
	//	echo '<meta http-equiv="refresh" content="0">';	
	}
}
// 0: Not available, 1: Private, 2: Public, 3: Custom
if(!isset($_SESSION)){session_start();}
$cuser=$_SESSION['live_chat']['cuser'];
$_SESSION['live_chat']['myAvailability']=3;
updateLivestat($cuser,3);
//mysql_close($conn);
ob_flush();
?>