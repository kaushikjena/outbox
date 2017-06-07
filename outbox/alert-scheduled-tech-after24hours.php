<?php
ob_start();
session_start();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();

//preparation for alert email send to technician after 24 hours if scheduled job is not completed
 foreach($dbf->fetch("work_order","work_status IN('Dispatched','In Progress','Scheduled') AND approve_status='1' ORDER BY id ASC")as $res_workorder){
		//fetch technician work start date and time
		$todate =date("Y-m-d");
		$resTech =$dbf->fetchSingle("assign_tech at,technicians tc","at.tech_id=tc.id AND at.wo_no='$res_workorder[wo_no]'");
		$start_date = $resTech['start_date'];
		$start_date = date("Y-m-d", strtotime('+1 days',strtotime($start_date)));
		//print_r($resTech);//exit;
		//echo $start_date.'<br/>';
		if($todate == $start_date) {
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
				$resEmailTech =$dbf->fetchSingle("emails","id='4'");
				$input1=$resEmailTech['message'];
				$subject1=$resEmailTech['subject']."==".$clientName."==".$wo_no;
				
				//replace email template
				$email_body1 = str_replace(array('%Name%'),array($techname),$input1);
				$headers = "MIME-Version: 1.0\n";
				$headers .= "Content-type: text/html; charset=UTF-8\n";
				$headers .= "From:".$res_admin['name']."<".$res_admin['email'].">\n";
				//echo $email_body1;//exit;
				@mail($toemail,$subject1,$email_body1,$headers);
				#############End-Email Send to Technician################
			}
 	}

?>