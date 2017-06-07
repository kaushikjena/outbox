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
?>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />
<link rel="stylesheet" href="css/table.css" type="text/css" />
<script type="text/javascript">
function Search_Records(){
	$("#SrchFrm").attr("action","admin_schedule_job_report");
	$("#SrchFrm").submit();
}
function ClearFields(){
	$('#srchClient').val("");
	$('#srchTechnician').val("");
	$('#srchService').val("");
	$('#FromDate').val("");
	$('#ToDate').val("");
	$('#hidaction').val("");
	window.location.href="admin_schedule_job_report";
}
//for exporting,print,pdf,word
function print_doc(val,page){
 if(val=='word'){
	$("#SrchFrm").attr("action","admin_schedule_job_report_word");
	$("#SrchFrm").submit();
 }else if(val=='excell'){
	$("#SrchFrm").attr("action","admin_schedule_job_report_excell");
	$("#SrchFrm").submit();
 }else if(val=='pdf'){
	$("#SrchFrm").attr("action","admin_schedule_job_report_pdf");
	$("#SrchFrm").submit();
 }else if(val=='print'){
	$("#SrchFrm").attr("action","admin_schedule_job_report_print?page="+page);
	$("#SrchFrm").attr("target","_blank");
	$("#SrchFrm").submit(); 
 }
}
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
                        <div class="headerbg">
                            <div style="float:left;;">Schedule Jobs Report</div>
                            <div style="float:right;">
                            <a href="javascript:void(0);" onClick="print_doc('word');"><img src="images/word2007.png" style="width:20px; height:20px;" title="Export to Word"/></a>
                            <a href="javascript:void(0);" onClick="print_doc('pdf');"><img src="images/pdf.png" style="width:20px; height:20px;" title="Export to PDF"/></a>
                            <a href="javascript:void(0);" onClick="print_doc('excell');"><img src="images/export_excel.png" style="width:20px; height:20px;" title="Export to Excel"></a>
                            <a href="javascript:void(0);"  onClick="print_doc('print','<?php echo (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);?>');" ><img src="images/print.png" alt="" style="width:20px; height:20px;" title="Print"></a>
                            </div>
                        </div>
                        <div id="contenttable">
                        	<div style="width:100%;float:left;">
                            <div class="spacer"></div>
                            <form name="SrchFrm" id="SrchFrm" action="" method="post">
                              <div style="margin-bottom:5px;" align="center">
                              	  <div  class="formtextaddsrch" align="center">Client:</div>
                                  <div class="textboxcsrch">
                                  <select name="srchClient" id="srchClient" class="selectboxsrch">
                                  		<option value="">--Select Client--</option>
                                        <option value="0" <?php if($_REQUEST['srchClient']=="0"){echo 'selected';}?>> COD </option>
                                        <?php foreach($dbf->fetchOrder("work_order w,clients cl","w.created_by=cl.id AND w.created_by <>'0' AND w.work_status='Scheduled'","cl.name ASC","","cl.name")as $client){?>
                                        <option value="<?php echo $client['id']?>" <?php if($client['id']==$_REQUEST['srchClient']){echo 'selected';}?>><?php echo $client['name'];?></option>
                                        <?php }?>
                                   </select>
                                    </div>
                                     <div class="formtextaddsrch" align="center">Technician:</div>
                                     <div class="textboxcsrch">
                                    <select name="srchTechnician" id="srchTechnician" class="selectboxsrch">
                                        <option value="">--Select Tech--</option>
                                        <?php foreach($dbf->fetch("technicians","id>0 ORDER BY first_name ASC")as $tech){?>
                                        <option value="<?php echo $tech['id']?>" <?php if($tech['id']==$_REQUEST['srchTechnician']){echo 'selected';}?>><?php echo $tech['first_name'].'&nbsp;'.$tech['middle_name'].'&nbsp;'.$tech['last_name'];?></option>
                                        <?php }?>
                                    </select>
                                    </div>
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
						       $sch="";
								$fromdt=date("Y-m-d",strtotime(($_REQUEST['FromDate'])));
								$todt=date("Y-m-d",strtotime(($_REQUEST['ToDate'])));
								
								if($_REQUEST['srchClient']!=''){
									$sch=$sch."w.created_by='$_REQUEST[srchClient]' AND ";
								}
								if($_REQUEST['srchTechnician']!=''){
									$sch=$sch."t.id='$_REQUEST[srchTechnician]' AND ";
								}
								if($_REQUEST['srchService']!=''){
									$sch=$sch."s.id='$_REQUEST[srchService]' AND ";
								}
								if($_REQUEST['FromDate']!='' && $_REQUEST['ToDate']==''){
									$sch=$sch."at.start_date >= '$fromdt' AND ";
								}
								if($_REQUEST['FromDate']=='' && $_REQUEST['ToDate']!=''){
									$sch=$sch."at.start_date <= '$todt' AND ";
								}
								if(($_REQUEST['FromDate']!='') && ($_REQUEST['ToDate']!='')){
									$sch=$sch."at.start_date BETWEEN '$fromdt' AND '$todt' AND ";
								}
							   $sch=substr($sch,0,-5);
							   //echo $sch;exit;
							   if($sch!=''){
								 $cond="c.state=st.state_code AND c.id=w.client_id AND w.service_id=s.id AND w.work_status='Scheduled' AND w.approve_status='1' AND w.wo_no=at.wo_no AND at.tech_id=t.id AND ".$sch;
							  // echo $cond;exit;
							   }
							   elseif($sch==''){
								 $cond="c.state=st.state_code AND c.id=w.client_id AND w.service_id=s.id AND w.work_status='Scheduled' AND w.approve_status='1' AND w.wo_no=at.wo_no AND at.tech_id=t.id";
							   }
							   //print $cond;
							   //Pagination 
                                $page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
                                $page = ($page == 0 ? 1 : $page);
                                $perpage =100;//limit in each page
                                $startpoint = ($page * $perpage) - $perpage;
                                //-----------------------------------				
                                $num=$dbf->countRows("state st,clients c,service s,technicians t,assign_tech at,work_order w",$cond); 
								if($num>0){
							   ?>
                           <div class="table">
                            <div class="table-head">
                                <div class="column" data-label="WO#" style="width:6%;">WO No</div>
                                <div class="column" data-label="CustomerName" style="width:8%;">CustomerName</div> 
                                <div class="column" data-label="CreatedDate" style="width:8%;">CreatedDate</div>
                                <div class="column" data-label="OrderStatus" style="width:6%;">OrderStatus</div>
                                <div class="column" data-label="ServiceType" style="width:8%;">ServiceType</div>
                                <div class="column" data-label="PickupAddress" style="width:8%;">PickupAddress</div>
                                <div class="column" data-label="Pickupcity" style="width:6%;">Pickupcity</div>
                                <div class="column" data-label="PickupPhone" style="width:10%;">PickupPhone</div>
                                <div class="column" data-label="DeliveryCity" style="width:7%;">DeliveryCity</div> 
                                <div class="column" data-label="DeliveryState" style="width:7%;">DeliveryState</div>
                                <div class="column" data-label="Client" style="width:10%;">Client</div>
                                <div class="column" data-label="TechName" style="width:10%;">TechName</div>
                            </div>
                           <?php
								foreach($dbf->fetchOrder("state st,clients c,service s,technicians t,assign_tech at,work_order w",$cond,"w.id DESC LIMIT $startpoint,$perpage","","")as  $res_JobBoard) {
								
								$techname =$dbf->fetchSingle("assign_tech at,technicians t","t.id=at.tech_id AND at.wo_no='$res_JobBoard[wo_no]'");
								if($res_JobBoard['work_status']=='Completed'){
									//check for payment completed work orders
									$paymentstatus = $dbf->getDataFromTable("work_order_bill","payment_status","wo_no='$res_JobBoard[wo_no]'");
									if($paymentstatus<>'Completed'){
										$color="#090";	
									}else{
										$color="#0FCBFF";
									}
								}else{
									$color='#F00';
								}
								if($res_JobBoard['created_by']<>'0'){
									$clientname=$dbf->getDataFromTable("clients","name","id='$res_JobBoard[created_by]'");}else{
									$clientname="COD";
								} 
							?>
                            <div class="row">
                                <div class="column" data-label="WO#" style="color:<?php echo $color;?>; font-weight:bold;"><?php echo $res_JobBoard['wo_no'];?></div>
                                <div class="column" data-label="CustomerName"><?php echo $dbf->cut($res_JobBoard['name'],15);?></div>
                                <div class="column" data-label="CreatedDate"><?php echo date("d-M-Y",strtotime($res_JobBoard['created_date']));?></div>
                                <div class="column" data-label="OrderStatus" style="font-weight:bold;" id="workstatus"><?php if($res_JobBoard['work_status']<>''){?><?php echo $res_JobBoard['work_status'];?><?php } else{echo 'Not Started';}?></div>
                                <div class="column" data-label="ServiceType"><?php echo $res_JobBoard['service_name'];?></div>
                                <div class="column" data-label="PickupAddress"><?php echo $res_JobBoard['pickup_address'];?></div>                                    
                                <div class="column" data-label="Pickupcity"><?php echo $res_JobBoard['pickup_city'];?></div>
                                <div class="column" data-label="PickupPhone"><?php echo $res_JobBoard['pickup_phone_no'];?></div>                            
                                <div class="column" data-label="DeliveryCity"><?php echo $res_JobBoard['city'];?></div>
                               <div class="column" data-label="DeliveryState"><?php echo $res_JobBoard['state_name'];?></div>
                               <div class="column" data-label="Client"><?php echo $clientname;?></div>      
                               <div class="column" data-label="TechName"><?php echo $techname['first_name'].' '.$techname['middle_name'].' '.$techname['last_name'];?></div>
                            </div>
                            <?php } ?>
                           </div>
						 <?php }else{?>
                              <div style="padding-left:40%;border:1px solid #000;color:#F00;">No records founds!!</div>
                            <?php }?>
                            <div  align="center"><?php if($num>0) { echo $dbf->Pages($num,$perpage,"admin_schedule_job_report?srchClient=$_REQUEST[srchClient]&srchTechnician=$_REQUEST[srchTechnician]&srchService=$_REQUEST[srchService]&FromDate=$_REQUEST[FromDate]&ToDate=$_REQUEST[ToDate]&");}?></div>
                          
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