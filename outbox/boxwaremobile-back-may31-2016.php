<?php
	header('Content-type: application/json');
	include_once 'includes/class.Main.php';
	//Object initialization
	$dbf = new User();
	//CONDITION FOR TECH LOGIN FROM MOBILE URL
	if(isset($_REQUEST['method']) && $_REQUEST['method']=="login" && $_SERVER['REQUEST_METHOD']=='GET'){
		//print_r(json_decode($_GET['data']));exit;
		$jsonData=json_decode($_REQUEST['data']);
		//for sql injection
		$useremail = stripslashes($jsonData->EmailLogin); // Get user name
		$useremail = mysql_real_escape_string($useremail);
		$userpwd = stripslashes($jsonData->PasswordLogin); // Get password
		$userpwd = mysql_real_escape_string($userpwd);
		$password = base64_encode(base64_encode($userpwd)); 
		if($useremail !='' && $userpwd !=''){
			$num=$dbf->countRows('login_view',"email='$useremail' AND password='$password'");
			if($num>0){
				$res_login=$dbf->fetchSingle('login_view',"email='$useremail' AND password='$password'");
				if($res_login['status']=='1'){
					$resTech =$dbf->fetchSingle("technicians","id='$res_login[id]'");
					$techname = ($resTech['middle_name']<>'')?($resTech['first_name'].' '.$resTech['middle_name'].' '.$resTech['last_name']):($resTech['first_name'].' '.$resTech['last_name']);
					//return data to iphone url
					if($res_login['user_type']=='tech'){
						echo '{"success":"true","userid":"'.$res_login[id].'","username":"'.$techname.'"}';exit;
					}
				}else{
					echo '{"success":"false"}';exit;
				}
			}else{
				echo '{"success":"false"}';exit;
			}
		}else{
			echo '{"success":"false"}';exit;
		}
	}
	##########CONDITION FOR SCHEDULED DATES#################
	if(isset($_REQUEST['method']) && $_REQUEST['method']=="getScheduledDate" && $_SERVER['REQUEST_METHOD']=='GET'){
		$jsonData=json_decode($_REQUEST['data']);
		$resultArray =$dbf->fetchOrder("technicians t,assign_tech at,work_order w","w.work_status='Scheduled' AND w.approve_status='1' AND w.wo_no=at.wo_no AND at.tech_id=t.id AND t.id='".$jsonData->userid."'","w.id DESC","w.id, at.start_date","at.start_date");
		$dataArray = array();
		foreach($resultArray as $res_JobBoard){
			array_push($dataArray,$res_JobBoard['start_date']);
		}
		echo '{"data":'.json_encode($dataArray).'}';
	}
	##########CONDITION FOR DASHBOARD SCHEDULE JOBS#################
	if(isset($_REQUEST['method']) && $_REQUEST['method']=="scheduled" && $_SERVER['REQUEST_METHOD']=='GET'){
		$jsonData=json_decode($_REQUEST['data']);
		$scheduledDate = date("Y-m-d",strtotime($jsonData->scheduledDate));
		$resultArray =$dbf->fetchOrder("clients c,service s,technicians t,assign_tech at,work_order w","c.id=w.client_id AND w.service_id=s.id AND w.work_status='Scheduled' AND w.approve_status='1' AND w.wo_no=at.wo_no AND at.tech_id=t.id AND t.id='".$jsonData->userid."' AND at.start_date='".$scheduledDate."'","w.id DESC","w.id, w.wo_no, s.service_name, c.name, c.latitude, c.longitude, at.start_date","");
		$dataArray = array();
		$headerArray =array("woid","wo#","ServiceName","CustomerName","ScheduledDate");
		foreach($resultArray as $res_JobBoard){
			$schdate = date("M/d/Y",strtotime($res_JobBoard['start_date']));
			$subArray = array("woid" => $res_JobBoard['id'],"wo#" => $res_JobBoard['wo_no'],"ServiceName" => $res_JobBoard['service_name'],"CustomerName" => $res_JobBoard['name'],"Latitude" => $res_JobBoard['latitude'],"Longitude" => $res_JobBoard['longitude'],"ScheduledDate" => $schdate);
			 array_push($dataArray,$subArray);
		}
		
		echo '{"header":'.json_encode($headerArray).',"data":'.json_encode($dataArray).'}';
		
	}
	##########CONDITION FOR DASHBOARD SCHEDULE JOBS#################
	if(isset($_REQUEST['method']) && $_REQUEST['method']=="assigned" && $_SERVER['REQUEST_METHOD']=='GET'){
		$jsonData=json_decode($_REQUEST['data']);
		$resultArray =$dbf->fetchOrder("clients c,service s,technicians t,assign_tech at,work_order w","c.id=w.client_id AND w.service_id=s.id AND w.work_status='Assigned' AND w.approve_status='1' AND w.wo_no=at.wo_no AND at.tech_id=t.id AND t.id='".$jsonData->userid."'","w.id DESC","w.id, w.wo_no, s.service_name, c.name, c.latitude, c.longitude, at.assign_date","");
		$dataArray = array();
		$headerArray =array("woid","wo#","ServiceName","CustomerName","AssignedDate");
		foreach($resultArray as $res_JobBoard){
			$assignDate = date("M/d/Y",strtotime($res_JobBoard['assign_date']));
			$subArray = array("woid" => $res_JobBoard['id'],"wo#" => $res_JobBoard['wo_no'],"ServiceName" => $res_JobBoard['service_name'],"CustomerName" => $res_JobBoard['name'],"Latitude" => $res_JobBoard['latitude'],"Longitude" => $res_JobBoard['longitude'],"AssignedDate" => $assignDate);
			 array_push($dataArray,$subArray);
		}
		
		echo '{"header":'.json_encode($headerArray).',"data":'.json_encode($dataArray).'}';
		
	}
	#######CONDITION FOR JOB DETAILS#############
	if(isset($_REQUEST['method']) && $_REQUEST['method']=="jobdetails" && $_SERVER['REQUEST_METHOD']=='GET'){
		//print_r(json_decode($_REQUEST['data']));exit;
		$jsonData=json_decode($_REQUEST['data']);
		$res_viewJobBoard=$dbf->fetchSingle("clients c,service s,work_order w","c.id=w.client_id AND w.service_id=s.id  AND w.id='".$jsonData->woid."'");
		$resTech = $dbf->fetchSingle("assign_tech at,technicians tc","at.tech_id=tc.id AND at.wo_no='$res_viewJobBoard[wo_no]' AND tc.id='".$jsonData->userid."'");
		//print_r($res_viewJobBoard);
		//print_r($resTech);
		$resTechName = ($resTech<>'')? (($resTech['middle_name']<>'')?($resTech['first_name'].' '.$resTech['middle_name'].' '.$resTech['last_name']):($resTech['first_name'].' '.$resTech['last_name'])):"";
		$assignedDate = ($resTech<>'' && $resTech['assign_date']<>'0000-00-00')? date("M/d/Y",strtotime($resTech['assign_date'])):"";
		$startDate = ($resTech<>'' && $resTech['start_date']<>'0000-00-00')?  date("M/d/Y",strtotime($resTech['start_date'])).' '.$resTech['start_time'] :"";
		//json data for work oredr details section
		$subArray1 = array("woid" => $res_viewJobBoard['id'],"wo#" => $res_viewJobBoard['wo_no'],"Purchase Order" => $res_viewJobBoard['purchase_order_no'],"Work Status" => $res_viewJobBoard['work_status'],"Service Name" => $res_viewJobBoard['service_name'],"Technician" => $resTechName,"Assigned Date" => $assignedDate,"Scheduled Date" => $startDate);
		$returnData = '{"Work Order Details":'.json_encode($subArray1) ;
		
		//json data for customer information section
		$res_jobState=$dbf->getDataFromTable("state","state_name","state_code='$res_viewJobBoard[state]'");
		$subArray2 = array("Name" => $res_viewJobBoard['name'],"Email Address" => $res_viewJobBoard['email'],"Address" => $res_viewJobBoard['address'],"Contact Name" => $res_viewJobBoard['contact_name'],"City" => $res_viewJobBoard['city'] ,"State" => $res_jobState ,"Zip Code" => $res_viewJobBoard['zip_code'],"Phone No" => $res_viewJobBoard['phone_no'],"Cell No" => $res_viewJobBoard['fax_no']);
		$returnData.= ', "Customer Information":'.json_encode($subArray2);
		
		//json data for Pick Up Information section
		$res_jobStatePick=$dbf->getDataFromTable("state","state_name","state_code='$res_viewJobBoard[pickup_state]'");
		$subArray3 = array("Location" => $res_viewJobBoard['pickup_location'],"City" => $res_viewJobBoard['pickup_city'],"State" => $res_jobStatePick,"Address" => $res_viewJobBoard['pickup_address'],"Zip Code" => $res_viewJobBoard['pickup_zip_code'] ,"Phone No" => $res_viewJobBoard['pickup_phone_no'],"Alt Phone" => $res_viewJobBoard['pickup_alt_phone']);		
		$returnData.= ', "Pick Up Information":'.json_encode($subArray3) ;
		
		//json data for Job Description section
		//$subArray4 = "Job Description".':"'.$res_viewJobBoard['notes'].'"';
		$description =str_replace(array("\r\n", "\r", "\n"), '\n', $res_viewJobBoard['notes']);
		$returnData.= ', "Job Description":"'.$description.'"';
		
		//json data for work type section
		$dataArray = array();$subtotal=0;
		$headerArray =array("Work Type","Equipment","Model","Quantity","Tech Price","Total Price");
		//array_push($jsonArray,$header);
		$res_woservice = $dbf->fetch("equipment e,work_type wt,workorder_service ws","e.id=ws.equipment AND wt.id=ws.work_type AND ws.workorder_id='".$jsonData->woid."'");
		  foreach($res_woservice as $arrWorkservice){
			  	$TechPrice=$arrWorkservice['tech_price'];
				$total = ($arrWorkservice['quantity']*$TechPrice);
				$subtotal = $subtotal+$total;
			
			$subArray = array("Work Type" => $arrWorkservice['worktype'],"Equipment" => $arrWorkservice['equipment_name'],"Model" => $arrWorkservice['model'],"Quantity" => $arrWorkservice['quantity'],"Tech Price" => "$".number_format($TechPrice,2),"Total Price" => "$".number_format($total,2)," Sub Total" => "$".number_format($subtotal,2));
			 array_push($dataArray,$subArray);
		}
		
		$subArray5 = '{"header":'.json_encode($headerArray).',"data":'.json_encode($dataArray).'}';
		$returnData.= ', "Work Type":'.$subArray5 ;
		//json data for tech notes
		$resNotes=$dbf->fetchOrder("workorder_notes","workorder_id='".$jsonData->woid."' AND user_type='tech'","created_date DESC");
		$dataArray1 = array();
		foreach($resNotes as $resn){
			 if($resn['user_type']=='tech'){
				 $unameTech = $dbf->fetchSingle("technicians","id='$resn[user_id]'");
				 $uname = $unameTech['first_name'].' '.$unameTech['middle_name'].' '.$unameTech['last_name'];
			 }
			$by = $uname ." on " . date("d-M-Y g:i A",strtotime($resn['created_date'])). " for " . $res_viewJobBoard['wo_no'];
			$subres = array("Note" => $resn['wo_notes'],"By" => $by);
			array_push($dataArray1,$subres); 
		}
		$returnData.= ', "Tech Notes":'.json_encode($dataArray1).'}';
		
		/*$returnData =json_decode($returnData);
		print "<pre>";
		print_r($returnData);exit;*/
		
		echo ($returnData);exit;
	}
	##############################################################
	################### CONDITION FOR ADD TECH NOTES
	if(isset($_REQUEST['method']) && $_REQUEST['method']=="addnote" && $_SERVER['REQUEST_METHOD']=='GET'){
		$jsonData=json_decode($_REQUEST['data']);
		$techNotes=mysql_real_escape_string($jsonData->note);
		$string="workorder_id='".$jsonData->woid."', user_type='tech', user_id='".$jsonData->userid."', wo_notes='$techNotes',created_date=now()";
		$insid = $dbf->insertSet("workorder_notes",$string);
		if($insid){
			echo '{"success":"true"}';exit;
		}else{
			echo '{"success":"false"}';exit;
		}
	}
	############CONDITION FOR SERVICE CALL#############
	if(isset($_REQUEST['method']) && $_REQUEST['method']=="servicecall" && $_SERVER['REQUEST_METHOD']=='GET'){
		//print_r(json_decode($_REQUEST['data']));exit;
		$jsonData=json_decode($_REQUEST['data']);
		$resultArray =$dbf->fetchOrder("state st,clients c,service s,technicians t,assign_tech at,work_order w","st.state_code=c.state AND c.id=w.client_id AND w.service_id=s.id AND w.work_status IN('Dispatched','In Progress') AND w.approve_status='1' AND w.wo_no=at.wo_no AND at.tech_id=t.id AND t.id='".$jsonData->userid."'","w.id DESC","st.state_name,c.name,c.city,w.id,w.wo_no,w.work_status,s.service_name,at.start_date,at.start_time","");
		$dataArray = array();
		$headerArray =array("woid","wo#","CustomerName","ServiceName","WorkStatus","DeliveryCity","DeliveryState","StartDate & Time");
		foreach($resultArray as $res_JobBoard){
			//echo $res_JobBoard['wo_no']."\n";
			$workstatus = $res_JobBoard['work_status']?$res_JobBoard['work_status']:"Not Started";
			$startdatetime = ($res_JobBoard['start_date']<>'0000-00-00')? date("M/d/Y",strtotime($res_JobBoard['start_date'])).' '.$res_JobBoard['start_time']:""; 
			$subArray = array("woid" => $res_JobBoard['id'],"wo#" => $res_JobBoard['wo_no'],"CustomerName" => $res_JobBoard['name'],"ServiceName" => $res_JobBoard['service_name'],"WorkStatus" => $workstatus,"DeliveryCity" => $res_JobBoard['city'],"DeliveryState" => $res_JobBoard['state_name'],"StartDate & Time" => $startdatetime);
			 array_push($dataArray,$subArray);
		}
		echo '{"header":'.json_encode($headerArray).',"data":'.json_encode($dataArray).'}';
	}
	############CONDITION FOR SCHEDULE JOB #############
	if(isset($_REQUEST['method']) && $_REQUEST['method']=="getschedule" && $_SERVER['REQUEST_METHOD']=='GET'){
		//print_r(json_decode($_REQUEST['data']));exit;
		$jsonData=json_decode($_REQUEST['data']);
		$resTech = $dbf->fetchSingle("technicians t,assign_tech at","t.id=at.tech_id AND at.wo_no='".$jsonData->wono."'");
		$resTechName = ($resTech<>'')? (($resTech['middle_name']<>'')?($resTech['first_name'].' '.$resTech['middle_name'].' '.$resTech['last_name']):($resTech['first_name'].' '.$resTech['last_name'])):"";
		$startDate = ($resTech['start_date']<>'0000-00-00')?date("M/d/Y",strtotime($resTech['start_date'])):'';
		$startTime = ($resTech['start_time']<>'')?$resTech['start_time']:'';
		$endTime = ($resTech['end_time']<>'')?$resTech['end_time']:'';
		echo '{"TechName":"'.$resTechName.'","Start Date":"'.$startDate.'","Start Time":"'.$startTime.'","End Time":"'.$endTime.'"}';
	}
	############CONDITION FOR SCHEDULE JOB #############
	if(isset($_REQUEST['method']) && $_REQUEST['method']=="submitschedule" && $_SERVER['REQUEST_METHOD']=='GET'){
		//print_r(json_decode($_REQUEST['data']));exit;
		$jsonData=json_decode($_REQUEST['data']);
		$Techid=$jsonData->userid;
		$WorkNo=$jsonData->wono;
		$asgnStartDate=date("Y-m-d",strtotime($jsonData->StartDate));
		$asgnStartTime=$jsonData->StartTime;
		$asgnEndTime=$jsonData->EndTime;
		$num = $dbf->countRows("assign_tech","wo_no='$WorkNo'");
		if($num>0){
			//update work order table
			if($asgnStartDate !=''){
				$dbf->updateTable("work_order","work_status='Scheduled',schedule_status='Scheduled'","wo_no='$WorkNo'");
			}	
			//update assign_tech table
			$string="start_date='$asgnStartDate', start_time='$asgnStartTime',end_time='$asgnEndTime',updated_date=now()";
			$dbf->updateTable("assign_tech",$string,"wo_no='$WorkNo'");
			###################Track notes in the work order notes table################################
			$woid = $dbf->getDataFromTable("work_order","id","wo_no='$WorkNo'");
			$techNotes="The scheduled date of this order is changed.";
			$strnotes="workorder_id='$woid', user_type='tech', user_id='$Techid', wo_notes='$techNotes',created_date=now()";
			$dbf->insertSet("workorder_notes",$strnotes);
			###################Track notes in the work order notes table################################
			//Email Sending Start
			/********Admin Email************/
			$res_template=$dbf->fetchSingle("email_template","id=11");
			$to=$res_template['from_email'];
			$AdminName=$res_template['from_name'];
			$subject=$res_template['subject'];
			$input=$res_template['message'];
			//get technician details
			$tech=$dbf->fetchSingle("technicians","id='$Techid'");
			$from=$tech['email'];
			$TechName=$tech['first_name'].'&nbsp;'.$tech['middle_name'].'&nbsp;'.$tech['last_name'];
			$body=str_replace(array('%AdminName%','%TechName%','%StartDate%','%StartTime%','%EndTime%','%WorkOrders%'),array($AdminName,$TechName,$asgnStartDate,$asgnStartTime,$asgnEndTime,$WorkNo),$input);
			$headers = "MIME-Version: 1.0\n";
			$headers .= "Content-type: text/html; charset=UTF-8\n";
			$headers .= "From:".$TechName." <".$from.">\n";
			//echo $body;exit;
			$send1= @mail($to,$subject,$body,$headers);
			/********Admin Email************/
			/********Client Email************/
			$res_template=$dbf->fetchSingle("email_template","id=12");
			$subject1=$res_template['subject'];
			$input1=$res_template['message'];
			$headers = "MIME-Version: 1.0\n";
			$headers .= "Content-type: text/html; charset=UTF-8\n";
			$headers .= "From:".$Techname." <".$from.">\n";
			$clientid = $dbf->getDataFromTable("work_order","client_id","wo_no='$WorkNo'");
			$resClients = $dbf->fetchSingle("clients","id='$clientid'");
			$toclient= $resClients['email'];
			$toclientname= $resClients['name'];
			$emailbody=str_replace(array('%ClientName%','%TechName%','%StartDate%','%StartTime%','%EndTime%','%WorkOrders%'),array($toclientname,$Techname,$asgnStartDate,$asgnStartTime,$asgnEndTime,$WorkNo),$input1);
			//echo $emailbody;exit;
			$send2 = @mail($toclient,$subject1,$emailbody,$headers);
			/********Client Email************/
			//Email Sending End
			echo '{"Success":"true"}';exit;
		}else{
			echo '{"Success":"false"}';exit;
		}
		/*if($send1 && $send2){
			echo '{"Success":"true"}';exit;
		}else{
			echo '{"Success":"false"}';exit;
		}*/
	}
	############CONDITION FOR SET WORK STATUS #############
	if(isset($_REQUEST['method']) && $_REQUEST['method']=="submitWorkstatus"){
		$jsonData=json_decode($_REQUEST['data']);
		//print_r(json_decode($_REQUEST['data']));exit;
		$arrivalDate=date("Y-m-d",strtotime($jsonData->ArrivalDate));
		$fileWorkImageAmount = $jsonData->fileWorkImageAmount;
		$fileWorkDocAmount = $jsonData->fileWorkDocAmount;
		//update work_order table
		 $string="work_status='".$jsonData->WorkStatus."'";
		 $dbf->updateTable("work_order",$string,"wo_no='".$jsonData->wono."'");
		 
		 $techNotes= mysql_real_escape_string($jsonData->TechNotes);
		 $woid = $dbf->getDataFromTable("work_order","id","wo_no='".$jsonData->wono."'");
		 //insert into work_order_tech table
		 $string="wo_no='".$jsonData->wono."',tech_id='".$jsonData->userid."', arrival_date='$arrivalDate', arrival_time='".$jsonData->ArrivalTime."', depart_time='".$jsonData->DepartTime."' ,notes='$techNotes', work_status='".$jsonData->WorkStatus."',created_date=now()";
		 $insid1 = $dbf->insertSet("work_order_tech",$string);
		 ###################Track notes in the work order notes table################################
		 if($insid1){
			$techNotes="The order status is changed into ".$jsonData->WorkStatus.".";
			$strnotes="workorder_id='".$woid."', user_type='tech', user_id='".$jsonData->userid."', wo_notes='$techNotes',created_date=now()";
			$dbf->insertSet("workorder_notes",$strnotes);
		 }
		 ###################Track notes in the work order notes table################################
		###########INSERT DATA INTO WORK ORDER TECH DATA TABLE#############
		$stringData = "wo_no='".$jsonData->wono."',tech_id='".$jsonData->userid."',latitude='".$jsonData->Latitude."',longitude='".$jsonData->Longitude."',created_date=now()";
		$insid2=$dbf->insertSet("work_order_tech_data",$stringData);
		###########INSERT DATA INTO WORK ORDER TECH DATA TABLE#############	
		//for signature
		if($_POST['fileSignature']){
			$filenamesign=$jsonData->wono.'_'.date('dhihis').'_fileSignature.png';
			$worksignpath="workorder_doc/";
			file_put_contents($worksignpath.$filenamesign,base64_decode($_POST['fileSignature']));
			//insert into workorder doc table
			$strings="workorder_id='".$woid."', wo_document='$filenamesign', created_date=now(), created_user='".$jsonData->userid."', user_type='tech'";
			$ins = $dbf->insertSet("workorder_doc",$strings);
			###################Track notes in the work order notes table################################
			 if($ins){
				$techNotes="Signature is submitted by tech to this order";
				$strnotes="workorder_id='".$woid."', user_type='tech', user_id='".$jsonData->userid."', wo_notes='$techNotes',created_date=now()";
				$dbf->insertSet("workorder_notes",$strnotes);
			 }
			###################Track notes in the work order notes table################################
			
		}
		//for work image
		if($fileWorkImageAmount >0){
			for($i=1;$i<=$fileWorkImageAmount;$i++){
				$fileWorkImage= "fileWorkImage".$i;
				$file_name=$jsonData->wono.'_'.date('dhihis')."_".$fileWorkImage.".png";
				$workimgpath="workorder_doc/";
				file_put_contents($workimgpath.$file_name,base64_decode($_POST[$fileWorkImage]));
				//insert into workorder doc table
				$stringf="workorder_id='".$woid."', wo_document='$file_name', created_date=now(), created_user='".$jsonData->userid."', user_type='tech'";
				$insf = $dbf->insertSet("workorder_doc",$stringf);
				###################Track notes in the work order notes table################################
				 if($insf){
					$techNotes="A new document is uploaded to this order";
					$strnotes="workorder_id='".$woid."', user_type='tech', user_id='".$jsonData->userid."', wo_notes='$techNotes',created_date=now()";
					$dbf->insertSet("workorder_notes",$strnotes);
				 }
				###################Track notes in the work order notes table################################
			}
		}
		//for work document
		if($fileWorkDocAmount >0){
			for($i=1;$i<=$fileWorkDocAmount;$i++){
				$fileWorkDoc= "fileWorkDoc".$i;
				$fileWorkDocExt= "fileWorkDocExt".$i;
				$file_name=$jsonData->wono.'_'.date('dhihis')."_".$fileWorkDoc.'.'.$_POST[$fileWorkDocExt];
				$workimgpath="workorder_doc/";
				file_put_contents($workimgpath.$file_name,base64_decode($_POST[$fileWorkDoc]));
				//insert into workorder doc table
				$stringdoc="workorder_id='".$woid."', wo_document='$file_name', created_date=now(), created_user='".$jsonData->userid."', user_type='tech'";
				$insd = $dbf->insertSet("workorder_doc",$stringdoc);
				###################Track notes in the work order notes table################################
				 if($insd){
					$techNotes="A new document is uploaded to this order";
					$strnotes="workorder_id='".$woid."', user_type='tech', user_id='".$jsonData->userid."', wo_notes='$techNotes',created_date=now()";
					$dbf->insertSet("workorder_notes",$strnotes);
				 }
				###################Track notes in the work order notes table################################
			}
		}
		###########INSERT DATA INTO WORK ORDER SERIAL NO TABLE#############
		//for serial no
		if(!empty($jsonData->MachineSerialNo)){
			foreach($jsonData->MachineSerialNo as $valserial){
				$stringSerial = "wo_no='".$jsonData->wono."',tech_id='".$jsonData->userid."',serial_no='".$valserial."',created_date=now()";				
				$dbf->insertSet("work_order_serial_no",$stringSerial);
			}
		}		
		###########INSERT DATA INTO WORK ORDER SERIAL NO TABLE#############
		 if($insid1 && $insid2){
			echo '{"Success":"true"}';exit;
		 }else{
			echo '{"Success":"false"}';exit; 
		 }
	}
	############CONDITION FOR TECH JOB REPORTS#############
	if(isset($_REQUEST['method']) && $_REQUEST['method']=="jobreport" && $_SERVER['REQUEST_METHOD']=='GET'){
		//print_r(json_decode($_REQUEST['data']));exit;
		$jsonData=json_decode($_REQUEST['data']);
		$resultArray =$dbf->fetchOrder("state st,clients c,service s,technicians t,assign_tech at,work_order w","st.state_code=c.state AND c.id=w.client_id AND w.service_id=s.id AND w.approve_status='1' AND w.wo_no=at.wo_no AND at.tech_id=t.id AND t.id='".$jsonData->userid."'","w.id DESC","st.state_name,c.name,c.city,w.id,w.wo_no,w.work_status,w.pickup_city,w.pickup_state,s.service_name,at.start_date","");
		$dataArray = array();
		$headerArray =array("woid","wo#","CustomerName","ServiceName","WorkStatus","DeliveryCity","DeliveryState","PickupCity","PickupState","ScheduledDate");
		foreach($resultArray as $res_JobBoard){
			$pickupstate = $dbf->getDataFromTable("state","state_name","state_code='$res_JobBoard[pickup_state]'");
			//echo $res_JobBoard['wo_no']."\n";
			$workstatus = $res_JobBoard['work_status']?$res_JobBoard['work_status']:"Not Started";
			$startdate = $res_JobBoard['start_date']? date("d-M-Y",strtotime($res_JobBoard['start_date'])):""; 
			$subArray = array("woid" => $res_JobBoard['id'],"wo#" => $res_JobBoard['wo_no'],"CustomerName" => $res_JobBoard['name'],"ServiceName" => $res_JobBoard['service_name'],"WorkStatus" => $workstatus,"DeliveryCity" => $res_JobBoard['city'],"DeliveryState" => $res_JobBoard['state_name'],"PickupCity" => $res_JobBoard['pickup_city'],"PickupState" => $pickupstate,"ScheduledDate" => $startdate);
			 array_push($dataArray,$subArray);
		}
		echo '{"header":'.json_encode($headerArray).',"data":'.json_encode($dataArray).'}';
	}
	############CONDITION FOR TECH PAYMENT REPORTS#############
	if(isset($_REQUEST['method']) && $_REQUEST['method']=="paymentreport" && $_SERVER['REQUEST_METHOD']=='GET'){
		//print_r(json_decode($_REQUEST['data']));exit;
		$jsonData=json_decode($_REQUEST['data']);
		$resultArray =$dbf->fetchOrder("state st,clients c,service s,technicians t,work_order wo,work_order_tech_bill wb","st.state_code=c.state AND wb.client_id=c.id AND wb.payment_status='Completed' AND wb.tech_id=t.id AND wo.wo_no=wb.wo_no AND wo.service_id=s.id AND wb.tech_id='".$jsonData->userid."'","wb.id DESC","st.state_name, c.name, s.service_name, wb.wo_no, wb.payment_status, wb.subtotal, wb.payment_date","");
		$dataArray = array();
		$headerArray =array("wo#","CustomerName","ServiceName","CustomerState","PaymentStatus","PaymentDate","Amount");
		foreach($resultArray as $res_JobBoard){
			$paymentdate = $res_JobBoard['payment_date']? date("d-M-Y",strtotime($res_JobBoard['payment_date'])):""; 
			$subArray = array("wo#" => $res_JobBoard['wo_no'],"CustomerName" => $res_JobBoard['name'],"ServiceName" => $res_JobBoard['service_name'],"CustomerState" => $res_JobBoard['state_name'],"PaymentStatus" => $res_JobBoard['payment_status'],"PaymentDate" => $paymentdate,"Amount" => $res_JobBoard['subtotal']);
			 array_push($dataArray,$subArray);
		}
		echo '{"header":'.json_encode($headerArray).',"data":'.json_encode($dataArray).'}';
	}
	############CONDITION FOR TECH PENDING BILLS#############
	if(isset($_REQUEST['method']) && $_REQUEST['method']=="pendingbills" && $_SERVER['REQUEST_METHOD']=='GET'){
		//print_r(json_decode($_REQUEST['data']));exit;
		$jsonData=json_decode($_REQUEST['data']);
		$resultArray =$dbf->fetchOrder("clients c,service s,technicians t,assign_tech at,work_order w","c.id=w.client_id AND w.service_id=s.id AND w.work_status='Assigned' AND w.approve_status='1' AND w.wo_no=at.wo_no AND at.tech_id=t.id AND t.id='".$jsonData->userid."' AND w.wo_no IN(select wo_no from work_order_tech_bill WHERE payment_status='Pending')","w.id DESC","w.id, w.wo_no, c.name, s.service_name","");
		$dataArray = array();
		$headerArray =array("wo#","CustomerName","ServiceName","CompletedDate");
		foreach($resultArray as $res_JobBoard){
			$compledate=$dbf->getDataFromTable("work_order_tech","arrival_date","wo_no='$res_JobBoard[wo_no]' ORDER BY id DESC");
			$compledate = $compledate? date("d-M-Y",strtotime($compledate)):""; 
			$subArray = array("woid" => $res_JobBoard['id'],"wo#" => $res_JobBoard['wo_no'],"CustomerName" => $res_JobBoard['name'],"ServiceName" => $res_JobBoard['service_name'],"CompletedDate" => $compledate);
			 array_push($dataArray,$subArray);
		}
		echo '{"header":'.json_encode($headerArray).',"data":'.json_encode($dataArray).'}';
	}
	############CONDITION FOR FORGOT PASSWORD#############
	if(isset($_REQUEST['method']) && $_REQUEST['method']=="forgotpassword" && $_SERVER['REQUEST_METHOD']=='GET'){
		//print_r(json_decode($_REQUEST['data']));exit;
		$jsonData=json_decode($_REQUEST['data']);
		//for sql injection
		$useremail = mysql_real_escape_string($jsonData->ForgotEmail);
		$num=$dbf->countRows('login_view',"email='$useremail' AND user_type='tech'");
		if($num>0){
			$res_forget=$dbf->fetchSingle('login_view',"email='$useremail' AND user_type='tech'");
			//$email_confirm=$res_forget['email_confirm'];
			$active_status=$res_forget['status'];
			if($active_status==1){
				$password= base64_decode(base64_decode($res_forget['password']));
				/*Email sending start*/
				$res_template=$dbf->fetchSingle("email_template","id='2'");
				$from=$res_template['from_email'];
				$from_name=$res_template['from_name'];
				$subject=$res_template['subject'];
				$input=$res_template['message'];
				$to=$useremail;
				$toName=ucfirst($res_forget['user_type']);
				$body = str_replace(array('%Name%','%Password%'),array($toName,$password ),$input);
				$headers = "MIME-Version: 1.0\n";
				$headers .= "Content-type: text/html; charset=UTF-8\n";
				$headers .= "From:".$from_name." <".$from.">\n";				
				//echo $body;exit;
				@mail($to,$subject,$body,$headers);
				/*Email sending end*/
				echo '{"Success":"true"}';exit;
			}else{
				echo '{"Success":"false"}';exit;
			}
		}else{
			echo '{"Success":"false"}';exit;
		}
	}
	############CONDITION FOR TECH MYACCOUNT VIEW#############
	if(isset($_REQUEST['method']) && $_REQUEST['method']=="myaccount" && $_SERVER['REQUEST_METHOD']=='GET'){
		//print_r(json_decode($_REQUEST['data']));exit;
		$jsonData=json_decode($_REQUEST['data']);
		//json data for customer information section
		$res_editTechProfile=$dbf->fetchOrder("state st,technicians t","st.state_code=t.state AND t.id='".$jsonData->userid."'","","st.state_name,t.*");
		$res_editTechProfile=$res_editTechProfile[0];
		//print "<pre>";
		//print_r($res_editTechProfile);
		$path = $dbf->get_server();
		$path = $path."/outbox";//for local server
		//$path = $path."/sys/outbox";//for box-ware server
		$vehicle_image = $res_editTechProfile['vehicle_image']?$path."/vehicle_image/".$res_editTechProfile['vehicle_image']:'';
		$tech_image = $res_editTechProfile['tech_image']?$path."/tech_image/".$res_editTechProfile['tech_image']:'';
		
		$dataArray = array("First Name" => $res_editTechProfile['first_name'],"Middle Name" => $res_editTechProfile['middle_name'],"Last Name" => $res_editTechProfile['last_name'],"Email ID" => $res_editTechProfile['email'],"Contact Phone" => $res_editTechProfile['contact_phone'],"Alt Phone" => $res_editTechProfile['alt_phone'],"Address" => $res_editTechProfile['address'],"City" => $res_editTechProfile['city'] ,"State" => $res_editTechProfile['state_name'] ,"Zip Code" => $res_editTechProfile['zip_code'],"Date Of Birth" => $res_editTechProfile['date_of_birth'],"Company Name" => $res_editTechProfile['company_name'],"SSN#" => $res_editTechProfile['SSN'],"FEIN#" => $res_editTechProfile['FEIN'],"Vehicle Image" => $vehicle_image, "Driving License No" => $res_editTechProfile['driver_license_no'],"Tech Picture" => $tech_image,"Payble To" => $res_editTechProfile['payble_to']);	
		echo '{"data":'.json_encode($dataArray).'}';
	}
	############CONDITION FOR FORGOT PASSWORD#############
	if(isset($_REQUEST['method']) && $_REQUEST['method']=="changepassword" && $_SERVER['REQUEST_METHOD']=='GET'){
		//print_r(json_decode($_REQUEST['data']));exit;
		$jsonData=json_decode($_REQUEST['data']);
		//for sql injection
		$oldpassword = mysql_real_escape_string($jsonData->oldpassword);
		$newpassword = mysql_real_escape_string($jsonData->newpassword);
		$oldpassword = base64_encode(base64_encode($oldpassword));
		$newpassword = base64_encode(base64_encode($newpassword));
		$dbpassword=$dbf->getDataFromTable('technicians',"password","id='".$jsonData->userid."'");
		
		if($oldpassword == $dbpassword){
			$dbf->updateTable("technicians","password='$newpassword'","id='".$jsonData->userid."'");
			echo '{"Success":"true"}';exit;
		}else{
			echo '{"Success":"false"}';exit;
		}
	}
?>