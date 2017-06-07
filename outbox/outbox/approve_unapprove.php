<?php 
	ob_start();
	session_start();
	include_once 'includes/class.Main.php';
	//Object initialization
	$dbf = new User();
	if($_SESSION['userid']==''){
		header("location:logout");exit;
	}
	ob_clean();
	if($_REQUEST['check']=='status'){
		$sql_client=$dbf->getDataFromTable("work_order","approve_status","id='$_REQUEST[id]'");
		if($sql_client['approve_status']=='1'){
			$string="approve_status='0'";
		}else{
			$string="approve_status='1'";
		}
		$dbf->updateTable("work_order",$string,"id='$_REQUEST[id]'");
	}
	print 2;exit;
?>