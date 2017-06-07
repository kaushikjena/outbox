<?php
ob_start();
session_start();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();

$alertStatus = $dbf->getDataFromTable("alert_email","status","id='2'");
##################Condition For Sending Email##############################
if($alertStatus =='1'){
//preparation for alert email send to technician after 24 hours if assigned job is not started
 foreach($dbf->fetch("work_order","work_status ='Assigned' AND approve_status='1' ORDER BY id ASC")as $res_workorder){
		//fetch technician work start date and time
		$todate =date("Y-m-d");
		$resTech =$dbf->fetchSingle("assign_tech at,technicians tc","at.tech_id=tc.id AND at.wo_no='$res_workorder[wo_no]'");
		$assign_date = $resTech['assign_date'];
		$assign_date = date("Y-m-d", strtotime('+1 days',strtotime($assign_date)));
		//print_r($resTech);//exit;
		if($resTech['start_time']=='' && $resTech['end_time']==''){
			if($todate == $assign_date) {
				//fetch work order details for email sending 
				$fetch_data=$dbf->fetchSingle("work_order wo,clients c,service s","wo.client_id=c.id AND wo.service_id=s.id AND wo.id='$res_workorder[id]'");
				$customer_name=$fetch_data['name'];
				$service_name=$fetch_data['service_name'];
				$wo_no=$res_workorder['wo_no'];
				$purchase_order=$res_workorder['purchase_order_no'];
				$startdate = ($resTech['start_date']<>'0000-00-00')? date("d-M-Y",strtotime($resTech['start_date'])):'';
				$starttime = $resTech['start_time'];
				########get client name for subject line###############
				$clientName =$dbf->getDataFromTable("work_order w,clients c","c.name","w.created_by=c.id AND w.id='$res_workorder[id]'");
				$clientName = $clientName ? $clientName :"COD";
				########get client name for subject line###############
				//fetch admin details for email sending
				$res_admin=$dbf->fetchSingle("admin","id='1'");
				#############Start-Email Send to Technician################
				$toemail = $resTech['email'];
				$techname = $resTech['first_name'].'&nbsp;'.$resTech['middle_name'].'&nbsp;'.$resTech['last_name'];
				$resEmailTech =$dbf->fetchSingle("email_template","id='18'");
				$input1=$resEmailTech['message'];
				$subject1=$resEmailTech['subject']."==".$clientName."==".$wo_no;
				$from_name1=$resEmailTech['from_name'];
				//replace email template
				$email_body1 = str_replace(array('%Name%'),array($techname),$input1);
				$headers = "MIME-Version: 1.0\n";
				$headers .= "Content-type: text/html; charset=UTF-8\n";
				$headers .= "From:".$from_name1."<".$res_admin['email'].">\n";
				//echo $email_body1;//exit;
				@mail($toemail,$subject1,$email_body1,$headers);
				#############End-Email Send to Technician################
				
				#############Start Email Send to Admin User################
				$admin_notification = $dbf->getDataFromTable("admin_email_notification","status","id=6");
				$admin_email = $dbf->getDataFromTable("admin_email_notification","to_email","id=6");
				if($admin_notification==1){
					$em=$dbf->fetchSingle("email_template","id='10'");
					$input=$em['message'];
					$subject=$em['subject']."==".$clientName."==".$wo_no;;
					$from_name=$em['from_name'];
					//$to=$res_admin['email'];
					$to=$admin_email;
					$to_name=$res_admin['name'];
					//replace email template
					$email_body = str_replace(array('%Name%','%Wo_No%','%PurchaseOrder%','%CustomerName%','%ServiceName%','%TechName%','%StartDate%','%StartTime%'),array($to_name,$wo_no,$purchase_order,$customer_name,$service_name,$techname,$startdate,$starttime),$input);
					$headers = "MIME-Version: 1.0\n";
					$headers .= "Content-type: text/html; charset=UTF-8\n";
					$headers .= "From:".$from_name."<".$to.">\n";
					//echo $email_body;exit;
					@mail($to,$subject,$email_body,$headers);
				}
				#############End Email Send to Admin User################
			}
		}
 	}
}
?>