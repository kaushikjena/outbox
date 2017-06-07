<?php
ob_start();
session_start();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();
$alertStatus = $dbf->getDataFromTable("alert_email","status","id='1'");
$admin_notification = $dbf->getDataFromTable("admin_email_notification","status","id=2");
$admin_email = $dbf->getDataFromTable("admin_email_notification","to_email","id=2");
##################Condition For Sending Email##############################
if($alertStatus =='1' && $admin_notification ==1){
//preparation for alert email send to admin users after x-days of job created
 foreach($dbf->fetch("work_order","work_status ='Open' AND approve_status='1' ORDER BY id ASC")as $res_workorder){
		//fetch x-days days after job created
		$todate =date("Y-m-d");
		$resdays= $dbf->fetchSingle("alert_assignment","");
		$alertdate = date("Y-m-d", strtotime('+'.$resdays['alert_days'].'days',strtotime($res_workorder['created_date'])));
		if($alertdate == $todate) {
			$res_admin=$dbf->fetchSingle("admin","id='1'");
			//$to=$res_admin['email'];
			$to=$admin_email;
			$to_name=$res_admin['name'];
			$fetch_data=$dbf->fetchSingle("work_order wo,clients c,service s","wo.client_id=c.id AND wo.service_id=s.id AND wo.wo_no='$res_workorder[wo_no]'");
			$wo_no=$fetch_data['wo_no'];
			$purchase_order=$fetch_data['purchase_order_no'];
			$client_name=$fetch_data['name'];
			$service_name=$fetch_data['service_name'];
			########get client name for subject line###############
			$clientName =$dbf->getDataFromTable("work_order w,clients c","c.name","w.created_by=c.id AND w.id='$res_workorder[id]'");
			$clientName = $clientName ? $clientName :"COD";
			########get client name for subject line###############
			$em=$dbf->fetchSingle("email_template","id='8'");
			$input=$em['message'];
			$subject=$em['subject']."==".$clientName."==".$wo_no;
			$from_name=$em['from_name'];
			//replace email template
			$email_body = str_replace(array('%Name%','%Wo_No%','%PurchaseOrder%','%Cname%','%Sname%'),array($to_name,$wo_no,$purchase_order,$client_name,$service_name),$input);
			$headers = "MIME-Version: 1.0\n";
			$headers .= "Content-type: text/html; charset=UTF-8\n";
			$headers .= "From:".$from_name."<".$to.">\n";
			$body=$email_body;
			//echo $to.'----'.$subject.'----'.$body.'---'.$from;exit;
			@mail($to,$subject,$body,$headers);
		}
	}
}
################################################
?>