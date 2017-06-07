<?php 
ob_start();
session_start();
include_once 'includes/class.Main.php';
include('textmagic-sms-api/TextMagicAPI.php');
//Object initialization
$dbf = new User();
###notification class object initialization here##########
include_once 'includes/class.Notification.php';
$dbn = new Notification();
###notification class object initialization here##########

if(isset($_REQUEST['choice']) && $_REQUEST['choice']=="assign_job"){ 
$resTech = $dbf->fetchSingle("assign_tech ","wo_no='$_REQUEST[wono]'");
$implode_techs = $_REQUEST['implode_techs'];
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
                <div id="loader" style="display:none;" align="center"><img src="images/ajax-loader.gif"/></div>
                <div class="technicianjobboard" id="AssignDiv">
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
                                    <?php 
									$cond1 = "id>0 AND status=1";
									//condition for users
									if($implode_techs <>''){
										$cond1.=" AND FIND_IN_SET(id,'$implode_techs')";
									}
									foreach($dbf->fetch("technicians",$cond1." ORDER BY first_name ASC")as $tech){?>
                                    <option value="<?php echo $tech['id'];?>" <?php if($tech['id']==$resTech['tech_id']){echo 'selected';}?>><?php echo $tech['first_name'].'&nbsp;'.$tech['middle_name'].'&nbsp;'.$tech['last_name'];?></option>
                                    <?php }?>
                                </select><br/>
                                      <label for="cmbTechnician" id="lblcmbTechnician" class="redText"></label>
                            	</div>
                                <div class="spacer" style="height:20px;"></div>
                                <div  class="formtextaddtech">Scheduled Date:</div>
                                <div class="textboxctech"><input type="text" class="textboxjob datepick" name="StartDate" id="StartDate" readonly value="<?php if($resTech !='' && $resTech['start_date']!='0000-00-00'){echo date("d-M-Y",strtotime($resTech['start_date']));}?>"/><br /><label for="StartDate" id="lblStartDate" class="redText"></label></div>
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
	$resOrder=$dbf->strRecordID("work_order","purchase_order_no,created_by","id='".$work_id."'");
	$purchaseOrder= $resOrder['purchase_order_no'];
	$asgnStartDate=$_REQUEST['StartDate']?date("Y-m-d",strtotime($_REQUEST['StartDate'])):'';
	//get technician details
	$totech1=$dbf->strRecordID("technicians","id,first_name,middle_name,last_name,email,contact_phone,pay_grade,sms_alert","id='".$Techid."'");
	$TechName=$totech1['first_name'].'&nbsp;'.$totech1['middle_name'].'&nbsp;'.$totech1['last_name'];
	$TechPhoneNo=$totech1['contact_phone'];
	########get client name for subject line###############
	$clientName =$dbf->getDataFromTable("work_order w,clients c","c.name","w.created_by=c.id AND w.id='".$work_id."'");
	$clientName = $clientName ? $clientName :"COD";
	########get client name for subject line###############
    $num = $dbf->countRows("assign_tech","wo_no='".$WorkNo."'");
	if($num>0){
		//alert user the order is already assigned
		echo 2;exit;
	}else{
		//update work order table
		if($asgnStartDate !=''){
			$dbf->updateTable("work_order","work_status='Scheduled',schedule_status='Scheduled',updated_date=now(), updated_by='".$_SESSION['userid']."'","wo_no='".$WorkNo."'");
		}else{
			//update work order table
			$dbf->updateTable("work_order","work_status='Assigned', updated_date=now(), updated_by='$_SESSION[userid]'","wo_no='$WorkNo'");	
		}
   		//insert into assign_tech table
		$string="tech_id='".$Techid."', wo_no='".$WorkNo."', assign_date=now(), start_date='".$asgnStartDate."', created_date=now()";
		$insassign =$dbf->insertSet("assign_tech",$string);
		###########Track user activity in work order notes table#############
		if($work_id){
			$adminNotes="This order is assigned to tech ".$TechName.".";
			$strnotes="workorder_id='".$work_id."', user_type='".$_SESSION['usertype']."', user_id='".$_SESSION['userid']."', wo_notes='".$adminNotes."',created_date=now()";
			$dbf->insertSet("workorder_notes",$strnotes);
		}
		###########Track user activity in work order notes table#############
		############update technician price in work order service table###############
		foreach($dbf->fetch("workorder_service","workorder_id='$work_id'") as $valsrvice){
			//get tech price from service price table
			$fieldname = "grade".$totech1['pay_grade']."_price";
			$tech_price = $dbf->getDataFromTable("service_price",$fieldname,"service_id='".$valsrvice['service_id']."' AND equipment='".$valsrvice['equipment']."' AND work_type='".$valsrvice['work_type']."'");
			//update work order service table
			if($valsrvice['tech_price'] =='0.00'){
				$dbf->updateTable("workorder_service","tech_price='".$tech_price."'","id='".$valsrvice['id']."'");
			}
		}
		############update technician price in work order service table###############
 		############Email Sending Start#############
		/********Technician Email************/
		$res_template=$dbf->fetchSingle("email_template","id='6'");
		$from=$res_template['from_email'];
		$from_name=$res_template['from_name'];
		$subject=$res_template['subject']."==".$clientName."==".$WorkNo;
		$input=$res_template['message'];
		//get technician details
		$to=$totech1['email'];
	    $email_body=str_replace(array('%TechName%','%WorkOrders%','%PurchaseOrder%'),array($TechName,$WorkNo,$purchaseOrder),$input);
	    $headers = "MIME-Version: 1.0\n";
	    $headers .= "Content-type: text/html; charset=UTF-8\n";
	    $headers .= "From:".$from_name." <".$from.">\n";
	    $body=$email_body;
	    //echo $body;exit;
	    @mail($to,$subject,$body,$headers);
		/********Technician Email************/
		/********Client Email************/
		$res_template=$dbf->fetchSingle("email_template","id='5'");
		$from1=$res_template['from_email'];
		$from_name1=$res_template['from_name'];
		$subject1=$res_template['subject']."==".$clientName."==".$WorkNo;
		$input1=$res_template['message'];
		$headers = "MIME-Version: 1.0\n";
	    $headers .= "Content-type: text/html; charset=UTF-8\n";
	    $headers .= "From:".$from_name1." <".$from1.">\n";
		$clientid = $dbf->getDataFromTable("work_order","client_id","wo_no='".$WorkNo."'");
		$resClients = $dbf->strRecordID("clients","name,email","id='".$clientid."'");
		$toclient= $resClients['email'];
		$toclientname= $resClients['name'];
		$emailbody=str_replace(array('%ClientName%','%TechName%','%ContactPhone%'),array($toclientname,$TechName,$TechPhoneNo),$input1);
		//echo $emailbody;exit;
		@mail($toclient,$subject1,$emailbody,$headers);
		/********Client Email************/
		############Email Sending End#############
		################APP Notification ##########################
		$techexit=$dbf->countRows("technician_token","tech_id='".$Techid."'");
		if($techexit){
			//Get device token from technician_token table 
			foreach($dbf->fetch("technician_token","tech_id='".$Techid."' AND device_token!='' AND device_type!=''") as $key=>$value){		
				$badge=1;
				$deviceToken=$value['device_token'];
				$message="A new work order is assigned to you.Please schedule after checking your Mail or SMS.";			
				//check for ios or android device
				if($value['device_type']=='iOS'){
					$dbn->send_ios_notification($deviceToken,$message,$badge);
				}else{
					$dbn->send_android_notification($value['gcm_id'],$message);
				}
			}
		}
	 ################APP Notification #########################################
	 ####################INTEGRATED TEXT MAGIC GATEWAY#########################
	 if($clientName=='Amazon'){
		 $msgtemplate=$dbf->getDataFromTable("sms_template_client","message","client_id='".$resOrder['created_by']."'");
		 if($msgtemplate !=""){
			$txaMessage=str_replace(array('%WorkOrder%'),array($WorkNo),$msgtemplate);
		  }else{
			$msgtemplate=$dbf->getDataFromTable("sms_template","message","id=1");
			$txaMessage=str_replace(array('%WorkOrder%'),array($WorkNo),$msgtemplate);
		  }
		  //send sms if the sms_alert is on for that tech
		  if($totech1['sms_alert'] =='1'){
			 $TechPhoneNo=addslashes($totech1['contact_phone']);
			 //REMOVE . ,white spaces,hyphen from the string
			 $TechPhoneNo = preg_replace('/[\. -]/', '', $TechPhoneNo);
			 $tosend="1".$TechPhoneNo; 
			//===================Call to Text magic SMS GATEWAY API===================//
			try{
				$api = new TextMagicAPI(array(
					"username" => TEXT_MAGIC_API_USER,
					"password" => TEXT_MAGIC_API_PASSWORD
				));
				//$phones = array(99912345678);
				$phones = array($tosend);
				$results = $api->send($txaMessage, $phones, true);
				//your data base code to insert; 
			}catch(Exception $e){
			}
		  }
	 }
	 ##########################################################################
	 ###############PREPARATION FOR SEND SMS###################################
	 echo 1;exit;
	}	
}?>
