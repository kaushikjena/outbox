<?php 
ob_start();
session_start();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();
if(isset($_REQUEST['choice']) && $_REQUEST['choice']=="assign_job"){ 
$resTech = $dbf->fetchSingle("assign_tech ","wo_no='$_REQUEST[wono]'");
?>
<script type="text/javascript">
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
});
</script>
 <div id="maindiv">
         <div  style="margin:2px;">
                <!-------------Main Body--------------->
                <div class="technicianjobboard">
            		<div class="rightcoluminner">
                        <div class="headerbg">Assign Technician</div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        <!-----Table area start------->
                          <form name="AssignTech" id="AssignTech"  method="post" autocomplete="off">                        	
                            <div>
                            	<div class="spacer" style="height:20px;"></div>
                            	<div  class="formtextaddtech">Select Technician:<span class="redText">*</span></div>
                            	<div  class="textboxctech">
                                <select name="cmbTechnician" id="cmbTechnician" class="selectboxjob">
                                    <option value="">--Select Technician--</option>
                                    <?php foreach($dbf->fetch("technicians","id")as $tech){?>
                                    <option value="<?php echo $tech['id'];?>" <?php if($tech['id']==$resTech['tech_id']){echo 'selected';}?>><?php echo $tech['first_name'].'&nbsp;'.$tech['middle_name'].'&nbsp;'.$tech['last_name'];?></option>
                                    <?php }?>
                                </select><br/>
                                      <label for="cmbTechnician" id="lblcmbTechnician" class="redText"></label>
                            	</div>
                                <div class="spacer" style="height:20px;"></div>
                                <div  class="formtextaddtech">Scheduled Date:<span class="redText">*</span></div>
                                <div class="textboxctech"><input type="text" class="textboxjob datepick" name="StartDate" id="StartDate" value="<?php if($resTech<>''){echo date("d-M-Y",strtotime($resTech['start_date']));}?>"/><br /><label for="StartDate" id="lblStartDate" class="redText"></label></div>
                                 <div class="spacer" style="height:20px;"></div>
                                 <div class="formtextaddtech" align="center"><input type="checkbox" name="chkWO" id="chkWO" value="<?php echo $_REQUEST['wono'];?>" <?php if($_REQUEST['wono']==$resTech['wo_no']){echo 'checked';}?>/></div>
                            	<div  class="textboxctech"><?php echo $_REQUEST['wono'];?>&nbsp;<span class="formtext">(WO#)</span>
                                <br/><label for="chkWO" id="lblchkWO" class="redText"></label>
                            	</div>
                                 <div class="spacer" style="height:20px;"></div>
                            </div>
                            <!-----service div end--------->
                            <div class="spacer"></div>
                            <div align="center">
                                <input type="hidden" name="work_id" id="work_id" value="<?php echo $_REQUEST['wo_id']; ?>"/>
                                <input type="button" name="submitbtn" id="submitbtn" class="buttonText" value="Assign" onclick="return insert_data();"/>
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
<?php }else if(isset($_REQUEST['choice'])&& $_REQUEST['choice']=="data_insert"){
	ob_clean();
	$Techid=addslashes($_REQUEST['cmbTechnician']);
	$WorkNo=addslashes($_REQUEST['chkWO']);
	$work_id=addslashes($_REQUEST['work_id']);
	$asgnStartDate=date("Y-m-d",strtotime($_REQUEST['StartDate']));
	//get technician details
	$totech1=$dbf->fetchSingle("technicians","id='$Techid'");
    $num = $dbf->countRows("assign_tech","wo_no='$WorkNo'");
	if($num>0){
		//update assign_tech table
		$string="tech_id='$Techid', wo_no='$WorkNo', assign_date=now(),start_date='$asgnStartDate',created_date=now()";
		$dbf->updateTable("assign_tech",$string,"wo_no='$WorkNo'");
		############update technician price in work order service table###############
		foreach($dbf->fetch("workorder_service","workorder_id='$work_id'") as $valsrvice){
			//get tech price from service price table
			$srPrice = $dbf->fetchSingle("service_price","service_id='$valsrvice[service_id]' AND equipment='$valsrvice[equipment]' AND work_type='$valsrvice[work_type]'");
			if($totech1['pay_grade']=='A'){
				$gradeA_price = $srPrice['gradeA_price'];
			}elseif($totech1['pay_grade']=='B'){
				$gradeB_price = $srPrice['gradeB_price'];
			}elseif($totech1['pay_grade']=='C'){
				$gradeC_price = $srPrice['gradeC_price'];
			}elseif($totech1['pay_grade']=='D'){
				$gradeD_price = $srPrice['gradeD_price'];
			}
			//update work order service table
			$dbf->updateTable("workorder_service","gradeA_price='$gradeA_price', gradeB_price='$gradeB_price', gradeC_price='$gradeC_price', gradeD_price='$gradeD_price'","id='$valsrvice[id]'");
		}
		############update technician price in work order service table##############
		//Email Sending Start
		/********Technician Email************/
		$res_template=$dbf->fetchSingle("email_template","id=6");
		$from=$res_template['from_email'];
		$from_name=$res_template['from_name'];
		$subject=$res_template['subject'];
		$input=$res_template['message'];
		//get technician details
		$to=$totech1['email'];
		$TechName=$totech1['first_name'].'&nbsp;'.$totech1['middle_name'].'&nbsp;'.$totech1['last_name'];
	    $email_body=str_replace(array('%TechName%','%WorkOrders%'),array($TechName,$WorkNo),$input);
	    $headers = "MIME-Version: 1.0\n";
	    $headers .= "Content-type: text/html; charset=UTF-8\n";
	    $headers .= "From:".$from_name." <".$from.">\n";
	    $body=$email_body;
	    //echo $body;exit;
	    @mail($to,$subject,$body,$headers);
		/********Technician Email************/
		/********Client Email************/
		$res_template=$dbf->fetchSingle("email_template","id=5");
		$from1=$res_template['from_email'];
		$from_name1=$res_template['from_name'];
		$subject1=$res_template['subject'];
		$input1=$res_template['message'];
		$headers = "MIME-Version: 1.0\n";
	    $headers .= "Content-type: text/html; charset=UTF-8\n";
	    $headers .= "From:".$from_name1." <".$from1.">\n";
		$clientid = $dbf->getDataFromTable("work_order","client_id","wo_no='$WorkNo'");
		$resClients = $dbf->fetchSingle("clients","id='$clientid'");
		$toclient= $resClients['email'];
		$toclientname= $resClients['name'];
		$emailbody=str_replace(array('%ClientName%','%TechName%'),array($toclientname,$TechName),$input1);
		//echo $emailbody;exit;
		@mail($toclient,$subject1,$emailbody,$headers);
		/********Client Email************/
		//Email Sending End
		echo 1;exit;
	}else{
		//update work order table
		$dbf->updateTable("work_order","job_status='Assigned'","wo_no='$WorkNo'");		
   		//insert into assign_tech table
		$string="tech_id='$Techid', wo_no='$WorkNo', assign_date=now(),start_date='$asgnStartDate',created_date=now()";
		$insassign =$dbf->insertSet("assign_tech",$string);
		############update technician price in work order service table###############
		foreach($dbf->fetch("workorder_service","workorder_id='$work_id'") as $valsrvice){
			//get tech price from service price table
			$srPrice = $dbf->fetchSingle("service_price","service_id='$valsrvice[service_id]' AND equipment='$valsrvice[equipment]' AND work_type='$valsrvice[work_type]'");
			if($totech1['pay_grade']=='A'){
				$gradeA_price = $srPrice['gradeA_price'];
			}elseif($totech1['pay_grade']=='B'){
				$gradeB_price = $srPrice['gradeB_price'];
			}elseif($totech1['pay_grade']=='C'){
				$gradeC_price = $srPrice['gradeC_price'];
			}elseif($totech1['pay_grade']=='D'){
				$gradeD_price = $srPrice['gradeD_price'];
			}
			//update work order service table
			$dbf->updateTable("workorder_service","gradeA_price='$gradeA_price', gradeB_price='$gradeB_price', gradeC_price='$gradeC_price', gradeD_price='$gradeD_price'","id='$valsrvice[id]'");
		}
		############update technician price in work order service table###############
 		//Email Sending Start
		/********Technician Email************/
		$res_template=$dbf->fetchSingle("email_template","id=6");
		$from=$res_template['from_email'];
		$from_name=$res_template['from_name'];
		$subject=$res_template['subject'];
		$input=$res_template['message'];
		//get technician details
		$to=$totech1['email'];
		$TechName=$totech1['first_name'].'&nbsp;'.$totech1['middle_name'].'&nbsp;'.$totech1['last_name'];
	    $email_body=str_replace(array('%TechName%','%WorkOrders%'),array($TechName,$WorkNo),$input);
	    $headers = "MIME-Version: 1.0\n";
	    $headers .= "Content-type: text/html; charset=UTF-8\n";
	    $headers .= "From:".$from_name." <".$from.">\n";
	    $body=$email_body;
	    //echo $body;exit;
	    @mail($to,$subject,$body,$headers);
		/********Technician Email************/
		/********Client Email************/
		$res_template=$dbf->fetchSingle("email_template","id=5");
		$from1=$res_template['from_email'];
		$from_name1=$res_template['from_name'];
		$subject1=$res_template['subject'];
		$input1=$res_template['message'];
		$headers = "MIME-Version: 1.0\n";
	    $headers .= "Content-type: text/html; charset=UTF-8\n";
	    $headers .= "From:".$from_name1." <".$from1.">\n";
		$clientid = $dbf->getDataFromTable("work_order","client_id","wo_no='$WorkNo'");
		$resClients = $dbf->fetchSingle("clients","id='$clientid'");
		$toclient= $resClients['email'];
		$toclientname= $resClients['name'];
		$emailbody=str_replace(array('%ClientName%','%TechName%'),array($toclientname,$TechName),$input1);
		//echo $emailbody;exit;
		@mail($toclient,$subject1,$emailbody,$headers);
		/********Client Email************/
		//Email Sending End
		echo 1;exit;
	}	
}?>


    
