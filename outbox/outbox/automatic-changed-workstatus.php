<?php
ob_start();
session_start();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();
##################Status changed from Scheduled to Dispatched##############################
//preparation for automatically change status from "Scheduled" to "Dispatched" on date of service.
 foreach($dbf->fetch("work_order","work_status ='Scheduled' AND approve_status='1' ORDER BY id ASC")as $res_workorder){
		//fetch technician work start date and time
		$todate =date("Y-m-d");
		$resTech =$dbf->fetchSingle("assign_tech at,technicians tc","at.tech_id=tc.id AND at.wo_no='$res_workorder[wo_no]'");
		$startdate = $resTech['start_date'];
		$starttime = $resTech['start_time'];
		
		if($todate == $startdate) {
			//update work status from Scheduled to Dispatched
			$dbf->updateTable("work_order","work_status='Dispatched',updated_date=now()","id='$res_workorder[id]' AND work_status='Scheduled'");
		}
 }
##################Status changed from Scheduled to Dispatched##############################
?>