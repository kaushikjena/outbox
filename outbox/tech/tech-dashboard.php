<?php 
ob_start();
session_start();
include_once '../includes/class.Main.php';
include '../includes/FusionCharts.php';
//Object initialization
$dbf = new User();
//page titlevariable
$pageTitle="Welcome To Out Of The Box";
include 'applicationtop-tech.php';
if($_SESSION['usertype']!='tech'){
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
function redirectPage(id){
	$("#hid").val(id);
	document.frmRedirect.action="tech-view-job-board";
	document.frmRedirect.submit();
}
/*********Function to redirect page************/
</script>
	<form name="frmRedirect" id="frmRedirect" action="" method="post"> 
    	<input type="hidden" name="id" id="hid" value=""/>
    </form>
    <div id="maindiv">
        <!-------------header--------------->
     	<?php include_once 'header-tech.php';?>
   		<!-------------header--------------->
        
        <!-------------top menu--------------->
     	<?php include_once 'tech-top-menu.php';?>
   		<!-------------top menu--------------->
         <div id="contentdiv">
                <!-------------Left menu--------------->
				<?php //include_once 'left-menu.php';?>
                <!-------------left menu--------------->
                <!-------------Main Body--------------->
                <div class="rightcolumjobboard">
            		<div class="rightcoluminner">
                        <div class="headerbg">Technician dashboard</div>
                        <div class="spacer"></div>
                        <div class="divdashleft">
                         <div align="center" class="dashboardHead"><a href="tech-manage-job-board" title="Click Here For More Jobs">Scheduled Jobs</a></div>
                          <div class="divdashconsec">
                          <div class="table">
                                <div class="table-head">
                                    <div class="column" data-label="WO#" style="width:20%;">WO#</div>
                                    <div class="column" data-label="ServiceName" style="width:32%;">ServiceName</div>
                                    <div class="column" data-label="TechName" style="width:31%;">CustomerName</div>
                                    <div class="column" data-label="AssignedDate" style="width:17%;">AssignedDate</div>
                                </div>
                                <?php 
								//count for scheduled jobs
							    $schedlejobs=$dbf->countRows("clients c,service s,technicians t,assign_tech at,work_order w","c.id=w.client_id AND w.service_id=s.id AND w.work_status='Scheduled' AND w.approve_status='1' AND w.wo_no=at.wo_no AND at.tech_id=t.id AND t.id='$_SESSION[userid]' AND at.start_date=CURDATE()");
								foreach($dbf->fetchOrder("clients c,service s,technicians t,assign_tech at,work_order w","c.id=w.client_id AND w.service_id=s.id AND w.work_status='Scheduled' AND w.approve_status='1' AND w.wo_no=at.wo_no AND at.tech_id=t.id AND t.id='$_SESSION[userid]' AND at.start_date=CURDATE()","w.id DESC LIMIT 0,8","c.name,s.service_name,at.assign_date,w.id,w.wo_no,w.work_status","")as $res_JobBoard){
								if($res_JobBoard['work_status']=='Assigned'){$color="#F00";}
								?>
                                <div class="row">
                                    <div class="column" data-label="WO#">
                                    <a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_JobBoard['id'];?>');" class="tooltip" style="color:<?php echo $color;?>">
									<?php echo $res_JobBoard['wo_no'];?><span><?php include '../schedule_details.php';?></span> 
                                    </a>
                                    </div>
                                    <div class="column" data-label="ServiceName"><?php echo $res_JobBoard['service_name'];?></div>
                                    <div class="column" data-label="CustomerName"><?php echo $res_JobBoard['name'];?></div>
                                    <div class="column" data-label="Created Date"><?php echo date("d-M-Y",strtotime($res_JobBoard['assign_date']));?></div>
                               </div>
                               <?php }?>
                        	</div>
                            <?php if($schedlejobs >0){?>
                          	<div style="float:right;" class="formtext"><a href="tech-manage-job-board">More...</a></div><?php }?>
                          	<?php if($schedlejobs ==0){?><div class="noRecords" align="center">No records founds!!</div><?php }?>
                          </div>
                        </div>
                        <div class="divdashcent">
                        	<div align="center" class="dashboardHead">WEEKLY SCHEDULED JOBS</div>
							<div class="divdashconsec">
						<?php
							$today = date("m/d/Y");
							$lastdate = date("m/d/Y", strtotime('+7days',strtotime($today)));
							echo $strXML4="<chart caption='Weekly Scheduled Jobs' subcaption='(From $today to $lastdate)' lineThickness='1' showValues='0' formatNumberScale='0' anchorRadius='2'   divLineAlpha='20' divLineColor='CC3300' divLineIsDashed='1' showAlternateHGridColor='1' alternateHGridColor='CC3300' shadowAlpha='40' labelStep='2' numvdivlines='5' chartRightMargin='35' bgColor='FFFFFF,CC3300' bgAngle='270' bgAlpha='10,10' showBorder='0'>
							<categories >";
							for($i=0; $i<7;$i++){
							$nextdate = date("d M", strtotime('+'.$i.'days',strtotime($today)));
							echo $strXML4.="<category label='$nextdate' />";
							}
							echo $strXML4.="</categories>
							<dataset seriesName='Scheduled Jobs' color='F1683C' anchorBorderColor='F1683C' anchorBgColor='F1683C'>";
							for($i=0; $i<7;$i++){
								$nextday = date("Y-m-d", strtotime('+'.$i.'days',strtotime($today)));
								$count = $dbf->countRows("technicians t,assign_tech at,work_order w","at.wo_no=w.wo_no AND at.tech_id=t.id AND t.id='$_SESSION[userid]' AND at.start_date='$nextday'");
								echo $strXML4.="<set value='$count' />";
							}
							echo $strXML4.="</dataset>
							</chart>";				
                               echo renderChartHTML("../FusionCharts/Charts/MSLine.swf", "", $strXML4, "myChart1", "100%", 280);
                            ?>
                           </div>
                        </div>
                       	<div class="divdashleft">
                         <div align="center" class="dashboardHead"><a href="technician-manage-payments-history" title="Click Here For More Jobs">Pending Bills</a></div>
                         	<div class="divdashconsec">
                            	<div class="table">
                                <div class="table-head">
                                    <div class="column" data-label="WO#" style="width:22%;">WO#</div>
                                    <div class="column" data-label="CustomerName" style="width:32%;">CustomerName</div>
                                    <div class="column" data-label="ServiceType" style="width:31%;">ServiceType</div>
                                    <div class="column" data-label="CompletedDate"style="width:15%;">CompletedDate</div>
                                </div>
                                <?php 
								//count for pending jobs 
								$nopendingbill=$dbf->countRows("clients c,service s,technicians t,assign_tech at,work_order w","c.id=w.client_id AND w.service_id=s.id AND w.work_status='Assigned' AND w.approve_status='1' AND w.wo_no=at.wo_no AND at.tech_id=t.id AND t.id='$_SESSION[userid]' AND w.wo_no IN(select wo_no from work_order_tech_bill WHERE payment_status='Pending')");
								
								foreach($dbf->fetchOrder("clients c,service s,technicians t,assign_tech at,work_order w","c.id=w.client_id AND w.service_id=s.id AND w.work_status='Assigned' AND w.approve_status='1' AND w.wo_no=at.wo_no AND at.tech_id=t.id AND t.id='$_SESSION[userid]' AND w.wo_no IN(select wo_no from work_order_tech_bill WHERE payment_status='Pending')","w.id DESC LIMIT 0,8","c.name,s.service_name,w.id,w.wo_no,w.work_status","")as $res_JobBoard){
								//work order completed date
								$compledate=$dbf->getDataFromTable("work_order_tech","arrival_date","wo_no='$res_JobBoard[wo_no]' ORDER BY id DESC");
								if($res_JobBoard['work_status']=='Completed'){$color="#090";}
								?>
                                <div class="row">
                                    <div class="column" data-label="WO#">
                                    <a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_JobBoard['id'];?>');" class="tooltip" style="color:<?php echo $color;?>">
									<?php echo $res_JobBoard['wo_no'];?><span><?php include '../schedule_details.php';?></span> 
                                    </a>
                                    </div>
                                    <div class="column" data-label="CustomerName"><?php echo $res_JobBoard['name'];?></div>
                                    <div class="column" data-label="ServiceType"><?php echo $res_JobBoard['service_name'];?></div>
                                    <div class="column" data-label="CcmpletedDate"><?php echo date("d-M-Y",strtotime($compledate));?></div>
                               </div>
                               <?php }?>
                        	</div>
                            <div style="float:right;" class="formtext"><a href="technician-manage-payments-history.php">More...</a></div>
                             <?php if($nopendingbill ==0){?><div class="noRecords" align="center">No records founds!!</div><?php }?>
                        </div>
                        </div>
                        <div class="spacer"></div>
                        <div class="divdashleft">
                        	<div align="center" class="dashboardHead">JOBS UNDER SERVICES</div>
                            <div class="divdashconsec">
							<?php
								$res_service=$dbf->fetch("service","");
                                echo $strXML2="<chart palette='2' caption='Total Works of different Services' xAxisName='Service Names' yAxisName='No of Jobs' showValues='0' decimals='0' formatNumberScale='0' showBorder='0'>";
                                foreach($res_service as $resv){
                                    $label = $resv['service_name']; $svrid = $resv['id'];
                                    $value = $dbf->countRows("work_order","service_id='$svrid'");
                                    echo $strXML2.="<set label='$label' value='$value'/>";
                                }
                                echo $strXML2.="</chart>";
                                echo renderChartHTML("../FusionCharts/Charts/Bar2D.swf","",$strXML2,"mychart2","100%",280);
                            ?>
                           </div>
                        </div>
                        <div class="divdashcent">
                         <div align="center" class="dashboardHead"><a href="technician-manage-payments-history" title="Click Here For More Jobs">Recent Payments</a></div>
                         	<div class="divdashconsec">
                            	<div class="table">
                                <div class="table-head">
                                    <div class="column" data-label="WO#" style="width:30%;">WO#</div>
                                    <div class="column" data-label="Subtotal" style="width:20%;">Subtotal</div>
                                    <div class="column" data-label="PaymentStatus" style="width:30%;">PaymentStatus</div>
                                    <div class="column" data-label="PaymentDate" style="width:20%;">PaymentDate</div>
                                </div>
                                <?php 
								//count for completed payments
								$nopayments=$dbf->countRows("technicians t,work_order_tech_bill wb","wb.tech_id=t.id AND wb.tech_id='$_SESSION[userid]' AND wb.payment_status='Completed'");
								
								foreach($dbf->fetchOrder("technicians t,work_order_tech_bill wb","wb.tech_id=t.id AND wb.tech_id='$_SESSION[userid]' AND wb.payment_status='Completed'","wb.id DESC LIMIT 0,8","wb.wo_no,wb.subtotal,wb.payment_status,wb.payment_date","")as $res_JobBoard){
								?>
                                <div class="row">
                                    <div class="column" data-label="WO#"><b><?php echo $res_JobBoard['wo_no'];?></b></div>
                                    <div class="column" data-label="Subtotal"><?php echo $res_JobBoard['subtotal'];?></div>
                                    <div class="column" data-label="PaymentStatus"><?php echo $res_JobBoard['payment_status'];?></div>
                                    <div class="column" data-label="PaymentDate"><?php echo date("d-M-Y",strtotime($res_JobBoard['payment_date']));?></div>
                               </div>
                               <?php }?>
                        	</div>
                            <div style="float:right;" class="formtext"><a href="technician-manage-payments-history.php">More...</a></div>
                             <?php if($nopayments ==0){?><div class="noRecords" align="center">No records founds!!</div><?php }?>
                        </div>
                        </div>
                       	<div class="divdashleft">
                        	<div align="center" class="dashboardHead">ORDER STATUS</div>
							<div class="divdashconsec">
						<?php
                            $Dispatchjobs=$dbf->countRows("technicians t,assign_tech at,work_order w","w.work_status='Dispatched' AND w.approve_status='1' AND w.wo_no=at.wo_no AND at.tech_id=t.id AND  t.id='$_SESSION[userid]'");
							$Assignedjobs=$dbf->countRows("technicians t,assign_tech at,work_order w","w.work_status='Assigned' AND w.approve_status='1' AND w.wo_no=at.wo_no AND at.tech_id=t.id AND  t.id='$_SESSION[userid]'");
                            $progressjobs=$dbf->countRows("technicians t,assign_tech at,work_order w","w.work_status='In Progress' AND w.approve_status='1' AND w.wo_no=at.wo_no AND at.tech_id=t.id AND  t.id='$_SESSION[userid]'");
                            $completejobs=$dbf->countRows("technicians t,assign_tech at,work_order w","w.work_status='Completed' AND w.approve_status='1' AND w.wo_no=at.wo_no AND at.tech_id=t.id AND  t.id='$_SESSION[userid]'");
							/*if($startjobs>0){$schedlejobs=$schedlejobs-$startjobs;}
							if($progressjobs>0){$schedlejobs=$schedlejobs-$progressjobs;}
							if($completejobs>0){$schedlejobs=$schedlejobs-$completejobs;}*/
							
                              echo $strXML1="<chart caption='Open vs Scheduled Jobs\n(Click on Chart to Slice)' palette='4' decimals='0' enableSmartLabels='1' enableRotation='0' bgColor='99CCFF,FFFFFF' bgAlpha='40,100' bgRatio='0,100' bgAngle='360' showBorder='0' startingAngle='60'>
                                    <set label='Assigned' value='$Assignedjobs'/>
									<set label='Dispatched' value='$Dispatchjobs'/>
									<set label='In Progress' value='$progressjobs'/>
									<set label='Completed' value='$completejobs'/>
                                </chart>";				
                               echo renderChartHTML("../FusionCharts/Charts/Pie3D.swf", "", $strXML1, "myChart1", "100%", 280);
                            ?>
                           </div>
                        </div>
                        <div class="spacer"></div>
                    </div>
                </div>
              <!-------------Main Body--------------->
         </div>
        <div class="spacer"></div>
        <?php include_once 'footer-tech.php'; ?>
    </div>
</body>
</html>