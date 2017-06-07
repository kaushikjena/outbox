<?php 
ob_start();
session_start();
ini_set('memory_limit', '-1');
ini_set('max_execution_time',1800);
include_once './includes/class.Main.php';
//Object initialization
$dbf = new User();
//Fetch details from work_order table 
$res_wo=$dbf->fetchSingle("work_order","id='$_REQUEST[id]'");
if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='view'){
	//fetch data from woroderer_notes table
	$workorder_notes=$dbf->fetchSingle("workorder_notes","workorder_id='$_REQUEST[id]' AND (user_type='admin' OR user_type='user' OR user_type='tech') AND waiting_parts!=0");
?>
<div id="maindiv">
    <div  style="margin:2px;">
        <!-------------Main Body--------------->
        <div class="technicianworkboard">
            <div class="rightcoluminner">
                <div class="headerbg">Waiting For Parts</div>
                <div class="spacer"></div>
                <div id="contenttable">
                <!-----Table area start------->
                  <form name="frmWaitingParts" id="frmWaitingParts" action="" method="post" autocomplete="off">
                    <input type="hidden" name="action" value="insert_work"/>
                    <input type="hidden" name="worid" id="worid"value="<?php echo $_REQUEST['id'];?>">
                    <input type="hidden" name="workorder_notes_id" id="workorder_notes_id" value="<?php echo $workorder_notes['id'];?>">
                    <!-----address div start--------->
                     <div  class="divTechworkStatus">
                       <div class="greenText" align="left">Waiting For parts Comments:</div>
                       <div><textarea name="waiting_parts_comments" id="waiting_parts_comments" class="textareaOrder"><?php echo trim($workorder_notes['wo_notes'])?></textarea><br/><label for="waiting_parts_comments" id="waiting_parts_commentslabel" class="redText"></label></div>
                      <div class="spacer" style="height:5px;"><input type="checkbox" name="chk_box" id="chk_box" style="margin-top:20px;" <?php if($workorder_notes['waiting_parts']==1){echo 'checked';}?>/>(Check to enable waiting for parts)</div>
                    </div>
                    <div class="spacer"></div>
                    <div align="center">
                        <input type="button" name="submitbtn" id="submitbtn" class="buttonText" value="Submit Form" onclick="waiting_for_parts_insert();"/>
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
<?php
}elseif(isset($_REQUEST['choice']) && $_REQUEST['choice']=='insert'){
	 //insert into work order tech table
	 ###########Insert Into work order notes table#############
	if($_REQUEST['id']<>''){ 
		$techNotes=mysql_real_escape_string(trim($_REQUEST['waiting_parts_comments']));
		$string="workorder_id='$_REQUEST[id]', user_type='$_SESSION[usertype]', user_id='$_SESSION[userid]', wo_notes='$techNotes',waiting_parts='$_REQUEST[chk_box]',created_date=now()";
		if($_REQUEST['workorder_notes_id']!=''){
			$dbf->updateTable("workorder_notes",$string,"id='$_REQUEST[workorder_notes_id]'");
		}else{
			$dbf->insertSet("workorder_notes",$string);
		}
		
		//email to admin
		//get client name
		$clientName =$dbf->getDataFromTable("clients","name","id='$res_wo[created_by]'");
		$clientName = $clientName ? $clientName :"COD";
		//get customer name
		$customerName = $dbf->getDataFromTable("clients","name","id='$res_wo[client_id]'");
		//get admin details
		$res_admin=$dbf->fetchSingle("admin","id=1");
		$AdminName=$res_admin['name'];
		$to=$res_admin['email'];
		//get email template
		$res_template=$dbf->fetchSingle("email_template","id=20");
		$from=$res_template['from_email'];
		$fromname = $res_template['from_name'];
		$subject=$res_template['subject']." == ".$clientName." == ".$customerName;
		$input=$res_template['message'];
		//get technician details
		//$tech=$dbf->fetchSingle("technicians","id='$_SESSION[userid]'");
		//$TechName=$tech['first_name'].'&nbsp;'.$tech['middle_name'].'&nbsp;'.$tech['last_name'];
		$body =str_replace(array('%AdminName%','%CustomerName%','%WorkOrder%','%ClientName%'),array($AdminName,$customerName,$res_wo['wo_no'],$clientName),$input);
		$headers = "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=UTF-8\n";
		$headers .= "From:".$fromname." <".$from.">\n";
		//echo $body;exit;
		@mail($to,$subject,$body,$headers);
		//print $to.'--'.$subject.'--'.$body.'--'.$headers;exit;
		//print"1";exit;
	}
	//fetch data from woroderer_notes table
	$workorder_notes=$dbf->fetchSingle("workorder_notes","workorder_id='$_REQUEST[id]' AND (user_type='admin' OR user_type='user' OR user_type='tech') AND waiting_parts!=0");
	if($workorder_notes['user_type']=='admin'){
		 $uname = $dbf->getDataFromTable("admin","name","id='$workorder_notes[user_id]'");
	}elseif($workorder_notes['user_type']=='user'){
		 $uname = $dbf->getDataFromTable("users","name","id='$workorder_notes[user_id]'");
	}elseif($workorder_notes['user_type']=='tech'){
		  $unameTech = $dbf->fetchSingle("technicians","id='$workorder_notes[user_id]'");
		  $uname = $unameTech['first_name'].' '.$unameTech['middle_name'].' '.$unameTech['last_name'];
	 }
?>
	<div class="textareaNoteView">
		 <div align="left"><?php echo $workorder_notes['wo_notes'];?></div>
		 <div class="spacer" style="border-bottom:dashed 1px #ccc;"></div>
		 <div align="right">WFP Note: By <?php echo $uname;?> on <?php echo date("d-M-Y g:i A",strtotime($workorder_notes['created_date']));?> for #<?php echo $res_wo['wo_no'];?></div>
	 </div><div class="spacer"></div>
<?php 
}
?>