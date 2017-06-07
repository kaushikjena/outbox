<?php
ob_clean();
session_start();
include_once '../includes/class.Main.php';
//Object initialization
$dbf = new User();
$contentString="";
$viewname = $_REQUEST['viewname'];
$startdate = $_REQUEST['startdate']? date("Y-m-d",strtotime($_REQUEST['startdate'])) :'';
$enddate = $_REQUEST['enddate'] ? date("Y-m-d",strtotime($_REQUEST['enddate'])) :'';

$cond="at.wo_no=wo.wo_no AND at.tech_id='$_SESSION[userid]' AND wo.client_id=c.id AND at.start_date BETWEEN '$startdate' AND '$enddate'"; 

$resArrayMap = $dbf->fetchOrder("assign_tech at,clients c,work_order wo",$cond,"id","at.start_date,at.start_time,wo.id,wo.purchase_order_no,at.wo_no,c.name,c.address,c.city,c.latitude,c.longitude");

foreach($resArrayMap as $resmap) {
	$string = '<b><u>'.$resmap['wo_no'].'</u></b><br/> '.addslashes($resmap['purchase_order_no']).'<br/> '.addslashes($resmap['name']).'<br/> '.addslashes($resmap['city']);
	$contentString.= "['".$string."'_".$resmap['latitude']."_".$resmap['longitude']."_'../images/green-dot.png'_'".$resmap['wo_no']."'],";
}
echo $contentString;exit;
?>