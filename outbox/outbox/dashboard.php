<?php 
ob_start();
session_start();
include_once 'includes/class.Main.php';
include 'includes/FusionCharts.php';
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
<body>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />
<link rel="stylesheet" href="css/tabledashboard.css" type="text/css" />
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
     	<?php include_once 'header.php';?>
   		<!-------------header--------------->
        <!-------------top menu--------------->
     	<?php include_once 'top-menu.php';?>
   		<!-------------top menu--------------->
         <div id="contentdiv">
                <!-------------Main Body--------------->
                <div class="rightcolumjobboard">
            		<div class="rightcoluminner">
                        <div class="headerbg">administrator dashboard</div>
                        <div class="spacer"></div>
                        <div class="divdashleft">
                        <div align="center" class="dashboardHead"><a href="manage-job-board" title="Click Here For More Jobs">Open Jobs</a></div>
                        	<div class="divdashconsec">
                            	<div class="table">
                                <div class="table-head">
                                    <div class="column" data-label="WO#" style="width:20%;">WO#</div>
                                    <div class="column" data-label="ServiceName" style="width:32%;">ServiceName</div>
                                    <div class="column" data-label="Client" style="width:28%;">Client</div>
                                    <div class="column" data-label="CreatedDate"  style="width:19%;">CreatedDate</div>
                                </div>
                                <?php
								$condo = "w.service_id=s.id AND w.work_status='Open' AND w.approve_status='1'";
								//condition for users
							   	if($implode_clients <>''){
									$condo.=" AND FIND_IN_SET(w.created_by,'$implode_clients')";
							   	}
								//count for open jobs  
								$openjobs=$dbf->countRows("service s,work_order w",$condo);
								foreach($dbf->fetchOrder("service s,work_order w",$condo,"w.id DESC LIMIT 0,8","s.service_name,w.id,w.wo_no,w.created_by,w.work_status,w.created_date","")as $res_JobBoard){
								//get client name
								if($res_JobBoard['created_by']<>0){
									$clientname =$dbf->getDataFromTable("clients","name","id='$res_JobBoard[created_by]'");
								}else{
									$clientname="COD";
								}
								if($res_JobBoard['work_status']=='Open'){$color="#333";}
								?>
                                <div class="row">
                                    <div class="column" data-label="WO#" >
                                    <a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_JobBoard['id'];?>','view-job-board');" class="tooltip" style="color:<?php echo $color;?>">
									<?php echo $res_JobBoard['wo_no'];?><span><?php include 'open_details.php';?></span></a>
                                    
                                    </div>
                                    <div class="column" data-label="ServiceName"><?php echo $res_JobBoard['service_name'];?></div>
                                    <div class="column" data-label="Client"><?php echo $clientname;?></div>
                                     <div class="column" data-label="CreatedDate"><?php echo date("d-M-Y",strtotime($res_JobBoard['created_date']));?></div>
                                    
                               </div>
                               <?php }?>
                        	</div>
                            <?php if($openjobs >0){?>
                            <div style="float:right;" class="formtext"><a href="manage-job-board">More...</a></div><?php }?>
                            <?php if($openjobs ==0){?><div class="noRecords" align="center">No records founds!!</div><?php }?>
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
								$count = $dbf->countRows("assign_tech","start_date='$nextday'");
								echo $strXML4.="<set value='$count' />";
							}
							echo $strXML4.="</dataset>
							</chart>";				
                               echo renderChartHTML("FusionCharts/Charts/MSLine.swf", "", $strXML4, "myChart1", "100%", 280);
                            ?>
                           </div>
                        </div>
                       	<div class="divdashleft">
                        <div align="center" class="dashboardHead"><a href="manage-job-board-dispatch" title="Click Here For More Jobs">Scheduled Jobs</a></div>
                        	<div class="divdashconsec">
                          	<div class="table">
                                <div class="table-head">
                                    <div class="column" data-label="WO#" style="width:20%;">WO#</div>
                                    <div class="column" data-label="ServiceName" style="width:32%;">ServiceName</div>
                                    <div class="column" data-label="TechName" style="width:31%;">TechName</div>
                                    <div class="column" data-label="AssignedDate" style="width:17%;">AssignedDate</div>
                                </div>
                                <?php 
								$conds = "w.service_id=s.id AND w.work_status='Scheduled' AND w.approve_status='1' AND w.wo_no=at.wo_no AND at.tech_id=t.id AND at.start_date=CURDATE()";
								//condition for users
							   	if($implode_clients <>''){
									$conds.=" AND FIND_IN_SET(w.created_by,'$implode_clients')";
							   	}
								if($implode_techs <>''){
									$conds.=" AND FIND_IN_SET(at.tech_id,'$implode_techs')";
							    }
								//count for scheduled jobs
								$schedlejobs=$dbf->countRows("service s,technicians t,assign_tech at,work_order w",$conds);
								foreach($dbf->fetchOrder("service s,technicians t,assign_tech at,work_order w",$conds,"w.id DESC LIMIT 0,8","t.first_name,t.middle_name,t.last_name,s.service_name,w.id,w.wo_no,w.work_status,at.assign_date","")as $res_JobBoard){
								$techName=$res_JobBoard['first_name'].' '.$res_JobBoard['middle_name'].' '.$res_JobBoard['last_name'];
								if($res_JobBoard['work_status']=='Assigned'){$color="#F00";}
								?>
                                <div class="row">
                                    <div class="column" data-label="WO#">
                                    <a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_JobBoard['id'];?>','view-job-board','disp');" class="tooltip" style="color:<?php echo $color;?>">
									<?php echo $res_JobBoard['wo_no'];?><span><?php include 'schedule_details.php';?></span> 
                                    </a>
                                    </div>
                                    <div class="column" data-label="ServiceName"><?php echo $res_JobBoard['service_name'];?></div>
                                    <div class="column" data-label="TechName"><?php echo $techName;?></div>
                                    <div class="column" data-label="Created Date"><?php echo date("d-M-Y",strtotime($res_JobBoard['assign_date']));?></div>
                               </div>
                               <?php }?>
                        	</div>
                            <?php if($schedlejobs >0){?>
                          	<div style="float:right;" class="formtext"><a href="manage-job-board-assigned">More...</a></div><?php }?>
                          	<?php if($schedlejobs ==0){?><div class="noRecords" align="center">No records founds!!</div><?php }?>
                          </div>
                        </div>
                        <div class="spacer"></div>
                        <div class="divdashleft">
                        <div align="center" class="dashboardHead">Completed Jobs - Bills To Be Generated</div>
							<?php if(in_array('Payments',$arrModule,true) || empty($arrModule)){?>
                            <div class="divdashconsec">
                                <div class="table">
                                <div class="table-head">
                                    <div class="column" data-label="WO#" style="width:22%;">WO#</div>
                                    <div class="column" data-label="CustomerName" style="width:32%;">CustomerName</div>
                                    <div class="column" data-label="Client" style="width:31%;">Client</div>
                                    <div class="column" data-label="CompletedDate"style="width:15%;">CompletedDate</div>
                                </div>
                                <?php
                                $condc = "c.id=w.client_id AND w.work_status='Completed' AND w.approve_status='1' AND w.wo_no NOT IN(select wo_no from work_order_bill)";
                                //condition for users
                               /* if($implode_clients <>''){
                                    $condc.=" AND FIND_IN_SET(w.created_by,'$implode_clients')";
                                }*/
                                //count for generated jobs 
                                $nogeneratebill=$dbf->countRows("clients c,work_order w",$condc);
                                foreach($dbf->fetchOrder("clients c,work_order w",$condc,"w.id DESC LIMIT 0,8","c.name,w.id,w.wo_no,w.created_by,w.work_status","")as $res_JobBoard){
                                //get client name
                                if($res_JobBoard['created_by']<>0){
                                    $clientname =$dbf->getDataFromTable("clients","name","id='$res_JobBoard[created_by]'");
                                }else{
                                    $clientname="COD";
                                }
                                //work order completed date
                                $compledate=$dbf->getDataFromTable("work_order_tech","arrival_date","wo_no='$res_JobBoard[wo_no]' ORDER BY id DESC");
                                if($res_JobBoard['work_status']=='Completed'){$color="#090";}
                                ?>
                                <div class="row">
                                    <div class="column" data-label="WO#">
                                    <a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_JobBoard['id'];?>','view-job-board','disp');" class="tooltip" style="color:<?php echo $color;?>">
                                    <?php echo $res_JobBoard['wo_no'];?><span><?php include 'schedule_details.php';?></span> 
                                    </a>
                                    </div>
                                    <div class="column" data-label="CustomerName"><?php echo $res_JobBoard['name'];?></div>
                                    <div class="column" data-label="Client"><?php echo $clientname;?></div>
                                    <div class="column" data-label="CompletedDate"><?php echo date("d-M-Y",strtotime($compledate));?></div>
                               </div>
                               <?php }?>
                            </div>
                            <?php if($nogeneratebill>0){?>
                            <div style="float:right;" class="formtext"><a href="manage-cod-billings">More...</a></div><?php }?>
                            <?php if($nogeneratebill ==0){?><div class="noRecords" align="center">No records founds!!</div><?php }?>
                           </div>
                            <?php }else{?>
                            <div class="noRecords" align="center" style="padding-top:120px;font-size:18px;color:#666;">Sorry , You are not authorized.</div>
                            <?php }?>
                        </div>
                        <div class="divdashcent">
                        	<div align="center" class="dashboardHead">BEST TECHNICIANS AND CLIENTS</div>
                            <div class="divdashconsec">
                             <div align="center" class="dashtext">Technicians</div>
                            <?php
								$condt = "id>0";
								if($implode_techs <>''){
									$condt.=" AND FIND_IN_SET(id,'$implode_techs')";
							    }
								$tqry = "SELECT tech_id,count(tech_id)as notech FROM assign_tech WHERE ".$condt." GROUP BY tech_id ORDER BY notech DESC LIMIT 0,5";
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
							 $condcl="approve_status=1 and created_by<>0";
							 if($implode_clients <>''){
								$condcl.=" AND FIND_IN_SET(created_by,'$implode_clients')";
							  }
							 $qry = "SELECT created_by,count(created_by) as noclient  FROM work_order WHERE ".$condcl." GROUP BY created_by ORDER BY noclient DESC LIMIT 0,5";
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
                                $totaljobs=$dbf->countRows("work_order","");
								$users =$dbf->countRows("users","status=1");
                                $clients=$dbf->countRows("clients","status=1 AND user_type='client'");
								$codclients=$dbf->countRows("clients","status=0 AND user_type='customer'");
                                $technicians=$dbf->countRows("technicians","");
                                
                                echo $strXML3 ="<chart caption='Total Statistical Data' xAxisName='Work Details' yAxisName='Numbers' showValues='0' decimals='0' formatNumberScale='0' showBorder='0'>
                                    <set label='Jobs' value='$totaljobs'/>
                                    <set label='Users' value='$users'/>
                                    <set label='Clients' value='$clients'/>
                                    <set label='Customer' value='$codclients'/>
                                    <set label='Techs' value='$technicians'/>
                                </chart>";
                                echo renderChartHTML("FusionCharts/Charts/Column3D.swf","",$strXML3,"myChart3","100%",280);
                                
                            ?>
                          </div>
                       </div>
                        <div class="spacer"></div>
                        <div class="divdashleft">
                         <div align="center" class="dashboardHead">Pending Bills</div>
                         	<?php if(in_array('Payments',$arrModule,true) || empty($arrModule)){?>
                         	<div class="divdashconsec">
                            	<div class="table">
                                <div class="table-head">
                                    <div class="column" data-label="WO#" style="width:22%;">WO#</div>
                                    <div class="column" data-label="CustomerName" style="width:32%;">CustomerName</div>
                                    <div class="column" data-label="Client" style="width:31%;">Client</div>
                                    <div class="column" data-label="CompletedDate"style="width:15%;">CompletedDate</div>
                                </div>
                                <?php
								 $condp="c.id=w.client_id AND w.work_status='Completed' AND w.approve_status='1' AND w.wo_no IN(select wo_no from work_order_bill WHERE payment_status='Pending')";
								 if($implode_clients <>''){
									$condp.=" AND FIND_IN_SET(w.created_by,'$implode_clients')";
								  }
								//count for pending jobs 
								$nopendingbill=$dbf->countRows("clients c,work_order w",$condp);
								foreach($dbf->fetchOrder("clients c,work_order w",$condp,"w.id DESC LIMIT 0,8","c.name,w.id,w.wo_no,w.created_by,w.work_status","")as $res_JobBoard){
								//get client name
								if($res_JobBoard['created_by']<>0){
									$clientname =$dbf->getDataFromTable("clients","name","id='$res_JobBoard[created_by]'");
								}else{
									$clientname="COD";
								}
								//work order completed date
								$compledate=$dbf->getDataFromTable("work_order_tech","arrival_date","wo_no='$res_JobBoard[wo_no]' ORDER BY id DESC");
								if($res_JobBoard['work_status']=='Completed'){$color="#090";}
								?>
                                <div class="row">
                                    <div class="column" data-label="WO#">
                                    <a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_JobBoard['id'];?>','view-job-board','disp');" class="tooltip" style="color:<?php echo $color;?>">
									<?php echo $res_JobBoard['wo_no'];?><span><?php include 'schedule_details.php';?></span> 
                                    </a>
                                    </div>
                                    <div class="column" data-label="CustomerName"><?php echo $res_JobBoard['name'];?></div>
                                    <div class="column" data-label="Client"><?php echo $clientname;?></div>
                                    <div class="column" data-label="CcmpletedDate"><?php echo date("d-M-Y",strtotime($compledate));?></div>
                               </div>
                               <?php }?>
                        	</div>
                            <div style="float:right;" class="formtext"><a href="manage-client-billings">More...</a></div>
                             <?php if($nopendingbill ==0){?><div class="noRecords" align="center">No records founds!!</div><?php }?>
                        </div>
                        <?php }else{?>
                         <div class="noRecords" align="center" style="padding-top:120px;font-size:18px;color:#666;">Sorry , You are not authorized.</div>
                         <?php }?>
                        </div>
                        <div class="divdashcent">
                        	<div align="center" class="dashboardHead">ORDER STATUS</div>
							<div class="divdashconsec">
						<?php
							$Openjobs=$dbf->countRows("work_order","work_status='Open'");
                            $Assignedjobs=$dbf->countRows("work_order","work_status='Assigned'");
                            $Dispatchedjobs=$dbf->countRows("work_order","work_status='Dispatched'");
                            $InProgressjobs=$dbf->countRows("work_order","work_status='In Progress'");
                            $Completedjobs=$dbf->countRows("work_order","work_status='Completed'");
							$Invoicedjobs=$dbf->countRows("work_order","work_status='Invoiced'");
							$Cancelledjobs=$dbf->countRows("work_order","work_status='Cancelled'");
                              echo $strXML1="<chart caption='Open vs Scheduled Jobs\n(Click on Chart to Slice)' palette='4' decimals='0' enableSmartLabels='1' enableRotation='0' bgColor='99CCFF,FFFFFF' bgAlpha='40,100' bgRatio='0,100' bgAngle='360' showBorder='0' startingAngle='60'>
                                    <set label='Open' value='$Openjobs'/>
                                    <set label='Assigned' value='$Assignedjobs'/>
									<set label='Dispatched' value='$Dispatchedjobs'/>
									<set label='In Progress' value='$InProgressjobs'/>
									<set label='Completed' value='$Completedjobs'/>
									<set label='Invoiced' value='$Invoicedjobs'/>
									<set label='Cancelled' value='$Cancelledjobs'/>
                                </chart>";				
                               echo renderChartHTML("FusionCharts/Charts/Pie3D.swf", "", $strXML1, "myChart1", "100%", 280);
                            ?>
                           </div>
                        </div>
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
                                echo renderChartHTML("FusionCharts/Charts/Bar2D.swf","",$strXML2,"mychart2","100%",280);
                            ?>
                           </div>
                        </div>
            		</div>
                </div>
              <!-------------Main Body--------------->
         </div>
        <div class="spacer"></div>
        <?php include_once 'footer.php'; ?>
    </div>
</body>
</html>