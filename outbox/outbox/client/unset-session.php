<?php 
	//ob_start();
	session_start();
	if($_REQUEST['src']=='disp'){
		unset($_SESSION['requestp']);
		$_SESSION['requestp']='';
	}elseif($_REQUEST['src']=='open'){
		unset($_SESSION['requesto']);
		$_SESSION['requesto']='';
	}
	echo "1";
?>