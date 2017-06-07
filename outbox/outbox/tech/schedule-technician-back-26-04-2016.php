<?php 
ob_start();
session_start();
include_once '../includes/class.Main.php';
//Object initialization
$dbf = new User();
if(isset($_REQUEST['choice']) && $_REQUEST['choice']=="assign_job"){ 
$resTech = $dbf->fetchSingle("assign_tech ","wo_no='$_REQUEST[wono]'");
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
                        <div class="headerbg">Schedule Work Time</div>
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
                                <div  class="formtextaddtech">Start Date:<span class="redText">*</span></div>
                                <div class="textboxctech"><input type="text" class="textboxjob datepick" name="StartDate" id="StartDate"  readonly="readonly" value="<?php if($resTech['start_date']<>'0000-00-00'){echo date("d-M-Y",strtotime($resTech['start_date']));}?>"/><br /><label for="StartDate" id="lblStartDate" class="redText"></label></div>
                                <div class="spacer" style="height:20px;"></div>
                                <div  class="formtextaddtech">Arrival Time:<span class="redText">*</span></div>
                                <div class="textboxctech">
                                	<div style="width:110px; float:left;"><input type="text" name="StartTime" id="StartTime" class="textboxjob datetime" value="<?php echo $resTech['start_time'];?>" readonly="readonly"/></div>
                                    <div style="float:left; width:26px; margin-left:2px;" align="center"><span class="formtext">To</span></div>
                                    <div style="width:110px;float:left;"><input type="text" name="EndTime" id="EndTime" class="textboxjob datetime" value="<?php echo $resTech['end_time'];?>" readonly="readonly"/></div>
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
                                <input type="hidden" name="work_id" id="work_id" value="<?php echo $_REQUEST['woid']; ?>"/>
                                <input type="button" name="submitbtn" id="submitbtn" class="buttonText" value="Schedule" onclick="return update_data();"/>
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
	//$woid=addslashes($_REQUEST['woid']);
	$asgnStartDate=date("Y-m-d",strtotime($_REQUEST['StartDate']));
	$asgnStartTime=$_REQUEST['StartTime'];
	$asgnEndTime=$_REQUEST['EndTime'];
	$num = $dbf->countRows("assign_tech","wo_no='$WorkNo'");
	if($num>0){
		//update work order table
		if($asgnStartDate !=''){
			$dbf->updateTable("work_order","work_status='Scheduled',schedule_status='Scheduled'","wo_no='$WorkNo'");
		}	
		//update assign_tech table
		$string="start_date='$asgnStartDate', start_time='$asgnStartTime',end_time='$asgnEndTime',updated_date=now()";
		$dbf->updateTable("assign_tech",$string,"wo_no='$WorkNo'");
		###################Track notes in the work order notes table################################
		$techNotes="The scheduled date of this order is changed.";
		$strnotes="workorder_id='$_REQUEST[woid]', user_type='$_SESSION[usertype]', user_id='$_SESSION[userid]', wo_notes='$techNotes',created_date=now()";
		$dbf->insertSet("workorder_notes",$strnotes);
		###################Track notes in the work order notes table################################
		//Email Sending Start
		/********Admin Email************/
		$admin_notification = $dbf->getDataFromTable("admin_email_notification","status","id=4");
		if($admin_notification==1){
			$res_template=$dbf->fetchSingle("email_template","id=11");
			$to=$res_template['from_email'];
			$AdminName=$res_template['from_name'];
			$subject=$res_template['subject'];
			$input=$res_template['message'];
			//get technician details
			$tech=$dbf->fetchSingle("technicians","id='$Techid'");
			$from=$tech['email'];
			$TechName=$tech['first_name'].'&nbsp;'.$tech['middle_name'].'&nbsp;'.$tech['last_name'];
			$email_body=str_replace(array('%AdminName%','%TechName%','%StartDate%','%StartTime%','%EndTime%','%WorkOrders%'),array($AdminName,$TechName,$_REQUEST['StartDate'],$_REQUEST['StartTime'],$_REQUEST['EndTime'],$WorkNo),$input);
			$headers = "MIME-Version: 1.0\n";
			$headers .= "Content-type: text/html; charset=UTF-8\n";
			$headers .= "From:".$TechName." <".$from.">\n";
			$body=$email_body;
			//echo $body;exit;
			@mail($to,$subject,$body,$headers);
		}
		/********Admin Email************/
		/********Client Email************/
		$res_template=$dbf->fetchSingle("email_template","id=12");
		$subject1=$res_template['subject'];
		$input1=$res_template['message'];
		$restech=$dbf->fetchSingle("technicians","id='$Techid'");
		$fromemail=$restech['email'];
		$Techname=$restech['first_name'].'&nbsp;'.$restech['middle_name'].'&nbsp;'.$restech['last_name'];
		$headers = "MIME-Version: 1.0\n";
	    $headers .= "Content-type: text/html; charset=UTF-8\n";
	    $headers .= "From:".$Techname." <".$fromemail.">\n";
		$clientid = $dbf->getDataFromTable("work_order","client_id","wo_no='$WorkNo'");
		$resClients = $dbf->fetchSingle("clients","id='$clientid'");
		
		$num_schedulecod=$dbf->countRows("cod_scheduled_notify","status=1");
		if($num_schedulecod!=0){
		  $array_email=array();
		  $array_name=array();
		  foreach($dbf->fetch("cod_scheduled_notify","status=1") as $val){
			array_push($array_email,$val['email']);
			$clientsName=$dbf->getDataFromTable("clients","name","id=$val[client_id]");
			array_push($array_name,$clientsName);
		  }
		  $toclient=implode(",",$array_email);
		  $toclientname=implode(",",$array_name);
	    }else{
		  $toclient= $resClients['email'];
		  $toclientname= $resClients['name'];
        }
		
		$emailbody=str_replace(array('%ClientName%','%TechName%','%StartDate%','%StartTime%','%EndTime%','%WorkOrders%'),array($toclientname,$Techname,$_REQUEST['StartDate'],$_REQUEST['StartTime'],$_REQUEST['EndTime'],$WorkNo),$input1);
		//echo $emailbody;exit;
		@mail($toclient,$subject1,$emailbody,$headers);
		/********Client Email************/
		//Email Sending End
		echo 1;exit;
	}	
}?>


    
