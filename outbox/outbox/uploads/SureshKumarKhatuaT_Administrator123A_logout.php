<?php
ob_start();
session_start();
include_once '../includes/class.Main.php';
$dbf = new User();

$delstring="userid='0' AND transaction_id='' AND payment_status='Unpaid'";

//delete iptable records
$Today=date('Y-m-d');
$NewDate=Date('Y-m-d', strtotime("-2 days"));
$dbf->deleteFromTable("getip","access_date < '$NewDate'");

foreach($dbf->fetch("master_order",$delstring,"","","") as $del_id)
{
	$order_id_s=mysql_real_escape_string($del_id[order_id]);
	$dbf->deleteFromTable("master_order","order_id=$order_id_s");
	$dbf->deleteFromTable("order_items","order_id=$order_id_s");
}
	
$dbf->user_logout();
header("location:index.php");

?>