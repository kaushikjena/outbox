<?php 
    ob_start("ob_gzhandler");
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
		$dbf->updateTable("work_order","work_status='Cancelled'","id='$_REQUEST[id]'");
		###########Track user activity in work order notes table#############
		$adminNotes="This order is cancelled.";
		$strnotes="workorder_id='$_REQUEST[id]', user_type='$_SESSION[usertype]', user_id='$_SESSION[userid]', wo_notes='$adminNotes',created_date=now()";
		$dbf->insertSet("workorder_notes",$strnotes);
		###########Track user activity in work order notes table#############
		header("Location:manage-job-board-assigned");exit;
	}
	###########Cancel record from work order Table###############
	$_SESSION['requesta']= $_SESSION['requesta']?$_SESSION['requesta']:array();
	if(isset($_REQUEST['schaction']) && $_REQUEST['schaction'] =='filtersch'){
		if($_REQUEST['page']=='AssignedBoard'){
			$_SESSION['requesta']['search']['srchDate']=$_REQUEST['search']['srchDate'];
			$_SESSION['requesta']['page']=$_REQUEST['page'];
		}elseif($_REQUEST['page']=='EditFilter'){
			$_SESSION['requesta']=$_REQUEST;
		}
	}
?>
<body>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />
<link rel="stylesheet" href="css/no_more_table.css" type="text/css" />
<style type="text/css">
	/* Easy CSS Tooltip - by Koller Juergen [www.kollermedia.at] 
	* {font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px; }*/
	a:hover {text-decoration:none;} /*BG color is a must for IE6*/
	a.tooltip span {display:none; padding:2px 3px 0px 5px; margin-left:6px; margin-top:-70px; width:280px;border-radius:5px;
	-moz-border-radius:5px;}
	a.tooltip:hover span{display:inline; position:absolute; border:3px solid  #ff9812; background:#EEEEEE; color:#000;border-radius:6px;-moz-border-radius:6px;}
	/* for second tooltips*/
	a.tooltip1 span {display:none; padding:2px 3px 0px 5px; margin-left:-65px; margin-top:-70px; width:150px;border-radius:5px;
	-moz-border-radius:5px;}
	a.tooltip1:hover span{display:inline; position:absolute; border:3px solid  #ff9812; background:#EEEEEE; color:#000;border-radius:6px;-moz-border-radius:6px;}
</style>
<script  type="text/javascript" src="js/dragtable.js"></script>
<script type="text/javascript">
function SubmitFields(){
	$("#schaction").val("filtersch");
	$("#SrchFrm").submit();
}
function ClearFields(){
	$('#FromDate').val("");
	$('#ToDate').val("");
	$.post("unset-session.php",{"src":"assigned"},function(res){
		$("#schaction").val("");
		$("#SrchFrm").submit();
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
	var implode_clients = $("#implode_clients").val();
	$.post(url,{"choice":"create","implode_clients":implode_clients},function(res){
		$.fancybox(res,{centerOnScroll:true,hideOnOverlayClick:false});
	});
}
function closeFancyBox(){
	$.fancybox.close();
}
function edit_filter(){
	window.location.href="edit-filter-search-assigned";
}
/*********Function to show create job************/
/*********Function to redirect page************/
function redirectPage(id,page){
	$("#hid").val(id);
	document.frmRedirect.action=page;
	document.frmRedirect.submit();
}
function cancel_order(id,page){
	var r =confirm("Are you sure you want to cancel this order?");
	if(r){
		window.location.href=page+"?action=cancel&id="+id;
	}else{
		return false;
	}
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
function showMessage(val){
	var url="send-email-technician.php";
	$.post(url,{"choice":"show_message","id":val},function(res){
		CKEDITOR.instances['message'].setData(res)	;	
	});
}
/**********Send Notification to assigned Tech*********/
</script>
	<form name="frmRedirect" id="frmRedirect" action="" method="post"> 
    	<input type="hidden" name="id" id="hid" value=""/>
        <input type="hidden" name="src" value="assigned"/>
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
                    	<?php
							##########RETRIVE THE SEARCH CONDITION FROM SESSION##########
							//print "<pre>";
							//print_r($_SESSION['requesta']);								
							$FromDate=$_SESSION['requesta']['search']['srchDate']['FromDate'];
							$ToDate=$_SESSION['requesta']['search']['srchDate']['ToDate'];
							$srchCust=$_SESSION['requesta']['search']['srchCust'];
							$srchClient=$_SESSION['requesta']['search']['srchClient'];
							$srchTechnician=$_SESSION['requesta']['search']['srchTechnician'];
							$srchService=$_SESSION['requesta']['search']['srchService'];
							$srchStatus=$_SESSION['requesta']['search']['srchStatus'];
							$srchJobStatus=$_SESSION['requesta']['search']['srchJobStatus'];								
							$srchWono=$_SESSION['requesta']['search']['srchWono'];
							$srchPurchaseNo=$_SESSION['requesta']['search']['srchPurchaseNo'];
							$columnGroup=$_SESSION['requesta']['search']['columnGroup'];
							$columnOrder=$_SESSION['requesta']['search']['columnOrder'];
							$orderType=$_SESSION['requesta']['search']['orderType'];
							##########RETRIVE THE SEARCH CONDITION FROM SESSION##########
							########insert string  for filter_search table###############
							$insert= "user_type='$_SESSION[usertype]',user_id='$_SESSION[userid]', page_name='assignedboard'";
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
							if($srchClient !=''){
								$implode_srchClient =implode(",",$srchClient);
								$sch=$sch."FIND_IN_SET(w.created_by,'$implode_srchClient') AND ";
								$insert.=",clients='$implode_srchClient'";
							}else{
								$insert.=",clients='$implode_srchClient'";
							}
							if($srchTechnician !=''){
								$implode_srchTechnician =implode(",",$srchTechnician);
								$sch=$sch."FIND_IN_SET(t.id,'$implode_srchTechnician') AND ";
								$insert.=",techs='$implode_srchTechnician'";
							}else{
								$insert.=",techs='$implode_srchTechnician'";
							}
							if($srchService !=''){
								$implode_srchService =implode(",",$srchService);
								$sch=$sch."FIND_IN_SET(s.id,'$implode_srchService') AND ";
								$insert.=",services='$implode_srchService'";
							}else{
								$insert.=",services='$implode_srchService'";
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
							if($FromDate !='' && $ToDate ==''){
								$sch=$sch."at.assign_date >= '$fromdt' AND ";
							}
							if($FromDate =='' && $ToDate !=''){
								$sch=$sch."at.assign_date <= '$todt' AND ";
							}
							if(($FromDate !='') && ($ToDate !='')){
								$sch=$sch."at.assign_date BETWEEN '$fromdt' AND '$todt' AND ";
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
						   if($_SESSION['requesta']['schaction'] =='filtersch'){
							   $count_filter_search =$dbf->countRows("filter_search","user_type='$_SESSION[usertype]' AND user_id='$_SESSION[userid]' AND page_name='assignedboard'");
							   if($count_filter_search){
									$dbf->updateTable("filter_search",$insert.",updated_date=now()","user_type='$_SESSION[usertype]' AND user_id='$_SESSION[userid]'  AND page_name='assignedboard'");
							   }else{
									$dbf->insertSet("filter_search",$insert.",created_date=now()");
							   }
						   }
						   ########insert or update  filter_search table###############
						   $sch=substr($sch,0,-5);
						   if($sch!=''){
							 $cond="c.state=st.state_code AND c.id=w.client_id AND w.service_id=s.id AND w.approve_status='1' AND w.wo_no=at.wo_no AND at.tech_id=t.id AND at.start_date='0000-00-00' AND ".$sch;
							  if($srchStatus==''){$cond.=" AND w.work_status='Assigned'";}
						   }
						   elseif($sch==''){
							 $cond="c.state=st.state_code AND c.id=w.client_id AND w.service_id=s.id AND w.work_status='Assigned' AND w.approve_status='1' AND w.wo_no=at.wo_no AND at.tech_id=t.id AND at.start_date='0000-00-00'";
						   }
						   //condition for users
						   if($implode_clients <>''){
								$cond.=" AND FIND_IN_SET(w.created_by,'$implode_clients')";
						   }
						   if($implode_techs <>''){
								$cond.=" AND FIND_IN_SET(at.tech_id,'$implode_techs')";
						   }
						  //echo $cond;
						  ########Search condition end here ###############
						  //count total orders
						  $num=$dbf->countRows("state st,clients c,service s,technicians t,assign_tech at,work_order w",$cond); 
						  ?>
                        <div class="headerbg">
                        	<div style="float:left; width:30%;">Assigned Order Board</div>
                            <div style="float:left;width:30%; text-align:center;">Total : <?php echo $num;?> Orders</div>
                        	<div style="float:right; width:40%; text-align:right;">
                            <input type="button" class="buttonText2" value="Edit Filter" onClick="edit_filter();"/> 
                            <input type="button" class="buttonText2" value="Create Order" onClick="add_job();"/></div>
                        </div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                            <form name="SrchFrm" id="SrchFrm" action="" method="post">
                            <input type="hidden" name="schaction"  id="schaction" value=""/>
                            <input type="hidden" name="page" value="AssignedBoard"/>
                               <div align="center">
                                  <div  class="formtextaddsrch"align="center">From:</div>
                                  <div class="textboxcsrch">
                                      <input type="text" class="textboxsrch datepick" name="search[srchDate][FromDate]" id="FromDate" value="<?php echo $_SESSION['requesta']['search']['srchDate']['FromDate'];?>" readonly>
                                  </div>
                                  <div  class="formtextaddsrch"align="center">To:</div>
                                  <div class="textboxcsrch">
                                      <input type="text" class="textboxsrch datepick" name="search[srchDate][ToDate]" id="ToDate" value="<?php echo $_SESSION['requesta']['search']['srchDate']['ToDate'];?>" readonly>
                                  </div>
                                  <div style="float:left; width:200px;">
                                    <input type="button" class="buttonText2" name="SearchRecord" value="Filter Orders" onClick="SubmitFields();">
                                    <input type="button" class="buttonText2" name="Reset" value="Reset Filter" onClick="ClearFields();"> 
                           		  </div>         
                                  </div>
                              </form>
                              <div class="spacer"></div>
                              
                              <div id="sortTable">
                              <!-----Table area start------->
                                <table id="no-more-tables" class="draggable" border="0">
                                	<thead>
                                        <tr>
                                            <th width="6%">WO#</th>
                                            <th width="7%">CustomerName</th>
                                            <th width="7%">PurchaseOrder#</th>
                                            <th width="7%">ServiceType</th>
                                            <th width="6%">PickupState</th>
                                            <th width="5%">Pickupcity</th>
                                            <th width="6%">DeliveryState</th>
                                            <th width="8%">DeliveryCity</th>
                                            <th width="11%">Client</th>
                                            <th width="10%">TechName</th>
                                            <th width="7%">AssignedDate</th>
                                            <th width="4%">CC</th>
                                            <th width="4%">WFP</th>
                                            <th width="12%" style="text-align:center;">Action</th>
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
                                        <td class="hiderow">&nbsp;</td>
                                    </tr>
                              		<?php 
										//group array
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
                                        <td class="hiderow">&nbsp;</td>
                                    </tr>				
								    <?php
										$resArray=$dbf->fetchOrder("state st,clients c,service s,technicians t,assign_tech at,work_order w","c.state='$sgRes[state]' AND " .$cond,$orderby." ".$orderType,"st.state_name, c.*, w.*, s.service_name, t.first_name, t.middle_name, t.last_name, at.assign_date",$groupby);
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
										}else{
											//$rcolor='';
											$color='#F00';
										}
										if($res_JobBoard['created_by']<>'0'){
											$clientname=$dbf->getDataFromTable("clients","name","id='$res_JobBoard[created_by]'");}else{
											$clientname="COD";
										}
										//count customer contact by both admin and tech
										$workorder_notes=$dbf->strRecordID("workorder_notes","MAX(customer_attempt) as customer_attempt","workorder_id='$res_JobBoard[id]' AND user_type='admin'");
										$workorder_notes_tech=$dbf->strRecordID("workorder_notes","MAX(customer_attempt) as customer_attempt","workorder_id='$res_JobBoard[id]' AND user_type='tech'");
										$workorder_notes_total=$workorder_notes['customer_attempt']+$workorder_notes_tech['customer_attempt'];
										//waiting for parts
										$workorder_work_parts=$dbf->strRecordID("workorder_notes","waiting_parts","workorder_id='$res_JobBoard[id]' AND waiting_parts !=0");
								     ?>
                                  <tr class="<?php echo $Cls;?> ro" <?php if($k!=0){?> style="display:none;"<?php }?>>
                                    <td data-title="WO#" class="coltext">
                                    <a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_JobBoard['id'];?>','view-job-board');" title="Click Here For Job Details" style="color:<?php echo $color;?>"><?php echo $res_JobBoard['wo_no'];?></a></td>
                                    <td data-title="CustomerName"><?php echo $dbf->cut($res_JobBoard['name'],15);?></td>
                                    <td data-title="PurchaseOrder#"><?php echo $res_JobBoard['purchase_order_no'];?></td>
                                    <td data-title="ServiceType"><?php echo $res_JobBoard['service_name'];?></td>
                                    <td data-title="PickupState"><?php echo $pickupstate ;?></td>                                    <td data-title="Pickupcity"><?php echo $res_JobBoard['pickup_city'];?></td>
                                    <td data-title="DeliveryState"><?php echo $res_JobBoard['state_name'];?></td>
                                    <td data-title="DeliveryCity"><?php echo $res_JobBoard['city'];?></td>
                                    <td data-title="Client"><?php echo $clientname;?></td>
                                    <td data-title="TechName" class="coltext"><a href="javascript:void(0);" class="tooltip"><?php echo $res_JobBoard['first_name'].' '.$res_JobBoard['middle_name'].' '.$res_JobBoard['last_name'];?><span><?php include 'tech_notes.php';?></span></a></td>
                                    <td data-title="AssignedDate"><?php if($res_JobBoard['assign_date']<>'0000-00-00'){echo date("d-M-Y",strtotime($res_JobBoard['assign_date']));}else{echo 'None';}?></td>
                                    <td data-title="CC" class="coltext"><a href="javascript:void(0);" class="tooltip1"><?php echo $workorder_notes_total;?><span><?php include 'cc_date.php';?></span></a></td>
                                    <td data-title="WFP" class="coltext"><a href="javascript:void(0);" class="tooltip1"><?php if($workorder_work_parts['waiting_parts']==1){echo "YES";}else{echo "NO";};?><span><?php include 'wfp_date.php';?></span></a></td>
                                    <td data-title="Action"><a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_JobBoard['id'];?>','edit-job-board-assign');"><img src="images/edit.png" title="Edit" alt="Edit"/></a>&nbsp;<a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_JobBoard['id'];?>','view-job-board');"><img src="images/view.png" title="View" alt="View"/></a>&nbsp;<a href="javascript:void();" onClick="cancel_order('<?php echo $res_JobBoard['id'];?>','manage-job-board-assigned');"><img src="images/cancel_round.png" title="Cancel" alt="Cancel"/></a>&nbsp;<a href="javascript:void(0);"  onClick="print_doc('print','<?php echo $res_JobBoard['id'];?>');" ><img src="images/print.png" alt="Print"  title="Print Workorder"></a>&nbsp;<a href="javascript:void(0);" onClick="print_doc('pdf','<?php echo $res_JobBoard['id'];?>');"><img src="images/pdf.png" style="width:16px; height:16px;" title="Export to PDF"/></a>&nbsp;<a href="javascript:void(0);" onClick="send_email_tech('<?php echo $res_JobBoard['id'];?>','<?php echo $res_JobBoard['wo_no'];?>');"><img src="images/email_go.png" title="Email To Tech" alt="Email"></a></td>
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