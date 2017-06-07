<?php 
ob_start();
session_start();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();
if(isset($_REQUEST['choice']) && $_REQUEST['choice']=="workstatus"){ 
    $resworkStatus=$dbf->fetchOrder("work_order_tech","wo_no='$_REQUEST[wono]'","id");
	$workstatus = $dbf->getDataFromTable("work_order","work_status","wo_no='$_REQUEST[wono]'");
	$res_techName=$dbf->fetchSingle("assign_tech at,technicians t","at.tech_id=t.id AND at.wo_no='$_REQUEST[wono]'");		
//}
?>
<div id="maindiv">
         <div  style="margin:2px;">
                <!-------------Main Body--------------->
                <div class="technicianworkboard">
            		<div class="rightcoluminner">
                        <div class="headerbg">Technician WorkStatus</div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        <!-----Table area start------->
                            <div>
                            	<div class="spacer" style="height:20px;"></div>
                            	<div  class="formtextaddtech">Technician Name:</div>
                            	<div  class="textboxfancjob"><?php echo $res_techName['first_name'].'&nbsp;'.$res_techName['middle_name'].'&nbsp;'.$res_techName['last_name'];?></div>
                              <div  class="formtextaddtech">Work Status:</div>
                                <div class="textboxfancjob"><?php echo $workstatus;?></div>
                                <div class="spacer" style="height:20px;"></div>
                                <?php if(!empty($resworkStatus)){?>
                                <div class="formtextaddtechlong">Arrival Date:</div>
                                <div class="formtextaddtechlong">Arrival Time:</div>
                                <div class="formtextaddtechlong">Depart Time:</div>
                                <div class="spacer" style="border-bottom:dashed 1px #999; margin-left:20px; margin-right:20px;"></div>
                                <?php 
								foreach($resworkStatus as $res_workStatus){?>
                                <div class="formtextaddtechview"><?php echo date("d-M-Y",strtotime($res_workStatus['arrival_date']));?></div>
                                <div class="formtextaddtechview"><?php echo $res_workStatus['arrival_time'];?></div>
                                <div class="formtextaddtechview"><?php echo $res_workStatus['depart_time'];?></div>
                                <div class="spacer"></div>
                                <div class="formtextaddtech">Tech Notes:</div>
                                <div  class="notetext"><?php echo $res_workStatus['notes'];?></div>
                                <div class="spacer" style="height:10px; border-bottom:dashed 1px #999;margin-left:20px; margin-right:20px;"></div>
                                <?php }
								}else{?>
                                <div class="redText" align="center">No work status given yet !!!.</div>
                                <?php }?>
                                <div class="spacer"></div>
                             </div>
                          <!-----Table area end------->
                    	</div>
            	</div>
               </div>
              <!-------------Main Body--------------->
         </div>
</div>
<?php }?>