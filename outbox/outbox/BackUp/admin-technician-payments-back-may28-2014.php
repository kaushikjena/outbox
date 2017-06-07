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
$resTechDetails = $dbf->fetchSingle("work_order_tech","tech_id='$resTech[id]' AND wo_no='$res_viewJobBoard[wo_no]' ORDER BY id DESC");
	if($resTech['pay_grade']=='A'){
	 	$techgrade= 'Grade A';
	}elseif($resTech['pay_grade']=='B'){
		 $techgrade= 'Grade B';
	}elseif($resTech['pay_grade']=='C'){
	 	$techgrade= 'Grade C';
	}elseif($resTech['pay_grade']=='D'){
	 	$techgrade= 'Grade D';
	}
?>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />
<script type="text/javascript">
function print_doc(val,woid){
 var r = confirm('Are you sure to pay technician now?');
 if(r){
	 if(val=='pdf'){
		 document.createJob.action="admin_technician_payment_pdf?id="+woid;
		 document.createJob.submit();
		 
	 }else if(val=='print'){
		 document.createJob.action="admin_technician_payment_print.php?id="+woid;
		 document.createJob.target="_blank";
		 document.createJob.submit();
	 }
 }
}
</script>
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
                        <div class="headerbg"><div style="float:left;">TECHNICIAN WORK ORDER PAYMENT</div>
                        	<div style="float:right;padding-right:10px;"></div>
                        </div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        <!-----Table area start------->
                          <form name="createJob" id="createJob" action="" method="post">
                          <input type="hidden" name="wono"  value="<?php echo $res_viewJobBoard['wo_no'];?>">
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
                            <?php if(!empty($resTech)){echo date("d-M-Y",strtotime($resTechDetails['arrival_date'])).' &nbsp '.$resTechDetails['depart_time'];}?>
                            </div>
                            <div  class="formtextaddbill">PayGrade:</div>
                            <div  class="textboxbillview"><?php echo $techgrade;?></div>
                            <div  class="formtextaddbill">Payble To:</div>
                            <div  class="textboxbillview"><?php echo $resTech['payble_to'];?></div>
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
							 	$res_woservice = $dbf->fetch("equipment e,work_type wt,workorder_service ws","e.id=ws.equipment AND wt.id=ws.work_type AND ws.service_id='$res_viewJobBoard[service_id]' AND workorder_id='$_REQUEST[id]'");
							    foreach($res_woservice as $resServicePrice){
									if($resTech['pay_grade']=='A'){
										$price=$resServicePrice['gradeA_price'];
									}elseif($resTech['pay_grade']=='B'){
										$price=$resServicePrice['gradeB_price'];
									}elseif($resTech['pay_grade']=='C'){
										$price=$resServicePrice['gradeC_price'];
									}elseif($resTech['pay_grade']=='D'){
										$price=$resServicePrice['gradeD_price'];
									}
									$total = ($resServicePrice['quantity']*$price);
									$subtotal = $subtotal+$total; 
						  ?>
								  <div align="left" class="billbody blWorkType"><?php echo $resServicePrice['worktype'];?></div>
								  <div align="left" class="billbody blEquipment"><?php echo $resServicePrice['equipment_name'];?></div>
								  <div align="left" class="billbody blModel"><?php echo $resServicePrice['model'];?></div>
								  <div align="center" class="billbody blQunt"><?php echo $resServicePrice['quantity'];?></div>
								  <div align="center" class="billbody blQunt">$ <?php echo $price;?></div>
								  <div align="center" class="billbody blQunt">$ <?php echo number_format($total,2);?></div>
								  <div style="clear:both; height:5px;"></div>
								  <div style="clear:both; border:dashed 1px #ccc;"></div>
							 <?php }
							 //insert into work order bill table
							 $numcount = $dbf->countRows("work_order_tech_bill","wo_no='$res_viewJobBoard[wo_no]'");
							 if($numcount==0){
								 $string = "wo_no='$res_viewJobBoard[wo_no]', client_id='$res_viewJobBoard[client_id]', created_by='$res_viewJobBoard[created_by]', tech_id='$resTech[id]', subtotal='$subtotal', payment_status='Pending', payment_date='',created_date=now()";
								 $dbf->insertSet("work_order_tech_bill",$string);
							 }else{
								 $dbf->updateTable("work_order_tech_bill","subtotal='$subtotal'","wo_no='$res_viewJobBoard[wo_no]' AND client_id='$res_viewJobBoard[client_id]' AND created_by='$res_viewJobBoard[created_by]' AND tech_id='$resTech[id]'"); 
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
                            <div align="center">
                         	 <input type="button" class="buttonText" value="Return Back" onClick="window.location='manage-technician-payments'"/>
                            <input type="button" class="buttonText" value="Payment Offline" onClick="print_doc('pdf','<?php echo $res_viewJobBoard['id'];?>');"/>
                             <input type="button" class="buttonText" value="Print" onClick="print_doc('print','<?php echo $res_viewJobBoard['id'];?>');"/>
                             </div>
                          	<div class="spacer"></div>
                           </form>
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