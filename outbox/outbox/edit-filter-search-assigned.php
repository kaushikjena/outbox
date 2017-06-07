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
$resFilterSearch = $dbf->fetchSingle("filter_search","user_type='$_SESSION[usertype]' AND user_id='$_SESSION[userid]' AND page_name='assignedboard'");
?>
<body>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />
<link rel="stylesheet" href="css/tabledashboard.css" type="text/css" />
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
	$('input:checkbox[name="search[srchClient][]"]').each(function(){
		if ($(this).is(':checked')){
			$(this).attr("checked",false);
		}
	});
	$('input:checkbox[name="search[srchTechnician][]"]').each(function(){
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
     	<?php include_once 'header.php';?>
   		<!-------------header--------------->
        <!-------------top menu--------------->
     	<?php include_once 'top-menu.php';?>
   		<!-------------top menu--------------->
         <div id="contentdiv">
                <!-------------Main Body--------------->
                <div class="rightcolumjobboard">
            		<div class="rightcoluminner">
                    	<form name="SrchFrm" id="SrchFrm" action="manage-job-board-assigned" method="post">
                        <input type="hidden" name="schaction"  id="schaction" value=""/>
                        <input type="hidden" name="page" value="EditFilter"/>
                        <div class="headerbg">
                        	<div style="float:left;">Edit Filter</div>
                            <div  style="float:left; text-transform:none; padding-left:100px;">( This filter applies to: Assigned Board )</div>
                            <div style="float:right;"><input type="button" class="buttonText2" value="Save & Filter" onClick="SubmitFields();"/>
                            <input type="button" class="buttonText2" value="Reset Filter" onClick="ClearFields();"/>
                            <input type="button" class="buttonText2" value="Return Back" onClick="document.location.href='manage-job-board-assigned'"/></div>
                        </div>
                        <div class="spacer"></div>
                        <div>
                        <div class="divFilterleft">
                        	<div align="center" class="filterdHead">Customers</div>
                            <div class="divFilterconsec">
                            	<?php
								$customerArray = ($resFilterSearch['customers'] !='')?explode(",",$resFilterSearch['customers']):array(); 
								foreach($dbf->fetchOrder("assign_tech at,work_order wo,clients cl","wo.client_id=cl.id AND wo.work_status='Assigned' AND wo.wo_no=at.wo_no AND at.start_date ='0000-00-00'","cl.name ASC","","cl.name")as $customer){?>
                            	<div><input type="checkbox" name="search[srchCust][]" value="<?php echo $customer['id']?>" <?php if(in_array($customer['id'],$customerArray,true)){echo "checked";}?>/><?php echo $customer['name'];?></div>
								<?php } ?>
                           	</div>
                        </div>
                        <div class="divFiltercent2">
                        	<div align="center" class="filterdHead">Clients</div>
                            <div class="divFilterconsec">
                            	<?php $clientsArray = ($resFilterSearch['clients'] !='')?explode(",",$resFilterSearch['clients']):array(); 
								if($implode_clients ==''){
								?>
                            	<div><input type="checkbox" name="search[srchClient][]" value="0" <?php if(in_array("0",$clientsArray,true)){echo "checked";}?>/> COD</div>
                                <?php 
								}
								$cond = "wo.created_by=cl.id AND wo.created_by<>'0' AND wo.work_status='Assigned' AND wo.wo_no=at.wo_no AND at.start_date ='0000-00-00'";
								//condition for users
								if($implode_clients <>''){
									$cond.=" AND FIND_IN_SET(cl.id,'$implode_clients')";
								}
								foreach($dbf->fetchOrder("assign_tech at,work_order wo,clients cl",$cond,"cl.name ASC","","cl.name")as $client){ ?>
                                <div><input type="checkbox" name="search[srchClient][]" value="<?php echo $client['id']?>" <?php if(in_array($client['id'],$clientsArray,true)){echo "checked";}?>/><?php echo $client['name'];?> </div> 
                               <?php }?>
                           	</div>
                        </div>
                        <div class="divFiltercent">
                        	<div align="center" class="filterdHead">Techs</div>
                            <div class="divFilterconsec">
                            	<?php 
								$techsArray = ($resFilterSearch['techs'] !='')?explode(",",$resFilterSearch['techs']):array();
								$cond1 = "id>0";
								//condition for users
								if($implode_techs <>''){
									$cond1.=" AND FIND_IN_SET(id,'$implode_techs')";
								}
								foreach($dbf->fetch("technicians",$cond1." ORDER BY first_name ASC")as $tech){?>
                            	<div><input type="checkbox" name="search[srchTechnician][]" value="<?php echo $tech['id']?>" <?php if(in_array($tech['id'],$techsArray,true)){echo "checked";}?>/><?php echo $tech['first_name'].'&nbsp;'.$tech['middle_name'].'&nbsp;'.$tech['last_name'];?> </div>
                              <?php }?>
                           	</div>
                        </div>
                        <div class="divFiltercent _postright">
                        	<div align="center" class="filterdHead">WO#</div>
                            <div class="divFilterconsec">
                            	<?php 
								$wonoArray = ($resFilterSearch['wonos'] !='')?explode(",",$resFilterSearch['wonos']):array();
								foreach($dbf->fetchOrder("technicians t,assign_tech at,work_order w","w.work_status='Assigned' AND w.approve_status='1' AND w.wo_no=at.wo_no AND at.tech_id=t.id AND at.start_date='0000-00-00'","w.id","w.wo_no")as $reswono){?>
                            	<div><input type="checkbox" name="search[srchWono][]" value="<?php echo $reswono['wo_no']?>" <?php if(in_array($reswono['wo_no'],$wonoArray,true)){echo "checked";}?>/><?php echo $reswono['wo_no'];?> </div>
                              <?php }?>
                           	</div>
                        </div>
                       	<div class="divFilterleft _right">
                         	<div align="center" class="filterdHead">Column Grouping</div>
                         	<div class="divFilterconsec">
                            <?php $columngroupArray = ($resFilterSearch['column_group'] !='')?explode(",",$resFilterSearch['column_group']):array(); ?>
                            	<div><input type="checkbox" name="search[columnGroup][]" value="w.client_id" <?php if(in_array("w.client_id",$columngroupArray,true)){echo "checked";}?>/>Customer</div>
                                <div><input type="checkbox" name="search[columnGroup][]" value="w.created_by" <?php if(in_array("w.created_by",$columngroupArray,true)){echo "checked";}?>/>Client</div> 
                                <div><input type="checkbox" name="search[columnGroup][]" value="t.id" <?php if(in_array("t.id",$columngroupArray,true)){echo "checked";}?>/>Tech</div>
                           	</div>
                        </div>
                        <div class="spacer"></div>
                        <div class="divFilterleft">
                        	<div align="center" class="filterdHead">Services</div>
                            <div class="divFilterconsec">
                            	<?php 
								$serviceArray = ($resFilterSearch['services'] !='')?explode(",",$resFilterSearch['services']):array();
								foreach($dbf->fetch("service","id>0 ORDER BY service_name ASC")as $service){?>
                            	<div><input type="checkbox" name="search[srchService][]" value="<?php echo $service['id'];?>" <?php if(in_array($service['id'],$serviceArray,true)){echo "checked";}?>/> <?php echo $service['service_name'];?> </div>
                                <?php }?>
                           	</div>
                        </div>
                        <div class="divFiltercent2">
                        	<div align="center" class="filterdHead">Statuses</div>
                            <div class="divFilterconsec">
                            <?php 
							$statusArray = ($resFilterSearch['status'] !='')?explode(",",$resFilterSearch['status']):array(); 							?>
                            	<div><input type="checkbox" name="search[srchStatus][]" value="Cancelled" <?php if(in_array("Cancelled",$statusArray,true)){echo "checked";}?>/> Show Cancelled</div>
                            	<div><input type="checkbox" name="search[srchStatus][]" value="Assigned" <?php if(in_array("Assigned",$statusArray,true)){echo "checked";}?>/> Assigned </div>
                           	</div>
                        </div>
                        <div class="divFiltercent">
                        	<div align="center" class="filterdHead">Assigned Date</div>
                            <div class="divFilterconsec">
                            	<div>
                                   <div  class="formtextaddsrchsmall" align="center">From:</div>
                                   <div class="textboxcsrch"><input type="text" class="textboxsrch datepick" name="search[srchDate][FromDate]" id="FromDate" value="<?php echo (!empty($resFilterSearch) && $resFilterSearch['from_date'] !='0000-00-00')? date("d-M-Y",strtotime($resFilterSearch['from_date'])):'';?>" readonly></div>
                                </div>
                                <div class="spacer"></div>
                                <div>
                                	<div  class="formtextaddsrchsmall"align="center">To:</div>
                                  	<div class="textboxcsrch"><input type="text" class="textboxsrch datepick" name="search[srchDate][ToDate]" id="ToDate" value="<?php echo (!empty($resFilterSearch) && $resFilterSearch['to_date'] !='0000-00-00')? date("d-M-Y",strtotime($resFilterSearch['to_date'])):'';?>" readonly></div>
                               </div>
                               <div style="float:right; padding-right:10px; cursor:pointer;"><img src="images/delete.png" alt="Delete" title="Remove Dates" onClick="clearDates();"/></div> 
                           	</div>
                        </div>
                        <div class="divFiltercent _postright">
                        	<div align="center" class="filterdHead">Purchase Order#</div>
                            <div class="divFilterconsec">
                            	<?php 
								$purchaseArray = ($resFilterSearch['purchasenos'] !='')?explode(",",$resFilterSearch['purchasenos']):array();
								foreach($dbf->fetchOrder("technicians t,assign_tech at,work_order w","w.work_status='Assigned' AND w.approve_status='1' AND w.wo_no=at.wo_no AND at.tech_id=t.id AND at.start_date='0000-00-00' AND w.purchase_order_no <>''","w.id","w.purchase_order_no")as $respurno){?>
                            	<div><input type="checkbox" name="search[srchPurchaseNo][]" value="<?php echo $respurno['purchase_order_no']?>" <?php if(in_array($respurno['purchase_order_no'],$purchaseArray,true)){echo "checked";}?>/><?php echo $respurno['purchase_order_no'];?> </div>
                              <?php }?>
                           	</div>
                        </div>
                       	<div class="divFilterleft _right">
                         	<div align="center" class="filterdHead">Column Ordering</div>
                          	<div class="divFilterconsec" style="position:relative;">
                            <?php $columnorderArray = ($resFilterSearch['column_order'] !='')?explode(",",$resFilterSearch['column_order']):array(); ?>
                                <div><input type="checkbox" name="search[columnOrder][]" value="w.wo_no" <?php if(in_array("w.wo_no",$columnorderArray,true)){echo "checked";}?>/> WO# </div>
                                <div><input type="checkbox" name="search[columnOrder][]" value="c.name" <?php if(in_array("c.name",$columnorderArray,true)){echo "checked";}?>/> CustomerName</div>
                                <div><input type="checkbox" name="search[columnOrder][]" value="w.purchase_order_no" <?php if(in_array("w.purchase_order_no",$columnorderArray,true)){echo "checked";}?>>PurchaseOrder#</div>
                                <div><input type="checkbox" name="search[columnOrder][]" value="s.service_name" <?php if(in_array("s.service_name",$columnorderArray,true)){echo "checked";}?>> ServiceType </div>
                                <div><input type="checkbox" name="search[columnOrder][]" value="w.pickup_state" <?php if(in_array("w.pickup_state",$columnorderArray,true)){echo "checked";}?>> PickupState </div>
                                <div><input type="checkbox" name="search[columnOrder][]" value="w.pickup_city" <?php if(in_array("w.pickup_city",$columnorderArray,true)){echo "checked";}?>> PickupCity </div>
                                <div><input type="checkbox" name="search[columnOrder][]" value="c.state" <?php if(in_array("c.state",$columnorderArray,true)){echo "checked";}?>> DeliveryState </div>
                                <div><input type="checkbox" name="search[columnOrder][]" value="c.city" <?php if(in_array("c.city",$columnorderArray,true)){echo "checked";}?>> DeliveryCity </div>
                                <div><input type="checkbox" name="search[columnOrder][]" value="t.first_name" <?php if(in_array("t.first_name",$columnorderArray,true)){echo "checked";}?>> TechName </div>
                                <div><input type="checkbox" name="search[columnOrder][]" value="at.assign_date" <?php if(in_array("at.assign_date",$columnorderArray,true)){echo "checked";}?>> AssignedDate </div>
                                <div style="position:absolute; right:0; top:0;padding:10px;">
                                <input type="radio" name="search[orderType][]" value="ASC" <?php if($resFilterSearch['order_type']=='ASC'){echo "checked";}?>/>ASC<br/>
                                <input type="radio" name="search[orderType][]" value="DESC" <?php if($resFilterSearch['order_type']=='DESC'){echo "checked";}?>/>DESC</div>
                           	</div>
                            
                       </div>
                       <div class="spacer"></div>
                       </div>
                       </form>
            		</div>
                </div>
              <!-------------Main Body--------------->
         </div>
        <div class="spacer"></div>
        <?php include_once 'footer.php'; ?>
    </div>
</body>
</html>