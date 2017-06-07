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
	$("#SrchFrm").attr("action","admin_total_job_report");
	$("#SrchFrm").submit();
}
function ClearFields(){
	$('#srchClient').val("");
	$('#srchTechnician').val("");
	$('#srchService').val("");
	$('#FromDate').val("");
	$('#ToDate').val("");
	$('#hidaction').val("");
	window.location.href="admin_total_job_report";
}
//for exporting,print,pdf,word
function print_doc(val,page){
 if(val=='word'){
	$("#SrchFrm").attr("action","admin_schedule_job_report_word");
	$("#SrchFrm").submit();
 }else if(val=='excell'){
	$("#SrchFrm").attr("action","admin_schedule_job_report_excell");
	$("#SrchFrm").submit();
 }else if(val=='pdf'){
	$("#SrchFrm").attr("action","admin_schedule_job_report_pdf");
	$("#SrchFrm").submit();
 }else if(val=='print'){
	$("#SrchFrm").attr("action","admin_schedule_job_report_print?page="+page);
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
                            <div style="float:left;;">Total Jobs Report</div>
                            <div style="float:right;">
                            <a href="javascript:void(0);" onClick="print_doc('word');"><img src="images/word2007.png" style="width:20px; height:20px;" title="Export to Word"/></a>
                            <a href="javascript:void(0);" onClick="print_doc('pdf');"><img src="images/pdf.png" style="width:20px; height:20px;" title="Export to PDF"/></a>
                            <a href="javascript:void(0);" onClick="print_doc('excell');"><img src="images/export_excel.png" style="width:20px; height:20px;" title="Export to Excel"></a>
                            <a href="javascript:void(0);"  onClick="print_doc('print','<?php echo (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);?>');" ><img src="images/print.png" alt="" style="width:20px; height:20px;" title="Print"></a>
                            </div>
                        </div>
                        <div id="contenttable">
                        	<div style="width:100%;float:left;">
                            <div class="spacer"></div>
                            <form name="SrchFrm" id="SrchFrm" action="" method="post">
                              <div style="margin-bottom:5px;" align="center">
                              	  <div  class="formtextaddsrchsmall" align="center">Client:</div>
                                  <div class="textboxcsrch">
                                  <select name="srchClient" id="srchClient" class="selectboxsrch">
                                  		<option value="">--Select Client--</option>
                                        <option value="0" <?php if($_REQUEST['srchClient']=="0"){echo 'selected';}?>> COD </option>
                                        <?php foreach($dbf->fetchOrder("work_order w,clients cl","w.created_by=cl.id AND w.created_by <>'0'","cl.name ASC","","cl.name")as $client){?>
                                        <option value="<?php echo $client['id']?>" <?php if($client['id']==$_REQUEST['srchClient']){echo 'selected';}?>><?php echo $client['name'];?></option>
                                        <?php }?>
                                   </select>
                                    </div>
                                    <div  class="formtextaddsrchsmall" align="center">Status</div>
                                    <div class="textboxcsrchsmall">
                                    <select name="srchStatus" id="srchStatus" class="selectboxsrch">
                                        <option value="">--Select Status--</option>
                                        <option value="Open" <?php if($_REQUEST['srchStatus']=='Open'){echo 'selected';}?>> Open </option>
                                        <option value="Assigned" <?php if($_REQUEST['srchStatus']=='Assigned'){echo 'selected';}?>>Assigned </option>
                                        <option value="Scheduled" <?php if($_REQUEST['srchStatus']=='Scheduled'){echo 'selected';}?>>Scheduled</option>
                                        <option value="Dispatched" <?php if($_REQUEST['srchStatus']=='Dispatched'){echo 'selected';}?>>Dispatched </option>
                                        <option value="In Progress" <?php if($_REQUEST['srchStatus']=='In Progress'){echo 'selected';}?>>In Progress</option>
                                        <option value="Completed" <?php if($_REQUEST['srchStatus']=='Completed'){echo 'selected';}?>>Completed </option>
                                        <option value="Ready to Invoice" <?php if($_REQUEST['srchStatus']=='Ready to Invoice'){echo 'selected';}?>>Ready to Invoice </option>
                                        <option value="Invoiced" <?php if($_REQUEST['srchStatus']=='Invoiced'){echo 'selected';}?>>Invoiced </option>
                                     </select>
                                    </div>
                                     <div class="formtextaddsrchsmall" align="center">Tech:</div>
                                     <div class="textboxcsrch">
                                    <select name="srchTechnician" id="srchTechnician" class="selectboxsrch">
                                        <option value="">--Select Tech--</option>
                                        <?php foreach($dbf->fetch("technicians","id>0 ORDER BY first_name ASC")as $tech){?>
                                        <option value="<?php echo $tech['id']?>" <?php if($tech['id']==$_REQUEST['srchTechnician']){echo 'selected';}?>><?php echo $tech['first_name'].'&nbsp;'.$tech['middle_name'].'&nbsp;'.$tech['last_name'];?></option>
                                        <?php }?>
                                    </select>
                                    </div>
                                    <div  class="formtextaddsrchsmall" align="center">Service:</div>
                                    <div class="textboxcsrch">
                                    <select name="srchService" id="srchService" class="selectboxsrch">
                                    	<option value="">--Service Type--</option>
                                        <?php foreach($dbf->fetch("service","id>0 ORDER BY service_name ASC")as $service){?>
                                        <option value="<?php echo $service['id'];?>" <?php if($service['id']==$_REQUEST['srchService']){echo 'selected';}?>><?php echo $service['service_name'];?></option>
                                        <?php }?>
                                    </select>
                                    </div>
                                    <div  class="formtextaddsrchsmall"align="center">From:</div>
                                    <div class="textboxcsrchsmall">
                                    <input type="text" class="textboxsrch datepick" name="FromDate" id="FromDate" value="<?php echo $_REQUEST['FromDate'];?>" readonly></div>
                                    <div  class="formtextaddsrchsmall"align="center">To:</div>
                                    <div class="textboxcsrchsmall">
                                    <input type="text" class="textboxsrch datepick" name="ToDate" id="ToDate" value="<?php echo $_REQUEST['ToDate'];?>" readonly></div>
                                    <div style="float:left;padding:5px;">(Plz choose created date)</div>
                                    <div style="float:left;">
                                    <input type="hidden" name="action"  value="search">
                                    <input type="hidden" name="hidaction"  value="<?php echo $x;?>">
                                    <input type="button" class="buttonText2" name="SearchRecord" id="SearchRecord" value="Filter Report" onClick="Search_Records();">
                                    <input type="button" class="buttonText2" name="Reset" value="Reset Filter" onClick="ClearFields();">
                                   </div>
                                  </div>
                              </form>
                              <?php
						       $sch="";
								$fromdt=date("Y-m-d",strtotime(($_REQUEST['FromDate'])));
								$todt=date("Y-m-d",strtotime(($_REQUEST['ToDate'])));
								
								if($_REQUEST['srchClient']!=''){
									$sch=$sch."w.created_by='$_REQUEST[srchClient]' AND ";
								}
								if($_REQUEST['srchStatus']!=''){
									$sch=$sch."w.work_status='$_REQUEST[srchStatus]' AND ";
								}
								if($_REQUEST['srchTechnician']!=''){
									$sch=$sch."t.id='$_REQUEST[srchTechnician]' AND ";
								}
								if($_REQUEST['srchService']!=''){
									$sch=$sch."s.id='$_REQUEST[srchService]' AND ";
								}
								if($_REQUEST['FromDate']!='' && $_REQUEST['ToDate']==''){
									$sch=$sch."w.created_date >= '$fromdt' AND ";
								}
								if($_REQUEST['FromDate']=='' && $_REQUEST['ToDate']!=''){
									$sch=$sch."w.created_date <= '$todt' AND ";
								}
								if(($_REQUEST['FromDate']!='') && ($_REQUEST['ToDate']!='')){
									$sch=$sch."at.created_date BETWEEN '$fromdt' AND '$todt' AND ";
								}
							   $sch=substr($sch,0,-5);
							   //echo $sch;exit;
							   if($sch!=''){
								 $cond="c.state=st.state_code AND c.id=w.client_id AND w.service_id=s.id AND w.approve_status='1' AND w.wo_no=at.wo_no AND at.tech_id=t.id AND ".$sch;
							  // echo $cond;exit;
							   }
							   elseif($sch==''){
								 $cond="c.state=st.state_code AND c.id=w.client_id AND w.service_id=s.id AND w.approve_status='1' AND w.wo_no=at.wo_no AND at.tech_id=t.id";
							   }
							   //print $cond;
							   //Pagination 
                                $page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
                                $page = ($page == 0 ? 1 : $page);
                                $perpage =10;//limit in each page
                                $startpoint = ($page * $perpage) - $perpage;
                                //-----------------------------------				
                                $num=$dbf->countRows("state st,clients c,service s,technicians t,assign_tech at,work_order w",$cond); 
								$resArray = $dbf->fetchOrder("state st,clients c,service s,technicians t,assign_tech at,work_order w",$cond,"w.id DESC LIMIT $startpoint,$perpage","w.id, w.wo_no, w.purchase_order_no, w.work_status, w.created_date, w.created_by,w.service_id, c.name, c.phone_no, c.city, st.state_name, s.service_name,t.first_name, t.middle_name, t.last_name,at.assign_date, at.start_date","");
								//print "<pre>";
								//print_r($resArray);
								if($num>0){
							   ?>
                           	   <!-----Table area start------->
                           		<table id="no-more-tables" class="draggable">
                                    <thead>
                                        <tr>
                                          <th width="6%">WO#</th>
                                          <th width="6%">PO#</th>
                                          <th width="7%">CreatedDate</th>
                                          <th width="7%">CustomerName</th>
                                          <th width="7%">DeliveryCity</th>
                                          <th width="7%">DeliveryState</th>
                                          <th width="7%">DeliverPhone</th>
                                          <th width="7%">ServiceType</th>
                                          <th width="6%">OrderStatus</th>
                                          <th width="7%">Client</th>
                                          <th width="7%">TechName</th>
                                          <th width="7%">AssignedDate</th>
                                          <th width="7%">ScheduledDate</th>
                                          <th width="6%">OrderTotal</th>
                                          <th width="6%">TechPay</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                     <?php
										foreach($resArray as $res_JobBoard){
											$clientname= ($res_JobBoard['created_by']<>'0') ? $dbf->getDataFromTable("clients","name","id='$res_JobBoard[created_by]'"):"COD";
											//calculating order billing total and tech pay
											$subtotal=0; $techsubtotal=0;
											$res_woservice = $dbf->fetch("equipment e,work_type wt,workorder_service ws","e.id=ws.equipment AND wt.id=ws.work_type AND ws.service_id='$res_JobBoard[service_id]' AND ws.workorder_id='$res_JobBoard[id]'");
											//print_r($res_woservice);
											foreach($res_woservice as $resServicePrice){
												$total = ($resServicePrice['quantity']*$resServicePrice['outbox_price']);
												$subtotal = $subtotal+$total; 
												$price=$resServicePrice['tech_price'];
												//$techtotal = ($resServicePrice['quantity']*$price);
												$techsubtotal = $techsubtotal+$price;
											}
										?>
                                        <tr>
                                            <td data-title="WO#"><b><?php echo $res_JobBoard['wo_no'];?></b></td>
                                            <td data-title="PO#"><?php echo $res_JobBoard['purchase_order_no'];?></td>
                                            <td data-title="CreatedDate"><?php echo date("d-M-Y",strtotime($res_JobBoard['created_date']));?></td>
                                            <td data-title="CustomerName"><?php echo $dbf->cut($res_JobBoard['name'],15);?></td>
                                            <td data-title="DeliveryCity"><?php echo $res_JobBoard['city'];?></td>
                                            <td data-label="DeliveryState"><?php echo $res_JobBoard['state_name'];?></td>
                                            <td data-label="DeliveryPhone"><?php echo $res_JobBoard['phone_no'];?></td>
                                            <td data-label="ServiceType"><?php echo $res_JobBoard['service_name'];?></td>
                                            <td data-label="OrderStatus"><?php echo $res_JobBoard['work_status'];?></td>
                                            <td data-label="Client"><?php echo $clientname;?></td>  
                                            <td data-label="TechName"><?php echo $res_JobBoard['first_name'].' '.$res_JobBoard['middle_name'].' '.$res_JobBoard['last_name'];?></td>
                                            <td data-label="AssignedDate"><?php echo ($res_JobBoard['assign_date']<>'0000-00-00')? date("d-M-Y",strtotime($res_JobBoard['assign_date'])):'';?></td>
                                            <td data-label="ScheduledDate"><?php echo ($res_JobBoard['start_date']<>'0000-00-00')? date("d-M-Y",strtotime($res_JobBoard['start_date'])):'';?></td>
                                            <td data-label="OrderTotal">$ <?php echo number_format($subtotal,2);?></td>      
                                            <td data-label="TechPay">$ <?php echo number_format($techsubtotal,2);?></td>
                                        </tr>
                                     <?php } ?> 
                                   </tbody>
                               </table>
                              <!-----Table area end------->
						 	<?php }else{?>
                              <div class="spacer"></div>
                              <div style="padding-left:40%;border:1px solid #000;color:#F00;">No records founds!!</div>
                            <?php }?>
                            <div  align="center"><?php if($num>0) { echo $dbf->Pages($num,$perpage,"admin_total_job_report?srchClient=$_REQUEST[srchClient]&srchTechnician=$_REQUEST[srchTechnician]&srchService=$_REQUEST[srchService]&FromDate=$_REQUEST[FromDate]&ToDate=$_REQUEST[ToDate]&");}?></div>
                          
                        </div>
                        <div class="spacer"></div>
                    </div>
            	    </div>
              <!-------------Main Body--------------->
                </div>
                <div class="spacer"></div>
        <?php include_once 'footer.php'; ?>
        </div>
    </div>
</body>
</html>