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
	$srchStatusArr= $_REQUEST['srchStatus']?$_REQUEST['srchStatus']:array();
	//GET current month and year
	$first_day_this_month = date('Y-m-01'); // hard-coded '01' for first day
    $last_day_this_month  = date('Y-m-t');
	
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
	$('#srchStatus').val("");
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
	$("#SrchFrm").attr("action","admin_total_job_report_word");
	$("#SrchFrm").submit();
 }else if(val=='excell'){
	$("#SrchFrm").attr("action","admin_total_job_report_excell");
	$("#SrchFrm").submit();
 }else if(val=='pdf'){
	$("#SrchFrm").attr("action","admin_total_job_report_pdf");
	$("#SrchFrm").submit();
 }else if(val=='print'){
	$("#SrchFrm").attr("action","admin_total_job_report_print?page="+page);
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
                                  <select name="srchClient[]" id="srchClient" class="selectboxsrch" style="height:auto;" multiple="multiple">
                                  		<option value="0" <?php if(in_array(0,$srchClientArr)){echo 'selected';}?>> COD </option>
                                        <?php foreach($dbf->fetchOrder("work_order w,clients cl","w.created_by=cl.id AND w.created_by <>'0'","cl.name ASC","","cl.name")as $client){
											?>
                                        <option value="<?php echo $client['id']?>" <?php if(in_array($client['id'],$srchClientArr)){echo 'selected';}?>><?php echo $client['name'];?></option>
                                        <?php }?>
                                   </select>
                                    </div>
                                    <div  class="formtextaddsrchsmall" align="center">Status</div>
                                    <div class="textboxcsrch">
                                    <select name="srchStatus[]" id="srchStatus" class="selectboxsrch" style="height:auto;" multiple>                         
                                   <!-- <option value="">--Select Status--</option>-->
                                        <option value="Open" <?php if(in_array('Open',$srchStatusArr)){echo 'selected';}?>> Open </option>
                                        <option value="Assigned" <?php if(in_array('Assigned',$srchStatusArr)){echo 'selected';}?>>Assigned </option>
                                        <option value="Scheduled" <?php if(in_array('Scheduled',$srchStatusArr)){echo 'selected';}?>>Scheduled</option>
                                        <option value="Dispatched" <?php if(in_array('Dispatched',$srchStatusArr)){echo 'selected';}?>>Dispatched </option>
                                        <option value="In Progress" <?php if(in_array('In Progress',$srchStatusArr)){echo 'selected';}?>>In Progress</option>
                                        <option value="Completed" <?php if(in_array('Completed',$srchStatusArr)){echo 'selected';}?>>Completed </option>
                                        <option value="Ready to Invoice" <?php if(in_array('Ready to Invoice',$srchStatusArr)){echo 'selected';}?>>Ready to Invoice </option>
                                        <option value="Invoiced" <?php if(in_array('Invoiced',$srchStatusArr)){echo 'selected';}?>>Invoiced </option>
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
                                    <div style="width:270px; float:left;">
                                        <div  class="formtextaddsrchsmall"align="center">From:</div>
                                        <div class="textboxcsrchsmall">
                                        <input type="text" class="textboxsrch datepick" name="FromDate" id="FromDate" value="<?php echo $_REQUEST['FromDate'];?>" readonly></div>
                                        <div  class="formtextaddsrchsmall"align="center">To:</div>
                                        <div class="textboxcsrchsmall">
                                        <input type="text" class="textboxsrch datepick" name="ToDate" id="ToDate" value="<?php echo $_REQUEST['ToDate'];?>" readonly></div>
                                        <div class="spacer"></div>
                                        <span>(Plz choose created date)</span>
                                    </div>
                                    
                                    <div  class="formtextaddsrch" align="center" style="width:120px;">Select Month:</div>
                                    <div class="textboxcsrch">
                                    <?php 	
										  $resCreatedDate=$dbf->getDataFromTable("work_order","created_date","id<>0 ORDER BY created_date ASC LIMIT 0,1");		
										  $oldyear=date("Y",strtotime($resCreatedDate)); 
										  $oldmonth=date("m",strtotime($resCreatedDate));
										   //echo $oldyear.'<br/>'.$oldmonth;
										  $curyear=date("Y"); 
										  $currentmonth=date("m");
									?>
                                    <select name="srchMonth" id="srchMonth" class="selectboxsrch">
                                    	<option value="">--Select Month--</option>
                                        <?php 
										for($i=$curyear;$i>=$oldyear;$i--){
											for($j=$currentmonth;$j>=1;$j--){
												$month=($j==1)?"Jan":(($j==2)?"Feb":(($j==3)?"Mar":(($j==4)?"Apr":(($j==5)?"May":(($j==6)?"Jun":(($j==7)?"July":(($j==8)?"Aug":(($j==9)?"Sep":(($j==10)?"Oct":(($j==11)?"Nov":"Dec"))))))))));
										?>
                                        <option value="<?php echo $i.'-'.str_pad($j,'2','0',STR_PAD_LEFT);?>" <?php //if($currentmonth==$j && $curyear){echo "selected";}
										if($_REQUEST['srchMonth']==$i.'-'.str_pad($j,'2','0',STR_PAD_LEFT)){echo 'selected';}
										
										?>><?php echo $month.'-'.$i;?></option>
                                        
										<?php if($i==$oldyear && $j==$oldmonth){
												 break;
											  }
										    } 
											//echo "SSSSSSSSSSSSSSSS".$j;
										    if($j==0){
												$currentmonth=12;
											}else{
												$currentmonth=$currentmonth;
											}
										}?>
                                    </select>
                                    </div>
                                    <div style="float:left; padding-left:62px;padding-top:5px;">
                                    <input type="hidden" name="action"  value="search">
                                    <input type="hidden" name="hidaction"  value="<?php echo $x;?>">
                                    <input type="button" class="buttonText2" name="SearchRecord" id="SearchRecord" value="Filter Report" onClick="Search_Records();">
                                    <input type="button" class="buttonText2" name="Reset" value="Reset Filter" onClick="ClearFields();">
                                   </div>
                                  </div>
                                  <div class="spacer"></div>
                              </form>
                              <?php
						        $sch="";
								$fromdt=date("Y-m-d",strtotime(($_REQUEST['FromDate'])));
								$todt=date("Y-m-d",strtotime(($_REQUEST['ToDate'])));
								
								if($_REQUEST['srchClient']!=''){
									//$sch=$sch."temp.created_by='$_REQUEST[srchClient]' AND ";
									$creatorlist=is_array($_REQUEST['srchClient'])?implode(",",$_REQUEST['srchClient']):$_REQUEST['srchClient'];
									$sch=$sch."w.created_by IN($creatorlist) AND ";
								}
								if($_REQUEST['srchStatus']!=''){
									//$sch=$sch."temp.work_status='$_REQUEST[srchStatus]' AND ";
									$tmpwstatusarray=array();
									foreach($_REQUEST['srchStatus'] as $val){
										array_push($tmpwstatusarray,"'".$val."'");
									}
									$workstatuslist=is_array($tmpwstatusarray)?implode(",",$tmpwstatusarray):$_REQUEST['srchStatus'];
								    $sch=$sch."w.work_status IN($workstatuslist) AND ";
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
									$sch=$sch."w.created_date BETWEEN '$fromdt' AND '$todt' AND ";
								}
								if($_REQUEST['srchMonth']!=""){
									$query_date=$_REQUEST['srchMonth'];
									$srcfirstday=date('Y-m-01', strtotime($query_date));
									$srchlastday=date('Y-m-t', strtotime($query_date));
									//echo $srcfirstday."<br/>".$srchlastday;exit;
								    $sch=$sch."w.created_date BETWEEN '$srcfirstday' AND '$srchlastday' AND ";
							    }
							   $sch=substr($sch,0,-5);
							   //echo $sch;exit;
							   if($sch!=''){
								  $cond=" AND ".$sch;
							   }
							   elseif($sch==''){
								  $cond=" AND w.id>0 AND w.created_date BETWEEN '$first_day_this_month' AND '$last_day_this_month'";  
							   }
							   //print $cond;
							   //Pagination 
                                $page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
                                $page = ($page == 0 ? 1 : $page);
                                $perpage =100;//limit in each page
                                $startpoint = ($page * $perpage) - $perpage;
                                //-----------------------------------	
								$res = mysql_query("SELECT w.id, w.wo_no, w.purchase_order_no, w.work_status, w.created_date, w.created_by,w.service_id,w.tracking_number,w.parts_arrive,c.name, c.phone_no, c.city, st.state_name,s.id as sid, s.service_name ,t.id as tid,t.first_name, t.middle_name, t.last_name,at.assign_date, at.start_date FROM work_order w LEFT JOIN clients c ON w.client_id=c.id LEFT JOIN state st ON c.state=st.state_code LEFT JOIN service s ON w.service_id=s.id LEFT JOIN assign_tech at  ON w.wo_no=at.wo_no LEFT JOIN technicians t ON t.id=at.tech_id WHERE  w.approve_status='1' ".$cond." ORDER BY w.id DESC");
								$num = mysql_num_rows($res);
								$qry ="SELECT w.id, w.wo_no, w.purchase_order_no, w.work_status, w.created_date, w.created_by,w.service_id,w.tracking_number,w.parts_arrive,c.name, c.phone_no, c.city, st.state_name,s.id as sid, s.service_name ,t.id as tid,t.first_name, t.middle_name, t.last_name,at.assign_date, at.start_date FROM work_order w LEFT JOIN clients c ON w.client_id=c.id LEFT JOIN state st ON c.state=st.state_code LEFT JOIN service s ON w.service_id=s.id LEFT JOIN assign_tech at  ON w.wo_no=at.wo_no LEFT JOIN technicians t ON t.id=at.tech_id WHERE  w.approve_status='1' ".$cond." ORDER BY w.id DESC LIMIT $startpoint,$perpage";
								$resArray = $dbf->simpleQuery($qry);
								//$num = count($resArray);
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
                                          <th width="7%">DeliveryState</th>
                                          <th width="7%">DeliverPhone</th>
                                          <th width="7%">TrackingNumber</th>
                                          <th width="7%">PartsArrival</th>
                                          <th width="7%">ServiceType</th>
                                          <th width="6%">OrderStatus</th>
                                          <th width="7%">Client</th>
                                          <th width="7%">TechName</th>
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
											$res_woservice = $dbf->simpleQuery("SELECT ws.quantity, ws.outbox_price, ws.tech_price FROM workorder_service ws LEFT JOIN equipment e ON ws.equipment=e.id LEFT JOIN work_type wt ON ws.work_type=wt.id WHERE ws.service_id='".$res_JobBoard['service_id']."' AND ws.workorder_id='".$res_JobBoard['id']."'");
											//print_r($res_woservice);
											foreach($res_woservice as $resServicePrice){
												$total = ($resServicePrice['quantity']*$resServicePrice['outbox_price']);
												$subtotal = $subtotal+$total; 
												$price=$resServicePrice['tech_price'];
												//$techtotal = ($resServicePrice['quantity']*$price);
												$techsubtotal = $techsubtotal+$price;
											}
											$techname = $res_JobBoard['first_name'].' '.$res_JobBoard['middle_name'].' '.$res_JobBoard['last_name'];
											$TrackingNumber=($res_JobBoard['tracking_number']!='')?$dbf->cut($res_JobBoard['tracking_number'],15):'NIL';
											$PartsArrival=($res_JobBoard['parts_arrive']!='0000-00-00')?date('d-M-Y',strtotime($res_JobBoard['parts_arrive'])):'NIL';
										?>
                                        <tr>
                                            <td data-title="WO#"><b><?php echo $res_JobBoard['wo_no'];?></b></td>
                                            <td data-title="PO#"><?php echo $res_JobBoard['purchase_order_no'];?></td>
                                            <td data-title="CreatedDate"><?php echo date("d-M-Y",strtotime($res_JobBoard['created_date']));?></td>
                                            <td data-title="CustomerName"><?php echo $dbf->cut($res_JobBoard['name'],15);?></td>
                                            <td data-label="DeliveryState"><?php echo $res_JobBoard['state_name'];?></td>
                                            <td data-label="DeliveryPhone"><?php echo $res_JobBoard['phone_no'];?></td>
                                            <td data-title="TrackingNumber"><?php echo $TrackingNumber;?></td>
                                            <td data-label="PartsArrival"><?php echo $PartsArrival;?></td>
                                            <td data-label="ServiceType"><?php echo $res_JobBoard['service_name'];?></td>
                                            <td data-label="OrderStatus"><?php echo $res_JobBoard['work_status'];?></td>
                                            <td data-label="Client"><?php echo $clientname;?></td>  
                                            <td data-label="TechName"><?php echo ($techname!='  ')?$techname:'NIL';?></td>
                                            <td data-label="ScheduledDate"><?php echo ($res_JobBoard['start_date']<>'0000-00-00' && $res_JobBoard['start_date']<>NULL)? date("d-M-Y",strtotime($res_JobBoard['start_date'])):'NIL';?></td>
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
                            <div  align="center"><?php if($num>0) { echo $dbf->Pages($num,$perpage,"admin_total_job_report?srchClient=$creatorlist&srchStatus=$workstatuslist&srchTechnician=$_REQUEST[srchTechnician]&srchService=$_REQUEST[srchService]&FromDate=$_REQUEST[FromDate]&ToDate=$_REQUEST[ToDate]&srchMonth=$_REQUEST[srchMonth]&");}?></div>
                          
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