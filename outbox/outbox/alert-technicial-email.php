<?php
ob_start();
session_start();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();
$alertStatus = $dbf->getDataFromTable("alert_email","status","id='2'");
$admin_notification = $dbf->strRecordID("admin_email_notification","status,to_email","id='3'");
$admin_email = $admin_notification['to_email'];
$to=$admin_email;
##################Condition For Sending Email##############################
if($alertStatus =='1' && $admin_notification['status'] ==1){
//preparation for alert email send to technician and admin users if assigned job is not started
 foreach($dbf->fetch("work_order","work_status ='Scheduled' AND approve_status='1' ORDER BY id ASC")as $res_workorder){
		//fetch technician work start date and time
		$todate =date("Y-m-d");
		$resTech =$dbf->strRecordID("assign_tech at,technicians tc","tc.first_name, tc.middle_name, tc.last_name,tc.email, at.start_date, at.start_time","at.tech_id=tc.id AND at.wo_no='$res_workorder[wo_no]'");
		$startdate = $resTech['start_date'];
		$starttime = $resTech['start_time'];
		
		if($todate > $startdate) {
			//fetch work order details for email sending 
			$fetch_data=$dbf->strRecordID("work_order wo,clients c,service s","wo.wo_no, wo.purchase_order_no, c.name, s.service_name","wo.client_id=c.id AND wo.service_id=s.id AND wo.wo_no='$res_workorder[wo_no]'");
			$wo_no=$fetch_data['wo_no'];
			$purchase_order=$fetch_data['purchase_order_no'];
			$customer_name=$fetch_data['name'];
			$service_name=$fetch_data['service_name'];
			########get client name for subject line###############
			$clientName =$dbf->getDataFromTable("work_order w,clients c","c.name","w.created_by=c.id AND w.id='$res_workorder[id]'");
			$clientName = $clientName ? $clientName :"COD";
			########get client name for subject line###############
			//fetch admin details for email sending
			$res_admin=$dbf->strRecordID("admin","name","id='1'");
			$startdate = ($startdate<>'0000-00-00')? date("d-M-Y",strtotime($startdate)):'';
			#############Start-Email Send to Technician################
			$toemail = $resTech['email'];
			$techname = $resTech['first_name'].'&nbsp;'.$resTech['middle_name'].'&nbsp;'.$resTech['last_name'];
			$resEmailTech =$dbf->fetchSingle("email_template","id='9'");
			$input1=$resEmailTech['message'];
			$subject1=$resEmailTech['subject']."==".$clientName."==".$wo_no;
			$from_name1=$resEmailTech['from_name'];
			//replace email template
			$email_body1 = str_replace(array('%Name%','%Wo_No%','%PurchaseOrder%','%CustomerName%','%ServiceName%','%StartDate%','%StartTime%'),array($techname,$wo_no,$purchase_order,$customer_name,$service_name,$startdate,$starttime),$input1);
			$headers = "MIME-Version: 1.0\n";
			$headers .= "Content-type: text/html; charset=UTF-8\n";
			$headers .= "From:".$from_name1."<".$to.">\n";
			//echo $email_body1;//exit;
			@mail($toemail,$subject1,$email_body1,$headers);
			#############End-Email Send to Technician################
			
			#############Start Email Send to Admin User################
			$em=$dbf->fetchSingle("email_template","id='10'");
			$input=$em['message'];
			$subject=$em['subject']."==".$clientName."==".$wo_no;
			$from_name=$em['from_name'];
			//$to=$res_admin['email'];
			$to=$admin_email;
			$to_name=$res_admin['name'];
			//replace email template
			$email_body = str_replace(array('%Name%','%Wo_No%','%PurchaseOrder%','%CustomerName%','%ServiceName%','%TechName%','%StartDate%','%StartTime%'),array($to_name,$wo_no,$purchase_order,$customer_name,$service_name,$techname,$startdate,$starttime),$input);
			$headers = "MIME-Version: 1.0\n";
			$headers .= "Content-type: text/html; charset=UTF-8\n";
			$headers .= "From:".$from_name."<".$to.">\n";
			$body=$email_body;
			//echo $body;exit;
			@mail($to,$subject,$body,$headers);
			#############End Email Send to Admin User################
		}
 	}
}
?>