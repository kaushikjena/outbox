<?php
ob_start();
session_start();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();

if(isset($_SESSION['userid'])){
	
	$username=$dbf->getDataFromTable("login_view","username","id='$_SESSION[userid]' AND user_type='$_SESSION[usertype]'");
	#########insert record into mychat online users table#######
	$sql="REPLACE INTO mychat_online_users (username,user_type,status,lastlogin) VALUES ('$username','$_SESSION[usertype]','0',now())";
	mysql_query($sql);
	#########insert record into mychat online users table#######
	unset($_SESSION['userid']);
	unset($_SESSION['usertype']);
	session_unset();
	session_destroy();
	session_regenerate_id();
}
header('Location:index');
exit;
?>