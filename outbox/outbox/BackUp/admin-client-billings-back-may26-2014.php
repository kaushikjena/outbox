<?php 
ob_start();
session_start();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();
//page titlevariable
$pageTitle="Welcome To Out Of The Box";
include 'applicationtop.php';
//logout for users other than admin and user
if($_SESSION['usertype']!='admin' && $_SESSION['usertype']!='user'){
	header("location:logout");exit;
}
//Fetch details from work_order table 
$res_viewJobBoard=$dbf->fetchSingle("state st,clients c,service s,work_order w","c.state=st.state_code AND c.id=w.client_id AND w.service_id=s.id AND w.id='$_REQUEST[id]'");
//technician details
$resTech = $dbf->fetchSingle("assign_tech at,technicians tc","at.tech_id=tc.id AND at.wo_no='$res_viewJobBoard[wo_no]'");
//technician workorder details
$resTechDetails = $dbf->fetchSingle("work_order_tech","tech_id='$resTech[id]' AND wo_no='$res_viewJobBoard[wo_no]' ORDER BY id DESC LIMIT 1");
//check for payment completed work orders
$paymentstatus = $dbf->getDataFromTable("work_order_bill","payment_status","wo_no='$res_viewJobBoard[wo_no]'"); 
######################EMAIL SENDING START ################################## 
if($_REQUEST['action']=='payment'){
		//email sending to COD Client
		$res_template=$dbf->fetchSingle("email_template","id=15");
		$subject=$res_template['subject'];
		$input=$res_template['message'];
		//fetch client details
		$resClient=$dbf->fetchSingle("clients","id='$res_viewJobBoard[created_by]'");
		$codname=$resClient['name'];
		$toemail=$resClient['email'];
		$servname=$res_viewJobBoard['service_name'];
		$compdate=!empty($resTechDetails)?date("d-M-Y",strtotime($resTechDetails['arrival_date'])):'';
		
		$techname=$resTech['first_name'].'&nbsp;'.$resTech['middle_name'].'&nbsp;'.$resTech['last_name'];
		//fetch admin data
		$res_admin=$dbf->fetchSingle("admin","id='1'");
		$from=$res_admin['email'];
		$from_name=$res_admin['name'];
		
		$Url=$res_admin['site_url'];
		$activateUrl='<a href="'.$Url.'" target="_blank">Click Here</a>';
		
		$email_body=str_replace(array('%ClientName%','%WoNo%','%ServiceName%','%CompletedDate%','%TechnicianName%','%Subtotal%','%ActivationLink%'),array($codname,$res_viewJobBoard['wo_no'],$servname,$compdate,$techname,number_format($_REQUEST['amount'],2),$activateUrl),$input);
		$headers = "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=UTF-8\n";
		$headers .= "From:".$from_name." <".$from.">\n";
		//echo $email_body;exit;
		@mail($toemail,$subject,$email_body,$headers);
		//email sending ends here
}
######################EMAIL SENDING END ################################## 						 
?>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />
<body>
    <div id="maindiv">
        <!-------------header--------------->
     	<?php include_once 'header.php';?>
   		<!-------------header--------------->
        
        <!-------------top menu--------------->
     	<?php include_once 'top-menu.php';?>
   		<!-------------top menu--------------->
         <div id="contentdiv">
                <!-------------Main Body--------------->
                <div class="rightcolumjobboard">
            		<div class="rightcoluminner">
                        <div class="headerbg">VIEW WORK ORDER BILL</div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        <!-----Table area start------->
                            <!-----Billing div start--------->
                            <div  class="divBilling">
                            <div class="greenText" align="left">Work Order Details</div>
                            <div  class="formtextaddbill">WO#:</div>
                            <div  class="textboxbillview" style="font-weight:bold; color:#090;"><?php echo $res_viewJobBoard['wo_no'];?></div>
                            <div  class="formtextaddbilllong">Work Status:</div>
                            <div  class="textboxbillview"><?php echo $res_viewJobBoard['work_status'];?></div>
                            <div class="spacer"></div>
                            <div  class="formtextaddbill">Job Status:</div>
                            <div  class="textboxbillview"><?php echo $res_viewJobBoard['job_status'];?></div>
                            <div  class="formtextaddbilllong">Service Name:</div>
                            <div  class="textboxbillview"><?php echo $res_viewJobBoard['service_name'];?></div>
                            <div class="spacer"></div>
                            <div  class="formtextaddbill">Customer Name:</div>
                            <div  class="textboxbillview"><?php echo $res_viewJobBoard['name'];?></div>
                            <div  class="formtextaddbilllong">Email ID:</div>
                            <div  class="textboxbillview"><?php echo $res_viewJobBoard['email'];?></div>
                            <div class="spacer"></div>
                            <div  class="formtextaddbill">Phone Number:</div>
                            <div  class="textboxbillview"><?php echo $res_viewJobBoard['phone_no'];?></div>
                            <div  class="formtextaddbilllong">State:</div>
                            <div  class="textboxbillview"><?php echo $res_viewJobBoard['state_name'];?></div>
                            <div class="spacer"></div>
                            <div  class="formtextaddbill">Technician:</div>
                            <div  class="textboxbillview"> 
							<?php echo $resTech['first_name'].'&nbsp;'.$resTech['middle_name'].'&nbsp;'.$resTech['last_name'];?></div>
                            <div  class="formtextaddbilllong">Completed Date:</div>
                            <div  class="textboxbillview">
                            <?php if(!empty($resTechDetails)){echo date("d-M-Y",strtotime($resTechDetails['arrival_date'])).' &nbsp '.$resTechDetails['depart_time'];}?>
                            </div>
                           <div class="spacer" style="height:12px;"></div>
                         </div>
                         	<!-----billing div end--------->
                            <!-----billing div start--------->
                          	<div  class="divBilling">
                            <div class="noRecords" align="center">Work Order Bill</div>
                             <div>
                                  <div align="left" class="jobheader blWorkType">WorkType</div>
                                  <div align="left" class="jobheader blEquipment">Equipment</div>
                                  <div align="left" class="jobheader blModel">Model</div>
                                  <div align="center" class="jobheader blQunt">Qnty</div>
                                  <div align="center" class="jobheader blQunt">Price</div>
                                  <div align="center" class="jobheader blQunt">Total</div>
                                  <div style="clear:both;"></div>
                             </div>
                             <div style="clear:both; border:dashed 1px #ccc;"></div>
                             <?php $subtotal=0;
							 	$res_woservice = $dbf->fetch("equipment e,work_type wt,workorder_service ws","e.id=ws.equipment AND wt.id=ws.work_type AND ws.service_id='$res_viewJobBoard[service_id]' AND ws.workorder_id='$_REQUEST[id]'");
							 	foreach($res_woservice as $resServicePrice){
								$total = ($resServicePrice['quantity']*$resServicePrice['outbox_price']);
								$subtotal = $subtotal+$total; 
							  ?>
                                  <div align="left" class="billbody blWorkType"><?php echo $resServicePrice['worktype'];?></div>
                                  <div align="left" class="billbody blEquipment"><?php echo $resServicePrice['equipment_name'];?></div>
                                  <div align="left" class="billbody blModel"><?php echo $resServicePrice['model'];?></div>
                                  <div align="center" class="billbody blQunt"><?php echo $resServicePrice['quantity'];?></div>
                                  <div align="center" class="billbody blQunt">$ <?php echo $resServicePrice['outbox_price'];?></div>
                                  <div align="center" class="billbody blQunt">$ <?php echo number_format($total,2);?></div>
                                  <div style="clear:both; height:5px;"></div>
								<div style="clear:both; border:dashed 1px #ccc;"></div>
							 <?php }
							 //insert into work order bill table
							 $numcount = $dbf->countRows("work_order_bill","wo_no='$res_viewJobBoard[wo_no]'");
							 if($numcount==0){
								 $string = "wo_no='$res_viewJobBoard[wo_no]', client_id='$res_viewJobBoard[client_id]', created_by='$res_viewJobBoard[created_by]',tech_id='$resTech[id]',subtotal='$subtotal',payment_status='Pending',created_date=now()";
								 $dbf->insertSet("work_order_bill",$string);
							 }else{
								 $dbf->updateTable("work_order_bill","subtotal='$subtotal'","wo_no='$res_viewJobBoard[wo_no]' AND client_id='$res_viewJobBoard[client_id]' AND created_by='$res_viewJobBoard[created_by]' AND tech_id='$resTech[id]'");
							 }
							 ?>
                              <div style="clear:both; height:5px;"></div>
                              <div id="subtotal">
                              <div class="blSubtotal">Subtotal:</div><div class="blSubtotalPrice">$ <?php echo number_format($subtotal,2);?></div>
                              </div>
                            </div>
                            <!-----billing div end--------->
                        	<div class="spacer"></div>
                            <div class="spacer"></div>
                            <form name="frmClient" id="frmClient" action="" method="post">
                          	<input type="hidden" name="action" value="payment">
                            <input type="hidden" name="amount" value="<?php echo $subtotal?>"/>
                            <div align="center">
                         	 <input type="button" class="buttonText" value="Return Back" onClick="window.location='manage-client-billings'"/> 
                             <input type="submit" class="buttonText" value="Send To Client"/>
                             </div>
                             </form>
                          	<div class="spacer"></div>
                           <!-----Table area end------->
                    	</div>
            	</div>
               </div>
              <!-------------Main Body--------------->
         </div>
        <div class="spacer"></div>
        <?php include_once 'footer.php'; ?>
  </div>
</body>
</html>