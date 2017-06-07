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
//print "<pre>";
//print_r($_REQUEST);//exit;
if(isset($_REQUEST['action']) && $_REQUEST['action']=='generatebill'){
	$implode_workorders = $_REQUEST['wonos'];
	//Fetch details from work_order table 
	$res_viewClient=$dbf->fetchSingle("state st,clients c","c.state=st.state_code AND c.id='$_REQUEST[cid]'");
} 
######################EMAIL SENDING START ################################## 
if(isset($_REQUEST['action']) && $_REQUEST['action']=='payment'){
	    //Get Purchase Order 
		$purchase_order=$dbf->getDataFromTable("work_order","purchase_order_no","wo_no='$_REQUEST[wonos]'");
		//email sending to COD Client
		$res_template=$dbf->fetchSingle("email_template","id=15");
		//fetch client details
		$resClient=$dbf->fetchSingle("clients","id='$_REQUEST[cid]'");
		$codname=$resClient['name'];
		$toemail=$resClient['email'];
		//$subject=$res_template['subject']
		$subject=$res_template['subject']."==".$codname;
		$input=$res_template['message'];
		//fetch admin data
		$res_admin=$dbf->fetchSingle("admin","id='1'");
		$from=$res_admin['email'];
		$from_name=$res_admin['name'];
		
		$Url=$res_admin['site_url'];
		$activateUrl='<a href="'.$Url.'" target="_blank">Click Here</a>';
		
		$email_body=str_replace(array('%ClientName%','%BillPeriod%','%WoNo%','%PurchaseOrder%','%Subtotal%','%ActivationLink%'),array($codname,$_REQUEST['billperiod'],$_REQUEST['wonos'],$purchase_order,number_format($_REQUEST['amount'],2),$activateUrl),$input);
		$headers = "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=UTF-8\n";
		$headers .= "From:".$from_name." <".$from.">\n";
		//echo $email_body;exit;
		@mail($toemail,$subject,$email_body,$headers);
		//email sending ends here
		header("Location:manage-client-billings");exit;
}
######################EMAIL SENDING END ################################## 						 
?>
<body>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />
<link rel="stylesheet" href="css/no_more_table.css" type="text/css" />
<script type="text/javascript">
//for exporting,print,pdf,word
function print_doc(val){
 if(val=='print'){
	var x = confirm("If you send the invoice manually,\nthen the payment status will update automatically in the system.");
	if(x){
		$("#frmClient").attr("action","admin-client-billings-invoice-print");
		$("#frmClient").attr("target","_blank");
		$("#frmClient").submit();
	}
 }
}
function sendClient(){
	$("#frmClient").attr("action","admin-client-billings");
	$("#frmClient").submit();
}
</script>
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
                        <div class="headerbg">
                        <div style="float:left;;">VIEW WORK ORDER BILL</div>
                        <div style="float:right;"><img src="images/print.png" alt="Pinter" style="width:20px;height:20px;cursor:pointer;" title="Print Invoice" onClick="print_doc('print');"></div>
                        </div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        <!-----Table area start------->
                         <!-----Billing div start--------->
                          <div  class="divBilling">
                            <div class="greenText" align="center">Total Work Order Bill For Period <?php echo $_REQUEST['billperiod'];?></div>
                            <div  class="formtextaddbill">Client Name:</div>
                            <div  class="textboxbillview"><?php echo $res_viewClient['name'];?></div>
                            <div  class="formtextaddbilllong">Email ID:</div>
                            <div  class="textboxbillview"><?php echo $res_viewClient['email'];?></div>
                            <div class="spacer"></div>
                            <div  class="formtextaddbill">Phone Number:</div>
                            <div  class="textboxbillview"><?php echo $res_viewClient['phone_no'];?></div>
                            <div  class="formtextaddbilllong">State:</div>
                            <div  class="textboxbillview"><?php echo $res_viewClient['state_name'];?></div>
                            <div class="spacer"></div>
                            <div  class="formtextaddbill">Order Status:</div>
                            <div  class="textboxbillview">Completed</div>
                            <div  class="formtextaddbilllong">Bill Period :</div>
                            <div  class="textboxbillview"><b><?php echo $_REQUEST['billperiod'];?></b></div>
                           <div class="spacer" style="height:12px;"></div>
                         </div>
                         <!-----billing div end--------->
                         <!-----billing div start--------->
                         <div  class="divBilling">
						<?php
						 $cond="c.id=w.client_id AND w.service_id=s.id AND w.approve_status='1' AND w.work_status='Invoiced' AND w.created_by<>0 AND at.wo_no=w.wo_no AND at.tech_id=t.id AND FIND_IN_SET(w.wo_no,'$implode_workorders')";
						 //echo $cond;
                         $num=$dbf->countRows("clients c,service s,technicians t,assign_tech at,work_order w",$cond); 
                          if($num>0){
                         ?>
                          <!-----Table area start------->
                            <table id="no-more-tables" class="draggable">
                                <thead>
                                    <tr>
                                      <th width="10%">Date Scheduled</th>
                                      <th width="15%">Service</th>
                                      <th width="15%">Work Type</th>
                                      <th width="15%">Model</th>
                                      <th width="15%">Customer Name</th>
                                      <th width="10%">WO#</th>
                                      <th width="10%">Purchase Order#</th>
                                      <th width="10%">Price Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                    $grandtotal=0;
                                    $resArray=$dbf->fetchOrder("clients c,service s,technicians t,assign_tech at,work_order w",$cond,"w.id DESC","c.name,s.service_name,at.start_date,t.id as techid,w.purchase_order_no, w.service_id, w.wo_no, w.client_id, w.created_by, w.id","");
                                    //print "<pre>";
                                    //print_r($resArray);
                                    foreach($resArray as $key=>$res_clientBill) { 
                                    //check for payment completed work orders
                                    $paymentstatus = $dbf->getDataFromTable("work_order_bill","payment_status","wo_no='$res_clientBill[wo_no]'");
                                        if($paymentstatus <>'Completed'){
                                            $subtotal=0;
                                            //fetch work type, model and total price of work order
                                            $workTypeArray =array(); $modelArray =array();
                                            //echo $res_clientBill[id];
                                            $res_woservice = $dbf->fetch("equipment e,work_type wt,workorder_service ws","e.id=ws.equipment AND wt.id=ws.work_type AND ws.service_id='$res_clientBill[service_id]' AND ws.workorder_id='$res_clientBill[id]'");
                                            //print_r($res_woservice);
                                            foreach($res_woservice as $resServicePrice){
                                                $total = ($resServicePrice['quantity']*$resServicePrice['outbox_price']);
                                                $subtotal = $subtotal+$total; 
                                                array_push($workTypeArray,$resServicePrice['worktype']);
                                                array_push($modelArray,$resServicePrice['model']);
                                            }
                                            $grandtotal=$grandtotal+$subtotal;
                                            //print_r($workTypeArray);
                                            $workType= !empty($workTypeArray) ? implode(", ",$workTypeArray):'';
                                            $model = !empty($modelArray) ? implode(", ",$modelArray):'';
                                            
                                    ?>
                                    <tr>
                                        <td data-title="Date Scheduled" class="coltext"><?php echo date("d-M-Y",strtotime($res_clientBill['start_date']));?></td>
                                        <td data-title="Service" class="coltext"><?php echo $res_clientBill['service_name'];?></td>
                                        <td data-title="Work Type"><?php echo $workType;?></td>
                                        <td data-title="Model"><?php echo $model;?></td>
                                        <td data-title="Customer Name"><?php echo $res_clientBill['name'];?></td>
                                        <td data-title="WO#"><?php echo $res_clientBill['wo_no'];?></td>
                                        <td data-title="Purchase Order#"><?php echo $res_clientBill['purchase_order_no'];?></td>
                                        <td data-title="Price Amount" style="text-align:center;">$ <?php echo number_format($subtotal,2);?></td>
                                            
                                    </tr>
                                    <?php 
                                        //insert into work order bill table
                                         $numcount = $dbf->countRows("work_order_bill","wo_no='$res_clientBill[wo_no]'");
                                         if($numcount==0){
                                             $string = "wo_no='$res_clientBill[wo_no]', client_id='$res_clientBill[client_id]', created_by='$res_clientBill[created_by]', tech_id='$res_clientBill[techid]', subtotal='$subtotal', payment_status='Pending', created_date=now()";
                                             $dbf->insertSet("work_order_bill",$string);
                                         }else{
                                             $dbf->updateTable("work_order_bill","subtotal='$subtotal',client_id='$res_clientBill[client_id]',updated_date=now()","wo_no='$res_clientBill[wo_no]'");
                                         }
                                        }
                                     }?>
                               </tbody>
                           </table>
                           <!-----Table area end------->
                           <div id="subtotal">
                              <div  class="reportSubtotal">Grand Total:</div><div class="reportPrice">$ <?php echo number_format($grandtotal,2);?></div>
                           </div>
                        <?php }else{?>
                          <div style="padding-left:40%;border:1px solid #000;color:#F00;">No records founds!!</div>
                         <?php }?>
                         </div>
                         <!-----billing div end--------->
                        <div class="spacer"></div>
                        <div class="spacer"></div>
                        <form name="frmClient" id="frmClient" action="" method="post">
                        <input type="hidden" name="action" value="payment">
                        <input type="hidden" name="amount" value="<?php echo $grandtotal;?>"/>
                        <input type="hidden" name="cid" value="<?php echo $_REQUEST['cid'];?>"/>
                        <input type="hidden" name="billperiod"  value="<?php echo $_REQUEST['billperiod'];?>"/>
                        <input type="hidden" name="wonos"  value="<?php echo $_REQUEST['wonos'];?>"/>
                        <div align="center">
                        <input type="button" class="buttonText" value="Return Back" onClick="window.location='manage-client-billings'"/> 
                        <input type="button" class="buttonText" value="Send To Client" onClick="sendClient();"/>
                        <input type="button" class="buttonText" value="Print Invoice" onClick="print_doc('print');"/>
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