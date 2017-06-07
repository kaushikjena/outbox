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
                        	<div align="center" class="dashboardHead"><a href="client-manage-job-board" title="Click Here For More Jobs">Open Jobs</a></div>
                            <div class="divdashconsec">
                            	<div class="table">
                                <div class="table-head">
                                    <div class="column" data-label="WO#" style="width:22%;">WO#</div>
                                    <div class="column" data-label="ServiceName" style="width:32%;">ServiceName</div>
                                    <div class="column" data-label="Customer" style="width:31%;">Customer</div>
                                    <div class="column" data-label="CreatedDate"  style="width:15%;">CreatedDate</div>
                                </div>
                                <?php
								//count for open jobs  
								$openjobs=$dbf->countRows("service s,clients cl,work_order wo","wo.client_id=cl.id AND wo.job_status='Open' AND wo.service_id=s.id AND wo.created_by='$_SESSION[userid]'");
								foreach($dbf->fetchOrder("service s,clients cl,work_order wo","wo.client_id=cl.id AND wo.job_status='Open' AND wo.service_id=s.id AND wo.created_by='$_SESSION[userid]'","wo.id DESC LIMIT 0,8","","")as $res_JobBoard){
									
							    if($res_JobBoard['job_status']=='Open'){$color="#333";}
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
                                echo renderChartHTML("../FusionCharts/Charts/Column2D.swf","",$strXML2,"mychart2","100%",280);
                            ?>
                           </div>
                        </div>
                       	<div class="divdashleft">
                         <div align="center" class="dashboardHead"><a href="client-manage-job-board-dispatch" title="Click Here For More Jobs">Scheduled Jobs</a></div>
                          <div class="divdashconsec">
                          <div class="table">
                                <div class="table-head">
                                    <div class="column" data-label="WO#" style="width:20%;">WO#</div>
                                    <div class="column" data-label="ServiceName" style="width:32%;">ServiceName</div>
                                    <div class="column" data-label="TechName" style="width:31%;">TechName</div>
                                    <div class="column" data-label="AssignedDate" style="width:17%;">AssignedDate</div>
                                </div>
                                <?php 
								//count for scheduled jobs
								$schedlejobs=$dbf->countRows("clients cl,service s,technicians t,assign_tech at,work_order wo","wo.client_id=cl.id AND wo.wo_no=at.wo_no AND at.tech_id=t.id AND wo.job_status='Assigned' AND wo.work_status='' AND wo.service_id=s.id  AND wo.created_by='$_SESSION[userid]' AND at.start_date=CURDATE()");
								foreach($dbf->fetchOrder("clients cl,service s,technicians t,assign_tech at,work_order wo","wo.client_id=cl.id AND wo.wo_no=at.wo_no AND at.tech_id=t.id AND wo.job_status='Assigned' AND wo.work_status='' AND wo.service_id=s.id  AND wo.created_by='$_SESSION[userid]' AND at.start_date=CURDATE()","wo.id DESC LIMIT 0,8","","")as $res_JobBoard){
								$techName=$res_JobBoard['first_name'].' '.$res_JobBoard['middle_name'].' '.$res_JobBoard['last_name'];
								if($res_JobBoard['job_status']=='Assigned'){$color="#F00";}
								?>
                                <div class="row">
                                    <div class="column" data-label="WO#">
                                    <a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_JobBoard['id'];?>','client-view-job-board','disp');" class="tooltip" style="color:<?php echo $color;?>">
									<?php echo $res_JobBoard['wo_no'];?><span><?php include '../schedule_details.php';?></span> 
                                    </a>
                                    </div>
                                    <div class="column" data-label="ServiceName"><?php echo $res_JobBoard['service_name'];?></div>
                                    <div class="column" data-label="TechName"><?php echo $techName;?></div>
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
                                    <div class="column" data-label="CustomerName" style="width:32%;">CustomerName</div>
                                    <div class="column" data-label="Client" style="width:31%;">Client</div>
                                    <div class="column" data-label="CompletedDate"style="width:15%;">CompletedDate</div>
                                </div>
                                <?php 
								//count for pending jobs 
								$nopendingbill=$dbf->countRows("clients cl,work_order wo","cl.id=wo.client_id AND wo.work_status='Completed' AND wo.approve_status='1' AND wo.created_by='$_SESSION[userid]' AND wo.wo_no IN(select wo_no from work_order_bill WHERE payment_status='Pending')");
								foreach($dbf->fetchOrder("clients cl,work_order wo","cl.id=wo.client_id AND wo.work_status='Completed' AND wo.approve_status='1' AND wo.created_by='$_SESSION[userid]' AND wo.wo_no IN(select wo_no from work_order_bill WHERE payment_status='Pending')","wo.id DESC LIMIT 0,8","","")as $res_JobBoard){
								
								$techname=$dbf->fetchSingle("assign_tech at,technicians t","t.id=at.tech_id AND at.wo_no='$res_JobBoard[wo_no]'");
								$compledate=$dbf->getDataFromTable("work_order_tech","arrival_date","wo_no='$res_JobBoard[wo_no]' ORDER BY id DESC");
								if($res_JobBoard['work_status']=='Completed'){$color="#090";}
								?>
                                <div class="row">
                                    <div class="column" data-label="WO#">
                                    <a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_JobBoard['id'];?>','client-view-job-board','disp');" class="tooltip" style="color:<?php echo $color;?>">
									<?php echo $res_JobBoard['wo_no'];?><span><?php include '../schedule_details.php';?></span> 
                                    </a>
                                    </div>
                                    <div class="column" data-label="CustomerName"><?php echo $res_JobBoard['name'];?></div>
                                    <div class="column" data-label="Client"><?php echo $techname['first_name'].' '.$techname['middle_name'].' '.$techname['last_name'];?></div>
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
                           
                            <div class="divdashconsec">
                             <div align="center" class="dashtext">Technicians</div>
                            <?php
								$tqry = "SELECT tech_id,count(tech_id)as notech FROM assign_tech GROUP BY tech_id ORDER BY notech DESC LIMIT 0,5";
								 $restQry=mysql_query($tqry);
                                 while($rest = mysql_fetch_array($restQry, MYSQL_ASSOC)){
                                     $techDetails=$dbf->fetchSingle("technicians","id='$rest[tech_id]'");
                                     $TechName=$techDetails['first_name'].' '.$techDetails['middle_name'].' '.$techDetails['last_name'];
									 $countCompleted=$dbf->countRows("work_order_tech","tech_id='$rest[tech_id]' AND work_status='Completed' ORDER BY id DESC");
                                    //if($countCompleted >0){
                                ?>
                                  <div class="dashboardbullet"><?php echo $TechName;?> &raquo; <?php echo $rest['notech'];?> Jobs Assigned &raquo;  <?php echo $countCompleted;?> Completed </div>
                                <?php } //}?>
                              <div class="spacer"></div>
                             <div align="center" class="dashtext">Clients</div>
                             <?php 
							 $qry = "SELECT created_by,count(created_by) as noclient  FROM work_order WHERE approve_status=1 and created_by<>0 GROUP BY created_by ORDER BY noclient DESC LIMIT 0,5";
							 $resQry=mysql_query($qry);
							 while($row = mysql_fetch_array($resQry, MYSQL_ASSOC)){
								$cName = $dbf->getDataFromTable("clients","name","id='$row[created_by]'");
								$clCompleted = $dbf->countRows("work_order_bill","created_by='$row[created_by]'");
							?>
							<div class="dashboardbullet"><?php echo $cName;?> &raquo; <?php echo $row['noclient'];?> Jobs Posted &raquo;  <?php echo $clCompleted;?> Completed </div>	
							<?php }?>
                           </div>
                        </div>
                       	<div class="divdashleft">
                         <div align="center" class="dashboardHead">TOTAL STATISTICS</div>
                          <div class="divdashconsec">
							 <?php
                                $totaljobs=$dbf->countRows("work_order","approve_status='1'");
								$users =$dbf->countRows("users","status=1");
                                $clients=$dbf->countRows("clients","status=1");
								$codclients=$dbf->countRows("clients","status=0");
                                $technicians=$dbf->countRows("technicians","");
                                
                                echo $strXML3 ="<chart caption='Total Statistical Data' xAxisName='Work Details' yAxisName='Numbers' showValues='0' decimals='0' formatNumberScale='0' showBorder='0'>
                                    <set label='Jobs' value='$totaljobs'/>
                                    <set label='Users' value='$users'/>
                                    <set label='Clients' value='$clients'/>
                                    <set label='COD' value='$codclients'/>
                                    <set label='Techs' value='$technicians'/>
                                </chart>";
                                echo renderChartHTML("../FusionCharts/Charts/Column3D.swf","",$strXML3,"myChart3","100%",280);
                                
                            ?>
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