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
?>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />
<link rel="stylesheet" href="css/no_more_table.css" type="text/css" />
<script type="text/javascript">
/*********Function to redirect page************/
function redirectPage(id,page,src){
	$("#hid").val(id);
	$("#src").val(src);
	document.frmRedirect.action=page;
	document.frmRedirect.submit();
}
/*********Function to redirect page************/
</script>
<body>
	<form name="frmRedirect" id="frmRedirect" action="" method="post"> 
    	<input type="hidden" name="id" id="hid" value=""/>
        <input type="hidden" name="src" id="src" value=""/>
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
                        <div class="headerbg"><div style="float:left;">Search Job Board</div>
                        </div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        <div class="spacer"></div>
                              <?php
							   	$sch="";
								if($_REQUEST['srchInputBox']!=''){
									$searchStringArr = explode(",",$_REQUEST['srchInputBox']);
									$searchString = "'".implode("','",$searchStringArr)."'";
									###########Search condition for search jobs################
									$sch=$sch."w.wo_no IN ($searchString) OR w.purchase_order_no IN ($searchString) OR w.work_status IN ($searchString) OR w.invoice_no IN ($searchString) OR c.name IN ($searchString) OR c.email IN ($searchString)  OR c.phone_no IN ($searchString) OR c.city IN ($searchString) OR c.address IN ($searchString) OR s.service_name IN ($searchString) OR cl.name IN($searchString) OR temp.techname IN($searchString)";
									if($sch !=''){
								 		$cond="c.state=st.state_code AND c.id=w.client_id AND w.service_id=s.id AND w.approve_status='1' AND (".$sch.")";
									}
									//echo $cond;
									###########Search condition for search jobs################
									###########qry for search jobs################
									$qry = "SELECT w.id,w.wo_no,w.purchase_order_no, w.work_status, w.created_by,c.name, c.email,c.city,c.phone_no,c.address, st.state_name, s.service_name,cl.name as cname , temp.techname,temp.start_date FROM state st,clients c,service s,work_order w LEFT JOIN clients as cl ON w.created_by=cl.id,work_order wo LEFT JOIN (select at.wo_no,at.start_date,CONCAT_WS(' ',t.first_name,t.last_name)as techname from assign_tech at,technicians t where at.tech_id=t.id) as temp ON temp.wo_no=wo.wo_no WHERE wo.wo_no=w.wo_no AND ".$cond." ORDER BY w.id DESC";
							   		$resArray=$dbf->simpleQuery($qry);
								   //print "<pre>";
								   //print_r($resArray);
								   ###########qry for search jobs################
								}
							   $resArray =!empty($resArray)?$resArray :array();
							   $num=count($resArray);
							  ?>
                             <!---------Open Job Table-------------->
                             <div align="center" class="heading">Search Job Board &nbsp;&nbsp;&raquo;&nbsp;&nbsp; Total : <?php echo $num;?> Orders</div>
                             <?php 
							   if($num > 0){ ?>
                               <!-----Table area start------->
                                <table id="no-more-tables" class="draggable">
                                    <thead>
                                        <tr>
                                            <th width="6%">WO#</th>
                                            <th width="8%">PO#</th>
                                            <th width="8%">OrderStatus</th>
                                            <th width="8%">ServiceType</th>
                                            <th width="8%">CustomerName</th>
                                            <th width="8%">PhoneNo</th>
                                            <th width="8%">Address</th>
                                            <th width="8%">City</th>
                                            <th width="8%">State</th>
                                            <th width="8%">Client</th>
                                            <th width="8%">Tech</th>
                                            <th width="8%">Baord</th>
                                            <th width="6%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                     <?php 
										foreach($resArray as $key=>$res_JobBoard) {
											//get client name
											$clientname = $res_JobBoard['cname']? $res_JobBoard['cname']:"COD";
											//get board name and redirect urls
											if($res_JobBoard['work_status']=='Open'){
												$board ="Open";
												$boardURL ="manage-job-board";
												$viewURL ="view-job-board-open";
												$editURL ="edit-job-board-open";
												$src ="open";
											}else{
												if($res_JobBoard['work_status']=='Assigned' && $res_JobBoard['start_date']=='0000-00-00'){
													$board ="Assigned";
													$boardURL ="manage-job-board-assigned";
													$viewURL ="view-job-board";
													$editURL ="edit-job-board-assign";
													$src ="assigned";
												}else{
													$board ="Dispatch";
													$boardURL ="manage-job-board-dispatch";
													$viewURL ="view-job-board";
													$editURL ="edit-job-board";
													$src ="disp";
												}
											}
										?>   
                                    	<tr>
                                        	<input type="hidden" id="WorkOrder<?php echo $res_JobBoard['id'];?>" value="<?php echo $res_JobBoard['wo_no'];?>"/>
                                            <td data-title="WO#" class="coltext"><a href="javascript:void();" onClick="redirectPage('<?php echo $res_JobBoard['id'];?>','<?php echo $viewURL;?>','<?php echo $src;?>');" title="Click Here For Job Details" ><?php echo $res_JobBoard['wo_no'];?></a></td>
                                            <td data-title="PO#"><?php echo $res_JobBoard['purchase_order_no'];?></td>
                                            <td data-title="OrderStatus" class="coltext"><?php echo $res_JobBoard['work_status'];?></td>
                                            <td data-title="ServiceType"><?php echo $res_JobBoard['service_name'];?></td>
                                            <td data-title="CustomerName"><?php echo $dbf->cut($res_JobBoard['name'],20);?></td>
                                            <td data-title="PhoneNo"><?php echo $res_JobBoard['phone_no'];?></td>
                                            <td data-title="Address"><?php echo $res_JobBoard['address'];?></td>
                                            <td data-title="City"><?php echo $res_JobBoard['city'];?></td>
                                            <td data-title="State"><?php echo $res_JobBoard['state_name'];?></td>
                                            <td data-title="Client" class="coltext"><?php echo $clientname;?></td>
                                            <td data-title="Tech" class="coltext"><?php echo $res_JobBoard['techname'];?></td>
                                            <td data-title="Tech" class="coltext"><a href="<?php echo $boardURL;?>"><?php echo $board;?></a></td>
                                            <td data-title="Action"><a href="javascript:void();" onClick="redirectPage('<?php echo $res_JobBoard['id'];?>','<?php echo $editURL;?>','<?php echo $src;?>');"><img src="images/edit.png" title="Edit" alt="Edit"/></a>&nbsp;<a href="javascript:void();" onClick="redirectPage('<?php echo $res_JobBoard['id'];?>','<?php echo $viewURL;?>','<?php echo $src;?>');"><img src="images/view.png" title="View" alt="View"/></a></td>
                                        </tr>
                                       <?php } ?> 
                                    </tbody>
                               </table>
                              <!-----Table area start-------> 
                             <?php }else {?><div class="noRecords" align="center">No records founds!!</div><?php }?>
                             <div class="spacer"></div>
                             <!---------Open Job Table-------------->
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