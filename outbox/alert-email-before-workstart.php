<?php
ob_start();
session_start();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();
$alertStatus = $dbf->getDataFromTable("alert_email","status","id='3'");
##################Condition For Sending Email##############################
if($alertStatus =='1'){
//preparation for alert email send to customer and technician before the work started
 foreach($dbf->fetch("work_order","work_status ='Scheduled' AND approve_status='1' ORDER BY id ASC")as $res_workorder){
		//fetch technician work start date and time
		$todate =date("Y-m-d");
		$resTech =$dbf->fetchSingle("assign_tech at,technicians tc","at.tech_id=tc.id AND at.wo_no='$res_workorder[wo_no]'");
		$startdate = $resTech['start_date'];
		$starttime = $resTech['start_time'];
		$endtime = $resTech['end_time'];
		$TechPhoneNo=$resTech['contact_phone'];
		//$techBeforeTime=date('h:i A',strtotime($starttime)-3600);
	    //$techAfterTime=date('h:i A',strtotime($starttime)+3600);
		
		$alertdate = date("Y-m-d", strtotime('-1 days',strtotime($startdate)));
		//echo $alertdate;exit;
		if($alertdate == $todate) {
			//fetch work order details for email sending 
			$fetch_data=$dbf->fetchSingle("work_order wo,clients c,service s","wo.client_id=c.id AND wo.service_id=s.id AND wo.wo_no='$res_workorder[wo_no]'");
			$wo_no=$fetch_data['wo_no'];
			$purchase_order=$fetch_data['purchase_order_no'];
			########get client name for subject line###############
			$clientName =$dbf->getDataFromTable("work_order w,clients c","c.name","w.created_by=c.id AND w.id='$res_workorder[id]'");
			$clientName = $clientName ? $clientName :"COD";
			########get client name for subject line###############
			//fetch admin details for email sending
			$res_admin=$dbf->fetchSingle("admin","id='1'");
			$startdate = date("d-M-Y",strtotime($startdate));
			#############Start-Email Send to Technician################
			$toemail = $resTech['email'];
			$techname = $resTech['first_name'].'&nbsp;'.$resTech['middle_name'].'&nbsp;'.$resTech['last_name'];
			$resEmailTech =$dbf->fetchSingle("email_template","id='6'");
			$input1=$resEmailTech['message'];
			$subject1=$resEmailTech['subject']."==".$clientName."==".$wo_no;
			$from_name1=$res_admin['name'];
			//replace email template
			$email_body1 = str_replace(array('%TechName%','%WorkOrders%','%PurchaseOrder%'),array($techname,$wo_no,$purchase_order),$input1);
			$headers = "MIME-Version: 1.0\n";
			$headers .= "Content-type: text/html; charset=UTF-8\n";
			$headers .= "From:".$from_name1."<".$res_admin['email'].">\n";
			//echo $email_body1;//exit;
			@mail($toemail,$subject1,$email_body1,$headers);
			#############End-Email Send to Technician################
			
			#############Start Email Send to Client User################
			$res=$dbf->fetchSingle("email_template","id='5'");
			$input=$res['message'];
			$subject=$res['subject']."==".$clientName."==".$wo_no;
			$from=$res_admin['email'];
			$from_name=$res_admin['name'];
			$toclient = $fetch_data['email'];
			$client_name=$fetch_data['name'];
			//replace email template
			$email_body = str_replace(array('%ClientName%','%TechName%','%ContactPhone%'),array($client_name,$techname,$TechPhoneNo),$input);
			$headers = "MIME-Version: 1.0\n";
			$headers .= "Content-type: text/html; charset=UTF-8\n";
			$headers .= "From:".$from_name."<".$from.">\n";
			//echo $body;exit;
			@mail($toclient,$subject,$email_body,$headers);
			#############End Email Send to Admin User################
		}
 	}
}
?>