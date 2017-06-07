<?php 
    ob_start();
	session_start();
	include_once '../includes/class.Main.php';
	//Object initialization
	$dbf = new User();
	//page titlevariable
	$pageTitle="Welcome To Out Of The Box";
	include 'applicationtop-client.php';
	//logout for users other than admin and user
	if($_SESSION['usertype']!='client'){
		header("location:../logout");exit;
	}
	$x = 0;
	if($_REQUEST['action']=='search' || $_GET["page"]){
	   $x=1; 
	}
?>
<script type="text/javascript">
function Search_Records(){
	$("#SrchFrm").attr("action", "client_report");
	$("#SrchFrm").submit();
}
function ClearFields(){
	$('#srchCust').val("");
	$('#srchStatus').val("");
	$('#srchState').val("");
	$('#srchService').val("");
	$('#FromDate').val("");
	$('#ToDate').val("");
	$("#SrchFrm").submit();
}
//for exporting,print,pdf,word
function print_doc(val,page){
 if(val=='word'){
	$("#SrchFrm").attr("action", "client_report_word");
	$("#SrchFrm").submit();
 }else if(val=='excell'){
	$("#SrchFrm").attr("action", "client_report_excell");
	$("#SrchFrm").submit(); 
 }else if(val=='pdf'){
	$("#SrchFrm").attr("action", "client_report_pdf");
	$("#SrchFrm").submit(); 
 }else if(val=='print'){
	$("#SrchFrm").attr("action", "client_report_print?page="+page);
	$("#SrchFrm").attr("target","_blank");
	$("#SrchFrm").submit();
 }
}
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
     	<?php include_once 'header-client.php';?>
   		<!-------------header--------------->
        
        <!-------------top menu--------------->
     	<?php include_once 'client-top-menu.php';?>
   		<!-------------top menu--------------->
         <div id="contentdiv">
                <!-------------Left menu--------------->
				<?php //include_once 'left-menu.php';?>
                <!-------------left menu--------------->
                <!-------------Main Body--------------->
                <div class="rightcolumjobboard">
            		<div class="rightcoluminner">
                        <div class="headerbg"><div style="float:left;">Order Report</div>
                        	<div style="float:right;padding-right:10px;">
                             <a href="javascript:void(0);" onClick="print_doc('word');"><img src="../images/word2007.png" style="width:20px; height:20px;" title="Export to Word"/></a>
                            <a href="javascript:void(0);" onClick="print_doc('pdf');"><img src="../images/pdf.png" style="width:20px; height:20px;" title="Export to PDF"/></a>
                            <a href="javascript:void(0);" onClick="print_doc('excell');"><img src="../images/export_excel.png" style="width:20px; height:20px;" title="Export to Excel"></a>
                            <a href="javascript:void(0);"  onClick="print_doc('print','<?php echo (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);?>');" ><img src="../images/print.png" alt="" style="width:20px; height:20px;" title="Print"></a>
                            </div>
                        </div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        	<div style="width:100%;float:left;">
                              <form name="SrchFrm" id="SrchFrm" action="" method="post">
                              <div style="margin-bottom:5px;" align="center">
                              	  <div  class="formtextaddsrch" align="center">Customer</div>
                                  <div class="textboxcsrch">
                                  <select name="srchCust" id="srchCust" class="selectboxsrch">
                                    <option value="">--Select Customer--</option>
                                    <?php foreach($dbf->fetchOrder("work_order wo,clients cl","wo.client_id=cl.id AND wo.created_by='$_SESSION[userid]' AND wo.work_status='Assigned'","cl.name ASC","","cl.name")as $customer){?>
                                    <option value="<?php echo $customer['id']?>" <?php if($customer['id']==$_REQUEST['srchCust']){echo 'selected';}?>><?php echo $customer['name'];?></option>
                                    <?php }?>
                                   </select>
                                  </div>
                                  <div class="formtextaddsrch" align="center">Status</div>
                                  <div class="textboxcsrch">
                                   <select name="srchStatus" id="srchStatus"class="selectboxsrch">
                                        <option value="">--Select Status--</option>
                                        <option value="Dispatched" <?php if($_REQUEST['srchStatus']=='Dispatched'){echo 'selected';}?>>Dispatched</option>
                                        <option value="In Progress" <?php if($_REQUEST['srchStatus']=='In Progress'){echo 'selected';}?>>In Progress</option>
                                        <option value="Completed" <?php if($_REQUEST['srchStatus']=='Completed'){echo 'selected';}?>>Completed</option>
                                        <option value="Invoiced" <?php if($_REQUEST['srchStatus']=='Invoiced'){echo 'selected';}?>>Invoiced</option>
                                    </select>
                                  </div>
                                   <div  class="formtextaddsrchsmall" align="center">State</div>
                                    <div class="textboxcsrch">
                                    <select name="srchState" id="srchState" class="selectboxsrch">
                                    	<option value="">--Select State--</option>
                                        <?php foreach($dbf->fetch("state","id>0 ORDER BY state_code ASC")as $srcState){?>
                                        <option value="<?php echo $srcState['state_code'];?>" <?php if($srcState['state_code']==$_REQUEST['srchState']){echo 'selected';}?>><?php echo $srcState['state_name']?></option>
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
                              <div class="spacer"></div>
                              <?php
							   	$sch="";
								$fromdt=date("Y-m-d",strtotime(($_REQUEST['FromDate'])));
								$todt=date("Y-m-d",strtotime(($_REQUEST['ToDate'])));
								
								if($_REQUEST['srchCust']!=''){
									$sch=$sch."c.id='$_REQUEST[srchCust]' AND ";
								}
								if($_REQUEST['srchStatus']!=''){
									$sch=$sch."w.work_status='$_REQUEST[srchStatus]' AND ";
								}
								if($_REQUEST['srchState']!=''){
									$sch=$sch."c.state = '$_REQUEST[srchState]' AND ";
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
								 $cond="c.state=st.state_code AND c.id=w.client_id AND w.service_id=s.id AND work_status='Assigned' AND w.wo_no=at.wo_no AND at.tech_id=t.id AND w.created_by='$_SESSION[userid]' AND ".$sch;
							   }
							   elseif($sch==''){
								 $cond="c.state=st.state_code AND c.id=w.client_id AND w.service_id=s.id AND work_status='Assigned' AND w.wo_no=at.wo_no AND at.tech_id=t.id AND w.created_by='$_SESSION[userid]'";
							   }
							   //Pagination 
                                $page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
                                $page = ($page == 0 ? 1 : $page);
                                $perpage =100;//limit in each page
                                $startpoint = ($page * $perpage) - $perpage;
                                //-----------------------------------	
								$num=$dbf->countRows("state st,clients c,service s,technicians t,assign_tech at,work_order w",$cond);
								if($num>0){ 
							  ?>
                               <!-----Table area start------->
                                <table id="no-more-tables" class="draggable">
                                	<thead>
                                        <tr>
                                            <th width="6%">WO#</th>
                                            <th width="10%">CustomerName</th>
                                            <th width="8%">ScheduledDate</th>
                                            <th width="7%">OrderStatus</th>
                                            <th width="9%">ServiceType</th>
                                            <th width="9%">PickupState</th>
                                            <th width="9%">Pickupcity</th>
                                            <th width="9%">PickupPhone</th>
                                            <th width="9%">DeliveryCity</th>
                                            <th width="9%">DeliveryState</th>
                                            <th width="9%">DeliveryPhone</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                              		<?php 
										$resGrArray=$dbf->fetchOrder("state st,clients c,service s,technicians t,assign_tech at,work_order w",$cond,"w.service_id ASC","w.service_id,s.*","s.id");
										//group by state loop
										foreach($resGrArray as $k=>$sgRes){
									?>
									<tr style="background-color:#f9f9f9;">
                                    	<td valign="top" class="grheading">
                                       	<div class="divgr">
                                            <span style="color:#ff9812;"><?php echo $sgRes['service_name'];?></span></div>
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
                                    </tr>				
								    <?php
										$resArray=$dbf->fetchOrder("state st,clients c,service s,technicians t,assign_tech at,work_order w","w.service_id='$sgRes[service_id]' AND " .$cond,"w.id DESC","st.state_name,c.*,s.service_name,at.start_date,w.*","");
										//print "<pre>";
										//print_r($resArray);
										foreach($resArray as $key=>$res_JobBoard) { 
										$pickupstate = $dbf->getDataFromTable("state","state_name","state_code='$res_JobBoard[pickup_state]'");
								     ?>
                                    <tr>
                                    <td data-title="WO#" class="coltext"><?php echo $res_JobBoard['wo_no'];?></td>
                                    <td data-title="CustomerName"><?php echo $res_JobBoard['name'];?></td>
                                    <td data-title="ScheduledDate"><?php echo date("d-M-Y",strtotime($res_JobBoard['start_date']));?></td>                          
                                    <td data-title="WorkStatus" style="font-weight:bold;" id="workstatus" class="coltext"><?php if($res_JobBoard['work_status']<>''){?><?php echo $res_JobBoard['work_status'];?><?php } else{echo 'Not Started';}?></td>
                                    <td data-title="ServiceType"><?php echo $res_JobBoard['service_name'];?></td>
                                    <td data-title="PickupState"><?php echo $pickupstate ;?></td>                                    <td data-title="Pickupcity"><?php echo $res_JobBoard['pickup_city'];?></td>
                                    <td data-title="PickupPhone"><?php echo $res_JobBoard['pickup_phone_no'];?></td>                                    <td data-title="DeliveryCity"><?php echo $res_JobBoard['city'];?></td>
                                    <td data-title="DeliveryState"><?php echo $res_JobBoard['state_name'];?></td>
                                    <td data-title="DeliveryPhone"><?php echo $res_JobBoard['phone_no'];?></td>
                               </tr>
                               <?php }
								}
							   ?>
                        	  </tbody>
                            </table>
                           <!-----Table area end------->
                            <?php }else{?>
                            <div style="padding-left:40%;border:1px solid #000;color:#F00;">No records founds!!</div>
                            <?php }?>
                             <div  align="center"><?php if($num>0) { echo $dbf->Pages($num,$perpage,"tech_report?srchCust=$_REQUEST[srchCust]&srchState=$_REQUEST[srchState]&FromDate=$_REQUEST[FromDate]&ToDate=$_REQUEST[ToDate]&srchStatus=$_REQUEST[srchStatus]&");}?></div>
                          </div>
                        </div>
                        <div class="spacer"></div>
                    </div>
            	</div>
              <!-------------Main Body--------------->
         </div>
        <div class="spacer"></div>
        <?php include_once 'footer-client.php'; ?>
    </div>
</body>
</html>