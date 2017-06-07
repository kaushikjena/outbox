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
	###########Delete record from work order Table###############
	if($_REQUEST['action']=='delete'){	
	    $dbf->deleteFromTable("workorder_service","workorder_id='$_REQUEST[id]'");
		$dbf->deleteFromTable("work_order","id='$_REQUEST[id]'");
		header("Location:manage-job-board");exit;
	}
	###########Delete record from work order Table###############
	###########Send Email To Customer############################
	if($_REQUEST['action']=='email'){
		//get customer details from table
		$resCust = $dbf->strRecordID("work_order w,clients c","c.name,c.email,w.wo_no","w.client_id=c.id AND w.id='$_REQUEST[id]'");	
	   //Email Sending Starts here
	   //Email send to customer as the order is posted in the system.
		$res_template=$dbf->fetchSingle("email_template","id='3'");
		$from=$res_template['from_email'];
		$fromName=$res_template['from_name'];
		$subject=$res_template['subject'];
		$input=$res_template['message'];
		$toName=ucfirst($resCust['name']);
		$to=$resCust['email'];
		$WorkOrder = $resCust['wo_no'];
		$body=str_replace(array('%Name%','%OrderNo%'),array($toName,$WorkOrder),$input);
		$headers = "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=UTF-8\n";
		$headers .= "From:".$fromName." <".$from.">\n";
	   	//echo $body;exit;
		if($to){
			@mail($to,$subject,$body,$headers);
			header("Location:manage-job-board?msg=005");exit;
		}else{
			header("Location:manage-job-board?msg=002");exit;
		}
		/*Email sending end*/
	}
	###########Send Email To Customer############################
	$_SESSION['requesto']=$_SESSION['requesto']?$_SESSION['requesto']:array();
	if(isset($_REQUEST['schaction']) && $_REQUEST['schaction'] =='filtersch'){
		//echo "here";
		$_SESSION['requesto']=$_REQUEST;
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
			window.location.href="manage-job-board"; 
		 }else{			
			 $.fancybox(res,{centerOnScroll:true,hideOnOverlayClick:false});			
		 }
	 	});
	}else{
		return false; 
	}
}
function SubmitFields(){
	$("#schaction").val("filtersch");
	document.SrchFrm.submit();
}
function ClearFields(){
	$('#srchCust').val("");
	$('#srchClient').val("");
	$('#srchState').val("");
	$('#srchService').val("");
	$('#FromDate').val("");
	$('#ToDate').val("");
	$.post("unset-session.php",{"src":"open"},function(res){
		$("#schaction").val("filtersch");
		document.SrchFrm.submit();
	});
	
}
/*********Function to expand and collapse group************/
function funHide(clss,id){
	$('.'+clss).hide();
	$('#e'+id).show();
	$('#c'+id).hide();
}
function funShow(clss,id){
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
/*********Function to show create job************/
/*********Function to sort job board************/
function showSortRecords(){
	var cmbColumn=$('#cmbColumn').val();
	var cmbType=$('#cmbType').val();
	var frmdata = $('#SrchFrm').serialize();
	//alert(frmdata);
	var url="ajax-manage-job-board.php?"+frmdata;
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
</script>
	<form name="frmRedirect" id="frmRedirect" action="" method="post"> 
    	<input type="hidden" name="id" id="hid" value=""/>
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
                        <div class="headerbg"><div style="float:left;">Open-job-Board</div>
                        	<div style="float:right;"><a href="javascript:void(0);" title="Click Here To Add Job" onClick="add_job();" style="text-decoration:none;"><input type="button" class="buttonText2" value="Create Jobs"/></a></div>
                        </div>
                        <?php if($_REQUEST['msg']=='005'){ ?>
							<div align="center" style="color:green;font-weight:bold;">Email send successfully!</div> 
						<?php }elseif($_REQUEST['msg']=='002'){ ?>
                        	<div align="center" style="color:red;font-weight:bold;">Email sending failed!</div> 
                        <?php }else{ ?>
                        	<div class="spacer"></div>
                        <?php }?>
                        <div id="contenttable">
                            <form name="SrchFrm" id="SrchFrm" action="" method="post">
                            <input type="hidden" name="schaction"  id="schaction" value=""/>
                              <div style="margin-bottom:5px;" align="center">
                              	 <div  class="formtextaddsrch" align="center">Customer</div>
                                  <div class="textboxcsrch">
                                  <select name="srchCust" id="srchCust" class="selectboxsrch">
                                  		<option value="">--Select Customer--</option>
                                        <?php foreach($dbf->fetchOrder("work_order wo,clients cl","wo.client_id=cl.id AND wo.job_status='Open'","cl.name ASC","","cl.name")as $customer){?>
                                        <option value="<?php echo $customer['id']?>" <?php if($customer['id']==$_SESSION['requesto']['srchCust']){echo 'selected';}?>><?php echo $customer['name'];?></option>
                                        <?php }?>
                                   </select>
                                	</div>
                                   <div  class="formtextaddsrchsmall" align="center">Client</div>
                                   <div class="textboxcsrch">
                                   <select name="srchClient" id="srchClient" class="selectboxsrch">
                                  		<option value="">--Select Client--</option>
                                        <option value="0" <?php if($_SESSION['requesto']['srchClient']=="0"){echo 'selected';}?>> COD </option>
                                        <?php foreach($dbf->fetchOrder("work_order wo,clients cl","wo.created_by=cl.id AND wo.created_by<>'0' AND wo.job_status='Open'","cl.name ASC","","cl.name")as $client){?>
                                        <option value="<?php echo $client['id']?>" <?php if($client['id']==$_SESSION['requesto']['srchClient']){echo 'selected';}?>><?php echo $client['name'];?></option>
                                        <?php }?>
                                   </select>
                                </div>
                                 <div  class="formtextaddsrch"align="center">Service</div>
                                   <div class="textboxcsrch">
                                    <select name="srchService" id="srchService" class="selectboxsrch">
                                    	<option value="">--Service Name--</option>
                                        <?php foreach($dbf->fetch("service","id>0 ORDER BY service_name ASC")as $service){?>
                                        <option value="<?php echo $service['id'];?>" <?php if($service['id']==$_SESSION['requesto']['srchService']){echo 'selected';}?>><?php echo $service['service_name'];?></option>
                                        <?php }?>
                                    </select>
                                    </div>
                                    <div  class="formtextaddsrchsmall"align="center">From:</div>
                                    <div class="textboxcsrchsmall">
                                    <input type="text" class="textboxsrch datepick" name="FromDate" id="FromDate" value="<?php echo $_SESSION['requesto']['FromDate'];?>" readonly></div>
                                    <div  class="formtextaddsrchsmall"align="center">To:</div>
                                    <div class="textboxcsrchsmall">
                                    <input type="text" class="textboxsrch datepick" name="ToDate" id="ToDate" value="<?php echo $_SESSION['requesto']['ToDate'];?>" readonly></div>
                                    <div style="float:left; width:200px;">
                                    <input type="button" class="buttonText2" name="SearchRecord" value="Filter Jobs" onClick="SubmitFields();">
                                    <input type="button" class="buttonText2" name="Reset" value="Reset Filter" onClick="ClearFields();">
                                   </div>
                                   	<div  class="formtextaddsrchsmall" align="center">Sort By:</div>
                                    <div class="textboxcsrch" style="width:100px;">
                                    <select class="selectboxsrch" name="cmbColumn" id="cmbColumn" onChange="showSortRecords();">
                                    	<option value="">--Column--</option>
                                        <option value="w.wo_no"> WO# </option>
                                        <option value="w.created_date"> CreatedDate </option>
                                        <option value="c.name"> CustomerName</option>
                                        <option value="w.pickup_state"> PickupState </option>
                                        <option value="w.pickup_city"> PickupCity </option>
                                        <option value="w.pickup_phone_no"> PickupPhone </option>
                                        <option value="c.city"> DeliveryCity </option>
                                        <option value="c.phone_no"> DeliveryPhone </option>
                                        <option value="s.service_name"> ServiceType </option>
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
							  	//print_r($_SESSION['requesto']);
								$FromDate=$_SESSION['requesto']['FromDate'];
								$ToDate=$_SESSION['requesto']['ToDate'];
								$srchCust=$_SESSION['requesto']['srchCust'];
								$srchClient=$_SESSION['requesto']['srchClient'];
								$srchService=$_SESSION['requesto']['srchService'];
							   ##########STORE AND RETRIVE THE SEARCH CONDITION FROM SESSION##########
							   #############Search Conditions#####################
							   	$sch="";
								$fromdt=date("Y-m-d",strtotime(($FromDate)));
								$todt=date("Y-m-d",strtotime(($ToDate)));
								
								if($srchCust !=''){
									$sch=$sch."c.id='$srchCust' AND ";
								}
								if($srchClient !=''){
									$sch=$sch."w.created_by='$srchClient' AND ";
								}
								if($srchService !=''){
									$sch=$sch."s.id='$srchService' AND ";
								}
								if($FromDate !='' && $ToDate ==''){
									$sch=$sch."w.created_date >= '$fromdt' AND ";
								}
								if($FromDate =='' && $ToDate!=''){
									$sch=$sch."w.created_date <= '$todt' AND ";
								}
							    if(($FromDate !='') && ($ToDate !='')){
									$sch=$sch."w.created_date BETWEEN '$fromdt' AND '$todt' AND ";
								}
							   $sch=substr($sch,0,-5);
							   //echo $sch;exit;
							   if($sch!=''){
								 $cond="c.state=st.state_code AND c.id=w.client_id AND w.service_id=s.id AND w.job_status='Open' AND w.approve_status='1' AND ".$sch;
								  // echo $cond;exit;
							   }
							   elseif($sch==''){
								 $cond="c.state=st.state_code AND c.id=w.client_id AND w.service_id=s.id AND w.job_status='Open' AND w.approve_status='1'";
							   }
							   #############Search Conditions#####################
							  ?>
                              <div id="sortTable">
                              <!-----Table area start------->
                                <table id="no-more-tables" class="draggable">
                                    <thead>
                                        <tr>
                                            <th width="6%">WO#</th>
                                            <th width="9%">CustomerName</th>
                                            <th width="8%">CreatedDate</th>
                                            <th width="6%">JobStatus</th>
                                            <th width="8%">ServiceType</th>
                                            <th width="7%">Pickupcity</th>
                                            <th width="6%">PickupState</th>
                                            <th width="8%">PickupPhone</th>
                                            <th width="8%">DeliveryCity</th>
                                            <th width="6%">DeliveryState</th>
                                            <th width="8%">DeliveryPhone</th>
                                            <th width="8%">Client</th>
                                            <th width="5%">Assign</th>
                                            <th width="7%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    	<tr>
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
										$num=$dbf->countRows("state st,clients c,service s,work_order w",$cond); 
										$resGrArray=$dbf->fetchOrder("state st,clients c,service s,work_order w",$cond,"c.state ASC","","c.state");
										//group by state loop
										foreach($resGrArray as $k=>$sgRes){
										$Cls="g$k";	
										$numres = $dbf->countRows("state st,clients c,service s,work_order w","c.state='$sgRes[state]' AND " .$cond);
									  ?>
										<tr style="background-color:#f9f9f9;" class="ho">
                                            <td valign="top" class="grheading">
                                            <div class="divgr">
                                            <a href="javascript:void(0);" onClick="funShow('<?php echo $Cls;?>','<?php echo $k;?>');" id="e<?php echo $k;?>" <?php if($k==0){?>style="display:none;" <?php }?> class="hoa"><img  src="images/plus.gif" height="13" width="13"/>&nbsp;<?php echo $numres;?> Open Jobs in <span style="color:#ff9812;"><?php echo $sgRes['state_name'];?></span> needs to be assigned</a> 
                                			<a href="javascript:void(0);" onClick="funHide('<?php echo $Cls;?>','<?php echo $k;?>');" id="c<?php echo $k;?>" <?php if($k!=0){?>style="display:none;" <?php }?> class="hob"><img  src="images/minus.gif" height="13" width="13"/>&nbsp;<?php echo $numres;?> Open Jobs in <span style="color:#ff9812;"><?php echo $sgRes['state_name'];?></span> needs to be assigned</a>
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
											$resArray=$dbf->fetchOrder("state st,clients c,service s,work_order w","c.state='$sgRes[state]' AND " .$cond,"w.id DESC","st.state_name,c.*,s.service_name,w.*","");
											foreach($resArray as $key=>$res_JobBoard) { 
											$pickupstate = $dbf->getDataFromTable("state","state_name","state_code='$res_JobBoard[pickup_state]'");
											if($res_JobBoard['job_status']=='Open'){$color='#333';}	
											//get client name
											if($res_JobBoard['created_by']<>0){
												$clientname =$dbf->getDataFromTable("clients","name","id='$res_JobBoard[created_by]'");
											}else{
												$clientname="COD";
											}							
										?>   
                                    	<tr class="<?php echo $Cls;?> ro" <?php if($k!=0){?> style="display:none;" <?php } ?>>
                                        	<input type="hidden" id="WorkOrder<?php echo $res_JobBoard['id'];?>" value="<?php echo $res_JobBoard['wo_no'];?>"/>
                                            <td data-title="WO#" class="coltext"><a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_JobBoard['id'];?>','view-job-board');" title="Click Here For Job Details" style="color:<?php echo $color;?>"><?php echo $res_JobBoard['wo_no'];?></a></td>
                                            <td data-title="CustomerName"><?php echo $dbf->cut($res_JobBoard['name'],15);?></td>
                                            <td data-title="CreatedDate"><?php echo date("d-M-Y",strtotime($res_JobBoard['created_date']));?></td>
                                            <td data-title="JobStatus" class="coltext" style="color:<?php echo $color;?>"><?php echo $res_JobBoard['job_status'];?></td>
                                            <td data-title="ServiceType"><?php echo $res_JobBoard['service_name'];?></td>
                                            <td data-title="Pickupcity"><?php echo $res_JobBoard['pickup_city'];?></td>
                                            <td data-title="PickupState"><?php echo $pickupstate;?></td>
                                            <td data-title="PickupPhone" ><?php echo $res_JobBoard['pickup_phone_no'];?></td>
                                            <td data-title="DeliveryCity"><?php echo $res_JobBoard['city'];?></td>
                                            <td data-title="DeliveryState"><?php echo $res_JobBoard['state_name'];?></td>
                                            <td data-title="DeliveryPhone"><?php echo $res_JobBoard['phone_no'];?></td>
                                            <td data-title="Client" class="coltext"><?php echo $clientname;?></td>
                                            <td data-title="Assign" class="coltext"><a href="javascript:void(0);" onClick="ShowTechnicians('<?php echo $res_JobBoard['id'];?>');" title="Click Here To Assign Tech">Assign</a></td>
                                            <td data-title="Action"><a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_JobBoard['id'];?>','edit-job-board');"><img src="images/edit.png" title="Edit" alt="Edit"/></a>&nbsp;<a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_JobBoard['id'];?>','view-job-board');"><img src="images/view.png" title="View" alt="View"/></a>&nbsp;<a href="manage-job-board?action=delete&id=<?php echo $res_JobBoard['id'];?>" onClick="return confirm('Are you sure you want to delete this record ?')"><img src="images/delete.png" title="delete" alt="delete"></a>&nbsp;<a href="manage-job-board?action=email&id=<?php echo $res_JobBoard['id'];?>" onClick="return confirm('Are you sure you want to send email ?')"><img src="images/email_go.png" title="Email To Customer" alt="Email"></a></td>
                                        </tr>
                                         <?php } 
											}
										?> 
                                    </tbody>
                               </table>
                              <!-----Table area start-------> 
                            <?php if($num == 0) {?><div class="noRecords" align="center">No records founds!!</div><?php }?>
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