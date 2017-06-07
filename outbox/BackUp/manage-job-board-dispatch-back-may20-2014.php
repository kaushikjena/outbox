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
	###########Cancel record from work order Table###############
	if(isset($_REQUEST['action']) && $_REQUEST['action']=='cancel'){	
		$dbf->updateTable("work_order","job_status='Cancelled'","id='$_REQUEST[id]'");
		header("Location:manage-job-board-dispatch");exit;
	}
	###########Cancel record from work order Table###############
	$_SESSION['requestp']= $_SESSION['requestp']?$_SESSION['requestp']:array();
	if(isset($_REQUEST['schaction']) && $_REQUEST['schaction'] =='filtersch'){
		$_SESSION['requestp']=$_REQUEST;
	}
?>
<body>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />
<link rel="stylesheet" href="css/no_more_table.css" type="text/css" />
<script  type="text/javascript" src="js/dragtable.js"></script>
<!--<script  type="text/javascript" src="js/sorttable.js"></script>-->
<script type="text/javascript">
function SubmitFields(){
	$("#schaction").val("filtersch");
	document.SrchFrm.submit();
}
function ClearFields(){
	$('#srchCust').val("");
	$('#srchClient').val("");
	$('#srchTechnician').val("");
	$('#srchState').val("");
	$('#srchService').val("");
	$('#srchStatus').val("");
	$('#FromDate').val("");
	$('#ToDate').val("");
	$.post("unset-session.php",{"src":"disp"},function(res){
		$("#schaction").val("");
		document.SrchFrm.submit();
	});
}
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
/*********Function to expand and collapse group************/
/*********Function to show create job************/
function add_job(){
	$.fancybox.showActivity();
	var url="ajax-create-job.php";
	$.post(url,{"choice":"create"},function(res){
		$.fancybox(res,{centerOnScroll:true,hideOnOverlayClick:false});
	});
}
function closeFancyBox(){
	$.fancybox.close();
}
function edit_filter(){
	window.location.href="edit-filter-search";
}
/*********Function to show create job************/
/*********Function to sort job board************/
function showSortRecords(){
	var cmbColumn=$('#cmbColumn').val();
	var cmbType=$('#cmbType').val();
	var frmdata = $('#SrchFrm').serialize();
	//alert(frmdata);
	var url="ajax-manage-job-board-dispatch.php?"+frmdata;
	if(cmbColumn !='' && cmbType !=''){
		$.post(url,{},function(res){
			$("#sortTable").html(res);
		});
	}
}
/*********Function to sort job board************/
/*********Function to redirect page************/
function redirectPage(id,page){
	$("#hid").val(id);
	document.frmRedirect.action=page;
	document.frmRedirect.submit();
}
/*********Function to redirect page************/
/*********Function to print job************/
function print_doc(val,woid){
	if(val=='print'){
		window.open("admin_job_board_print.php?id="+woid,'_blank');
    }else if(val=='pdf'){
		window.location.href="admin_job_board_pdf.php?id="+woid;
    }
}
/*********Function to print job************/
/**********Send Notification to assigned Tech*********/
function send_email_tech(woid,wono){
	$.fancybox.showActivity();	
	var url="send-email-technician.php";
	$.post(url,{"choice":"show_email","wono":wono,"woid":woid},function(res){			
		$.fancybox(res,{centerOnScroll:true,hideOnOverlayClick:false});				
	});
}
function validate_assig_email(){
	if(document.frmEmailTech.fromemail.value == ''){
		document.getElementById('lblfromemail').innerHTML = 'This field is required';
		document.frmEmailTech.fromemail.focus();
		return false;
	}else{
		document.getElementById('lblfromemail').innerHTML = '';
	}
	if(document.frmEmailTech.fromname.value==''){
		document.getElementById('lblfromname').innerHTML='This field is required';
		document.frmEmailTech.fromname.focus();
		return false;
	}else{
		document.getElementById('lblfromname').innerHTML='';
	}
	if(document.frmEmailTech.subject.value==''){
		document.getElementById('lblsubject').innerHTML='This field is required';
		document.frmEmailTech.subject.focus();
		return false;
	}else{
		document.getElementById('lblsubject').innerHTML='';
	}
	return true;	
}
function sendEmail(){
	$.fancybox.showActivity();	
	var url="send-email-technician.php";	
	var x=validate_assig_email();
	var fromemail=$('#fromemail').val();
	var fromname=$('#fromname').val();
	var subject=$('#subject').val();
	var message = CKEDITOR.instances['message'].getData();
	//alert(message);
	var woid=$('#woid').val();
	var wono=$('#wono').val();
	if(x){
	 	$.post(url,{"choice":"send_email","wono":wono,"woid":woid,"fromemail":fromemail,"fromname":fromname,"subject":subject,"message":message},function(res){ 
		 if(res=='1'){
			$.fancybox("Email Send Successfully",{centerOnScroll:true,hideOnOverlayClick:false});
		 }else{			
			 $.fancybox("Email Sending failed",{centerOnScroll:true,hideOnOverlayClick:false});			
		 }
	 	});
	}else{
		return false; 
	}
	
}
/**********Send Notification to assigned Tech*********/
</script>
	<form name="frmRedirect" id="frmRedirect" action="" method="post"> 
    	<input type="hidden" name="id" id="hid" value=""/>
        <input type="hidden" name="src" value="disp"/>
    </form>
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
                        <div class="headerbg"><div style="float:left;">Dispatch-job-Board</div>
                        <div style="float:right;"><input type="button" class="buttonText2" value="Edit Filter" onClick="edit_filter();"/> <input type="button" class="buttonText2" value="Create Jobs" onClick="add_job();"/></div>
                        </div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                            <form name="SrchFrm" id="SrchFrm" action="" method="post">
                            <input type="hidden" name="schaction"  id="schaction" value=""/>
                               <div align="center">
                               <div  class="formtextaddsrch" align="center">Customer</div>
                                  <div class="textboxcsrch">
                                    <select name="srchCust" id="srchCust" class="selectboxsrch">
                                  		<option value="">--Select Customer--</option>
                                        <?php foreach($dbf->fetchOrder("work_order wo,clients cl","wo.client_id=cl.id AND wo.job_status='Assigned'","cl.name ASC","","cl.name")as $customer){?>
                                        <option value="<?php echo $customer['id']?>" <?php if($customer['id']==$_SESSION['requestp']['srchCust']){echo 'selected';}?>><?php echo $customer['name'];?></option>
                                        <?php }?>
                                   </select>
                                  </div>
                              	  <div  class="formtextaddsrchsmall" align="center">Client</div>
                                  <div class="textboxcsrch">
                                    <select name="srchClient" id="srchClient" class="selectboxsrch">
                                  		<option value="">--Select Client--</option>
                                        <option value="0" <?php if($_SESSION['requestp']['srchClient']=="0"){echo 'selected';}?>> COD </option>
                                        <?php foreach($dbf->fetchOrder("work_order wo,clients cl","wo.created_by=cl.id AND wo.created_by<>'0' AND wo.job_status='Assigned'","cl.name ASC","","cl.name")as $client){ ?>
                                        <option value="<?php echo $client['id']?>" <?php if($client['id']==$_SESSION['requestp']['srchClient']){echo 'selected';}?>><?php echo $client['name'];?></option>
                                        <?php }?>
                                   </select>
                                  </div>
                                  <div class="formtextaddsrchsmall" align="center">Tech</div>
                                  <div class="textboxcsrch">
                                        <select name="srchTechnician" id="srchTechnician" class="selectboxsrch">
                                            <option value="">--Select Tech--</option>
                                            <?php foreach($dbf->fetch("technicians","id>0 AND status=1 ORDER BY first_name ASC")as $tech){?>
                                            <option value="<?php echo $tech['id']?>" <?php if($tech['id']==$_SESSION['requestp']['srchTechnician']){echo 'selected';}?>><?php echo $tech['first_name'].'&nbsp;'.$tech['middle_name'].'&nbsp;'.$tech['last_name'];?></option>
                                            <?php }?>
                                        </select>
                                   </div>
                                  <div  class="formtextaddsrch"align="center">Service</div>
                                  <div class="textboxcsrch">
                                        <select name="srchService" id="srchService" class="selectboxsrch">
                                            <option value="">--Service Name--</option>
                                            <?php foreach($dbf->fetch("service","id>0 ORDER BY service_name ASC")as $service){?>
                                            <option value="<?php echo $service['id'];?>" <?php if($service['id']==$_SESSION['requestp']['srchService']){echo 'selected';}?>><?php echo $service['service_name'];?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                   <div  class="formtextaddsrch"align="center">Status</div>
                                  <div class="textboxcsrch">
                                    <select name="srchStatus" id="srchStatus"class="selectboxsrch">
                                        <option value="">--Select Status--</option>
                                        <option value="Dispatched" <?php if($_SESSION['requestp']['srchStatus']=='Dispatched'){echo 'selected';}?>>Dispatched</option>
                                        <option value="In Progress" <?php if($_SESSION['requestp']['srchStatus']=='In Progress'){echo 'selected';}?>>In Progress</option>
                                        <option value="Completed" <?php if($_SESSION['requestp']['srchStatus']=='Completed'){echo 'selected';}?>>Completed</option>
                                        <option value="Invoiced" <?php if($_SESSION['requestp']['srchStatus']=='Invoiced'){echo 'selected';}?>>Invoiced</option>
                                    </select>
                                  </div>
                                  <div  class="formtextaddsrchsmall"align="center">From:</div>
                                  <div class="textboxcsrchsmall">
                                      <input type="text" class="textboxsrch datepick" name="FromDate" id="FromDate" value="<?php echo $_SESSION['requestp']['FromDate'];?>" readonly>
                                  </div>
                                  <div  class="formtextaddsrchsmall"align="center">To:</div>
                                  <div class="textboxcsrchsmall">
                                      <input type="text" class="textboxsrch datepick" name="ToDate" id="ToDate" value="<?php echo $_SESSION['requestp']['ToDate'];?>" readonly>
                                  </div>
                                  </div>
                                  <div class="spacer"></div>
                                  <div style="float:right;">
                                    <div style="float:left;">
                                        <input type="button" class="buttonText2" name="SearchRecord" value="Filter Jobs" onClick="SubmitFields();">
                                        <input type="button" class="buttonText2" name="Reset" value="Reset Filter" onClick="ClearFields();">
                                     </div>
                                  	<div  class="formtextaddsrch" align="center">Sort By:</div>
                                    <div class="textboxcsrch" style="width:100px;">
                                    <select class="selectboxsrch" name="cmbColumn" id="cmbColumn" onChange="showSortRecords();">
                                    	<option value="">--Column--</option>
                                        <option value="w.wo_no"> WO# </option>
                                        <option value="c.name"> CustomerName</option>
                                        <option value="w.work_status"> WorkStatus</option>
                                        <option value="s.service_name"> ServiceType </option>
                                        <option value="w.pickup_state"> PickupState </option>
                                        <option value="w.pickup_city"> PickupCity </option>
                                        <option value="c.state"> DeliveryState</option>
                                        <option value="c.city"> DeliveryCity </option>
                                        <option value="t.first_name"> TechName </option>
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
							  	##########STORE AND RETRIVE THE SEARCH CONDITION FROM SESSION##########
							  	//print_r($_SESSION['requestp']);
								$FromDate=$_SESSION['requestp']['FromDate'];
								$ToDate=$_SESSION['requestp']['ToDate'];
								$srchCust=$_SESSION['requestp']['srchCust'];
								$srchClient=$_SESSION['requestp']['srchClient'];
								$srchTechnician=$_SESSION['requestp']['srchTechnician'];
								$srchService=$_SESSION['requestp']['srchService'];
								$srchStatus=$_SESSION['requestp']['srchStatus'];
								##########STORE AND RETRIVE THE SEARCH CONDITION FROM SESSION##########
							   	$sch=""; 
								$fromdt=date("Y-m-d",strtotime(($FromDate)));
								$todt=date("Y-m-d",strtotime(($ToDate)));
								
								if($srchCust !=''){
									$sch=$sch."c.id='$srchCust' AND ";
								}
								if($srchClient !=''){
									$sch=$sch."w.created_by='$srchClient' AND ";
								}
								if($srchTechnician !=''){
									$sch=$sch."t.id='$srchTechnician' AND ";
								}
								if($srchService !=''){
									$sch=$sch."s.id='$srchService' AND ";
								}
								if($srchStatus !=''){
									$sch=$sch."w.work_status='$srchStatus' AND ";
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
							   $sch=substr($sch,0,-5);
							   //echo $sch;exit;
							   if($sch!=''){
								 $cond="c.state=st.state_code AND c.id=w.client_id AND w.service_id=s.id AND w.job_status='Assigned' AND w.approve_status='1' AND w.wo_no=at.wo_no AND at.tech_id=t.id AND ".$sch;
							  // echo $cond;exit;
							   }
							   elseif($sch==''){
								 $cond="c.state=st.state_code AND c.id=w.client_id AND w.service_id=s.id AND w.job_status='Assigned' AND w.approve_status='1' AND w.wo_no=at.wo_no AND at.tech_id=t.id AND (at.start_date=CURDATE() OR at.start_date='0000-00-00')";
							   }
							   //echo $cond;
							  ?>
                              <div id="sortTable">
                              <!-----Table area start------->
                                <table id="no-more-tables" class="draggable">
                                	<thead>
                                        <tr>
                                            <th width="6%">WO#</th>
                                            <th width="9%">CustomerName</th>
                                            <th width="8%">WorkStatus</th>
                                            <th width="9%">ServiceType</th>
                                            <th width="6%">PickupState</th>
                                            <th width="6%">Pickupcity</th>
                                            <th width="8%">DeliveryState</th>
                                            <th width="6%">DeliveryCity</th>
                                            <th width="9%">Client</th>
                                            <th width="10%">TechName</th>
                                            <th width="7%">StartDate</th>
                                            <th width="6%">StartTime</th>
                                            <th width="12%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                     <tr style="background-color:#f9f9f9;">
                                    	<td valign="top" class="grheading">
                                        <div class="divgr">
                                			<a href="javascript:void(0);" onClick="funShow1('ho','ro');" id="expand" style="display:none;"><img  src="images/expand.png" height="21" width="73" alt="Expand All" /></a> 
                                			<a href="javascript:void(0);" onClick="funHide1('ho','ro');" id="colapse" ><img  src="images/collapse.png"  height="21" width="73" alt="Collapse All"/></a>
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
                                    </tr>
                              		<?php 
										$num=$dbf->countRows("state st,clients c,service s,technicians t,assign_tech at,work_order w",$cond); 
										$resGrArray=$dbf->fetchOrder("state st,clients c,service s,technicians t,assign_tech at,work_order w",$cond,"c.state ASC","st.*,c.*","c.state");
										//group by state loop
										foreach($resGrArray as $k=>$sgRes){
										$Cls="g$k";	
										$numres = $dbf->countRows("state st,clients c,service s,technicians t,assign_tech at,work_order w","c.state='$sgRes[state]' AND " .$cond);
									?>
									<tr style="background-color:#f9f9f9;" class="ho">
                                    	<td valign="top" class="grheading">
                                        <div class="divgr">
                                		<a href="javascript:void(0);" onClick="funShow('<?php echo $Cls;?>','<?php echo $k;?>');" id="e<?php echo $k;?>" <?php if($k==0){?>style="display:none;" <?php }?> class="hoa"><img  src="images/plus.gif" height="13" width="13"/>&nbsp;<?php echo $numres;?> Jobs in &nbsp;<span style="color:#ff9812;"><?php echo $sgRes['state_name'];?></span> assigned to Tech</a> 
                                		<a href="javascript:void(0);" onClick="funHide('<?php echo $Cls;?>','<?php echo $k;?>');" id="c<?php echo $k;?>" <?php if($k!=0){?>style="display:none;" <?php }?> class="hob"><img  src="images/minus.gif" height="13" width="13"/>&nbsp;<?php echo $numres;?> Jobs in &nbsp;<span style="color:#ff9812;"><?php echo $sgRes['state_name'];?></span> assigned to Tech</a>
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
                                    </tr>				
								    <?php
										$resArray=$dbf->fetchOrder("state st,clients c,service s,technicians t,assign_tech at,work_order w","c.state='$sgRes[state]' AND " .$cond,"w.id DESC","st.state_name,c.*,w.*,s.service_name,t.first_name,t.middle_name,t.last_name,at.start_date,at.start_time","");
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
											//$link = 'javascript:void(0)';
											//$link = 'edit-job-board?id='.$res_JobBoard['id'].'&src=disp';
										}else{
											//$rcolor='';
											$color='#F00';
											//$link = 'edit-job-board?id='.$res_JobBoard['id'].'&src=disp';
										}
										
										if($res_JobBoard['created_by']<>'0'){
											$clientname=$dbf->getDataFromTable("clients","name","id='$res_JobBoard[created_by]'");}else{
											$clientname="COD";
										}
								     ?>
                                  <tr class="<?php echo $Cls;?> ro" <?php if($k!=0){?> style="display:none;"<?php }?>>
                                    <td data-title="WO#" class="coltext">
                                    <a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_JobBoard['id'];?>','view-job-board');" title="Click Here For Job Details" style="color:<?php echo $color;?>"><?php echo $res_JobBoard['wo_no'];?></a></td>
                                    <td data-title="CustomerName"><?php echo $dbf->cut($res_JobBoard['name'],15);?></td>
                                    <td data-title="WorkStatus" style="font-weight:bold;" id="workstatus" class="coltext"><?php if($res_JobBoard['work_status']<>''){?><a href="javascript:void(0);" onClick="Show_Workstatus('<?php echo $res_JobBoard['wo_no'];?>')" title="Click Here To See WorkStatus"><?php echo $res_JobBoard['work_status'];?></a><?php } else{echo 'Not Started';}?></td>
                                    <td data-title="ServiceType"><?php echo $res_JobBoard['service_name'];?></td>
                                    <td data-title="PickupState"><?php echo $pickupstate ;?></td>                                    <td data-title="Pickupcity"><?php echo $res_JobBoard['pickup_city'];?></td>
                                    <td data-title="DeliveryState"><?php echo $res_JobBoard['state_name'];?></td>
                                    <td data-title="DeliveryCity"><?php echo $res_JobBoard['city'];?></td>
                                    <td data-title="Client"><?php echo $clientname;?></td>
                                    <td data-title="TechName"><?php echo $res_JobBoard['first_name'].' '.$res_JobBoard['middle_name'].' '.$res_JobBoard['last_name'];?></td>
                                    <td data-title="StartDate"><?php if($res_JobBoard['start_date']<>'0000-00-00'){echo date("d-M-Y",strtotime($res_JobBoard['start_date']));}else{echo 'None';}?></td>
                                    <td data-title="StartTime"><?php if($res_JobBoard['start_time']){echo $res_JobBoard['start_time'];}else{echo 'None';}?></td>
                                    <td data-title="Action"><a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_JobBoard['id'];?>','edit-job-board');"><img src="images/edit.png" title="Edit" alt="Edit"/></a>&nbsp;<a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_JobBoard['id'];?>','view-job-board');"><img src="images/view.png" title="View" alt="View"/></a>&nbsp;<a href="manage-job-board-dispatch?action=cancel&id=<?php echo $res_JobBoard['id'];?>"><img src="images/cancel_round.png" title="Cancel" alt="Cancel"/></a>&nbsp;<a href="javascript:void(0);"  onClick="print_doc('print','<?php echo $res_JobBoard['id'];?>');" ><img src="images/print.png" alt="Print"  title="Print Workorder"></a>&nbsp;<a href="javascript:void(0);" onClick="print_doc('pdf','<?php echo $res_JobBoard['id'];?>');"><img src="images/pdf.png" style="width:16px; height:16px;" title="Export to PDF"/></a>&nbsp;<a href="javascript:void(0);" onClick="send_email_tech('<?php echo $res_JobBoard['id'];?>','<?php echo $res_JobBoard['wo_no'];?>');"><img src="images/email_go.png" title="Email To Tech" alt="Email"></a></td>
                               </tr>
                               <?php }
								}
							   ?>
                        	  </tbody>
                            </table>
                              <!-----Table area end------->
                            <?php if($num == 0){?><div class="noRecords" align="center">No records founds!!</div><?php }?>
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