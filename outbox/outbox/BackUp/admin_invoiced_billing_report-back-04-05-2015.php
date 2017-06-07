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
	$("#SrchFrm").attr("action","admin_invoiced_billing_report");
	$("#SrchFrm").submit();
}
function ClearFields(){
	$('#srchClient').val("");
	$('#FromDate').val("");
	$('#ToDate').val("");
	$('#hidaction').val("");
	//document.SrchFrm.submit();
	/*below line added to refreash page as to prevent url 
	mismatch problem in search using pagination.*/
	window.location.href="admin_invoiced_billing_report";
}
//for exporting,print,pdf,word
function print_doc(val,page){
 if(val=='word'){
	$("#SrchFrm").attr("action","admin_invoiced_billing_report_word");
	$("#SrchFrm").submit();
 }else if(val=='excell'){
	$("#SrchFrm").attr("action","admin_invoiced_billing_report_excell");
	$("#SrchFrm").submit(); 
 }else if(val=='pdf'){
	$("#SrchFrm").attr("action","admin_invoiced_billing_report_pdf");
	$("#SrchFrm").submit(); 
 }else if(val=='print'){
	$("#SrchFrm").attr("action","admin_invoiced_billing_report_print?page="+page);
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
                            <div style="float:left;;">Invoiced Order Report</div>
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
                                <select name="srchClient" id="srchClient" class="selectboxsrch">
                                    <option value="">--Select Client--</option>
                                    <?php foreach($dbf->fetchOrder("work_order w,clients cl","w.created_by=cl.id AND w.created_by <>'0'","cl.name ASC","","cl.name")as $client){?>
                                    <option value="<?php echo $client['id'];?>" <?php if($client['id']==$_REQUEST['srchClient']){echo 'selected';}?>><?php echo $client['name']?></option>
                                    <?php }?>
                                 </select>
                                </div>
                                <div  class="formtextaddsrch"align="center">From:</div>
                                <div class="textboxcsrch">
                                <input type="text" class="textboxsrch datepick" name="FromDate" id="FromDate" value="<?php echo $_REQUEST['FromDate'];?>" readonly></div>
                                <div  class="formtextaddsrch"align="center">To:</div>
                                <div class="textboxcsrch">
                                <input type="text" class="textboxsrch datepick" name="ToDate" id="ToDate" value="<?php echo $_REQUEST['ToDate'];?>" readonly></div>
                                <div style="float:left;padding:5px;">(Plz choose Invoiced date)</div>
                                <div style="float:left;">
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
								
								if($_REQUEST['srchClient']!=''){
									$sch=$sch."w.created_by='$_REQUEST[srchClient]' AND ";
								}
								if($_REQUEST['FromDate']!='' && $_REQUEST['ToDate']==''){
									$sch=$sch."w.invoiced_date >= '$fromdt' AND ";
								}
								if($_REQUEST['FromDate']=='' && $_REQUEST['ToDate']!=''){
									$sch=$sch."w.invoiced_date <= '$todt' AND ";
								}
								if(($_REQUEST['FromDate']!='') && ($_REQUEST['ToDate']!='')){
									$sch=$sch."w.invoiced_date BETWEEN '$fromdt' AND '$todt' AND ";
								}
							   $sch=substr($sch,0,-5);
							   if($sch!=''){
								 $cond="c.id=w.client_id AND w.service_id=s.id AND w.approve_status='1' AND w.work_status='Invoiced' AND at.wo_no=w.wo_no AND at.tech_id=t.id AND ".$sch;
							   }elseif($sch==''){
								 $cond="c.id=w.client_id AND w.service_id=s.id AND  w.approve_status='1' AND w.work_status='Invoiced' AND at.wo_no=w.wo_no AND at.tech_id=t.id";
							   }
							   //print $cond;
							   //Pagination 
                                $page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
                                $page = ($page == 0 ? 1 : $page);
                                $perpage =100;//limit in each page
                                $startpoint = ($page * $perpage) - $perpage;
                                //-----------------------------------	
								$countqry = "SELECT c.name,s.service_name,at.start_date,w.invoiced_date,w.purchase_order_no, w.service_id, w.wo_no, w.client_id, w.created_by, w.id ,wb.payment_status,wt.payment_status as tpayment_status FROM clients c,service s,technicians t,assign_tech at,work_order w LEFT JOIN work_order_bill wb ON w.wo_no=wb.wo_no LEFT JOIN work_order_tech_bill wt ON w.wo_no=wt.wo_no WHERE ".$cond." AND ((wb.payment_status <>'Completed' OR wb.payment_status is NULL) OR (wt.payment_status <>'Completed' OR wt.payment_status is NULL))";
								$resCount=$dbf->simpleQuery($countqry);
								
								$qry = "SELECT c.name,s.service_name,at.start_date,t.pay_grade, w.invoiced_date, w.purchase_order_no, w.service_id, w.wo_no, w.client_id, w.created_by, w.id ,wb.payment_status,wt.payment_status as tpayment_status FROM clients c,service s,technicians t,assign_tech at,work_order w LEFT JOIN work_order_bill wb ON w.wo_no=wb.wo_no LEFT JOIN work_order_tech_bill wt ON w.wo_no=wt.wo_no WHERE ".$cond." AND ((wb.payment_status <>'Completed' OR wb.payment_status is NULL) OR (wt.payment_status <>'Completed' OR wt.payment_status is NULL)) order by w.id DESC LIMIT $startpoint,$perpage";
								$resArray=$dbf->simpleQuery($qry);			
                                $num=count($resCount);
								if($num>0){
							   ?>
                           	  <!-----Table area start------->
                           		<table id="no-more-tables" class="draggable">
                                    <thead>
                                        <tr>
                                          <th width="10%">Date Scheduled</th>
                                          <th width="10%">Date Invoiced</th>
                                          <th width="10%">Service</th>
                                          <th width="10%">Work Type</th>
                                          <th width="12%">Model</th>
                                          <th width="12%">Customer Name</th>
                                          <th width="10%">WO#</th>
                                          <th width="10%">PO#</th>
                                          <th width="8%">Client Price</th>
                                          <th width="8%">Tech Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
									 	$grandtotal=0; $techgrandtotal =0;
										//print "<pre>";
										//print_r($resArray);
										foreach($resArray as $key=>$res_clientBill) { 
										//check for payment completed work orders
											$subtotal=0; $techsubtotal=0;
											//fetch work type, model and total price of work order
											$workTypeArray =array(); $modelArray =array();
											//echo $res_clientBill[id];
											$res_woservice = $dbf->fetch("equipment e,work_type wt,workorder_service ws","e.id=ws.equipment AND wt.id=ws.work_type AND ws.service_id='$res_clientBill[service_id]' AND ws.workorder_id='$res_clientBill[id]'");
											//print_r($res_woservice);
											foreach($res_woservice as $resServicePrice){
												$total = ($resServicePrice['quantity']*$resServicePrice['outbox_price']);
												$subtotal = $subtotal+$total; 
												$price=$resServicePrice['tech_price'];
												//$techtotal = ($resServicePrice['quantity']*$price);//commentd on nov24 2014
												$techsubtotal = $techsubtotal+$price;
												
												array_push($workTypeArray,$resServicePrice['worktype']);
												array_push($modelArray,$resServicePrice['model']);
											}
											$grandtotal=$grandtotal+$subtotal;
											$techgrandtotal=$techgrandtotal+$techsubtotal;
											//print_r($workTypeArray);
											$workType= !empty($workTypeArray) ? implode(", ",$workTypeArray):'';
											$model = !empty($modelArray) ? implode(", ",$modelArray):'';
												
										?>
                                        <tr>
                                            <td data-title="Date Scheduled" class="coltext"><?php echo ($res_clientBill['start_date']<>'0000-00-00')? date("d-M-Y",strtotime($res_clientBill['start_date'])):"00-00-0000";?></td>
                                            <td data-title="Date Invoiced" class="coltext"><?php echo ($res_clientBill['invoiced_date']<>'0000-00-00')? date("d-M-Y",strtotime($res_clientBill['invoiced_date'])):"00-00-0000";?></td>
                                            <td data-title="Service" class="coltext"><?php echo $res_clientBill['service_name'];?></td>
                                            <td data-title="Work Type"><?php echo $workType;?></td>
                                            <td data-title="Model"><?php echo $model;?></td>
                                            <td data-title="Customer Name"><?php echo $res_clientBill['name'];?></td>
                                            <td data-title="WO#"><?php echo $res_clientBill['wo_no'];?></td>
                                            <td data-title="PO#"><?php echo $res_clientBill['purchase_order_no'];?></td>
                                            <td data-title="ClientPrice">$ <?php echo number_format($subtotal,2);?></td>
                                            <td data-title="TechPrice">$ <?php echo number_format($techsubtotal,2);?></td>   	   	
                                        </tr>
                                       <?php } ?>
                                   </tbody>
                               </table>
                               <!-----Table area end------->
                           <div id="subtotal">
                              <div  class="reportSubtotal" style="width:82%; padding-right:20px;">Grand Total:</div><div class="reportPrice" style="width:8%; text-align:left;">$ <?php echo number_format($grandtotal,2);?></div>
                              <div class="reportPrice" style="width:8%; text-align:left;">$ <?php echo number_format($techgrandtotal,2);?></div>
                           </div>
                           <?php }else{?>
                              <div style="padding-left:40%;border:1px solid #000;color:#F00;">No records founds!!</div>
                            <?php }?>
                            <div  align="center"><?php if($num>0) { echo $dbf->Pages($num,$perpage,"admin_invoiced_billing_report?srchClient=$_REQUEST[srchClient]&FromDate=$_REQUEST[FromDate]&ToDate=$_REQUEST[ToDate]&");}?></div>
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