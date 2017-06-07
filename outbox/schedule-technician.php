<?php 
ob_start();
session_start();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();
if(isset($_REQUEST['choice']) && $_REQUEST['choice']=="assign_job"){ 
	$resTech = $dbf->fetchSingle("assign_tech ","wo_no='$_REQUEST[wono]'");
	$resOrder = $dbf->strRecordID("work_order","work_status,reschedule_status","id='".$_REQUEST['wo_id']."'");
?>
<script>
$(function() {
	$( ".datepick").datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'M-dd-yy',
		yearRange: '-80:+20'
	});
});
$(function() {
	$( ".datetime").timepicker({
		ampm: true
	});
	/*$('.datetime').timepicker({ 'timeFormat': 'h:i A' });*/
});
</script>
 <div id="maindiv">
     <div  style="margin:2px;">
          <!-------------Main Body--------------->
            <div class="technicianjobboard">
                <div class="rightcoluminner">
                    <div class="headerbg">Schedule Work Time </div>
                    <div class="spacer"></div>
                    <div id="contenttable">
                    <!-----Table area start------->
                      <form name="AssignTech" id="AssignTech"  method="post" autocomplete="off">                        	
                        <div>
                            <div class="spacer" style="height:20px;"></div>
                            <div  class="formtextaddtech">Select Technician:<span class="redText">*</span></div>
                            <div  class="textboxctech">
                            <select name="cmbTechnician" id="cmbTechnician" class="selectboxjob" disabled="disabled">
                                <option value="">--Select Technician--</option>
                                <?php foreach($dbf->fetch("technicians","id")as $tech){?>
                                <option value="<?php echo $tech['id'];?>" <?php if($tech['id']==$resTech['tech_id']){echo 'selected';}?>><?php echo $tech['first_name'].'&nbsp;'.$tech['middle_name'].'&nbsp;'.$tech['last_name'];?></option>
                                <?php }?>
                            </select><br/>
                                  <label for="cmbTechnician" id="lblcmbTechnician" class="redText"></label>
                            </div>
                            <div class="spacer" style="height:20px;"></div>
                            <div  class="formtextaddtech">Start Date:</div>
                            <div class="textboxctech"><input type="text" class="textboxjob datepick" name="StartDate" id="StartDate" value="<?php if($resTech['start_date']<>'0000-00-00'){echo date("d-M-Y",strtotime($resTech['start_date']));}?>"/><br /><label for="StartDate" id="lblStartDate" class="redText"></label></div>
                            <div class="spacer" style="height:20px;"></div>
                            <div  class="formtextaddtech">Arrival Time:</div>
                            <div class="textboxctech">
                                <div style="width:110px; float:left;"><input type="text" name="StartTime" id="StartTime" class="textboxjob datetime" value="<?php echo $resTech['start_time'];?>" readonly/></div>
                                <div style="float:left; width:26px; margin-left:2px;" align="center"><span class="formtext">To</span></div>
                                <div style="width:110px;float:left;"><input type="text" name="EndTime" id="EndTime" class="textboxjob datetime" value="<?php echo $resTech['end_time'];?>" readonly/></div>
                                <br /><label for="StartTime" id="lblStartTime" class="redText"></label>
                             </div>
                             <div class="spacer" style="height:20px;"></div>
                             <div class="formtextaddtech" align="center"><input type="checkbox" name="chkWO" id="chkWO" value="<?php echo $_REQUEST['wono'];?>" <?php if($_REQUEST['wono']==$resTech['wo_no']){echo 'checked'.'  '.'disabled';}?>/></div>
                            <div  class="textboxctech"><?php echo $_REQUEST['wono'];?>&nbsp;<span class="formtext">(WO#)</span>
                            <br/><label for="chkWO" id="lblchkWO" class="redText"></label>
                            </div>
                             <div class="spacer" style="height:20px;"></div>
                        </div>
                        <!-----service div end--------->
                        <div class="spacer"></div>
                        <div align="center">
                            <input type="hidden" name="work_id" id="work_id" value="<?php echo $_REQUEST['wo_id']; ?>"/>
                            <?php if($resOrder['work_status']=='Assigned' || (($resOrder['work_status']=='Scheduled' || $resOrder['work_status']=='In Progress' || $resOrder['work_status']=='Dispatched') && ($resOrder['reschedule_status']==1))){?>
                            <input type="button" name="submitbtn" id="submitbtn" class="buttonText" value="Schedule" onclick="return update_data();"/><?php }?>
                         </div>
                        <div class="spacer"></div>
                       </form>
                       <!-----Table area end------->
                    </div>
            </div>
           </div>
          <!-------------Main Body--------------->
     </div>
  </div>	
<?php }else if(isset($_REQUEST['choice'])&& $_REQUEST['choice']=="data_update"){
	ob_clean();//print "<pre>";print_r($_REQUEST);exit;
	$Techid=addslashes($_REQUEST['cmbTechnician']);
	$WorkNo=addslashes($_REQUEST['chkWO']);
	$woid=addslashes($_REQUEST['wo_id']);
	$asgnStartDate=$_REQUEST['StartDate']?date("Y-m-d",strtotime($_REQUEST['StartDate'])):'';
	$asgnStartTime=$_REQUEST['StartTime'];
	$asgnEndTime=$_REQUEST['EndTime'];
	$num = $dbf->countRows("assign_tech","wo_no='$WorkNo'");
	if($num>0){
		//update work order table
		if($asgnStartDate !=''){
			$dbf->updateTable("work_order","work_status='Scheduled',schedule_status='Scheduled',reschedule_status=0","wo_no='$WorkNo'");
		}else{
			//update work order table
			$dbf->updateTable("work_order","work_status='Assigned'","wo_no='$WorkNo'");	
		}
		//update assign_tech table
		$string="start_date='$asgnStartDate', start_time='$asgnStartTime',end_time='$asgnEndTime',updated_date=now()";
		$dbf->updateTable("assign_tech",$string,"wo_no='$WorkNo'");
		###########Track user activity in work order notes table#############
		$adminNotes="The scheduled date of this work order is updated.";
		$strnotes="workorder_id='$woid', user_type='$_SESSION[usertype]', user_id='$_SESSION[userid]', wo_notes='$adminNotes',created_date=now()";
		$dbf->insertSet("workorder_notes",$strnotes);
		###########Track user activity in work order notes table#############
		echo 1;exit;
	}	
}?>
    