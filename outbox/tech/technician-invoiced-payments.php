<?php 
	ob_start();
	session_start();
	include_once '../includes/class.Main.php';
	//Object initialization
	$dbf = new User();
	//page titlevariable
	$pageTitle="Welcome To Out Of The Box";
	include 'applicationtop-tech.php';
	//logout for users other than admin and user
	if($_SESSION['usertype']!='tech'){
		header("location:../logout");exit;
	}
?>
<link rel="stylesheet" href="../css/innermain.css" type="text/css" />
<link rel="stylesheet" href="../css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="../css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="../css/respmenu.css" type="text/css" />
<link rel="stylesheet" href="../css/no_more_table.css" type="text/css" />
<script type="text/javascript">
function ClearFields(){
	$('#FromDate').val("");
	$('#ToDate').val("");
	$('#srchMonth').val("");
	$('#SrchFrm').submit();
}
function displayRecords(month){
	$("#SrchFrm").submit();
}
</script>
<body>
    <div id="maindiv">
        <!-------------header--------------->
     	<?php include_once 'header-tech.php';?>
   		<!-------------header--------------->
        <!-------------top menu--------------->
     	<?php include_once 'tech-top-menu.php';?>
   		<!-------------top menu--------------->
         <div id="contentdiv">
                <!-------------Main Body--------------->
                <div class="rightcolumjobboard">
            		<div class="rightcoluminner">
                        <div class="headerbg"><div style="float:left;">Weekly Invoiced Payments</div>
                        	<div style="float:right;padding-right:10px;">
                            </div>
                        </div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        	<div style="width:100%;float:left;">
                            <form name="SrchFrm" id="SrchFrm" action="" method="post">
                              <div style="margin-bottom:5px;" align="center">
                                   <div  class="formtextaddsrch"align="center">From:</div>
                                    <div class="textboxcsrch">
                                    <input type="text" class="textboxsrch datepick" name="FromDate" id="FromDate" value="<?php echo $_REQUEST['FromDate'];?>" readonly></div>
                                    <div  class="formtextaddsrch"align="center">To:</div>
                                    <div class="textboxcsrch">
                                    <input type="text" class="textboxsrch datepick" name="ToDate" id="ToDate" value="<?php echo $_REQUEST['ToDate'];?>" readonly></div>
                                    <div style="float:left;padding:5px;">(Plz choose week start and end date)</div>
                                    <div  style="float:left;">
                                    <input type="submit" class="buttonText2" name="SearchRecord" value="Filter Records">
                                    <input type="button" class="buttonText2" name="Reset" value="Reset Filter" onClick="ClearFields();">
                                   </div>
                                   <div  class="formtextaddsrch"align="center">Month:</div>
                                    <div class="textboxcsrchsmall" style="width:100px; margin-left:5px;">
                                    <select class="selectboxsrch" name="srchMonth" id="srchMonth" onChange="displayRecords(this.value);">
                                    	<option value="">--Month--</option>
                                        <option value="01"<?php if($_REQUEST['srchMonth']=='01'){echo 'selected';}?>>Jan</option>
                                        <option value="02"<?php if($_REQUEST['srchMonth']=='02'){echo 'selected';}?>>Feb</option>
                                        <option value="03"<?php if($_REQUEST['srchMonth']=='03'){echo 'selected';}?>>Mar</option>
                                        <option value="04"<?php if($_REQUEST['srchMonth']=='04'){echo 'selected';}?>>Apr</option>
                                        <option value="05"<?php if($_REQUEST['srchMonth']=='05'){echo 'selected';}?>>May</option>
                                        <option value="06"<?php if($_REQUEST['srchMonth']=='06'){echo 'selected';}?>>June</option>
                                        <option value="07"<?php if($_REQUEST['srchMonth']=='07'){echo 'selected';}?>>July</option>
                                        <option value="08"<?php if($_REQUEST['srchMonth']=='08'){echo 'selected';}?>>Aug</option>
                                        <option value="09"<?php if($_REQUEST['srchMonth']=='09'){echo 'selected';}?>>Sept</option>
                                        <option value="10"<?php if($_REQUEST['srchMonth']=='10'){echo 'selected';}?>>Oct</option>
                                        <option value="11"<?php if($_REQUEST['srchMonth']=='11'){echo 'selected';}?>>Nov</option>
                                        <option value="12" <?php if($_REQUEST['srchMonth']=='12'){echo 'selected';}?>>Dec</option>
                                    </select>
                                    </div>
                                  </div>
                              </form>
                              <div class="spacer"></div>
                              <?php
							  ###########################################################################
							  ############CALCULATION OF WEEK DATE AND FETCH WORK ORDERS#################
							  ###########################################################################
							  function getStartAndEndDate($week, $year) {
								  $dto = new DateTime();
								  $dto->setISODate($year, $week,0);
								  $ret['week_start'] = $dto->format('Y-m-d');
								  $dto->modify('+6 days');
								  $ret['week_end'] = $dto->format('Y-m-d');
								  return $ret;
								}
								$current_year = date("Y");//current year
								if($_REQUEST['srchMonth'] !=''){
									$month = $_REQUEST['srchMonth'];
									$first_day_this_month = date('Y-m-d', mktime(0, 0, 0, $month, 1, $current_year));
									$last_day_this_month = date('Y-m-t', mktime(0, 0, 0, $month, 1, $current_year));
								}else{
									$first_day_this_month = date('Y-m-01');//first date of month
									$last_day_this_month  = date('Y-m-t');//last date of month
								}
								
								$first_week_no = date("W",strtotime(date($first_day_this_month)));//first week number of current month
								$last_week_no = date("W",strtotime(date($last_day_this_month)));//last week number of current month
								$first_week_no = ($first_week_no >='52')?'01':$first_week_no;
								$last_week_no = ($last_week_no=='01')?'52':$last_week_no;
								 
								for($i=$first_week_no; $i<=$last_week_no; $i++){
									$week_array[] = getStartAndEndDate($i,$current_year);
								}
								if(($_REQUEST['FromDate'] !='') && ($_REQUEST['ToDate'] !='')){
									$newdet=array();
									$newdet[0]['week_start']=date("Y-m-d",strtotime($_REQUEST['FromDate']));
									$newdet[0]['week_end']=date("Y-m-d",strtotime($_REQUEST['ToDate']));
									$week_array=$newdet;
								}
								//print "<pre>";
								//print_r($week_array);
								
								foreach($week_array as $k=>$w){
									//print_r($w);
									$subWoArray =array();
									$date_range = "'".$w['week_start']."' AND '".$w['week_end']."'";
									$date_range_key = date("d-M-Y",strtotime($w['week_start']))." To ".date("d-M-Y",strtotime($w['week_end']));
									$qrydt = "SELECT w.wo_no FROM assign_tech at,technicians t,work_order w LEFT JOIN work_order_tech_bill wt ON w.wo_no=wt.wo_no WHERE at.tech_id=t.id AND at.wo_no=w.wo_no AND w.work_status='Invoiced' AND at.tech_id='$_SESSION[userid]' AND w.invoiced_date BETWEEN $date_range AND (wt.payment_status <>'Completed' OR wt.payment_status is NULL)";
									$queryResult = $dbf->simpleQuery($qrydt);
									foreach($queryResult as $res){
										array_push($subWoArray,$res['wo_no']);
									}
									$mainWoArray[$date_range_key]=$subWoArray;
									
								}
							  //print "<pre>";	
							  //print_r($mainWoArray);
							  ###########################################################################
							  ############CALCULATION OF WEEK DATE AND FETCH WORK ORDERS#################
							  ###########################################################################
							   	$sch="";
								$fromdt=date("Y-m-d",strtotime(($_REQUEST['FromDate'])));
								$todt=date("Y-m-d",strtotime(($_REQUEST['ToDate'])));
								
							   $sch=substr($sch,0,-5);
							   //echo $sch;exit;
							   if($sch!=''){
								 $cond="w.approve_status='1' AND w.work_status='Invoiced' AND t.id=at.tech_id AND at.wo_no=w.wo_no AND ".$sch;
								  // echo $cond;exit;
							   }
							   elseif($sch==''){
								 $cond="w.approve_status='1' AND w.work_status='Invoiced' AND t.id=at.tech_id AND at.wo_no=w.wo_no";
							   }
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
								$c=1;
                                foreach($mainWoArray as $key=>$warray){
                                   //echo count($warray);
                                   if(!empty($warray)){
									$implode_workorders=implode(",",$warray);
									//count records according to search
									$count=$dbf->countRows("assign_tech at,technicians t,work_order w",$cond." AND FIND_IN_SET(w.wo_no,'$implode_workorders')");
									 if($count>0){
                                ?>
									<tr style="background-color:#f9f9f9;">
                                    	<td valign="top" class="grheading">
                                        <div class="divgr"><span style="color:#ff9812;">Payments For Period &nbsp;<?php echo $key;?></span></div></td>
                                 		<td class="hiderow"><input type="hidden" id="bPeriod_<?php echo $c;?>" value="<?php echo $key;?>"/></td>
                                        <td class="hiderow">&nbsp;</td>
                                        <td class="hiderow">&nbsp;</td>
                                        <td class="hiderow">&nbsp;</td>
                                        <td class="hiderow">&nbsp;</td>
                                        <td class="hiderow">&nbsp;</td>
                                        <td class="hiderow">&nbsp;</td>
                                    </tr>				
								 <?php 
                                    $grandtotal=0;
									$cond1="c.id=w.client_id AND w.service_id=s.id AND w.approve_status='1' AND w.work_status='Invoiced' AND at.wo_no=w.wo_no AND at.tech_id=t.id AND FIND_IN_SET(w.wo_no,'$implode_workorders')";
                                    $resArray=$dbf->fetchOrder("clients c,service s,technicians t,assign_tech at,work_order w",$cond1,"w.id DESC","c.name,s.service_name,at.start_date,t.id as techid,w.purchase_order_no, w.service_id, w.wo_no, w.client_id, w.created_by, w.id","");
                                    //print "<pre>";
                                    //print_r($resArray);
                                    foreach($resArray as $key=>$res_clientBill) { 
										$subtotal=0;
										//fetch work type, model and total price of work order
										$workTypeArray =array(); $modelArray =array();
										$res_woservice = $dbf->fetch("equipment e,work_type wt,workorder_service ws","e.id=ws.equipment AND wt.id=ws.work_type AND ws.service_id='$res_clientBill[service_id]' AND ws.workorder_id='$res_clientBill[id]'");
										//print_r($res_woservice);
										foreach($res_woservice as $resServicePrice){
											$price=$resServicePrice['tech_price'];
											//$total = ($resServicePrice['quantity']*$price);
											$subtotal = $subtotal+$price; 
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
                                        <td data-title="Price Amount">$ <?php echo number_format($subtotal,2);?></td>
                                            
                                    </tr>
								   <?php 
                                	}$c++;//end of group array
									?>
                                   <tr style="background-color:#f9f9f9;">
                                    <td class="hiderow"></td>
                                    <td class="hiderow"></td>
                                    <td class="hiderow">&nbsp;</td>
                                    <td class="hiderow">&nbsp;</td>
                                    <td class="hiderow">&nbsp;</td>
                                    <td class="hiderow">&nbsp;</td>
                                    <td class="grheading"><span style="color:#ff9812;">Grand Total :</span></td>
                                    <td class="grheading"><span style="color:#ff9812;">$ <?php echo number_format($grandtotal,2);?></span></td>
                                  </tr>		
                                  <?php
								  }//end of empty records
								 }//end of empty array
								}//end of main array loop
							   ?>
                        	  </tbody>
                            </table>
                            <!-----Table area end------->
                            <?php if($c == 1){?><div class="noRecords" align="center">No records founds!!</div><?php }?>
                          </div>
                        </div>
                        <div class="spacer"></div>
                    </div>
            	</div>
              <!-------------Main Body--------------->
         </div>
        <div class="spacer"></div>
        <?php include_once 'footer-tech.php'; ?>
    </div>
</body>
</html>