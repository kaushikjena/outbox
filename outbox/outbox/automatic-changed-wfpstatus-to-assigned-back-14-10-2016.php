<?php
ob_start();
session_start();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();
##################Status changed from WFP to Assigned##############################
//preparation for automatically change status from "WFP" to "Assigned" after 7 days.
 foreach($dbf->fetchOrder("clients c,work_order w","w.client_id=c.id AND w.work_status ='WFP' AND w.approve_status='1'","w.id ASC","w.id,w.wo_no,c.name,c.city,c.phone_no","")as $res_workorder){
		//fetch technician work start date and time
		$todate =date("Y-m-d");
		$lastwfpDate=$dbf->getDataFromTable("workorder_notes","created_date","workorder_id='$res_workorder[id]' AND (user_type='admin' OR user_type='user' OR user_type='tech') AND waiting_parts!=0 ORDER BY id DESC LIMIT 1");
		$lastdate = date("Y-m-d", strtotime('+7 days',strtotime($lastwfpDate)));
		
		$resTech =$dbf->fetchSingle("assign_tech at,technicians tc","at.tech_id=tc.id AND at.wo_no='$res_workorder[wo_no]'");
		//echo $todate .'=='. $lastdate;
		if($todate == $lastdate) {
			//update work status from Scheduled to Dispatched
			//$dbf->updateTable("work_order","work_status='Assigned',updated_date=now()","id='$res_workorder[id]'");
			$wo_no=$res_workorder['wo_no'];
			########get client name for subject line###############
			$clientName =$dbf->getDataFromTable("work_order w,clients c","c.name","w.created_by=c.id AND w.id='$res_workorder[id]'");
			$clientName = $clientName ? $clientName :"COD";
			########get client name for subject line###############
			//fetch admin details for email sending
			$res_admin=$dbf->fetchSingle("admin","id='1'");
			#############Start-Email Send to Technician################
			$toemail = $resTech['email'];
			$techname = $resTech['first_name'].'&nbsp;'.$resTech['middle_name'].'&nbsp;'.$resTech['last_name'];
			$resEmailTech =$dbf->fetchSingle("email_template","id='22'");
			$input1=$resEmailTech['message'];
			$subject1=$resEmailTech['subject']."==".$clientName."==".$wo_no;
			
			//replace email template
			$email_body1 = str_replace(array('%TechName%','%CustomerCity%','%CustomerPhoneNo%'),array($techname,$res_workorder['city'],$res_workorder['phone_no']),$input1);
			$headers = "MIME-Version: 1.0\n";
			$headers .= "Content-type: text/html; charset=UTF-8\n";
			$headers .= "From:".$res_admin['name']."<".$res_admin['email'].">\n";
			//echo $email_body1;exit;
			@mail($toemail,$subject1,$email_body1,$headers);
			#############End-Email Send to Technician################
		}
 }
##################Status changed from Scheduled to Dispatched##############################
?>