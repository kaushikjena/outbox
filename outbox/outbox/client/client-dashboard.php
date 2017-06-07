<?php 
ob_start();
session_start();
include_once '../includes/class.Main.php';
include '../includes/FusionCharts.php';
//Object initialization
$dbf = new User();
//page titlevariable
$pageTitle="Welcome To Out Of The Box";
include 'applicationtop-client.php';
if($_SESSION['usertype']!='client'){
	header("location:../logout");exit;
}
?>
<body>
<link rel="stylesheet" href="../css/innermain.css" type="text/css" />
<link rel="stylesheet" href="../css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="../css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="../css/respmenu.css" type="text/css" />
<link rel="stylesheet" href="../css/tabledashboard.css" type="text/css" />
<style type="text/css">
	/* Easy CSS Tooltip - by Koller Juergen [www.kollermedia.at] 
	* {font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px; }*/
	a:hover {text-decoration:none;} /*BG color is a must for IE6*/
	a.tooltip span {display:none; padding:2px 3px 0px 5px; margin-left:6px; margin-top:-100px; width:280px;border-radius:5px;
	-moz-border-radius:5px;}
	a.tooltip:hover span{display:inline; position:absolute; border:3px solid  #ff9812; background:#EEEEEE; color:#000;border-radius:6px;-moz-border-radius:6px;}
</style>
<script type="text/javascript">
/*********Function to redirect page************/
function redirectPage(id,page,src){
	$("#hid").val(id);
	$("#hsrc").val(src);
	document.frmRedirect.action=page;
	document.frmRedirect.submit();
}
/*********Function to redirect page************/
</script>
	<form name="frmRedirect" id="frmRedirect" action="" method="post"> 
    	<input type="hidden" name="id" id="hid" value=""/>
        <input type="hidden" name="src" id="hsrc" value=""/>
    </form>
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
                        <div class="headerbg">Client dashboard</div>
                        <div class="spacer"></div>
                        <div class="divdashleft">
                        	<div align="center" class="dashboardHead"><a href="client-manage-job-board" title="Click Here For More Jobs">Open Orders</a></div>
                            <div class="divdashconsec">
                            	<div class="table">
                                <div class="table-head">
                                    <div class="column" data-label="WO#" style="width:22%;">WO#</div>
                                    <div class="column" data-label="Service" style="width:32%;">Service</div>
                                    <div class="column" data-label="Customer" style="width:31%;">Customer</div>
                                    <div class="column" data-label="CreatedDate"  style="width:15%;">CreatedDate</div>
                                </div>
                                <?php
								//count for open jobs  
								$openjobs=$dbf->countRows("service s,clients cl,work_order wo","wo.client_id=cl.id AND wo.work_status='Open' AND wo.service_id=s.id AND wo.created_by='$_SESSION[userid]'");
								foreach($dbf->fetchOrder("service s,clients cl,work_order wo","wo.client_id=cl.id AND wo.work_status='Open' AND wo.service_id=s.id AND wo.created_by='$_SESSION[userid]'","wo.id DESC LIMIT 0,8","s.service_name,cl.name,wo.id,wo.wo_no,wo.work_status,wo.created_date","")as $res_JobBoard){
									
							    if($res_JobBoard['work_status']=='Open'){$color="#333";}
								?>
                                <div class="row">
                                    <div class="column" data-label="WO#" >
                                    <a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_JobBoard['id'];?>','client-view-job-board');" class="tooltip" style="color:<?php echo $color;?>">
									<?php echo $res_JobBoard['wo_no'];?><span><?php include '../open_details.php';?></span></a>
                                    
                                    </div>
                                    <div class="column" data-label="ServiceName"><?php echo $res_JobBoard['service_name'];?></div>
                                    <div class="column" data-label="Customer"><?php echo $res_JobBoard['name'];?></div>
                                     <div class="column" data-label="CreatedDate"><?php echo date("d-M-Y",strtotime($res_JobBoard['created_date']));?></div>
                                    
                               </div>
                               <?php }?>
                        	</div>
                            <?php if($openjobs >0){?>
                            <div style="float:right;" class="formtext"><a href="client-manage-job-board">More...</a></div><?php }?>
                            <?php if($openjobs ==0){?><div class="noRecords" align="center">No records founds!!</div><?php }?>
                           </div>
                        </div>
                        <div class="divdashcent">
                        	<div align="center" class="dashboardHead">ORDERS UNDER SERVICES</div>
                            <div class="divdashconsec">
							<?php
								$res_service=$dbf->fetch("service","");
                                echo $strXML2="<chart palette='2' caption='Total Works of different Services' xAxisName='Service Names' yAxisName='No of Jobs' showValues='0' decimals='0' formatNumberScale='0' showBorder='0'>";
                                foreach($res_service as $resv){
                                    $label = $resv['service_name']; $svrid = $resv['id'];
                                    $value = $dbf->countRows("work_order","service_id='$svrid' AND created_by='$_SESSION[userid]'");
                                    echo $strXML2.="<set label='$label' value='$value'/>";
                                }
                                echo $strXML2.="</chart>";
                                echo renderChartHTML("../FusionCharts/Charts/Bar2D.swf","",$strXML2,"mychart2","100%",280);
                            ?>
                           </div>
                        </div>
                       	<div class="divdashleft">
                         <div align="center" class="dashboardHead"><a href="client-manage-job-board-dispatch" title="Click Here For More Jobs">Scheduled Orders</a></div>
                          <div class="divdashconsec">
                          <div class="table">
                                <div class="table-head">
                                    <div class="column" data-label="WO#" style="width:20%;">WO#</div>
                                    <div class="column" data-label="Service" style="width:32%;">Service</div>
                                    <div class="column" data-label="Customer" style="width:31%;">Customer</div>
                                    <div class="column" data-label="AssignedDate" style="width:17%;">AssignedDate</div>
                                </div>
                                <?php 
								//count for scheduled jobs
								$schedlejobs=$dbf->countRows("clients cl,service s,technicians t,assign_tech at,work_order wo","wo.client_id=cl.id AND wo.wo_no=at.wo_no AND at.tech_id=t.id AND wo.work_status='Assigned' AND wo.service_id=s.id  AND wo.created_by='$_SESSION[userid]' AND at.start_date=CURDATE()");
								foreach($dbf->fetchOrder("clients cl,service s,technicians t,assign_tech at,work_order wo","wo.client_id=cl.id AND wo.wo_no=at.wo_no AND at.tech_id=t.id AND wo.work_status='' AND wo.service_id=s.id  AND wo.created_by='$_SESSION[userid]' AND at.start_date=CURDATE()","wo.id DESC LIMIT 0,8","s.service_name,cl.name,wo.id,wo.wo_no,wo.work_status,at.assign_date","")as $res_JobBoard){
								if($res_JobBoard['work_status']=='Assigned'){$color="#F00";}
								?>
                                <div class="row">
                                    <div class="column" data-label="WO#">
                                    <a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_JobBoard['id'];?>','client-view-job-board','disp');" class="tooltip" style="color:<?php echo $color;?>">
									<?php echo $res_JobBoard['wo_no'];?><span><?php include '../schedule_details.php';?></span> 
                                    </a>
                                    </div>
                                    <div class="column" data-label="Service"><?php echo $res_JobBoard['service_name'];?></div>
                                    <div class="column" data-label="Customer"><?php echo $res_JobBoard['name'];?></div>
                                    <div class="column" data-label="Created Date"><?php echo date("d-M-Y",strtotime($res_JobBoard['assign_date']));?></div>
                               </div>
                               <?php }?>
                        	</div>
                            <?php if($schedlejobs >0){?>
                          	<div style="float:right;" class="formtext"><a href="client-manage-job-board-dispatch">More...</a></div><?php }?>
                          	<?php if($schedlejobs ==0){?><div class="noRecords" align="center">No records founds!!</div><?php }?>
                          </div>
                        </div>
                        <div class="spacer"></div>
                        <div class="divdashleft">
                         <div align="center" class="dashboardHead"><a href="client-workorder-billings" title="Click Here For More Jobs">Pending Bills</a></div>
                         	<div class="divdashconsec">
                            	<div class="table">
                                <div class="table-head">
                                    <div class="column" data-label="WO#" style="width:22%;">WO#</div>
                                    <div class="column" data-label="Service" style="width:31%;">Service</div>
                                    <div class="column" data-label="Customer" style="width:32%;">Customer</div>
                                    <div class="column" data-label="CompletedDate"style="width:15%;">CompletedDate</div>
                                </div>
                                <?php 
								//count for pending jobs 
								$nopendingbill=$dbf->countRows("clients cl,service s,work_order wo","wo.service_id=s.id AND cl.id=wo.client_id AND wo.work_status='Completed' AND wo.approve_status='1' AND wo.created_by='$_SESSION[userid]' AND wo.wo_no IN(select wo_no from work_order_bill WHERE payment_status='Pending')");
								foreach($dbf->fetchOrder("clients cl,service s,work_order wo","wo.service_id=s.id AND cl.id=wo.client_id AND wo.work_status='Completed' AND wo.approve_status='1' AND wo.created_by='$_SESSION[userid]' AND wo.wo_no IN(select wo_no from work_order_bill WHERE payment_status='Pending')","wo.id DESC LIMIT 0,8","s.service_name,cl.name,wo.id,wo.wo_no,wo.work_status","")as $res_JobBoard){
								$compledate=$dbf->getDataFromTable("work_order_tech","arrival_date","wo_no='$res_JobBoard[wo_no]' ORDER BY id DESC");
								if($res_JobBoard['work_status']=='Completed'){$color="#090";}
								?>
                                <div class="row">
                                    <div class="column" data-label="WO#">
                                    <a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_JobBoard['id'];?>','client-view-job-board','disp');" class="tooltip" style="color:<?php echo $color;?>">
									<?php echo $res_JobBoard['wo_no'];?><span><?php include '../schedule_details.php';?></span> 
                                    </a>
                                    </div>
                                     <div class="column" data-label="Service"><?php echo $res_JobBoard['service_name'];?></div>
                                    <div class="column" data-label="Customer"><?php echo $res_JobBoard['name'];?></div>
                                    <div class="column" data-label="CcmpletedDate"><?php echo date("d-M-Y",strtotime($compledate));?></div>
                               </div>
                               <?php }?>
                        	</div>
                            <div style="float:right;" class="formtext"><a href="client-workorder-billings">More...</a></div>
                             <?php if($nopendingbill ==0){?><div class="noRecords" align="center">No records founds!!</div><?php }?>
                        </div>
                        </div>
                        <div class="divdashcent">
                        	<div align="center" class="dashboardHead">BEST TECHNICIANS AND CLIENTS</div>
                             <div class="noRecords" align="center" style="padding-top:120px;font-size:18px;color:#666;">Sorry , You are not authorized.</div>
                        </div>
                       	<div class="divdashleft">
                         <div align="center" class="dashboardHead">TOTAL STATISTICS</div>
                          <div class="divdashconsec">
							 <div class="noRecords" align="center" style="padding-top:120px;font-size:18px;color:#666;">Sorry , You are not authorized.</div>
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