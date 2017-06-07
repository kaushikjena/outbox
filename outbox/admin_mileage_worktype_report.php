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
	$srchClientArr = $_REQUEST['srchClient']? $_REQUEST['srchClient']:array();
?>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />
<link rel="stylesheet" href="css/no_more_table.css" type="text/css" />
<script type="text/javascript">
function Search_Records(){
	$("#SrchFrm").attr("action","admin_mileage_worktype_report");
	$("#SrchFrm").submit();
}
function ClearFields(){
	$('#srchClient').val("");
	$('#srchTechnician').val("");
	$('#srchService').val("");
	$('#FromDate').val("");
	$('#ToDate').val("");
	$('#hidaction').val("");
	window.location.href="admin_mileage_worktype_report";
}
//for exporting,print,pdf,word
function print_doc(val,page){
 if(val=='word'){
	$("#SrchFrm").attr("action","admin_mileage_worktype_report_word");
	$("#SrchFrm").submit();
 }else if(val=='excell'){
	$("#SrchFrm").attr("action","admin_mileage_worktype_report_excell");
	$("#SrchFrm").submit();
 }else if(val=='pdf'){
	$("#SrchFrm").attr("action","admin_mileage_worktype_report_pdf.php");
	$("#SrchFrm").submit(); 
 }else if(val=='print'){
	$("#SrchFrm").attr("action","admin_mileage_worktype_report_print?page="+page);
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
                            <div style="float:left;;">Mileage Worktype Report</div>
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
                                   <div  class="formtextaddsrch" align="center">Client:</div>
                                   <div class="textboxcsrch">
                                   <select name="srchClient[]" id="srchClient" class="selectboxsrch" style="height:auto;" multiple="multiple">
                                        <option value="0" <?php if(in_array(0,$srchClientArr)){echo 'selected';}?>> COD </option>
                                        <?php foreach($dbf->fetchOrder("work_order w,clients cl","w.created_by=cl.id AND w.created_by <>'0' AND (w.work_status='Completed' OR w.work_status='Invoiced')","cl.name ASC","","cl.name")as $client){?>
                                        <option value="<?php echo $client['id']?>" <?php if(in_array($client['id'],$srchClientArr)){echo 'selected';}?>><?php echo $client['name'];?></option>
                                        <?php }?>
                                    </select>
                                    </div>
                                    <div class="formtextaddsrch" align="center">Technician:</div>
                                    <div class="textboxcsrch">
                                    <select name="srchTechnician" id="srchTechnician" class="selectboxsrch">
                                        <option value="">--Select Tech--</option>
                                        <?php foreach($dbf->fetch("technicians","id>0 ORDER BY first_name ASC")as $tech){?>
                                        <option value="<?php echo $tech['id']?>" <?php if($tech['id']==$_REQUEST['srchTechnician']){echo 'selected';}?>><?php echo $tech['first_name'].'&nbsp;'.$tech['middle_name'].'&nbsp;'.$tech['last_name'];?></option>
                                        <?php }?>
                                    </select>
                                    </div>
                                     <div  class="formtextaddsrch"align="center">Service:</div>
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
									//$sch=$sch."w.created_by='$_REQUEST[srchClient]' AND ";
									$creatorlist=implode(",",$_REQUEST['srchClient']);
									$sch=$sch."w.created_by IN($creatorlist) AND ";
								}
								if($_REQUEST['srchTechnician']!=''){
									$sch=$sch."t.id='$_REQUEST[srchTechnician]' AND ";
								}
								if($_REQUEST['srchService']!=''){
									$sch=$sch."s.id='$_REQUEST[srchService]' AND ";
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
							    //echo $sch;exit;
							    if($sch!=''){
								 $cond="c.id=w.client_id AND w.service_id=s.id AND  w.approve_status='1' AND (w.work_status='Completed' OR w.work_status='Invoiced') AND w.wo_no=at.wo_no AND at.tech_id=t.id AND w.id=ws.workorder_id AND ws.work_type=wt.id AND wt.worktype='Mileage' AND ".$sch;
							  // echo $cond;exit;
							    }
							    elseif($sch==''){
								 $cond="c.id=w.client_id AND w.service_id=s.id AND  w.approve_status='1' AND (w.work_status='Completed' OR w.work_status='Invoiced') AND w.wo_no=at.wo_no AND at.tech_id=t.id AND w.id=ws.workorder_id AND ws.work_type=wt.id AND wt.worktype='Mileage'";
							    }
							    //print $cond;
							    //Pagination 
								$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
								$page = ($page == 0 ? 1 : $page);
								$perpage =100;//limit in each page
								$startpoint = ($page * $perpage) - $perpage;
								//-----------------------------------				
								$num=$dbf->countRows("clients c,service s,technicians t,assign_tech at,work_order w,workorder_service ws,work_type wt",$cond); 
								if($num>0){
							   ?>
                               <!-----Table area start------->
                           		<table id="no-more-tables" class="draggable">
                                    <thead>
                                        <tr>
                                          <th width="8%">WO#</th>
                                          <th width="10%">PO#</th>              
                                          <th width="8%">Order Status</th>
                                          <th width="12%">Service Name</th>
                                          <th width="10%">Work Type</th>
                                          <th width="12%">Customer Name</th>
                                          <th width="12%">Client</th>
                                          <th width="12%">Tech</th>
                                          <th width="8%">Client Price</th>
                                          <th width="8%">Tech Price</th> 
                                        </tr>
                                    </thead>
                                    <tbody>
                           <?php
						   		$grandtotal=0; $techgrandtotal =0;
								foreach($dbf->fetchOrder("clients c,service s,technicians t,assign_tech at,work_order w,workorder_service ws,work_type wt",$cond,"w.id DESC LIMIT $startpoint,$perpage","c.name, s.service_name, w.id, w.wo_no, w.purchase_order_no, w.work_status, w.created_by, w.service_id, t.first_name,t.middle_name,t.last_name","")as  $res_JobBoard) {
								//get client name
								if($res_JobBoard['created_by']<>'0'){
									$clientname=$dbf->getDataFromTable("clients","name","id='$res_JobBoard[created_by]'");}else{
									$clientname="COD";
								}
								$subtotal=0; $techsubtotal=0;
								//fetch work type, model and total price of work order
								$workTypeArray =array(); //$modelArray =array();
								//echo $res_clientBill[id];
								$res_woservice = $dbf->fetch("equipment e,work_type wt,workorder_service ws","e.id=ws.equipment AND wt.id=ws.work_type AND ws.service_id='$res_JobBoard[service_id]' AND ws.workorder_id='$res_JobBoard[id]' AND wt.worktype='Mileage'");
								//print_r($res_woservice);
								foreach($res_woservice as $resServicePrice){
									$total = ($resServicePrice['quantity']*$resServicePrice['outbox_price']);
									$subtotal = $subtotal+$total; 
									$price=$resServicePrice['tech_price'];
									//$techtotal = ($resServicePrice['quantity']*$price);//commentd on nov24 2014
									$techsubtotal = $techsubtotal+$price;
									
									array_push($workTypeArray,$resServicePrice['worktype']);
									//array_push($modelArray,$resServicePrice['model']);
								}
								$grandtotal=$grandtotal+$subtotal;
								$techgrandtotal=$techgrandtotal+$techsubtotal;
								//print_r($workTypeArray);
								$workType= !empty($workTypeArray) ? implode(", ",$workTypeArray):'';
								//$model = !empty($modelArray) ? implode(", ",$modelArray):''; 
							?>
                            	<tr>
                                    <td data-title="WO#" class="coltext"><?php echo $res_JobBoard['wo_no'];?></td>
                                    <td data-title="PO#" class="coltext"><?php echo $res_JobBoard['purchase_order_no'];?></td>
                                    <td data-title="Order Status"><?php echo $res_JobBoard['work_status'];?></td>
                                     <td data-title="Service Name"><?php echo $res_JobBoard['service_name'];?></td>
                                    <td data-title="Work Type" class="coltext"><?php echo $workType;?></td>   
                                    <td data-title="Customer Name"><?php echo $res_JobBoard['name'];?></td>
                                    <td data-title="Client"><?php echo $clientname;?></td>
                                   <td data-title="Tech"><?php echo $res_JobBoard['first_name'].' '.$res_JobBoard['middle_name'].' '.$res_JobBoard['last_name'];?></td>   
                                    <td data-title="Client Price">$ <?php echo number_format($subtotal,2);?></td>
                                    <td data-title="Tech Price">$ <?php echo number_format($techsubtotal,2);?></td>   	
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
                             <div class="spacer"></div>
                             <div style="padding-left:40%;border:1px solid #000;color:#F00;">No records founds!!</div>
                            <?php }?>
                            <div  align="center"><?php if($num>0) { echo $dbf->Pages($num,$perpage,"admin_mileage_worktype_report?srchClient=$_REQUEST[srchClient]&srchTechnician=$_REQUEST[srchTechnician]&srchService=$_REQUEST[srchService]&FromDate=$_REQUEST[FromDate]&ToDate=$_REQUEST[ToDate]&");}?></div>
                          
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