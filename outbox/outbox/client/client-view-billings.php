<?php 
	ob_start();
	session_start();
	include_once '../includes/class.Main.php';
	//Object initialization
	$dbf = new User();
	//page titlevariable
	$pageTitle="Welcome To Out Of The Box";
	include 'applicationtop-client.php';
	//logout if user type is not client
	if($_SESSION['usertype']!='client'){
		header("location:../logout");exit;
	}
	//Fetch details from work_order table 
	$res_viewJobBoard=$dbf->fetchSingle("state st,clients c,service s,work_order w","c.state=st.state_code AND c.id=w.client_id AND w.service_id=s.id AND w.id='$_REQUEST[id]'");
?>
<link rel="stylesheet" href="../css/innermain.css" type="text/css" />
<link rel="stylesheet" href="../css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="../css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="../css/respmenu.css" type="text/css" />
<body>
    <div id="maindiv">
        <!-------------header--------------->
     	<?php include_once 'header-client.php';?>
   		<!-------------header--------------->
        <!-------------top menu--------------->
     	<?php include_once 'client-top-menu.php';?>
   		<!-------------top menu--------------->
         <div id="contentdiv">
                <!-------------Main Body--------------->
                <div class="rightcolumjobboard">
            		<div class="rightcoluminner">
                        <div class="headerbg">VIEW WORK ORDER BILL</div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        <!-----Table area start------->
                          <form name="viewBill" id="viewBill" action="" method="post">
                            <!-----Billing div start--------->
                            <div  class="divBilling">
                            <div class="greenText" align="left">Work Order Details</div>
                            <div  class="formtextaddbill">WO#:</div>
                            <div  class="textboxbillview"><?php echo $res_viewJobBoard['wo_no'];?></div>
                            <div  class="formtextaddbilllong">Order Status:</div>
                            <div  class="textboxbillview" style="font-weight:bold; color:#090;"><?php echo $res_viewJobBoard['work_status'];?></div>
                            <div class="spacer"></div>
                            <div  class="formtextaddbill">Service Name:</div>
                            <div  class="textboxbillview"><?php echo $res_viewJobBoard['service_name'];?></div>
                            <div  class="formtextaddbilllong">Customer Name:</div>
                            <div  class="textboxbillview"><?php echo $res_viewJobBoard['name'];?></div>
                            <div class="spacer"></div>
                            <div  class="formtextaddbill">Email ID:</div>
                            <div  class="textboxbillview"><?php echo $res_viewJobBoard['email'];?></div>
                            <div  class="formtextaddbilllong">Phone Number:</div>
                            <div  class="textboxbillview"><?php echo $res_viewJobBoard['phone_no'];?></div>
                            <div class="spacer"></div>
                            <div  class="formtextaddbill">State:</div>
                            <div  class="textboxbillview"><?php echo $res_viewJobBoard['state_name'];?></div>
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
							 <?php }?>
                              <div style="clear:both; height:5px;"></div>
                              <div id="subtotal">
                              <div class="blSubtotal">Subtotal:</div><div class="blSubtotalPrice">$ <?php echo number_format($subtotal,2);?></div>
                              </div>
                            </div>
                            <!-----billing div end--------->
                        	<div class="spacer"></div>
                            <div class="spacer"></div>
                            <div align="center">
                         	 <input type="button" class="buttonText" value="Return Back" onClick="window.location='client-workorder-billings'"/></div>
                          	<div class="spacer"></div>
                           </form>
                           <!-----Table area end------->
                    	</div>
            	</div>
               </div>
              <!-------------Main Body--------------->
         </div>
        <div class="spacer"></div>
        <?php include_once 'footer-client.php'; ?>
  </div>
</body>
</html>