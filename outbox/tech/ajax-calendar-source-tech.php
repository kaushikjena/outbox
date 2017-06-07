<?php
ob_clean();
session_start();
include_once '../includes/class.Main.php';
//Object initialization
$dbf = new User();
$contentString=array();
$site_url = $dbf->getDataFromTable("admin","site_url","id=1");
$startdate = $_REQUEST['startdate']? date("Y-m-d",strtotime($_REQUEST['startdate'])) :'';
$enddate = $_REQUEST['enddate'] ? date("Y-m-d",strtotime($_REQUEST['enddate'])) :'';

$cond="at.wo_no=wo.wo_no AND at.tech_id='$_SESSION[userid]' AND wo.client_id=c.id AND at.start_date BETWEEN '$startdate' AND '$enddate'"; 

$i=0; 
$resArray = $dbf->fetchOrder("assign_tech at,clients c,work_order wo",$cond,"id","at.start_date,at.start_time,wo.id,wo.purchase_order_no,at.wo_no,c.name,c.city");
foreach($resArray as $resevent) { 
	$subArray=array();
	//$eventdt= date("D M d Y H:i:s \G\M\TO (T)",strtotime($resevent['start_date']));
	$eventdt= date("D M d Y ",strtotime($resevent['start_date']));
	if($resevent['start_time']<>''){$eventdt.= date("H:i:s",strtotime($resevent['start_time']));}
	$linkhref= $site_url."/tech/tech-view-job-board?id=$resevent[id]&src=cal"; $target = "_self";
	$string =$resevent['wo_no']."\n".addslashes($resevent['purchase_order_no'])."\n".addslashes($resevent['name'])."\n".addslashes($resevent['city']);
	/*$contentString.= "{id:'".$i."', title:'".$string."', start:'".$eventdt."', url:'".$linkhref."',allDay: false},";*/
	$subArray['id']=$i;$subArray['title']=$string;$subArray['start']=$eventdt;$subArray['url']=$linkhref;$subArray['allDay']='false';
	array_push($contentString,$subArray);
	$i++;
}
echo json_encode($contentString);exit;
?>