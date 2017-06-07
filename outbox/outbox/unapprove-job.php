<?php 
	ob_start();
	session_start();
	include_once 'includes/class.Main.php';
	//Object initialization
	$dbf = new User();
	//page titlevariable
	$pageTitle="Welcome To Out Of The Box";
	include 'applicationtop.php';
	if($_SESSION['userid']==''){
		header("location:logout");exit;
	}
	//Delete record from users Table
	if($_REQUEST['action']=='delete')
	{	
		$dbf->deleteFromTable("workorder_service","workorder_id='$_REQUEST[id]'");
		$dbf->deleteFromTable("work_order","id='$_REQUEST[id]'");
		header("Location:unapprove-job");exit;
	}
	//for approve  jobs
	if($_REQUEST['action']=='approve'){	
		$dbf->updateTable("work_order","approve_status='1'","id='$_REQUEST[id]'");
		header("Location:unapprove-job");exit;
	}
	
?>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />
<link rel="stylesheet" href="css/no_more_table.css" type="text/css" />
<script  type="text/javascript" src="js/dragtable.js"></script>
<script type="text/javascript">
function ClearFields(){
	$('#srchCust').val("");
	$('#srchClient').val("");
	$('#Delivrystate').val("");
	$('#srchService').val("");
	$('#FromDate').val("");
	$('#ToDate').val("");
	document.SrchFrm.submit();
}
/*********Function to expand and collapse group************/
function funHide(clss,id){
	//alert(id);
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
/*********Function to redirect page************/
function redirectPage(id,page){
	$("#hid").val(id);
	document.frmRedirect.action=page;
	document.frmRedirect.submit();
}
/*********Function to redirect page************/
</script>
<body>
	<form name="frmRedirect" id="frmRedirect" action="" method="post"> 
    	<input type="hidden" name="id" id="hid" value=""/>
        <input type="hidden" name="src" id="src" value="unapprv"/>
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
						 	###############SEARCH CONDITIONS START HERE###################
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
								$sch=$sch."w.created_date = '$fromdt' AND ";
							}
							if($_REQUEST['FromDate']=='' && $_REQUEST['ToDate']!=''){
								$sch=$sch."w.created_date = '$todt' AND ";
							}
							if(($_REQUEST['FromDate']!='') && ($_REQUEST['ToDate']!='')){
								$sch=$sch."w.created_date BETWEEN '$fromdt' AND '$todt' AND ";
							}
						   $sch=substr($sch,0,-5);
						   //echo $sch;exit;
						   if($sch!=''){
							 $cond="c.state=st.state_code AND c.id=w.client_id AND w.service_id=s.id AND w.work_status='Open' AND w.approve_status='0' AND ".$sch;
							  // echo $cond;exit;
						   }
						   elseif($sch==''){
							 $cond="c.state=st.state_code AND c.id=w.client_id AND w.service_id=s.id AND w.work_status='Open' AND w.approve_status='0'";
						   }
						   //condition for users
						   if($implode_clients <>''){
								$cond.=" AND FIND_IN_SET(w.created_by,'$implode_clients')";
						   }
						   //echo $cond;
						   ###############SEARCH CONDITIONS START HERE###################
						   //count total records
						   $num=$dbf->countRows("state st,clients c,service s,work_order w",$cond); 
						 ?>
                        <div class="headerbg">
                        	<div style="float:left; width:30%;">Unapproved Order Board</div>
                            <div style="float:left;width:30%; text-align:center;">Total : <?php echo $num;?> Orders</div>
                        	<div style="float:right; width:40%; text-align:right;"><input type="button" class="buttonText2" value="Create Order" onClick="javascript:window.location.href='create-job'"/></div>
                        </div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                              <form name="SrchFrm" id="SrchFrm" action="" method="post">
                              <div style="margin-bottom:5px;" align="center">
                              	  <div  class="formtextaddsrch" align="center">Customer</div>
                                  <div class="textboxcsrch">
                                  <select name="srchCust" id="srchCust" class="selectboxsrch">
                                  		<option value="">--Select Customer--</option>
                                        <?php foreach($dbf->fetchOrder("work_order wo,clients cl","wo.client_id=cl.id AND wo.work_status='Open'","cl.name ASC","","cl.name")as $customer){?>
                                        <option value="<?php echo $customer['id']?>" <?php if($customer['id']==$_REQUEST['srchCust']){echo 'selected';}?>><?php echo $customer['name'];?></option>
                                        <?php }?>
                                   </select>
                                  </div>
                              	  <div  class="formtextaddsrch" align="center">Client</div>
                                  <div class="textboxcsrch">
                                  <select name="srchClient" id="srchClient" class="selectboxsrch">
                                    <option value="">--Select Client--</option>
                                    <?php if($implode_clients ==''){?>
                                    <option value="0" <?php if($_REQUEST['srchClient']=="0"){echo 'selected';}?>> COD </option>
                                    <?php 
									}
                                    $condc = "wo.created_by=cl.id AND wo.created_by<>'0' AND wo.work_status='Open'";
                                    //condition for users
                                    if($implode_clients <>''){
                                        $condc.=" AND FIND_IN_SET(cl.id,'$implode_clients')";
                                    }
                                    foreach($dbf->fetchOrder("work_order wo,clients cl",$condc,"cl.name ASC","","cl.name")as $client){?>
                                    <option value="<?php echo $client['id']?>" <?php if($client['id']==$_REQUEST['srchClient']){echo 'selected';}?>><?php echo $client['name'];?></option>
                                    <?php }?>
                                   </select>
                                    </div>
                                    <div  class="formtextaddsrch"align="center">Service</div>
                                    <div class="textboxcsrch">
                                    <select name="srchService" id="srchService" class="selectboxsrch">
                                    	<option value="">--Service Type--</option>
                                        <?php foreach($dbf->fetch("service","id>0 ORDER BY service_name ASC")as $service){?>
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
                                    <div>
                                    <input type="submit" class="buttonText2" name="SearchRecord" value="Filter Order">
                                    <input type="button" class="buttonText2" name="Reset" value="Reset Filter" onClick="ClearFields();">
                                   </div>
                                  </div>
                              </form>
                              <div class="spacer"></div>
                             
                              <!-----Table area start------->
                                <table id="no-more-tables" class="draggable">
                                    <thead>
                                        <tr>
                                            <th width="6%">WO#</th>
                                            <th width="10%">CustomerName</th>
                                            <th width="8%">CreatedDate</th>
                                            <th width="6%">OrderStatus</th>
                                            <th width="10%">ServiceType</th>
                                            <th width="8%">Pickupcity</th>
                                            <th width="7%">PickupState</th>
                                            <th width="7%">DeliveryCity</th>
                                            <th width="8%">DeliveryState</th>
                                            <th width="8%">DeliveryPhone</th>
                                            <th width="10%">Client</th>
                                            <th width="6%">Status</th>
                                            <th width="6%">Action</th>
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
										//group array
										$resGrArray=$dbf->fetchOrder("state st,clients c,service s,work_order w",$cond,"c.state ASC","","c.state");
										//group by state loop
										foreach($resGrArray as $k=>$sgRes){
										$Cls="g$k";	
										$numres = $dbf->countRows("state st,clients c,service s,work_order w","c.state='$sgRes[state]' AND " .$cond);
									  ?>
										<tr style="background-color:#f9f9f9;" class="ho">
                                            <td valign="top" class="grheading">
                                            <div class="divgr">
                                            <a href="javascript:void(0);" onClick="funShow('<?php echo $Cls;?>','<?php echo $k;?>');" id="e<?php echo $k;?>" <?php if($k==0){?>style="display:none;" <?php }?> class="hoa"><img  src="images/plus.gif" height="13" width="13"/>&nbsp;<?php echo $numres;?> unapprove Jobs in <span style="color:#ff9812;"><?php echo $sgRes['state_name'];?></span> needs to be approved</a> 
                                			<a href="javascript:void(0);" onClick="funHide('<?php echo $Cls;?>','<?php echo $k;?>');" id="c<?php echo $k;?>" <?php if($k!=0){?>style="display:none;" <?php }?> class="hob"><img  src="images/minus.gif" height="13" width="13"/>&nbsp;<?php echo $numres;?> unapprove Jobs in <span style="color:#ff9812;"><?php echo $sgRes['state_name'];?></span> needs to be approved</a>
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
											$resArray=$dbf->fetchOrder("state st,clients c,service s,work_order w","c.state='$sgRes[state]' AND " .$cond,"w.id DESC","","");
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
                                    	<tr class="<?php echo $Cls;?> ro" <?php if($k!=0){?> style="display:none;" <?php } ?>>
                                        	<input type="hidden" id="WorkOrder<?php echo $res_JobBoard['id'];?>" value="<?php echo $res_JobBoard['wo_no'];?>"/>
                                            <td data-title="WO#" class="coltext"><a href="view-job-board?src=unapprv&id=<?php echo $res_JobBoard['id'];?>" title="Click Here For Job Details" style="color:<?php echo $color;?>"><?php echo $res_JobBoard['wo_no'];?></a></td>
                                            <td data-title="CustomerName"><?php echo $res_JobBoard['name'];?></td>
                                            <td data-title="CreatedDate"><?php echo date("d-M-Y",strtotime($res_JobBoard['created_date']));?></td>
                                            <td data-title="JobStatus" class="coltext" style="color:<?php echo $color;?>"><?php echo $res_JobBoard['work_status'];?></td>
                                            <td data-title="ServiceType"><?php echo $res_JobBoard['service_name'];?></td>
                                            <td data-title="Pickupcity"><?php echo $res_JobBoard['pickup_city'];?></td>
                                            <td data-title="PickupState"><?php echo $pickupstate;?></td>
                                            <td data-title="DeliveryCity"><?php echo $res_JobBoard['city'];?></td>
                                            <td data-title="DeliveryState"><?php echo $res_JobBoard['state_name'];?></td>
                                            <td data-title="DeliveryPhone"><?php echo $res_JobBoard['phone_no'];?></td>
                                            <td data-title="Client" class="coltext"><?php echo $clientname;?></td>
                                            <td data-title="Status" class="coltext"><a href="unapprove-job.php?action=approve&id=<?php echo $res_JobBoard['id'];?>" onClick="return confirm('Are you sure you want to approve this record ?')">Unapproved</a></td>
                                            <td data-title="Action"><a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_JobBoard['id'];?>','edit-job-board-open');"><img src="images/edit.png" title="Edit" alt="Edit"/></a>&nbsp;<a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_JobBoard['id'];?>','view-job-board-open');"><img src="images/view.png" title="View" alt="View"/></a>&nbsp;<a href="unapprove-job.php?action=delete&id=<?php echo $res_JobBoard['id'];?>" onClick="return confirm('Are you sure you want to delete this record ?')"><img src="images/delete.png" title="delete" alt="delete"></a></td>
                                        </tr>
                                         <?php } 
											}
										?> 
                                    </tbody>
                               </table>
                              <!-----Table area start-------> 
                            <?php if($num == 0) {?><div class="noRecords" align="center">No records founds!!</div><?php }?>
                          
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