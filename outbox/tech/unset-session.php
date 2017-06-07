<?php 
	//ob_start();
	session_start();
	if($_REQUEST['src']=='tech'){
		unset($_SESSION['requestc']);
		$_SESSION['requestc']='';
	}
	echo "1";
?>