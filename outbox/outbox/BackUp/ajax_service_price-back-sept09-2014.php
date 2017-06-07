<?php
ob_start();
session_start();
include 'includes/class.Main.php';
$dbf = new User();

$resPrice = $dbf->fetchSingle("service_price","service_id='$_REQUEST[serviceid]' AND equipment='$_REQUEST[equipid]' AND work_type='$_REQUEST[worktypeid]'");
if($_REQUEST['techgrade']=='A'){
	$gradePrice = $resPrice['gradeA_price'];
}elseif($_REQUEST['techgrade']=='B'){
	$gradePrice = $resPrice['gradeB_price'];
}elseif($_REQUEST['techgrade']=='C'){
	$gradePrice = $resPrice['gradeC_price'];
}elseif($_REQUEST['techgrade']=='D'){
	$gradePrice = $resPrice['gradeD_price'];
}else{
	$gradePrice = "0.00";
}
echo $resPrice['outbox_price']."_".$gradePrice;
?>

                           