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
	$x = 0;
	if($_REQUEST['action']=='search' || $_GET["page"]){
	   $x=1; 
	}
	###########Cancel record from work order Table###############
	if(isset($_REQUEST['action']) && $_REQUEST['action']=='cancel'){	
		$dbf->updateTable("work_order","work_status='Cancelled'","id='$_REQUEST[id]'");
		###########Track user activity in work order notes table#############
		$adminNotes="This order is cancelled.";
		$strnotes="workorder_id='$_REQUEST[id]', user_type='$_SESSION[usertype]', user_id='$_SESSION[userid]', wo_notes='$adminNotes',created_date=now()";
		$dbf->insertSet("workorder_notes",$strnotes);
		###########Track user activity in work order notes table#############
		header("Location:admin_assigned_orders");exit;
	}
	###########Cancel record from work order Table###############
?>
<body>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />
<link rel="stylesheet" href="css/no_more_table.css" type="text/css" />
<script type="text/javascript">
function Search_Records(){
	$("#SrchFrm").attr("action","admin_assigned_orders");
	$("#SrchFrm").submit();
}
function ClearFields(){
	//$('#srchTechnician').val("");
	$('#srchService').val("");
	$('#FromDate').val("");
	$('#ToDate').val("");
	$('#hidaction').val("");
	window.location.href="admin_assigned_orders";
}
/*********Function to redirect page************/
function redirectPage(id,page,k){
	//alert(k);
	$("#hid").val(id);
	$("#hidk").val(k);
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
</script>

	<form name="frmRedirect" id="frmRedirect" action="" method="post"> 
    	<input type="hidden" name="id" id="hid" value=""/>
        <input type="hidden" name="src" value="assigned"/>
        <input type="hidden" name="hidk" id="hidk" value=""/>
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
						$sch="";
						$fromdt=date("Y-m-d",strtotime(($_REQUEST['FromDate'])));
						$todt=date("Y-m-d",strtotime(($_REQUEST['ToDate'])));
						if($_REQUEST['srchService']!=''){
							$sch=$sch."s.id='$_REQUEST[srchService]' AND ";
						}
						if($_REQUEST['FromDate']!='' && $_REQUEST['ToDate']==''){
							$sch=$sch."at.assign_date >= '$fromdt' AND ";
						}
						if($_REQUEST['FromDate']=='' && $_REQUEST['ToDate']!=''){
							$sch=$sch."at.assign_date <= '$todt' AND ";
						}
						if(($_REQUEST['FromDate']!='') && ($_REQUEST['ToDate']!='')){
							$sch=$sch."at.assign_date BETWEEN '$fromdt' AND '$todt' AND ";
						}
					   $sch=substr($sch,0,-5);
					   //echo $sch;exit;
					   if($sch!=''){
						 $cond="w.service_id=s.id AND c.id=w.client_id AND c.state=st.state_code AND w.work_status ='Assigned' AND w.wo_no NOT IN (SELECT wo_no FROM assign_tech) AND ".$sch;
					  // echo $cond;exit;
					   }elseif($sch==''){
						 $cond="w.service_id=s.id AND c.id=w.client_id AND c.state=st.state_code AND w.work_status ='Assigned' AND w.wo_no NOT IN (SELECT wo_no FROM assign_tech)";
					   }
					   //print $cond;
					   //Pagination 
						$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
						$page = ($page == 0 ? 1 : $page);
						$perpage =100;//limit in each page
						$startpoint = ($page * $perpage) - $perpage;
						//-----------------------------------				
						$num=$dbf->countRows("service s,state st,clients c,work_order w",$cond); 
						?>
                        <div class="headerbg">
                            <div style="float:left;;">Assigned Jobs Without Tech</div>
                            <div style="float:left;width:30%; text-align:center;">Total : <?php echo $num;?> Orders</div>
                        </div>
                        <div id="contenttable">
                        	<div style="width:100%;float:left;">
                            <div class="spacer"></div>
                            <form name="SrchFrm" id="SrchFrm" action="" method="post">
                              <div style="margin-bottom:5px;" align="center">
                                    <div  class="formtextaddsrch"align="center">Service:</div>
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
                                    <input type="hidden" name="action"  value="search">
                                    <input type="hidden" name="hidaction"  value="<?php echo $x;?>">
                                    <input type="button" class="buttonText2" name="SearchRecord" id="SearchRecord" value="Filter Report" onClick="Search_Records();">
                                    <input type="button" class="buttonText2" name="Reset" value="Reset Filter" onClick="ClearFields();">
                                   </div>
                                  </div>
                              </form>
                              <?php
								if($num>0){
							   ?>
                            <!-----Table area start------->
                            <table id="no-more-tables" class="draggable">
                                <thead>
                                    <tr>
                                      <th width="6%">WO#</th>
                                      <th width="10%">PO#</th>
                                      <th width="10%">CreatedDate</th>
                                      <th width="10%">CustomerName</th>
                                      <th width="10%">DeliveryState</th>
                                      <th width="10%">DeliverPhone</th>
                                      <th width="10%">ServiceType</th>
                                      <th width="7%">OrderStatus</th>
                                      <th width="10%">Client</th>
                                      <th width="10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                           <?php
								foreach($dbf->fetchOrder("service s,state st,clients c,work_order w",$cond,"w.id DESC LIMIT $startpoint,$perpage","w.*,c.name, c.phone_no, c.city,st.state_name,s.service_name","")as  $res_JobBoard) {
								
								if($res_JobBoard['created_by']<>'0'){
									$clientname=$dbf->getDataFromTable("clients","name","id='$res_JobBoard[created_by]'");}else{
									$clientname="COD";
								} 
							?>
                            <tr>
                                <td data-title="WO#"><b><?php echo $res_JobBoard['wo_no'];?></b></td>
                                <td data-title="PO#"><?php echo $res_JobBoard['purchase_order_no'];?></td>
                                <td data-title="CreatedDate"><?php echo date("d-M-Y",strtotime($res_JobBoard['created_date']));?></td>
                                <td data-title="CustomerName"><?php echo $dbf->cut($res_JobBoard['name'],15);?></td>
                                <td data-label="DeliveryState"><?php echo $res_JobBoard['state_name'];?></td>
                                <td data-label="DeliveryPhone"><?php echo $res_JobBoard['phone_no'];?></td>
                                <td data-label="ServiceType"><?php echo $res_JobBoard['service_name'];?></td>
                                <td data-label="OrderStatus"><?php echo $res_JobBoard['work_status'];?></td>
                                <td data-label="Client"><?php echo $clientname;?></td> 
                                <td data-title="Action"><a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_JobBoard['id'];?>','edit-job-board-assign','<?php echo $k;?>');"><img src="images/edit.png" title="Edit" alt="Edit"/></a>&nbsp;<a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_JobBoard['id'];?>','view-job-board','<?php echo $k;?>');"><img src="images/view.png" title="View" alt="View"/></a>&nbsp;<a href="javascript:void();" onClick="cancel_order('<?php echo $res_JobBoard['id'];?>','admin_assigned_orders');"><img src="images/cancel_round.png" title="Cancel" alt="Cancel"/></a>&nbsp;<a href="javascript:void(0);"  onClick="print_doc('print','<?php echo $res_JobBoard['id'];?>');" ><img src="images/print.png" alt="Print"  title="Print Workorder"></a>&nbsp;<a href="javascript:void(0);" onClick="print_doc('pdf','<?php echo $res_JobBoard['id'];?>');"><img src="images/pdf.png" style="width:16px; height:16px;" title="Export to PDF"/></a></td>
                            </tr>
                            <?php } ?>
                            </tbody>
                           </table>
                          <!-----Table area end------->
						 <?php }else{?>
                              <div style="padding-left:40%;border:1px solid #000;color:#F00;">No records founds!!</div>
                            <?php }?>
                            <div  align="center"><?php if($num>0) { echo $dbf->Pages($num,$perpage,"admin_assigned_orders?srchService=$_REQUEST[srchService]&FromDate=$_REQUEST[FromDate]&ToDate=$_REQUEST[ToDate]&");}?></div>
                          
                        </div>
                        <div class="spacer"></div>
                    </div>
            	    </div>
              <!-------------Main Body--------------->
                </div>
                <div class="spacer"></div>
        <?php include_once 'footer.php'; ?>
        </div>
    </div>
</body>
</html>