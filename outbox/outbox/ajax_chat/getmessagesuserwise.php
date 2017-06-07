<?php
ob_start();
//require_once(dirname(dirname(__FILE__))."/libs/mysql.php");
require_once(dirname(__FILE__)."/../libs/mysql.php");

if(!isset($_POST['cuser'])){
	$cuser=$_GET['cuser'];
	if(isset($_GET['friend'])){
		$friend=$_GET['friend'];
	}
	if(isset($_GET['lmid']))
		$lmid=$_GET['lmid'];

}
else{
	$cuser=$_POST['cuser'];
	if(isset($_POST['friend']))
		$friend=$_POST['friend'];
	if(isset($_POST['firstid']))
		$firstmsgid=$_POST['firstid'];
		
	$lmid=$_POST['lmid'];
}
if(isset($_POST['friend']) || isset($_GET['friend'])){ ob_clean();
	echo(json_encode(getMessages_byFriend($cuser,$friend,$lmid,0)));
}

//mysql_close($conn);

?>