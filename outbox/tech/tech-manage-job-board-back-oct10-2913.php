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
?>
<script type="text/javascript">
function ClearFields(){
	$('#srchCust').val("");
	$('#srchClient').val("");
	$('#srchState').val("");
	$('#srchService').val("");
	$('#FromDate').val("");
	$('#ToDate').val("");
	document.SrchFrm.submit();
}
function Show_Workstatus(wono){
	$.fancybox.showActivity();
	var url="../technician_workstatus.php";
	$.post(url,{"choice":"workstatus","wono":wono},function(res){			
		$.fancybox(res,{centerOnScroll:true,hideOnOverlayClick:false});				
	});
}
function Set_Workstatus(wono){
	$.fancybox.showActivity();
	var url="tech-workorder-status.php";
	$.post(url,{"wono":wono},function(res){
		$.fancybox(res,{centerOnScroll:true,hideOnOverlayClick:false});
	});
}
/*********Function to schedule job************/
function ShowTechnicians(id){
	$.fancybox.showActivity();	
	var url="schedule-technician.php";
	var wono = $("#WorkOrder"+id).val();
	$.post(url,{"choice":"assign_job","wono":wono},function(res){			
		$.fancybox(res,{centerOnScroll:true,hideOnOverlayClick:false});				
	});
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
	if(x){
	 	$.post(url,{"choice":"data_update","cmbTechnician":cmbTechnician,"StartDate":StartDate,"StartTime":StartTime,"EndTime":EndTime,"chkWO":chkWO},function(res){
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
/*********Function to show/hide************/
/*********Function to print job************/
function print_doc(val,woid){
	if(val=='print'){
		 document.SrchFrm.action="tech_job_board_print.php?id="+woid;
		 document.SrchFrm.target="_blank";
		 document.SrchFrm.submit();
    }
}
/*********Function to print job************/
/*********Function to sort job board************/
function showSortRecords(){
	var cmbColumn=$('#cmbColumn').val();
	var cmbType=$('#cmbType').val();
	var frmdata = $('#SrchFrm').serialize();
	//alert(frmdata);
	var url="ajax-tech-manage-job-board.php?"+frmdata;
	if(cmbColumn !='' && cmbType !=''){
		$.post(url,{},function(res){
			$("#sortTable").html(res);
		});
	}
}
/*********Function to sort job board************/
</script>
<link rel="stylesheet" href="../css/innermain.css" type="text/css" />
<link rel="stylesheet" href="../css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="../css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="../css/respmenu.css" type="text/css" />
<link rel="stylesheet" href="../css/no_more_table.css" type="text/css" />
<script  type="text/javascript" src="../js/dragtable.js"></script>
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
                        <div class="headerbg"><div style="float:left;">Tech-job-Board</div>
                        	<div style="float:right;padding-right:10px;"></div>
                        </div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                              <form name="SrchFrm" id="SrchFrm" action="" method="post">
                              <div style="margin-bottom:5px;" align="center">
                              <div  class="formtextaddsrch" align="center">Customer</div>
                               <div class="textboxcsrch">
                                 <select name="srchCust" id="srchCust" class="selectboxsrch">
                                  		<option value="">--Select Customer--</option>
                                        <?php foreach($dbf->fetchOrder("work_order wo,clients cl","wo.client_id=cl.id AND wo.job_status='Assigned'","cl.id DESC","","cl.name")as $customer){?>
                                        <option value="<?php echo $customer['id']?>" <?php if($customer['id']==$_REQUEST['srchCust']){echo 'selected';}?>><?php echo $customer['name'];?></option>
                                        <?php }?>
                                 </select>
                                </div>
                               <div  class="formtextaddsrchsmall" align="center">Client</div>
                               <div class="textboxcsrch">
                                <select name="srchClient" id="srchClient" class="selectboxsrch">
                                  		<option value="">--Select Client--</option>
                                        <option value="0"> COD </option>
                                        <?php foreach($dbf->fetchOrder("work_order wo,clients cl","wo.created_by=cl.id AND wo.created_by<>'0' AND wo.job_status='Assigned'","cl.id DESC","","cl.name")as $client){?>
                                        <option value="<?php echo $client['id']?>" <?php if($client['id']==$_REQUEST['srchClient']){echo 'selected';}?>><?php echo $client['name'];?></option>
                                        <?php }?>
                                </select>
                                </div>
                                <div  class="formtextaddsrch"align="center">Service</div>
                                <div class="textboxcsrch">
                                <select name="srchService" id="srchService" class="selectboxsrch">
                                    <option value="">--Service Type--</option>
                                    <?php foreach($dbf->fetch("service","id")as $service){?>
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
                                <div style="float:left; width:200px;">
                                <input type="submit" class="buttonText2" name="SearchRecord" value="Filter Jobs">
                                <input type="button" class="buttonText2" name="Reset" value="Reset Filter" onClick="ClearFields();">
                               </div>
                               <div  class="formtextaddsrchsmall" align="center">Sort By:</div>
                               <div class="textboxcsrch" style="width:100px;">
                                <select class="selectboxsrch" name="cmbColumn" id="cmbColumn" onChange="showSortRecords();">
                                    <option value="">--Column--</option>
                                    <option value="w.wo_no"> WO# </option>
                                    <option value="c.name"> CustomerName</option>
                                    <option value="w.work_status"> WorkStatus</option>
                                    <option value="s.service_name"> ServiceType </option>
                                    <option value="w.pickup_state"> PickupState </option>
                                    <option value="w.pickup_city"> PickupCity </option>
                                    <option value="w.pickup_phone_no"> PickupPhone </option>
                                    <option value="c.city"> DeliveryCity </option>
                                    <option value="at.start_date"> StartDate </option>
                                    <option value="at.start_time"> StartTime </option>
                                </select>
                                </div>
                                <div class="textboxcsrchsmall" style="width:70px; margin-left:5px;">
                                <select class="selectboxsrch" name="cmbType" id="cmbType" onChange="showSortRecords();">
                                    <option value="">--Type--</option>
                                    <option value="ASC"> ASC </option>
                                    <option value="DESC"> DESC </option>
                                </select>
                                </div>
                               </div>
                              </form>
                              <div class="spacer"></div>
                              <?php
							   	$sch="";
								$fromdt=date("Y-m-d",strtotime(($_REQUEST['FromDate'])));
								$todt=date("Y-m-d",strtotime(($_REQUEST['ToDate'])));
								
								if($_REQUEST['srchCust']!=''){
									$sch=$sch."c.id='$_REQUEST[srchCust]' AND ";
								}
								if($_REQUEST['srchClient']!=''){
									$sch=$sch."w.created_by='$_REQUEST[srchClient]' AND ";
								}
								if($_REQUEST['srchService']!=''){
									$sch=$sch."s.id='$_REQUEST[srchService]' AND ";
								}
								if($_REQUEST['FromDate']!='' && $_REQUEST['ToDate']==''){
									$sch=$sch."at.start_date = '$fromdt' AND ";
								}
								if($_REQUEST['FromDate']=='' && $_REQUEST['ToDate']!=''){
									$sch=$sch."at.start_date = '$todt' AND ";
								}
								if(($_REQUEST['FromDate']!='') && ($_REQUEST['ToDate']!='')){
									$sch=$sch."at.start_date BETWEEN '$fromdt' AND '$todt' AND ";
								}
							   $sch=substr($sch,0,-5);
							   //echo $sch;exit;
							   if($sch!=''){
								 $cond="c.state=st.state_code AND c.id=w.client_id AND w.service_id=s.id AND w.job_status='Assigned' AND w.wo_no=at.wo_no AND at.tech_id=t.id AND t.id='$_SESSION[userid]' AND ".$sch;
							  // echo $cond;exit;
							   }
							   elseif($sch==''){
								 $cond="c.state=st.state_code AND c.id=w.client_id AND w.service_id=s.id AND w.job_status='Assigned' AND w.wo_no=at.wo_no AND at.tech_id=t.id AND t.id='$_SESSION[userid]' AND (at.start_date=CURDATE() OR at.start_date='0000-00-00')";
							   }
							  ?>
                              <div id="sortTable">
                              <!-----Table area start------->
                                <table id="no-more-tables" class="draggable">
                                	<thead>
                                        <tr>
                                            <th width="6%">WO#</th>
                                            <th width="9%">CustomerName</th>
                                            <th width="8%">WorkStatus</th>
                                            <th width="7%">ServiceType</th>
                                            <th width="6%">PickupState</th>
                                            <th width="6%">Pickupcity</th>
                                            <th width="8%">PickupPhone</th>
                                            <th width="6%">DeliveryCity</th>
                                            <th width="8%">DeliveryState</th>
                                            <th width="8%">Client</th>
                                            <th width="8%">StartDate</th>
                                            <th width="6%">StartTime</th>
                                            <th width="7%">Schedule</th>
                                            <th width="7%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                              		<?php 
										$num=$dbf->countRows("state st,clients c,service s,technicians t,assign_tech at,work_order w",$cond); 
										$resGrArray=$dbf->fetchOrder("state st,clients c,service s,technicians t,assign_tech at,work_order w",$cond,"c.state ASC","st.*,c.*","c.state");
										//group by state loop
										foreach($resGrArray as $k=>$sgRes){
										$Cls="g$k";	
										$numres = $dbf->countRows("state st,clients c,service s,technicians t,assign_tech at,work_order w","c.state='$sgRes[state]' AND " .$cond);
									?>
									<tr style="background-color:#f9f9f9;">
                                    	<td valign="top" class="grheading">
                                        <div class="divgr">
                                		<a href="javascript:void(0);" onClick="funShow('<?php echo $Cls;?>','<?php echo $k;?>');" id="e<?php echo $k;?>" <?php if($k==0){?>style="display:none;" <?php }?>><img  src="../images/plus.gif" height="13" width="13"/>&nbsp;<?php echo $numres;?> Jobs in &nbsp;<span style="color:#ff9812;"><?php echo $sgRes['state_name'];?></span> assigned to Tech</a> 
                                		<a href="javascript:void(0);" onClick="funHide('<?php echo $Cls;?>','<?php echo $k;?>');" id="c<?php echo $k;?>" <?php if($k!=0){?>style="display:none;" <?php }?>><img  src="../images/minus.gif" height="13" width="13"/>&nbsp;<?php echo $numres;?> Jobs in &nbsp;<span style="color:#ff9812;"><?php echo $sgRes['state_name'];?></span> assigned to Tech</a>
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
										$resArray=$dbf->fetchOrder("state st,clients c,service s,technicians t,assign_tech at,work_order w","c.state='$sgRes[state]' AND " .$cond,"w.id DESC","st.state_name,c.*,w.*,s.service_name,at.start_date,at.start_time","");
										foreach($resArray as $key=>$res_JobBoard) { 
										$pickupstate = $dbf->getDataFromTable("state","state_name","state_code='$res_JobBoard[pickup_state]'");
										if($res_JobBoard['work_status']=='Completed'){
											//check for payment completed work orders
											$paymentstatus = $dbf->getDataFromTable("work_order_bill","payment_status","wo_no='$res_JobBoard[wo_no]'");
											if($paymentstatus<>'Completed'){
												$color="#090";	
											}else{
												$color="#0FCBFF";
											}
											//$rcolor='background-color:#F3FCEB';
											$link = 'javascript:void(0)';
										}else{
											//$rcolor='';
											$color='#F00';
											$link = 'edit-job-board?id='.$res_JobBoard['id'].'&src=disp';
										}
										
										if($res_JobBoard['created_by']<>'0'){
											$clientname=$dbf->getDataFromTable("clients","name","id='$res_JobBoard[created_by]'");}else{
											$clientname="COD";
										}
								     ?>
                                    <tr class="<?php echo $Cls;?>"<?php if($k!=0){?> style="display:none;"<?php }?>>
                                    <input type="hidden" id="WorkOrder<?php echo $res_JobBoard['id'];?>" value="<?php echo $res_JobBoard['wo_no'];?>"/>
                                    <td data-title="WO#" class="coltext">
                                    <a href="tech-view-job-board?id=<?php echo $res_JobBoard['id'];?>" title="Click Here For Job Details" style="color:<?php echo $color;?>"><?php echo $res_JobBoard['wo_no'];?></a></td>
                                    <td data-title="CustomerName"><?php echo $res_JobBoard['name'];?></td>
                                    <td data-title="WorkStatus" style="font-weight:bold;" id="workstatus" class="coltext"><?php if($res_JobBoard['work_status']<>''){?><a href="javascript:void(0);" onClick="Show_Workstatus('<?php echo $res_JobBoard['wo_no'];?>')" title="Click Here To See WorkStatus"><?php echo $res_JobBoard['work_status'];?></a><?php } else{echo 'Not Started';}?></td>
                                    <td data-title="ServiceType"><?php echo $res_JobBoard['service_name'];?></td>
                                    <td data-title="PickupState"><?php echo $pickupstate ;?></td>                                    <td data-title="Pickupcity"><?php echo $res_JobBoard['pickup_city'];?></td>
                                    <td data-title="PickupPhone"><?php echo $res_JobBoard['pickup_phone_no'];?></td>                                    <td data-title="DeliveryCity"><?php echo $res_JobBoard['city'];?></td>
                                    <td data-title="DeliveryState"><?php echo $res_JobBoard['state_name'];?></td>                                    <td data-title="Client"><?php echo $clientname;?></td>
                                    <td data-title="StartDate"><?php if($res_JobBoard['start_date']<>'0000-00-00'){echo date("d-M-Y",strtotime($res_JobBoard['start_date']));}else{echo 'None';}?></td>
                                    <td data-title="StartTime"><?php if($res_JobBoard['start_time']){echo $res_JobBoard['start_time'];}else{echo 'None';}?></td>
                                    <td data-title="Schedule" class="coltext"><a href="javascript:void(0);" <?php if($res_JobBoard['work_status']<>'Completed'){?>onClick="ShowTechnicians('<?php echo $res_JobBoard['id'];?>')"<?php }?> title="Click Here To Schedule Date">Schedule</a></td>          
                                    <td data-title="Action"><a href="tech-view-job-board?id=<?php echo $res_JobBoard['id'];?>"><img src="../images/view.png" title="View" alt="View"/></a>&nbsp;&nbsp;<a href="javascript:void(0);" <?php if($res_JobBoard['work_status']<>'Completed'){?>onClick="Set_Workstatus('<?php echo $res_JobBoard['wo_no'];?>')" <?php }?> title="Click Here To Set WorkStatus"><img src="../images/setworkorder.png" title="Click Here To Set WorkStatus" alt="Set workorder status" width="16" height="16"></a>&nbsp;&nbsp;<a href="javascript:void(0);"  onClick="print_doc('print','<?php echo $res_JobBoard['id'];?>');" ><img src="../images/print.png" alt="" style="width:20px; height:20px;" title="Print Workorder"></a></td>
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