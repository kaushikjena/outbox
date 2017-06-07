<?php
ob_start();
session_start();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();
$alertStatus = $dbf->getDataFromTable("alert_email","status","id='2'");
$admin_notification = $dbf->getDataFromTable("admin_email_notification","status","id=3");
##################Condition For Sending Email##############################
if($alertStatus =='1' && $admin_notification ==1){
//preparation for alert email send to technician and admin users if assigned job is not started
 foreach($dbf->fetch("work_order","work_status ='Scheduled' AND approve_status='1' ORDER BY id ASC")as $res_workorder){
		//fetch technician work start date and time
		$todate =date("Y-m-d");
		$resTech =$dbf->fetchSingle("assign_tech at,technicians tc","at.tech_id=tc.id AND at.wo_no='$res_workorder[wo_no]'");
		$startdate = $resTech['start_date'];
		$starttime = $resTech['start_time'];
		
		if($todate > $startdate) {
			//fetch work order details for email sending 
			$fetch_data=$dbf->fetchSingle("work_order wo,clients c,service s","wo.client_id=c.id AND wo.service_id=s.id AND wo.wo_no='$res_workorder[wo_no]'");
			$wo_no=$fetch_data['wo_no'];
			$customer_name=$fetch_data['name'];
			$service_name=$fetch_data['service_name'];
			########get client name for subject line###############
			$clientName =$dbf->getDataFromTable("work_order w,clients c","c.name","w.created_by=c.id AND w.id='$res_workorder[id]'");
			$clientName = $clientName ? $clientName :"COD";
			########get client name for subject line###############
			//fetch admin details for email sending
			$res_admin=$dbf->fetchSingle("admin","id='1'");
			$startdate = ($startdate<>'0000-00-00')? date("d-M-Y",strtotime($startdate)):'';
			#############Start-Email Send to Technician################
			$toemail = $resTech['email'];
			$techname = $resTech['first_name'].'&nbsp;'.$resTech['middle_name'].'&nbsp;'.$resTech['last_name'];
			$resEmailTech =$dbf->fetchSingle("email_template","id='9'");
			$input1=$resEmailTech['message'];
			$subject1=$resEmailTech['subject']."==".$clientName."==".$wo_no;
			$from_name1=$resEmailTech['from_name'];
			//replace email template
			$email_body1 = str_replace(array('%Name%','%Wo_No%','%CustomerName%','%ServiceName%','%StartDate%','%StartTime%'),array($techname,$wo_no,$customer_name,$service_name,$startdate,$starttime),$input1);
			$headers = "MIME-Version: 1.0\n";
			$headers .= "Content-type: text/html; charset=UTF-8\n";
			$headers .= "From:".$from_name1."<".$res_admin['email'].">\n";
			//echo $email_body1;//exit;
			@mail($toemail,$subject1,$email_body1,$headers);
			#############End-Email Send to Technician################
			
			#############Start Email Send to Admin User################
			$em=$dbf->fetchSingle("email_template","id='10'");
			$input=$em['message'];
			$subject=$em['subject']."==".$clientName."==".$wo_no;
			$from_name=$em['from_name'];
			$to=$res_admin['email'];
			$to_name=$res_admin['name'];
			//replace email template
			$email_body = str_replace(array('%Name%','%Wo_No%','%CustomerName%','%ServiceName%','%TechName%','%StartDate%','%StartTime%'),array($to_name,$wo_no,$customer_name,$service_name,$techname,$startdate,$starttime),$input);
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