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
?>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />
<link rel="stylesheet" href="css/no_more_table.css" type="text/css" />
<script type="text/javascript">
function ClearFields(){
	$('#srchTech').val("");
	$('#FromDate').val("");
	$('#ToDate').val("");
	$('#srchMonth').val("");
	$('#SrchFrm').submit();
}
function generateBill(c,tid,page){
	var wonos = $("#wonoArr_"+c+"_"+tid).val();
	var billperiod = $("#bPeriod_"+c).val();
	//alert(billperiod);
	$("#action").val("generatebill");
	$("#tid").val(tid);
	$("#wonos").val(wonos);
	$("#billperiod").val(billperiod);
	$("#BillFrm").attr("action",page);
	$("#BillFrm").submit();
}
function displayRecords(month){
	$("#SrchFrm").submit();
}
</script>
<body>
	<form name="BillFrm" id="BillFrm" action="" method="post">
    	<input type="hidden" name="action" id="action" value=""/>
    	<input type="hidden" name="tid" id="tid" value=""/>
        <input type="hidden" name="wonos" id="wonos" value="" />
        <input type="hidden" name="billperiod" id="billperiod" value="" />
    </form>
    <div id="maindiv">
        <!-------------header--------------->
     	<?php include_once 'header.php';?>
   		<!-------------header--------------->
        <!-------------top menu--------------->
     	<?php include_once 'top-menu.php';?>
   		<!-------------top menu--------------->
         <div id="contentdiv">
                <!-------------Left menu--------------->
				<?php //include_once 'left-menu.php';?>
                <!-------------left menu--------------->
                <!-------------Main Body--------------->
                <div class="rightcolumjobboard">
            		<div class="rightcoluminner">
                        <div class="headerbg"><div style="float:left;">Manage Technician Payments</div>
                        	<div style="float:right;padding-right:10px;">
                            </div>
                        </div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        	<div style="width:100%;float:left;">
                            <form name="SrchFrm" id="SrchFrm" action="" method="post">
                              <div style="margin-bottom:5px;" align="center">
                              	  <div  class="formtextaddsrch" align="center">Technician</div>
                                  <div class="textboxcsrch">
                                  <select name="srchTech" id="srchTech" class="selectboxsrch">
                                  		<option value="">--Select Tech--</option>
                                        <?php foreach($dbf->fetch("technicians","id>0 ORDER BY first_name ASC")as $tech){?>
                                        <option value="<?php echo $tech['id']?>" <?php if($tech['id']==$_REQUEST['srchTech']){echo 'selected';}?>><?php echo $tech['first_name'].' '.$tech['middle_name'].' '.$tech['last_name'];?></option>
                                        <?php }?>
                                   </select>
                                    </div>
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
									foreach($dbf->fetchOrder("work_order w,work_order_tech wt","w.wo_no=wt.wo_no AND wt.work_status='Completed' AND wt.arrival_date BETWEEN $date_range","","wt.wo_no","")as $res){
										$paymentstatus = $dbf->getDataFromTable("work_order_tech_bill","payment_status","wo_no='$res[wo_no]'");
										if($paymentstatus<>'Completed'){
											array_push($subWoArray,$res['wo_no']);
										}
									}
									$mainWoArray[$date_range_key]=$subWoArray;
									
								}
							  //print_r($mainWoArray);
							  ###########################################################################
							  ############CALCULATION OF WEEK DATE AND FETCH WORK ORDERS#################
							  ###########################################################################
							   	$sch="";
								$fromdt=date("Y-m-d",strtotime(($_REQUEST['FromDate'])));
								$todt=date("Y-m-d",strtotime(($_REQUEST['ToDate'])));
								
								if($_REQUEST['srchTech']!=''){
									$sch=$sch."t.id='$_REQUEST[srchTech]' AND ";
								}
								
							   $sch=substr($sch,0,-5);
							   //echo $sch;exit;
							   if($sch!=''){
								 $cond="w.approve_status='1' AND w.work_status='Completed' AND t.id=at.tech_id AND at.wo_no=w.wo_no AND ".$sch;  
							   }elseif($sch==''){
								 $cond="w.approve_status='1' AND w.work_status='Completed' AND t.id=at.tech_id AND at.wo_no=w.wo_no";
							   }
							  ?>
                            <!-----Table area start------->
                            <table id="no-more-tables" class="draggable">
                                <thead>
                                    <tr>
                                        <th width="25%">Tech Name</th>
                                        <th width="25%">Email</th>
                                        <th width="15%">Phone No</th>
                                        <th width="15%">OrderStatus</th>
                                        <th width="10%">No Of Jobs</th>
                                        <th width="10%">Action</th>
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
                                    </tr>				
								<?php 
                                    $resGrArray=array();
                                    $resGrArray=$dbf->fetchOrder("assign_tech at,technicians t,work_order w",$cond." AND FIND_IN_SET(w.wo_no,'$implode_workorders')","","t.first_name, t.middle_name, t.last_name, t.email, t.contact_phone, w.work_status, t.id","t.id");
                                    //group by tech loop
                                    foreach($resGrArray as $k=>$sgRes){
									//count no of work orders
									$resSubArray = array();
                                    //collect work orders of the client
									foreach($dbf->fetchOrder("assign_tech at,technicians t,work_order w","t.id='$sgRes[id]' AND " .$cond." AND FIND_IN_SET(w.wo_no,'$implode_workorders')","","w.wo_no","") as $resw){
										array_push($resSubArray,$resw['wo_no']);
									}
									$numres = count($resSubArray);
									//print_r($resSubArray); 
								?>
                                  <tr>
                                    <td data-title="Tech"><?php echo $sgRes['first_name'].' '.$sgRes['middle_name'].' '.$sgRes['last_name'];?></td>
                                    <td data-title="Email"><?php echo $sgRes['email'];?></td>
                                    <td data-title="PhoneNo"><?php echo $sgRes['contact_phone'];?></td>
                                    <td data-title="WorkStatus"><?php echo $sgRes['work_status'];?></td>
                                    <td data-title="NoOfJobs"><?php echo $numres;?></td>
                                    <td data-title="Action" class="coltext"><a href="javascript:void(0);" onClick="generateBill('<?php echo $c;?>','<?php echo $sgRes['id'];?>','admin-technician-payments');">Generate</a> <input type="hidden" id="wonoArr_<?php echo $c;?>_<?php echo $sgRes['id'];?>" value="<?php echo implode(",",$resSubArray);?>"/>
                                    </td>
                               	 </tr>
								   <?php 
                                	}$c++;//end of group array
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
        <?php include_once 'footer.php'; ?>
    </div>
</body>
</html>