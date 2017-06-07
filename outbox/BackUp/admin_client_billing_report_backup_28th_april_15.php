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
	$x = 0;
	if($_REQUEST['action']=='search' || $_GET["page"]){
	   $x=1; 
	}
?>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />
<link rel="stylesheet" href="css/no_more_table.css" type="text/css" />
<script type="text/javascript">
function Search_Records(){
	$("#SrchFrm").attr("action","admin_client_billing_report");
	$("#SrchFrm").submit();
}
function ClearFields(){
	$('#srchClient').val("");
	$('#srchStatus').val("");
	$('#FromDate').val("");
	$('#ToDate').val("");
	$('#hidaction').val("");
	//document.SrchFrm.submit();
	/*below line added to refreash page as to prevent url 
	mismatch problem in search using pagination.*/
	window.location.href="admin_client_billing_report";
}
//for exporting,print,pdf,word
function print_doc(val,page){
 if(val=='word'){
	$("#SrchFrm").attr("action","admin_client_billing_report_word");
	$("#SrchFrm").submit();
 }else if(val=='excell'){
	$("#SrchFrm").attr("action","admin_client_billing_report_excell");
	$("#SrchFrm").submit(); 
 }else if(val=='pdf'){
	$("#SrchFrm").attr("action","admin_client_billing_report_pdf");
	$("#SrchFrm").submit(); 
 }else if(val=='print'){
	$("#SrchFrm").attr("action","admin_client_billing_report_print?page="+page);
	$("#SrchFrm").attr("target","_blank");
	$("#SrchFrm").submit();
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
                        <div class="headerbg">
                            <div style="float:left;;">Client Billing Report</div>
                            <div style="float:right;">
                            <a href="javascript:void(0);" onClick="print_doc('word');"><img src="images/word2007.png" style="width:20px; height:20px;" title="Export to Word"/></a>
                            <a href="javascript:void(0);" onClick="print_doc('pdf');"><img src="images/pdf.png" style="width:20px; height:20px;" title="Export to PDF"/></a>
                            <a href="javascript:void(0);" onClick="print_doc('excell');"><img src="images/export_excel.png" style="width:20px; height:20px;" title="Export to Excel"></a>
                            <a href="javascript:void(0);"  onClick="print_doc('print','<?php echo (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);?>');" ><img src="images/print.png" alt="" style="width:20px; height:20px;" title="Print"></a>
                            </div>
                        </div>
                        <div id="contenttable">
                        	<div style="width:100%;float:left;">
                            <form name="SrchFrm" id="SrchFrm" action="" method="post">
                              <div style="margin-bottom:5px;" align="center">
                               <div  class="formtextaddsrch" align="center">Client</div>
                                <div class="textboxcsrch">
                                <select name="srchClient[]" id="srchClient" class="selectboxsrch" style="height:auto;" multiple="multiple">
                                    <option value="">--Select Client--</option>
                                    <?php foreach($dbf->fetchOrder("work_order w,clients cl","w.created_by=cl.id AND w.created_by <>'0'","cl.name ASC","","cl.name")as $client){?>
                                    <option value="<?php echo $client['id'];?>" <?php if($_REQUEST['srchClient']!=''){if(in_array($client['id'],$_REQUEST['srchClient'])){echo 'selected';}}?>><?php echo $client['name']?></option>
                                    <?php }?>
                                 </select>
                                </div>
                                <div  class="formtextaddsrch" align="center">Status</div>
                                <div class="textboxcsrch">
                                <select name="srchStatus" id="srchStatus" class="selectboxsrch">
                                    <option value="">--Select Status--</option>
                                    <option value="Completed" <?php if($_REQUEST['srchStatus']=='Completed'){echo 'selected';}?>> Completed </option>
                                    <option value="Ready to Invoice" <?php if($_REQUEST['srchStatus']=='Ready to Invoice'){echo 'selected';}?>>Ready to Invoice </option>
                                    <option value="Invoiced" <?php if($_REQUEST['srchStatus']=='Invoiced'){echo 'selected';}?>> Invoiced </option>
                                 </select>
                                </div>
                                <div  class="formtextaddsrch"align="center">From:</div>
                                <div class="textboxcsrch">
                                <input type="text" class="textboxsrch datepick" name="FromDate" id="FromDate" value="<?php echo $_REQUEST['FromDate'];?>" readonly></div>
                                <div  class="formtextaddsrch"align="center">To:</div>
                                <div class="textboxcsrch">
                                <input type="text" class="textboxsrch datepick" name="ToDate" id="ToDate" value="<?php echo $_REQUEST['ToDate'];?>" readonly></div>
                                <div style="float:left;padding-left:40px;">
                                <input type="hidden" name="action"  value="search">
                                <input type="hidden" name="hidaction"  value="<?php echo $x;?>">
                                <input type="button" class="buttonText2" name="SearchRecord" id="SearchRecord" value="Filter Report" onClick="Search_Records();">
                                <input type="button" class="buttonText2" name="Reset" value="Reset Filter" onClick="ClearFields();">
                               </div>
                              </div>
                          </form>
                          <div class="spacer"></div>
                          <?php
						        $sch="";
								$fromdt=date("Y-m-d",strtotime(($_REQUEST['FromDate'])));
								$todt=date("Y-m-d",strtotime(($_REQUEST['ToDate'])));
								//print "<pre>";print_r($_REQUEST);exit;
								if($_REQUEST['srchClient']!=''){
									//$sch=$sch."w.created_by='$_REQUEST[srchClient]' AND ";
									$creatorlist=implode(",",$_REQUEST['srchClient']);
									$sch=$sch."w.created_by IN($creatorlist) AND ";
								}
								if($_REQUEST['srchStatus']!=''){
									$sch=$sch."w.work_status='$_REQUEST[srchStatus]' AND ";
								}
								if($_REQUEST['FromDate']!='' && $_REQUEST['ToDate']==''){
									$sch=$sch."at.start_date >= '$fromdt' AND ";
								}
								if($_REQUEST['FromDate']=='' && $_REQUEST['ToDate']!=''){
									$sch=$sch."at.start_date <= '$todt' AND ";
								}
								if(($_REQUEST['FromDate']!='') && ($_REQUEST['ToDate']!='')){
									$sch=$sch."at.start_date BETWEEN '$fromdt' AND '$todt' AND ";
								}
							   $sch=substr($sch,0,-5);
							   //echo $sch;exit;
							   if($sch!=''){
								 $cond="c.id=w.client_id AND w.service_id=s.id AND w.approve_status='1' AND at.wo_no=w.wo_no AND at.tech_id=t.id AND ".$sch;
								 if($_REQUEST['srchStatus']==''){$cond.=" AND (w.work_status='Invoiced' OR w.work_status='Completed')";}
								 if($_REQUEST['srchClient']==''){$cond.=" AND w.created_by<>0";}
								  //echo $cond;exit;
							   }
							   elseif($sch==''){
								 $cond="c.id=w.client_id AND w.service_id=s.id AND  w.approve_status='1' AND (w.work_status='Invoiced' OR w.work_status='Completed') AND w.created_by<>0 AND at.wo_no=w.wo_no AND at.tech_id=t.id";
							   }
							   //print $cond;
							   //Pagination 
                                $page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
                                $page = ($page == 0 ? 1 : $page);
                                $perpage =100;//limit in each page
                                $startpoint = ($page * $perpage) - $perpage;
                                //-----------------------------------				
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
                                          <th width="10%" style="text-align:center">Price Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
									 	$grandtotal=0;
										if($_REQUEST['srchClient']!=''){
										foreach($_REQUEST['srchClient'] as $k => $val){
										$resArray=$dbf->fetchOrder("clients c,service s,technicians t,assign_tech at,work_order w",$cond."  AND w.created_by =$val ","w.id DESC LIMIT $startpoint,$perpage","c.name,s.service_name,at.start_date,t.id as techid,w.purchase_order_no, w.service_id, w.wo_no, w.client_id, w.created_by, w.id",""); ?>
                                        <tr>
                                        	<td colspan="8" style="padding:3px;"></td>
                                        </tr>
										<tr style="background:#E6E6E6;" >
                                        	<?php $clientnm=$dbf->fetchSingle("clients","id='$val'","name ASC","","cl.name");?>	
											<td colspan="8" style="text-align:center;"><b><?php echo $clientnm['name'];?></b></td>
										</tr>
							    	<?php 
										$restotal=0;
										foreach($resArray as $key=>$res_clientBill) { 
										
									    //check for payment completed work orders
										$paymentstatus = $dbf->getDataFromTable("work_order_bill","payment_status","wo_no='$res_clientBill[wo_no]'");
											if($paymentstatus <>'Completed'){
												$subtotal=0;
												//fetch work type, model and total price of work order
												$workTypeArray =array(); $modelArray =array();
												//echo $res_clientBill[id];
												$res_woservice = $dbf->fetch("equipment e,work_type wt,workorder_service ws","e.id=ws.equipment AND wt.id=ws.work_type AND ws.service_id='$res_clientBill[service_id]' AND ws.workorder_id='$res_clientBill[id]'");
												//print "<pre>";print_r($res_woservice);exit;
												foreach($res_woservice as $resServicePrice){
													$total = ($resServicePrice['quantity']*$resServicePrice['outbox_price']);
													$subtotal = $subtotal+$total;
													array_push($workTypeArray,$resServicePrice['worktype']);
													array_push($modelArray,$resServicePrice['model']);
												}
												$restotal = $restotal+$subtotal;
												$grandtotal=$grandtotal+$subtotal;
												//print_r($workTypeArray);
												$workType= !empty($workTypeArray) ? implode(", ",$workTypeArray):'';
												$model = !empty($modelArray) ? implode(", ",$modelArray):'';
												
										?>
                                        <tr>
                                            <td data-title="Date Scheduled" class="coltext"><?php if($res_clientBill['start_date']<>'0000-00-00'){echo date("d-M-Y",strtotime($res_clientBill['start_date']));}else{echo "00-00-0000";}?></td>
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
                                         
                                        <tr>
                                            <td colspan="6" style="text-align:center;"><?php 
												if(empty($resArray)){
													echo "No record found";	
												}
											?></td>
                                            <td style="text-align:right;">Sub Total:</td>
                                            <td style="text-align:center;">$ <?php echo number_format($restotal,2);?></td>
                                            	
                                        </tr>
                                        <?php } 
										}else{
											
										$resArray=$dbf->fetchOrder("clients c,service s,technicians t,assign_tech at,work_order w",$cond,"w.id DESC LIMIT $startpoint,$perpage","c.name,s.service_name,at.start_date,t.id as techid,w.purchase_order_no, w.service_id, w.wo_no, w.client_id, w.created_by, w.id",""); 
										foreach($resArray as $key=>$res_clientBill) { 
									    //check for payment completed work orders
										$paymentstatus = $dbf->getDataFromTable("work_order_bill","payment_status","wo_no='$res_clientBill[wo_no]'");
											if($paymentstatus <>'Completed'){
												$subtotal=0;
												//fetch work type, model and total price of work order
												$workTypeArray =array(); $modelArray =array();
												//echo $res_clientBill[id];
												$res_woservice = $dbf->fetch("equipment e,work_type wt,workorder_service ws","e.id=ws.equipment AND wt.id=ws.work_type AND ws.service_id='$res_clientBill[service_id]' AND ws.workorder_id='$res_clientBill[id]'");
												
												foreach($res_woservice as $resServicePrice){
													$total = ($resServicePrice['quantity']*$resServicePrice['outbox_price']);												$subtotal = $subtotal+$total;
													array_push($workTypeArray,$resServicePrice['worktype']);
													array_push($modelArray,$resServicePrice['model']);
												}
												$grandtotal=$grandtotal+$subtotal;
												//print_r($workTypeArray);
												$workType= !empty($workTypeArray) ? implode(", ",$workTypeArray):'';
												$model = !empty($modelArray) ? implode(", ",$modelArray):'';
												
										?>
                                        <tr>
                                            <td data-title="Date Scheduled" class="coltext"><?php if($res_clientBill['start_date']<>'0000-00-00'){echo date("d-M-Y",strtotime($res_clientBill['start_date']));}else{echo "00-00-0000";}?></td>
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
                                         
                                        <?php 
										}?>
                                        
                                        <tr style="">
                                            <td colspan="6"></td>
                                            <td style="text-align:right;font-family: Tahoma,Geneva,sans-serif;font-size: 12px;color: #090;font-weight: bold;">Grand Total:</td>
                                            <td style="text-align:center;font-family: Tahoma,Geneva,sans-serif;font-size: 12px;color: #090;font-weight: bold;">$ <?php echo number_format($grandtotal,2);?></td>
                                            	
                                        </tr>
                                        
                                   </tbody>
                               </table>
                               <!-----Table area end------->
                          
                           <?php }else{?>
                              <div style="padding-left:40%;border:1px solid #000;color:#F00;">No records founds!!</div>
                            <?php }?>
                            <div  align="center"><?php if($num>0) { echo $dbf->Pages($num,$perpage,"admin_client_billing_report?srchClient=$_REQUEST[srchClient]&srchStatus=$_REQUEST[srchStatus]&FromDate=$_REQUEST[FromDate]&ToDate=$_REQUEST[ToDate]&");}?></div>
                          </div>
                        </div>
                        <div class="spacer"></div>
                    </div>
            	</div>
              <!-------------Main Body--------------->
         </div>
        <div class="spacer"></div>
        <?php include_once 'footer.php'; ?>
    </div>
</body>
</html>