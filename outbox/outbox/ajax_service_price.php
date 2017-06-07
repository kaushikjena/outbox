<?php
ob_start();
session_start();
include 'includes/class.Main.php';
$dbf = new User();
//condition for client price
if($_REQUEST['clientid']){
	$table ="service_price_client";
	$condtion = "service_id='$_REQUEST[serviceid]' AND equipment='$_REQUEST[equipid]' AND work_type='$_REQUEST[worktypeid]' AND client_id='$_REQUEST[clientid]'";
	$field = "client_price";
}else{
	$table ="service_price_outbox";
	$condtion = "service_id='$_REQUEST[serviceid]' AND equipment='$_REQUEST[equipid]' AND work_type='$_REQUEST[worktypeid]'";
	$field = "outbox_price";
}
$clientprice = $dbf->getDataFromTable($table,$field,$condtion);

//condition for tech grade price
if($_REQUEST['techgrade']){
	$fieldname = "grade".$_REQUEST['techgrade']."_price";
	$gradePrice = $dbf->getDataFromTable("service_price",$fieldname,"service_id='$_REQUEST[serviceid]' AND equipment='$_REQUEST[equipid]' AND work_type='$_REQUEST[worktypeid]'");
}else{
	$gradePrice = "0.00";
}

echo $clientprice."_".$gradePrice;
?>

                           