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
$resFilterSearch = $dbf->fetchSingle("filter_search","user_type='$_SESSION[usertype]' AND user_id='$_SESSION[userid]' AND page_name='dispatchboard'");
?>
<body>
<link rel="stylesheet" href="../css/innermain.css" type="text/css" />
<link rel="stylesheet" href="../css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="../css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="../css/respmenu.css" type="text/css" />
<link rel="stylesheet" href="../css/tabledashboard.css" type="text/css" />
<script type="text/javascript">
/*********Function to redirect page************/
function SubmitFields(){
	$("#schaction").val("filtersch");
	document.SrchFrm.submit();
}
function ClearFields(){
	$('input:checkbox[name="search[srchCust][]"]').each(function(){
		if ($(this).is(':checked')){
			$(this).attr("checked",false);
		}
	});
	
	$('input:checkbox[name="search[srchStatus][]"]').each(function(){
		if ($(this).is(':checked')){
			$(this).attr("checked",false);
		}
	});
	$('input:checkbox[name="search[srchWono][]"]').each(function(){
		if ($(this).is(':checked')){
			$(this).attr("checked",false);
		}
	});
	$('input:checkbox[name="search[srchPurchaseNo][]"]').each(function(){
		if ($(this).is(':checked')){
			$(this).attr("checked",false);
		}
	});
	$('input:checkbox[name="search[columnGroup][]"]').each(function(){
		if ($(this).is(':checked')){
			$(this).attr("checked",false);
		}
	});
	$('input:checkbox[name="search[srchService][]"]').each(function(){
		if ($(this).is(':checked')){
			$(this).attr("checked",false);
		}
	});
	$('input:checkbox[name="search[columnOrder][]"]').each(function(){
		if ($(this).is(':checked')){
			$(this).attr("checked",false);
		}
	});
	$('input:radio[name="search[orderType][]"]').each(function(){
		if ($(this).is(':checked')){
			$(this).attr("checked",false);
		}
	});
	$('#FromDate').val('');
	$('#ToDate').val('');
	/*$.post("unset-session.php",{"src":"disp"},function(res){
		$("#schaction").val("");
		document.SrchFrm.submit();
	});*/
	$("#schaction").val("filtersch");
	document.SrchFrm.submit();
}
function clearDates(){
	$('#FromDate').val('');
	$('#ToDate').val('');
}
/*********Function to redirect page************/
</script>
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
                    	<form name="SrchFrm" id="SrchFrm" action="tech-manage-job-board" method="post">
                        <input type="hidden" name="schaction"  id="schaction" value=""/>
                        <input type="hidden" name="page" value="EditFilter"/>
                        <div class="headerbg">
                        	<div style="float:left;">Edit Filter</div>
                            <div  style="float:left; text-transform:none; padding-left:100px;">( This filter applies to: Dispatch Board )</div>
                            <div style="float:right;"><input type="button" class="buttonText2" value="Save & Filter" onClick="SubmitFields();"/>
                            <input type="button" class="buttonText2" value="Reset Filter" onClick="ClearFields();"/>
                            <input type="button" class="buttonText2" value="Return Back" onClick="document.location.href='tech-manage-job-board'"/></div>
                        </div>
                        <div class="spacer"></div>
                        <div class="divFilterleftOpen">
                        	<div align="center" class="filterdHead">Customers</div>
                            <div class="divFilterconsec">
                            	<?php
								$customerArray = ($resFilterSearch['customers'] !='')?explode(",",$resFilterSearch['customers']):array(); 
								foreach($dbf->fetchOrder("work_order wo,clients cl","wo.client_id=cl.id AND wo.work_status IN('Dispatched','In Progress','Completed','Ready to Invoice','Invoiced','Assigned')","cl.name ASC","","cl.name")as $customer){?>
                            	<div><input type="checkbox" name="search[srchCust][]" value="<?php echo $customer['id']?>" <?php if(in_array($customer['id'],$customerArray,true)){echo "checked";}?>/><?php echo $customer['name'];?></div>
								<?php } ?>
                           	</div>
                        </div>
                        <div class="divFiltercentOpen2">
                        	<div align="center" class="filterdHead">Statuses</div>
                            <div class="divFilterconsec">
                            <?php 
							$statusArray = ($resFilterSearch['status'] !='')?explode(",",$resFilterSearch['status']):array(); 
							?>
                            	<div><input type="checkbox" name="search[srchStatus][]" value="Cancelled" <?php if(in_array("Cancelled",$statusArray,true)){echo "checked";}?>/> Show Cancelled</div>
                            	<div><input type="checkbox" name="search[srchStatus][]" value="Assigned" <?php if(in_array("Assigned",$statusArray,true)){echo "checked";}?>/> Assigned</div>
                                <div><input type="checkbox" name="search[srchStatus][]" value="Scheduled" <?php if(in_array("Scheduled",$statusArray,true)){echo "checked";}?>/> Scheduled</div>
                            	<div><input type="checkbox" name="search[srchStatus][]" value="Dispatched" <?php if(in_array("Dispatched",$statusArray,true)){echo "checked";}?>/> Dispatched</div>
                                <div><input type="checkbox" name="search[srchStatus][]" value="In Progress" <?php if(in_array("In Progress",$statusArray,true)){echo "checked";}?>/> In Progress </div> 
                                <div><input type="checkbox" name="search[srchStatus][]" value="Completed" <?php if(in_array("Completed",$statusArray,true)){echo "checked";}?>/> Completed </div>
                                 <div><input type="checkbox" name="search[srchStatus][]" value="Ready to Invoice" <?php if(in_array("Ready to Invoice",$statusArray,true)){echo "checked";}?>/> Ready to Invoice </div>
                                <div><input type="checkbox" name="search[srchStatus][]" value="Invoiced" <?php if(in_array("Invoiced",$statusArray,true)){echo "checked";}?>/> Invoiced </div>
                           	</div>
                        </div>
                        <div class="divFiltercentOpen">
                        	<div align="center" class="filterdHead">Wo#</div>
                            <div class="divFilterconsec">
                            	<?php 
								$wonoArray = ($resFilterSearch['wonos'] !='')?explode(",",$resFilterSearch['wonos']):array();
								foreach($dbf->fetchOrder("technicians t,assign_tech at,work_order w","w.work_status IN('Dispatched','In Progress','Completed','Ready to Invoice','Invoiced','Assigned') AND w.wo_no=at.wo_no AND at.tech_id=t.id AND t.id='$_SESSION[userid]'","w.id","w.wo_no")as $reswono){?>
                            	<div><input type="checkbox" name="search[srchWono][]" value="<?php echo $reswono['wo_no']?>" <?php if(in_array($reswono['wo_no'],$wonoArray,true)){echo "checked";}?>/><?php echo $reswono['wo_no'];?> </div>
                              <?php }?>
                           	</div>
                        </div>
                       	<div class="divFilterleftOpen _right">
                         	<div align="center" class="filterdHead">Column Grouping</div>
                         	<div class="divFilterconsec">
                            <?php $columngroupArray = ($resFilterSearch['column_group'] !='')?explode(",",$resFilterSearch['column_group']):array(); ?>
                            	<div><input type="checkbox" name="search[columnGroup][]" value="w.client_id" <?php if(in_array("w.client_id",$columngroupArray,true)){echo "checked";}?>/>Customer</div>
                           	</div>
                        </div>
                        <div class="spacer"></div>
                        <div class="divFilterleftOpen">
                        	<div align="center" class="filterdHead">Services</div>
                            <div class="divFilterconsec">
                            	<?php 
								$serviceArray = ($resFilterSearch['services'] !='')?explode(",",$resFilterSearch['services']):array();
								foreach($dbf->fetch("service","id>0 ORDER BY service_name ASC")as $service){?>
                            	<div><input type="checkbox" name="search[srchService][]" value="<?php echo $service['id'];?>" <?php if(in_array($service['id'],$serviceArray,true)){echo "checked";}?>/> <?php echo $service['service_name'];?> </div>
                                <?php }?>
                           	</div>
                        </div>
                        <div class="divFiltercentOpen2">
                        	<div align="center" class="filterdHead">Schedule Date</div>
                            <div class="divFilterconsec">
                            	<div>
                                   <div  class="formtextaddsrch"align="center">From:</div>
                                   <div class="textboxcsrch"><input type="text" class="textboxsrch datepick" name="search[srchDate][FromDate]" id="FromDate" value="<?php echo (!empty($resFilterSearch) && $resFilterSearch['from_date'] !='0000-00-00')? date("d-M-Y",strtotime($resFilterSearch['from_date'])):'';?>" readonly></div>
                                </div>
                                <div class="spacer"></div>
                                <div>
                                	<div  class="formtextaddsrch"align="center">To:</div>
                                  	<div class="textboxcsrch"><input type="text" class="textboxsrch datepick" name="search[srchDate][ToDate]" id="ToDate" value="<?php echo (!empty($resFilterSearch) && $resFilterSearch['to_date'] !='0000-00-00')? date("d-M-Y",strtotime($resFilterSearch['to_date'])):'';?>" readonly></div>
                               </div>
                               <div style="float:right; padding-right:50px; cursor:pointer;"><img src="../images/delete.png" alt="Delete" title="Remove Dates" onClick="clearDates();"/></div> 
                           	</div>
                        </div>
                        <div class="divFiltercentOpen">
                        	<div align="center" class="filterdHead">Purchase Order#</div>
                            <div class="divFilterconsec">
                            	<?php 
								$purchaseArray = ($resFilterSearch['purchasenos'] !='')?explode(",",$resFilterSearch['purchasenos']):array();
								foreach($dbf->fetchOrder("technicians t,assign_tech at,work_order w","w.work_status IN('Dispatched','In Progress','Completed','Ready to Invoice','Invoiced','Assigned') AND w.wo_no=at.wo_no AND at.tech_id=t.id AND t.id='$_SESSION[userid]' AND w.purchase_order_no <>''","w.id","w.purchase_order_no")as $respurno){?>
                            	<div><input type="checkbox" name="search[srchPurchaseNo][]" value="<?php echo $respurno['purchase_order_no']?>" <?php if(in_array($respurno['purchase_order_no'],$purchaseArray,true)){echo "checked";}?>/><?php echo $respurno['purchase_order_no'];?> </div>
                              <?php }?>
                           	</div>
                        </div>
                       	<div class="divFilterleftOpen _right">
                         	<div align="center" class="filterdHead">Column Ordering</div>
                          	<div class="divFilterconsec" style="position:relative;">
                            <?php $columnorderArray = ($resFilterSearch['column_order'] !='')?explode(",",$resFilterSearch['column_order']):array(); ?>
                                <div><input type="checkbox" name="search[columnOrder][]" value="w.wo_no" <?php if(in_array("w.wo_no",$columnorderArray,true)){echo "checked";}?>/> WO# </div>
                                <div><input type="checkbox" name="search[columnOrder][]" value="c.name" <?php if(in_array("c.name",$columnorderArray,true)){echo "checked";}?>/> CustomerName</div>
                                <div><input type="checkbox" name="search[columnOrder][]" value="s.service_name" <?php if(in_array("s.service_name",$columnorderArray,true)){echo "checked";}?>> ServiceType </div>
                                <div><input type="checkbox" name="search[columnOrder][]" value="w.work_status" <?php if(in_array("w.work_status",$columnorderArray,true)){echo "checked";}?>> 
                                Order Status </div>
                                <div><input type="checkbox" name="search[columnOrder][]" value="w.pickup_state" <?php if(in_array("w.pickup_state",$columnorderArray,true)){echo "checked";}?>> PickupState </div>
                                <div><input type="checkbox" name="search[columnOrder][]" value="w.pickup_city" <?php if(in_array("w.pickup_city",$columnorderArray,true)){echo "checked";}?>> PickupCity </div>
                                <div><input type="checkbox" name="search[columnOrder][]" value="w.pickup_phone_no" <?php if(in_array("w.pickup_phone_no",$columnorderArray,true)){echo "checked";}?>> PickupPhone </div>
                                <div><input type="checkbox" name="search[columnOrder][]" value="c.state" <?php if(in_array("c.state",$columnorderArray,true)){echo "checked";}?>> DeliveryState </div>
                                <div><input type="checkbox" name="search[columnOrder][]" value="c.city" <?php if(in_array("c.city",$columnorderArray,true)){echo "checked";}?>> DeliveryCity </div>
                                <div><input type="checkbox" name="search[columnOrder][]" value="c.phone_no" <?php if(in_array("c.phone_no",$columnorderArray,true)){echo "checked";}?>> DeliveryPhone </div>
                                 <div><input type="checkbox" name="search[columnOrder][]" value="at.start_date" <?php if(in_array("at.start_date",$columnorderArray,true)){echo "checked";}?>> StartDate </div>
                                <div><input type="checkbox" name="search[columnOrder][]" value="at.start_time" <?php if(in_array("at.start_time",$columnorderArray,true)){echo "checked";}?>> StartTime </div>
                                <div style="position:absolute; right:0; top:0;padding:10px;">
                                <input type="radio" name="search[orderType][]" value="ASC" <?php if($resFilterSearch['order_type']=='ASC'){echo "checked";}?>/>ASC<br/>
                                <input type="radio" name="search[orderType][]" value="DESC" <?php if($resFilterSearch['order_type']=='DESC'){echo "checked";}?>/>DESC</div>
                           	</div>
                            
                       </div>
                       <div class="spacer"></div>
                       </form>
            		</div>
                </div>
              <!-------------Main Body--------------->
         </div>
        <div class="spacer"></div>
        <?php include_once 'footer-tech.php'; ?>
    </div>
</body>
</html>