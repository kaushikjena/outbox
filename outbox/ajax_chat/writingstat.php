<?php
ob_start();
//require_once(dirname(dirname(__FILE__))."/libs/mysql.php");
require_once(dirname(__FILE__)."/../libs/mysql.php");
if(!isset($_SESSION)){session_start();}
$cuser=$_SESSION['live_chat']['cuser'];

if(!isset($_POST['to_user']))
	$_POST['to_user']=$_GET['to_user'];
$to_user=$_POST['to_user'];
echo json_encode(updatewriting($cuser,$to_user));
//mysql_close($conn);
ob_flush();
?>