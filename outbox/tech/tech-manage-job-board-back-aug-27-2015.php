<?php 
	ob_start();
	session_start();
	include_once '../includes/class.Main.php';
	//Object initialization
	$dbf = new User();
	//page titlevariable
	$pageTitle="Welcome To Out Of The Box";
	include 'applicationtop-tech.php';
	if($_SESSION['usertype']!='tech'){
	   header("location:../logout");exit;
	}
	$_SESSION['requestc']=$_SESSION['requestc']?$_SESSION['requestc']:array();
	if(isset($_REQUEST['schaction']) && $_REQUEST['schaction'] =='filtersch'){
		if($_REQUEST['page']=='DispatchBoard'){
			$_SESSION['requestc']['search']['srchDate']=$_REQUEST['search']['srchDate'];
			$_SESSION['requestc']['page']=$_REQUEST['page'];
		}elseif($_REQUEST['page']=='EditFilter'){
			$_SESSION['requestc']=$_REQUEST;
		}
	}
?>
<body>
<script type="text/javascript">
function SubmitFields(){
	$("#schaction").val("filtersch");
	document.SrchFrm.submit();
}
function ClearFields(){
	$('#FromDate').val("");
	$('#ToDate').val("");
	$.post("unset-session.php",{"src":"tech"},function(res){
		$("#schaction").val("");
		document.SrchFrm.submit();
	});
	
}
function edit_filter(){
	window.location.href="tech-edit-filter-search";
}
function Show_Workstatus(wono){
	$.fancybox.showActivity();
	var url="../technician_workstatus.php";
	$.post(url,{"choice":"workstatus","wono":wono},function(res){			
		$.fancybox(res,{centerOnScroll:true,hideOnOverlayClick:false});				
	});
}
function Set_Workstatus(wono,woid){
	//alert(woid);
	$.fancybox.showActivity();
	var url="tech-workorder-status.php";
	$.post(url,{"wono":wono,"woid":woid},function(res){
		$.fancybox(res,{centerOnScroll:true,hideOnOverlayClick:false,'onComplete': function(){
			   setTimeout( function() {$.fancybox.close(); },300000);}
  		});
	});
}
function Set_Workstatus_alert(v){
	if(v=='n'){
		alert("Sorry !!! \n\nPlease enter the scheduled date for change work status.");
	}else{
		alert("Sorry !!! \n\nThis work is Completed or Invoiced.");
	}
}
function viewDocument(fname,wono,woid){
	$.fancybox.showActivity();	
	var url="tech-view-docs.php";
	$.post(url,{"fname":fname,"wono":wono,"woid":woid},function(res){			
		$.fancybox(res,{centerOnScroll:true,hideOnOverlayClick:false});				
	});
}
function returnBack(wono,woid){
	Set_Workstatus(wono,woid);
}
/*********Function to schedule job************/
function ShowTechnicians(id){
	//alert(id);
	$.fancybox.showActivity();	
	var url="schedule-technician.php";
	var wono = $("#WorkOrder"+id).val();
	$.post(url,{"choice":"assign_job","wono":wono,"woid":id},function(res){			
		$.fancybox(res,{centerOnScroll:true,hideOnOverlayClick:false,'onComplete': function(){
			   setTimeout( function() {$.fancybox.close(); },120000);}
		});				
	});
}
function ShowTechnicians_alert(){
	alert("Sorry !!! \n\nThis work is already scheduled.");
}
function validate_assigntech(){
	if(document.AssignTech.cmbTechnician.value == ''){
		document.getElementById('lblcmbTechnician').innerHTML = 'This field is required';
		document.AssignTech.cmbTechnician.focus();
		return false;
	}else{
		document.getElementById('lblcmbTechnician').innerHTML = '';
	}
	if(document.AssignTech.StartDate.value==''){
		document.getElementById('lblStartDate').innerHTML='This field is required';
		document.AssignTech.StartDate.focus();
		return false;
	}else{
		document.getElementById('lblStartDate').innerHTML='';
	}
	if(document.AssignTech.StartTime.value==''){
		document.getElementById('lblStartTime').innerHTML='This field is required';
		document.AssignTech.StartTime.focus();
		return false;
	}else{
		document.getElementById('lblStartTime').innerHTML='';
	}
	if(document.AssignTech.EndTime.value==''){
		document.getElementById('lblStartTime').innerHTML='This field is required';
		document.AssignTech.EndTime.focus();
		return false;
	}else{
		document.getElementById('lblStartTime').innerHTML='';
	}
	var chk=$('input:checkbox[name=chkWO]:checked').length;
	if(chk == 0){
		document.getElementById('lblchkWO').innerHTML = 'Please select checkbox';
		return false;
	}else{
		document.getElementById('lblchkWO').innerHTML = '';
	}
	return true;	
}
function update_data(){
	$.fancybox.showActivity();	
	var url="schedule-technician.php";	
	var x=validate_assigntech();
	var cmbTechnician=$('#cmbTechnician').val();
	var StartDate=$('#StartDate').val();
	var StartTime=$('#StartTime').val();
	var EndTime=$('#EndTime').val();
	var chkWO=$('#chkWO').val();
	var woid=$('#work_id').val();
	if(x){
	 	$.post(url,{"choice":"data_update","cmbTechnician":cmbTechnician,"StartDate":StartDate,"StartTime":StartTime,"EndTime":EndTime,"chkWO":chkWO,"woid":woid},function(res){
		 if(res=='1'){
			window.location.href="tech-manage-job-board"; 
		 }else{			
			 $.fancybox(res,{centerOnScroll:true,hideOnOverlayClick:false});			
		 }
	 	});
	}else{
		return false; 
	}
	
}
/*********Function to schedule job************/
/*********Function to show/hide************/
function funHide(clss,id){
	//alert(id);
	$('.'+clss).hide();
	$('#e'+id).show();
	$('#c'+id).hide();
}
function funShow(clss,id){
	//alert(id);
	$('.'+clss).show();
	$('#c'+id).show();
	$('#e'+id).hide();
}
function funHide1(clss,clss2){
	//$('.'+clss).hide();
	$('.'+clss2).hide();
	$('.hoa').show();
	$('.hob').hide();
	$('#expand').show();
	$('#colapse').hide();
}
function funShow1(clss,clss2){
	$('.'+clss).show();
	$('.'+clss2).show();
	$('.hoa').hide();
	$('.hob').show();
	$('#colapse').show();
	$('#expand').hide();
}
/*********Function to show/hide************/
/*********Function to print job************/
function print_doc(val,woid){
	if(val=='print'){
		 window.open("tech_job_board_print?id="+woid,'_blank');
    }
}
/*********Function to print job************/
function downLoadDocument(fname){
	window.location.href='../docdnd.php?file=workorder_doc/'+fname;
}
/*********Function to redirect page************/
function redirectPage(id){
	$("#hid").val(id);
	document.frmRedirect.action="tech-view-job-board";
	document.frmRedirect.submit();
}
/*********Function to redirect page************/
</script>
<link rel="stylesheet" href="../css/innermain.css" type="text/css" />
<link rel="stylesheet" href="../css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="../css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="../css/respmenu.css" type="text/css" />
<link rel="stylesheet" href="../css/no_more_table.css" type="text/css" />
<script  type="text/javascript" src="../js/dragtable.js"></script>
	<form name="frmRedirect" id="frmRedirect" action="" method="post"> 
    	<input type="hidden" name="id" id="hid" value=""/>
    </form>
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
                    	<?php
						  ##########STORE AND RETRIVE THE SEARCH CONDITION FROM SESSION##########
							//print "<pre>";
							//print_r($_SESSION['requestc']);								
							$FromDate=$_SESSION['requestc']['search']['srchDate']['FromDate'];
							$ToDate=$_SESSION['requestc']['search']['srchDate']['ToDate'];
							$srchCust=$_SESSION['requestc']['search']['srchCust'];
							$srchService=$_SESSION['requestc']['search']['srchService'];
							$srchStatus=$_SESSION['requestc']['search']['srchStatus'];
							$srchWono=$_SESSION['requestc']['search']['srchWono'];
							$srchPurchaseNo=$_SESSION['requestc']['search']['srchPurchaseNo'];
							$columnGroup=$_SESSION['requestc']['search']['columnGroup'];
							$columnOrder=$_SESSION['requestc']['search']['columnOrder'];
							$orderType=$_SESSION['requestc']['search']['orderType'];
						   ##########STORE AND RETRIVE THE SEARCH CONDITION FROM SESSION##########
						   ########insert string  for filter_search table###############
							$insert= "user_type='$_SESSION[usertype]',user_id='$_SESSION[userid]', page_name='dispatchboard'";
							########Search condition start here ###############
							$sch=""; 
							$fromdt= $FromDate?date("Y-m-d",strtotime($FromDate)):'';
							$todt= $ToDate?date("Y-m-d",strtotime($ToDate)):'';
							if($srchCust !=''){
								$implode_srchCust =implode(",",$srchCust);
								$sch=$sch."FIND_IN_SET(c.id,'$implode_srchCust') AND ";
								$insert.=",customers='$implode_srchCust'";
							}else{
								$insert.=",customers='$implode_srchCust'";
							}
							if($srchStatus !=''){
								$implode_srchStatus =implode(",",$srchStatus);
								$sch=$sch."FIND_IN_SET(w.work_status,'$implode_srchStatus') AND ";
								$insert.=",status='$implode_srchStatus'";
							}else{
								$insert.=",status='$implode_srchStatus'";
							}
						
							if($srchWono !=''){
								$implode_srchWono =implode(",",$srchWono);
								$sch=$sch."FIND_IN_SET(w.wo_no,'$implode_srchWono') AND ";
								$insert.=",wonos='$implode_srchWono'";
							}else{
								$insert.=",wonos='$implode_srchWono'";
							}
							if($srchPurchaseNo !=''){
								$implode_srchPurchaseNo =implode(",",$srchPurchaseNo);
								$sch=$sch."FIND_IN_SET(w.purchase_order_no,'$implode_srchPurchaseNo') AND ";
								$insert.=",purchasenos='$implode_srchPurchaseNo'";
							}else{
								$insert.=",purchasenos='$implode_srchPurchaseNo'";
							}
							if($srchService !=''){
								$implode_srchService =implode(",",$srchService);
								$sch=$sch."FIND_IN_SET(s.id,'$implode_srchService') AND ";
								$insert.=",services='$implode_srchService'";
							}else{
								$insert.=",services='$implode_srchService'";
							}
							if($FromDate !='' && $ToDate ==''){
								$sch=$sch."at.start_date >= '$fromdt' AND ";
							}
							if($FromDate =='' && $ToDate !=''){
								$sch=$sch."at.start_date <= '$todt' AND ";
							}
							if(($FromDate !='') && ($ToDate !='')){
								$sch=$sch."at.start_date BETWEEN '$fromdt' AND '$todt' AND ";
							}	
							$insert.=",from_date='$fromdt',to_date='$todt'";
							
							if($columnGroup !=''){
								$groupby = implode(",",$columnGroup);
								$insert.=",column_group='$groupby'";
							}else{
								$insert.=",column_group='$groupby'";
							}
							if($columnOrder !=''){
								$orderby = implode(",",$columnOrder);
								$insert.=",column_order='$orderby'";
							}else{
								$orderby = "w.id";
								$insert.=",column_order='$orderby'";
							}
							if($orderType !=''){
								$orderType =$orderType[0];
								$insert.=",order_type='$orderType'";
							}else{
								$orderType ="DESC";
								$insert.=",order_type='$orderType'";
							}
							//echo $insert;
						   ########insert or update  filter_search table###############
						   if($_SESSION['requestc']['schaction'] =='filtersch'){
							   $count_filter_search =$dbf->countRows("filter_search","user_type='$_SESSION[usertype]' AND user_id='$_SESSION[userid]' AND page_name='dispatchboard'");
							   if($count_filter_search){
									$dbf->updateTable("filter_search",$insert.",updated_date=now()","user_type='$_SESSION[usertype]' AND user_id='$_SESSION[userid]'  AND page_name='dispatchboard'");
							   }else{
									$dbf->insertSet("filter_search",$insert.",created_date=now()");
							   }
						   }
						   ########insert or update  filter_search table###############
						   $sch=substr($sch,0,-5);
						   //echo $sch;exit;
						   if($sch!=''){
							 $cond="c.state=st.state_code AND c.id=w.client_id AND w.service_id=s.id AND w.wo_no=at.wo_no AND at.tech_id=t.id AND t.id='$_SESSION[userid]' AND ".$sch;
							if($srchStatus==''){$cond.=" AND w.work_status IN('Dispatched','In Progress','Completed','Ready to Invoice','Invoiced','Assigned','Scheduled')";}
						  // echo $cond;exit;
						   }
						   elseif($sch==''){
							 $cond="c.state=st.state_code AND c.id=w.client_id AND w.service_id=s.id AND (w.work_status='Assigned' OR w.work_status='Dispatched') AND w.wo_no=at.wo_no AND at.tech_id=t.id AND t.id='$_SESSION[userid]' AND (at.start_date=CURDATE() OR at.start_date='0000-00-00')";
						   }
						  // echo $cond;
						   ########Search condition end here ###############
						   //count total orders
						   $num=$dbf->countRows("state st,clients c,service s,technicians t,assign_tech at,work_order w",$cond); 
						  ?>
                        <div class="headerbg">
                        	<div style="float:left; width:30%;"style="float:left;">Tech-job-Board</div>
                            <div style="float:left;width:30%; text-align:center;">Total : <?php echo $num;?> Orders</div>
                        	<div style="float:right; width:40%; text-align:right;"><input type="button" class="buttonText2" value="Edit Filter" onClick="edit_filter();"/></div>
                        </div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                              <form name="SrchFrm" id="SrchFrm" action="" method="post">
                              <input type="hidden" name="schaction"  id="schaction" value=""/>
                              <input type="hidden" name="page" value="DispatchBoard"/>
                              <div style="margin-bottom:5px;" align="center">
                                <div  class="formtextaddsrch"align="center">From:</div>
                                <div class="textboxcsrch">
                                <input type="text" class="textboxsrch datepick" name="search[srchDate][FromDate]" id="FromDate" value="<?php echo $_SESSION['requestc']['search']['srchDate']['FromDate'];?>" readonly></div>
                                <div  class="formtextaddsrch"align="center">To:</div>
                                <div class="textboxcsrch">
                                <input type="text" class="textboxsrch datepick" name="search[srchDate][ToDate]" id="ToDate" value="<?php echo $_SESSION['requestc']['search']['srchDate']['ToDate'];?>" readonly></div>
                                <div style="float:left; width:200px;">
                                <input type="button" class="buttonText2" name="SearchRecord" value="Filter Jobs" onClick="SubmitFields();"/>
                                <input type="button" class="buttonText2" name="Reset" value="Reset Filter" onClick="ClearFields();"/>
                               </div>
                               </div>
                              </form>
                              <div class="spacer"></div>
                              
                              <div id="sortTable">
                              <!-----Table area start------->
                                <table id="no-more-tables" class="draggable">
                                	<thead>
                                        <tr>
                                            <th width="6%">WO#</th>
                                            <th width="9%">CustomerName</th>
                                            <th width="8%">OrderStatus</th>
                                            <th width="7%">ServiceType</th>
                                            <th width="6%">Pickupcity</th>
                                            <th width="8%">PickupPhone</th>
                                            <th width="6%">DeliveryCity</th>
                                            <th width="8%">DeliveryState</th>
                                            <th width="8%">DeliveryPhone</th>
                                            <th width="8%">StartDate</th>
                                            <th width="6%">StartTime</th>
                                            <th width="7%">Schedule</th>
                                            <th width="7%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      <tr style="background-color:#f9f9f9;" class="ho">
                                    	<td valign="top" class="grheading">
                                        <div class="divgr">
                                			<a href="javascript:void(0);" onClick="funShow1('ho','ro');" id="expand" style="display:none;"><img  src="../images/expand.png" height="21" width="73" alt="Expand All" /></a> 
                                			<a href="javascript:void(0);" onClick="funHide1('ho','ro');" id="colapse" ><img  src="../images/collapse.png"  height="21" width="73" alt="Collapse All"/></a>
                                		</div>
                                        </td>
                                 		<td class="hiderow">&nbsp;</td>
                                        <td class="hiderow">&nbsp;</td>
                                        <td class="hiderow">&nbsp;</td>
                                        <td class="hiderow">&nbsp;</td>
                                        <td class="hiderow">&nbsp;</td>
                                        <td class="hiderow">&nbsp;</td>
                                        <td class="hiderow">&nbsp;</td>
                                        <td class="hiderow">&nbsp;</td>
                                        <td class="hiderow">&nbsp;</td>
                                        <td class="hiderow">&nbsp;</td>
                                        <td class="hiderow">&nbsp;</td>
                                        <td class="hiderow">&nbsp;</td>
                                        <td class="hiderow">&nbsp;</td>
                                    </tr>
                              		<?php 
										//group array
										$resGrArray=$dbf->fetchOrder("state st,clients c,service s,technicians t,assign_tech at,work_order w",$cond,"c.state ASC","c.state,st.state_name","c.state");
										//group by state loop
										foreach($resGrArray as $k=>$sgRes){
										$Cls="g$k";	
										$numres = $dbf->countRows("state st,clients c,service s,technicians t,assign_tech at,work_order w","c.state='$sgRes[state]' AND " .$cond);
									?>
									<tr style="background-color:#f9f9f9;">
                                    	<td valign="top" class="grheading">
                                        <div class="divgr">
                                		<a href="javascript:void(0);" onClick="funShow('<?php echo $Cls;?>','<?php echo $k;?>');" id="e<?php echo $k;?>" <?php if($k==0){?>style="display:none;" <?php }?> class="hoa"><img  src="../images/plus.gif" height="13" width="13"/>&nbsp;<?php echo $numres;?> Jobs in &nbsp;<span style="color:#ff9812;"><?php echo $sgRes['state_name'];?></span> assigned to Tech</a> 
                                		<a href="javascript:void(0);" onClick="funHide('<?php echo $Cls;?>','<?php echo $k;?>');" id="c<?php echo $k;?>" <?php if($k!=0){?>style="display:none;" <?php }?> class="hob"><img  src="../images/minus.gif" height="13" width="13"/>&nbsp;<?php echo $numres;?> Jobs in &nbsp;<span style="color:#ff9812;"><?php echo $sgRes['state_name'];?></span> assigned to Tech</a>
                                		</div>
                                        </td>
                                 		<td class="hiderow">&nbsp;</td>
                                        <td class="hiderow">&nbsp;</td>
                                        <td class="hiderow">&nbsp;</td>
                                        <td class="hiderow">&nbsp;</td>
                                        <td class="hiderow">&nbsp;</td>
                                        <td class="hiderow">&nbsp;</td>
                                        <td class="hiderow">&nbsp;</td>
                                        <td class="hiderow">&nbsp;</td>
                                        <td class="hiderow">&nbsp;</td>
                                        <td class="hiderow">&nbsp;</td>
                                        <td class="hiderow">&nbsp;</td>
                                        <td class="hiderow">&nbsp;</td>
                                        <td class="hiderow">&nbsp;</td>
                                    </tr>				
								    <?php
										$resArray=$dbf->fetchOrder("state st,clients c,service s,technicians t,assign_tech at,work_order w","c.state='$sgRes[state]' AND " .$cond,$orderby." ".$orderType,"st.state_name,c.name,w.*,s.service_name,at.start_date,at.start_time",$groupby);
										//print "<pre>";
										//print_r($resArray);
										foreach($resArray as $key=>$res_JobBoard) { 
										$pickupstate = $dbf->getDataFromTable("state","state_name","state_code='$res_JobBoard[pickup_state]'");
										if($res_JobBoard['work_status']=='Completed' || $res_JobBoard['work_status']=='Invoiced' || $res_JobBoard['work_status']=='Ready to Invoice'){
											//check for payment completed work orders
											$paymentstatus = $dbf->getDataFromTable("work_order_bill","payment_status","wo_no='$res_JobBoard[wo_no]'");
											if($paymentstatus<>'Completed'){
												$color="#090";	
											}else{
												$color="#0FCBFF";
											}
											$statusFunction="Set_Workstatus_alert('c')";
										}else{
											$color='#F00';
											if($res_JobBoard['start_date']<>'0000-00-00'){
												$statusFunction = "Set_Workstatus('".$res_JobBoard['wo_no']."','".$res_JobBoard['id']."')";}else{$statusFunction="Set_Workstatus_alert('n')";}
										}
										if($res_JobBoard['work_status']=='Assigned' || $res_JobBoard['work_status']=='Scheduled'){
											$schldFunction ="ShowTechnicians('".$res_JobBoard['id']."')";
										}else{
											$schldFunction ="ShowTechnicians_alert()";
										}
										
								     ?>
                                    <tr class="<?php echo $Cls;?> ro" <?php if($k!=0){?> style="display:none;"<?php }?>>
                                    <input type="hidden" id="WorkOrder<?php echo $res_JobBoard['id'];?>" value="<?php echo $res_JobBoard['wo_no'];?>"/>
                                    <td data-title="WO#" class="coltext">
                                    <a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_JobBoard['id'];?>');" title="Click Here For Job Details" style="color:<?php echo $color;?>"><?php echo $res_JobBoard['wo_no'];?></a></td>
                                    <td data-title="CustomerName"><?php echo $dbf->cut($res_JobBoard['name'],15);?></td>
                                    <td data-title="OrderStatus" style="font-weight:bold;" id="workstatus" class="coltext"><?php if($res_JobBoard['work_status']<>''){?><a href="javascript:void(0);" onClick="Show_Workstatus('<?php echo $res_JobBoard['wo_no'];?>')" title="Click Here To See OrderStatus"><?php echo $res_JobBoard['work_status'];?></a><?php } else{echo 'Not Started';}?></td>
                                    <td data-title="ServiceType"><?php echo $res_JobBoard['service_name'];?></td>
                                    <td data-title="PickupState"><?php echo $pickupstate ;?></td>                                    <td data-title="Pickupcity"><?php echo $res_JobBoard['pickup_city'];?></td>
                                    <td data-title="PickupPhone"><?php echo $res_JobBoard['pickup_phone_no'];?></td>                                    <td data-title="DeliveryCity"><?php echo $res_JobBoard['city'];?></td>
                                    <td data-title="DeliveryState"><?php echo $res_JobBoard['state_name'];?></td>                                    <td data-title="DeliveryPhone"><?php echo $res_JobBoard['phone_no'];?></td>
                                    <td data-title="StartDate"><?php if($res_JobBoard['start_date']<>'0000-00-00'){echo date("d-M-Y",strtotime($res_JobBoard['start_date']));}else{echo 'None';}?></td>
                                    <td data-title="StartTime"><?php if($res_JobBoard['start_time']){echo $res_JobBoard['start_time'];}else{echo 'None';}?></td>
                                    <td data-title="Schedule" class="coltext"><a href="javascript:void(0);" onClick="<?php echo $schldFunction;?>"title="Click Here To Schedule Date">Schedule</a></td>          
                                    <td data-title="Action"><a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_JobBoard['id'];?>');"><img src="../images/view.png" title="View" alt="View"/></a>&nbsp;<a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_JobBoard['id'];?>');"><img src="../images/note.png" title="Add Notes" alt="Add Notes"/></a>&nbsp;<a href="javascript:void(0);" onClick="<?php echo $statusFunction;?>" title="Click Here To Set OrderStatus"><img src="../images/setworkorder.png" title="Click Here To Set OrderStatus" alt="Set workorder status" width="16" height="16"></a>&nbsp;<a href="javascript:void(0);"  onClick="print_doc('print','<?php echo $res_JobBoard['id'];?>');" ><img src="../images/print.png" alt="Print" title="Print Workorder" width="16" height="16"></a></td>
                               </tr>
                               <?php }
								}
							   ?>
                        	  </tbody>
                            </table>
                              <!-----Table area end------->
                            <?php if($num == 0) {?><div class="noRecords" align="center">No records founds!!</div><?php }?>
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