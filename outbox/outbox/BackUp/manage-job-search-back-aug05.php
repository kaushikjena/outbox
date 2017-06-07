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
	//Delete record from users Table
	if($_REQUEST['action']=='delete')
	{	
	    $dbf->deleteFromTable("workorder_service","workorder_id='$_REQUEST[id]'");
		$dbf->deleteFromTable("work_order","id='$_REQUEST[id]'");
		header("Location:manage-job-search");exit;
	}
?>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />
<link rel="stylesheet" href="css/no_more_table.css" type="text/css" />
<script  type="text/javascript" src="js/dragtable.js"></script>
<!--<script  type="text/javascript" src="js/sorttable.js"></script>-->
<script type="text/javascript">
/*********Function to assign tech************/
function ShowTechnicians(id){
	$.fancybox.showActivity();	
	var url="assign-technician.php";
	var wono = $("#WorkOrder"+id).val();
	$.post(url,{"choice":"assign_job","wono":wono,"wo_id":id},function(res){			
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
	var chk=$('input:checkbox[name=chkWO]:checked').length;
	if(chk == 0){
		document.getElementById('lblchkWO').innerHTML = 'Please select checkbox';
		return false;
	}else{
		document.getElementById('lblchkWO').innerHTML = '';
	}
	return true;	
}
function insert_data(){
	$.fancybox.showActivity();	
	var url="assign-technician.php";	
	var x=validate_assigntech();
	var cmbTechnician=$('#cmbTechnician').val();
	var chkWO=$('#chkWO').val();
	var work_id=$('#work_id').val();
	if(x){
	 	$.post(url,{"choice":"data_insert","cmbTechnician":cmbTechnician,"chkWO":chkWO,"work_id":work_id},function(res){
		 if(res=='1'){
			window.location.href="manage-job-search"; 
		 }else{			
			 $.fancybox(res,{centerOnScroll:true,hideOnOverlayClick:false});			
		 }
	 	});
	}else{
		return false; 
	}
}
/*********Function to assign tech************/
function Show_Workstatus(wono){
	$.fancybox.showActivity();
	var url="technician_workstatus.php";
	$.post(url,{"choice":"workstatus","wono":wono},function(res){			
		$.fancybox(res,{centerOnScroll:true,hideOnOverlayClick:false});				
	});
}
/*********Function to expand and collapse group************/
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
function funHide1(clss,id){
	//alert(id);
	$('.'+clss).hide();
	$('#ae'+id).show();
	$('#ac'+id).hide();
}
function funShow1(clss,id){
	//alert(id);
	$('.'+clss).show();
	$('#ac'+id).show();
	$('#ae'+id).hide();
}
/*********Function to expand and collapse group************/
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
                        <div class="headerbg"><div style="float:left;">Search Job Board</div>
                        </div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        <div class="spacer"></div>
                              <?php
							   	$sch="";$sch1="";
								if($_REQUEST['srchInputBox']!=''){
									###########Search condition for open jobs################
									$sch=$sch."w.wo_no='$_REQUEST[srchInputBox]' OR c.name LIKE '$_REQUEST[srchInputBox]%' OR c.email LIKE '$_REQUEST[srchInputBox]%' OR w.pickup_phone_no='$_REQUEST[srchInputBox]' OR w.pickup_address LIKE '$_REQUEST[srchInputBox]%' OR w.pickup_city LIKE '$_REQUEST[srchInputBox]%' OR w.work_status LIKE '$_REQUEST[srchInputBox]%' OR c.phone_no='$_REQUEST[srchInputBox]' OR c.city LIKE '$_REQUEST[srchInputBox]%' OR c.address LIKE '$_REQUEST[srchInputBox]%' OR s.service_name LIKE '$_REQUEST[srchInputBox]%' OR ";
									$sch=substr($sch,0,-4);
									###########Search condition for open jobs################
									##########Search condition for assign jobs#################
									$sch1=$sch1."w.wo_no='$_REQUEST[srchInputBox]' OR c.name LIKE '$_REQUEST[srchInputBox]%' OR c.email LIKE '$_REQUEST[srchInputBox]%' OR t.first_name LIKE '$_REQUEST[srchInputBox]%' OR t.middle_name LIKE '$_REQUEST[srchInputBox]%' OR t.last_name LIKE '$_REQUEST[srchInputBox]%' OR w.pickup_phone_no='$_REQUEST[srchInputBox]' OR w.pickup_address LIKE '$_REQUEST[srchInputBox]%' OR w.pickup_city LIKE '$_REQUEST[srchInputBox]%' OR w.work_status LIKE '$_REQUEST[srchInputBox]%' OR c.phone_no='$_REQUEST[srchInputBox]' OR c.city LIKE '$_REQUEST[srchInputBox]%' OR c.address LIKE '$_REQUEST[srchInputBox]%' OR s.service_name LIKE '$_REQUEST[srchInputBox]%' OR ";
									$sch1=substr($sch1,0,-4);
									##########Search condition for assign jobs#################
								}
								###########Search condition for open jobs################
							   if($sch!=''){
								 $cond="c.state=st.state_code AND c.id=w.client_id AND w.service_id=s.id AND w.work_status='Open' AND w.approve_status='1' AND (".$sch.")";
								 $tables = "state st,clients c,service s,work_order w";
							   }
							   elseif($sch==''){
								 $cond="c.state=st.state_code AND c.id=w.client_id AND w.service_id=s.id AND w.work_status='Open' AND w.approve_status='1'";
								 $tables = "state st,clients c,service s,work_order w";
							   }
							   
							   ###########Search condition for open jobs################
							   ##########Search condition for assign jobs#################
							   if($sch1!=''){
								 $cond1="c.state=st.state_code AND c.id=w.client_id AND w.service_id=s.id AND w.work_status IN('Dispatched','In Progress','Completed','Ready to Invoice','Invoiced','Assigned') AND w.approve_status='1' AND w.wo_no=at.wo_no AND at.tech_id=t.id AND (".$sch1.")";
								 $tables1 = "state st,clients c,service s,technicians t,assign_tech at,work_order w";
							   }
							   elseif($sch1==''){
								 $cond1="c.state=st.state_code AND c.id=w.client_id AND w.service_id=s.id AND w.work_status IN('Dispatched','In Progress','Completed','Ready to Invoice','Invoiced','Assigned') AND w.approve_status='1' AND w.wo_no=at.wo_no AND at.tech_id=t.id";
								 $tables1 = "state st,clients c,service s,technicians t,assign_tech at,work_order w";
							   }
							   //echo $cond1;
							   ##########Search condition for assign jobs#################
							  ?>
                             <!---------Open Job Table-------------->
                             <div align="center" class="heading">Open Jobs</div>
                             <?php $num=$dbf->countRows($tables,$cond);
							   if($num > 0){ ?>
                               <!-----Table area start------->
                                <table id="no-more-tables" class="draggable">
                                    <thead>
                                        <tr>
                                            <th width="6%">WO#</th>
                                            <th width="9%">CustomerName</th>
                                            <th width="8%">CreatedDate</th>
                                            <th width="6%">OrderStatus</th>
                                            <th width="8%">ServiceType</th>
                                            <th width="7%">Pickupcity</th>
                                            <th width="6%">PickupState</th>
                                            <th width="8%">PickupPhone</th>
                                            <th width="8%">DeliveryCity</th>
                                            <th width="6%">DeliveryState</th>
                                            <th width="8%">DeliveryPhone</th>
                                            <th width="8%">Client</th>
                                            <th width="6%">Assign</th>
                                            <th width="6%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                     <?php 
										$resGrArray=$dbf->fetchOrder($tables,$cond,"c.state ASC","st.*,c.*","c.state");
										//group by state loop
										foreach($resGrArray as $k=>$sgRes){
										$Cls="g$k";	
										$numres = $dbf->countRows($tables,"c.state='$sgRes[state]' AND " .$cond);
									  ?>
										<tr style="background-color:#f9f9f9;">
                                            <td valign="top" class="grheading">
                                            <div class="divgr">
                                            <a href="javascript:void(0);" onClick="funShow('<?php echo $Cls;?>','<?php echo $k;?>');" id="e<?php echo $k;?>" <?php if($k==0){?>style="display:none;" <?php }?>><img  src="images/plus.gif" height="13" width="13"/>&nbsp;<?php echo $numres;?> Open Jobs in <span style="color:#ff9812;"><?php echo $sgRes['state_name'];?></span> needs to be assigned</a> 
                                			<a href="javascript:void(0);" onClick="funHide('<?php echo $Cls;?>','<?php echo $k;?>');" id="c<?php echo $k;?>" <?php if($k!=0){?>style="display:none;" <?php }?>><img  src="images/minus.gif" height="13" width="13"/>&nbsp;<?php echo $numres;?> Open Jobs in <span style="color:#ff9812;"><?php echo $sgRes['state_name'];?></span> needs to be assigned</a>
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
											$resArray=$dbf->fetchOrder($tables,"c.state='$sgRes[state]' AND " .$cond,"w.id DESC","st.state_name,c.*,s.service_name,w.*","");
											foreach($resArray as $key=>$res_JobBoard) { 
											$pickupstate = $dbf->getDataFromTable("state","state_name","state_code='$res_JobBoard[pickup_state]'");
											if($res_JobBoard['work_status']=='Open'){$color='#333';}	
											//get client name
											if($res_JobBoard['created_by']<>0){
												$clientname =$dbf->getDataFromTable("clients","name","id='$res_JobBoard[created_by]'");
											}else{
												$clientname="COD";
											}							
										?>   
                                    	<tr class="<?php echo $Cls;?>" <?php if($k!=0){?> style="display:none;" <?php } ?>>
                                        	<input type="hidden" id="WorkOrder<?php echo $res_JobBoard['id'];?>" value="<?php echo $res_JobBoard['wo_no'];?>"/>
                                            <td data-title="WO#" class="coltext"><a href="view-job-board?id=<?php echo $res_JobBoard['id'];?>" title="Click Here For Job Details" style="color:<?php echo $color;?>"><?php echo $res_JobBoard['wo_no'];?></a></td>
                                            <td data-title="CustomerName"><?php echo $res_JobBoard['name'];?></td>
                                            <td data-title="CreatedDate"><?php echo date("d-M-Y",strtotime($res_JobBoard['created_date']));?></td>
                                            <td data-title="JobStatus" class="coltext" style="color:<?php echo $color;?>"><?php echo $res_JobBoard['work_status'];?></td>
                                            <td data-title="ServiceType"><?php echo $res_JobBoard['service_name'];?></td>
                                            <td data-title="Pickupcity"><?php echo $res_JobBoard['pickup_city'];?></td>
                                            <td data-title="PickupState"><?php echo $pickupstate;?></td>
                                            <td data-title="PickupPhone" ><?php echo $res_JobBoard['pickup_phone_no'];?></td>
                                            <td data-title="DeliveryCity"><?php echo $res_JobBoard['city'];?></td>
                                            <td data-title="DeliveryState"><?php echo $res_JobBoard['state_name'];?></td>
                                            <td data-title="DeliveryPhone"><?php echo $res_JobBoard['phone_no'];?></td>
                                            <td data-title="Client" class="coltext"><?php echo $clientname;?></td>
                                            <td data-title="Assign" class="coltext"><a href="javascript:void(0);" onClick="ShowTechnicians('<?php echo $res_JobBoard['id'];?>');" title="Click Here To Assign Tech">Assign</a></td>
                                            <td data-title="Action"><a href="edit-job-board?id=<?php echo $res_JobBoard['id']?>"><img src="images/edit.png" title="Edit" alt="Edit"/></a>&nbsp;<a href="view-job-board?id=<?php echo $res_JobBoard['id'];?>"><img src="images/view.png" title="View" alt="View"/></a>&nbsp;<a href="manage-job-search?action=delete&id=<?php echo $res_JobBoard['id'];?>" onClick="return confirm('Are you sure you want to delete this record ?')"><img src="images/delete.png" title="delete" alt="delete"></a></td>
                                        </tr>
                                         <?php } 
											}
										?> 
                                    </tbody>
                               </table>
                              <!-----Table area start-------> 
                             <?php }else {?><div class="noRecords" align="center">No records founds!!</div><?php }?>
                             <div class="spacer"></div>
                             <!---------Open Job Table-------------->
                             <!---------Assign Job Table-------------->
                             <div align="center" class="heading"> Assign Jobs</div>
                             <?php $num1=$dbf->countRows($tables1,$cond1); 
							   if($num1 > 0){ ?>
                              <!-----Table area start------->
                                <table id="no-more-tables" class="draggable">
                                	<thead>
                                        <tr>
                                            <th width="6%">WO#</th>
                                            <th width="9%">CustomerName</th>
                                            <th width="8%">OrderStatus</th>
                                            <th width="7%">ServiceType</th>
                                            <th width="6%">PickupState</th>
                                            <th width="6%">Pickupcity</th>
                                            <th width="8%">PickupPhone</th>
                                            <th width="6%">DeliveryCity</th>
                                            <th width="8%">DeliveryState</th>
                                            <th width="8%">Client</th>
                                            <th width="9%">TechName</th>
                                            <th width="8%">StartDate</th>
                                            <th width="6%">StartTime</th>
                                            <th width="5%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                              		<?php 
										$resGrArray=$dbf->fetchOrder($tables1,$cond1,"c.state ASC","st.*,c.*","c.state");
										//group by state loop
										foreach($resGrArray as $k=>$sgRes){
										$Cls="ag$k";	
										$numres = $dbf->countRows($tables1,"c.state='$sgRes[state]' AND " .$cond1);
									?>
									<tr style="background-color:#f9f9f9;">
                                    	<td valign="top" class="grheading">
                                        <div class="divgr">
                                		<a href="javascript:void(0);" onClick="funShow1('<?php echo $Cls;?>','<?php echo $k;?>');" id="ae<?php echo $k;?>" <?php if($k==0){?>style="display:none;" <?php }?>><img  src="images/plus.gif" height="13" width="13"/>&nbsp;<?php echo $numres;?> Jobs in &nbsp;<span style="color:#ff9812;"><?php echo $sgRes['state_name'];?></span> assigned to Tech</a> 
                                		<a href="javascript:void(0);" onClick="funHide1('<?php echo $Cls;?>','<?php echo $k;?>');" id="ac<?php echo $k;?>" <?php if($k!=0){?>style="display:none;" <?php }?>><img  src="images/minus.gif" height="13" width="13"/>&nbsp;<?php echo $numres;?> Jobs in &nbsp;<span style="color:#ff9812;"><?php echo $sgRes['state_name'];?></span> assigned to Tech</a>
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
										$resArray=$dbf->fetchOrder($tables1,"c.state='$sgRes[state]' AND " .$cond1,"w.id DESC","st.state_name,c.*,w.*,s.service_name,t.first_name,t.middle_name,t.last_name,at.start_date,at.start_time","");
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
                                    <td data-title="WO#" class="coltext">
                                    <a href="view-job-board?id=<?php echo $res_JobBoard['id'];?>&src=disp" title="Click Here For Job Details" style="color:<?php echo $color;?>"><?php echo $res_JobBoard['wo_no'];?></a></td>
                                    <td data-title="CustomerName"><?php echo $res_JobBoard['name'];?></td>
                                    <td data-title="WorkStatus" style="font-weight:bold;" id="workstatus" class="coltext"><?php if($res_JobBoard['work_status']<>''){?><a href="javascript:void(0);" onClick="Show_Workstatus('<?php echo $res_JobBoard['wo_no'];?>')" title="Click Here To See WorkStatus"><?php echo $res_JobBoard['work_status'];?></a><?php } else{echo 'Not Started';}?></td>
                                    <td data-title="ServiceType"><?php echo $res_JobBoard['service_name'];?></td>
                                    <td data-title="PickupState"><?php echo $pickupstate ;?></td>                                    <td data-title="Pickupcity"><?php echo $res_JobBoard['pickup_city'];?></td>
                                    <td data-title="PickupPhone"><?php echo $res_JobBoard['pickup_phone_no'];?></td>                                    <td data-title="DeliveryCity"><?php echo $res_JobBoard['city'];?></td>
                                    <td data-title="DeliveryState"><?php echo $res_JobBoard['state_name'];?></td>                                    <td data-title="Client"><?php echo $clientname;?></td>
                                    <td data-title="TechName"><?php echo $res_JobBoard['first_name'].' '.$res_JobBoard['middle_name'].' '.$res_JobBoard['last_name'];?></td>
                                    <td data-title="StartDate"><?php echo date("d-M-Y",strtotime($res_JobBoard['start_date']));?></td>
                                    <td data-title="StartTime"><?php echo $res_JobBoard['start_time'];?></td>          
                                    <td data-title="Action"><a href="<?php echo $link;?>"><img src="images/edit.png" title="Edit" alt="Edit"/></a>&nbsp;&nbsp;<a href="view-job-board?id=<?php echo $res_JobBoard['id'];?>&src=srch"><img src="images/view.png" title="View" alt="View"/></a></td>
                               </tr>
                               <?php }
								}
							   ?>
                        	  </tbody>
                            </table>
                              <!-----Table area end------->
                            <?php }else {?><div class="noRecords" align="center">No records founds!!</div><?php }?>
                            <div class="spacer"></div>
                            <!---------Assign Job Table-------------->
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