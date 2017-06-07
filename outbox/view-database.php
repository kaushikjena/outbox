<?php
ob_start();
session_start();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();

$data=$dbf->fetchOrder("work_order","1");
foreach($data as $resdata){
	//print "<pre>";print_r($resdata);
	if($resdata['work_status']==''){
		$string="work_status='$resdata[job_status]'";
		$dbf->updateTable("work_order",$string,"id='$resdata[id]'");
	}
}

?>