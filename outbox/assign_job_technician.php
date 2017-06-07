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
	//page titlevariable
	$pageTitle="Welcome To Out Of The Box";
	include 'applicationtop.php';
	if($_SESSION['userid']==''){
		header("location:logout");exit;
	}
	$arrWonos = array(); $arrPonos = array();
	if($_REQUEST['action']=='assign'){
	    $SrchTechnician = $_REQUEST['SrchTechnician'];
		//get technician details
		$totech1=$dbf->strRecordID("technicians","id,first_name,middle_name,last_name,email,contact_phone,pay_grade,sms_alert","id='".$SrchTechnician."'");
		$TechPhoneNo=$totech1['contact_phone'];
		foreach($_REQUEST['chkTech'] as $val){
			$arrVal=explode("_",$val);
			$vwoid =$arrVal[0];$vwono =$arrVal[1];$vpono =$arrVal[2];
			array_push($arrWonos,$vwono);
			array_push($arrPonos,$vpono);
			//insert data into the assign tech table
			$string = "wo_no='$vwono',tech_id='$SrchTechnician',assign_date=now(),created_date=now()";
			$num=$dbf->countRows("assign_tech","wo_no='$vwono'");
			if($num == 0){
				$insassign =$dbf->insertSet("assign_tech",$string);
				//update work order table
				$dbf->updateTable("work_order","work_status='Assigned'","wo_no='$vwono'");
			}
			############update technician price in work order service table###############
			foreach($dbf->fetch("workorder_service","workorder_id='$vwoid'") as $valsrvice){
				//get tech price from service price table
				$fieldname = "grade".$totech1['pay_grade']."_price";
				$tech_price = $dbf->getDataFromTable("service_price",$fieldname,"service_id='$valsrvice[service_id]' AND equipment='$valsrvice[equipment]' AND work_type='$valsrvice[work_type]'");
				//update work order service table
				if($valsrvice['tech_price'] =='0.00'){
					$dbf->updateTable("workorder_service","tech_price='$tech_price'","id='$valsrvice[id]'");
				}
			}
			############update technician price in work order service table##############
		}
		$wonos = implode(",",$arrWonos); $ponos = implode(",",$arrPonos);
		#######Email Sending Start#################
		/********Technician Email************/
		$res_template=$dbf->fetchSingle("email_template","id=6");
		$from=$res_template['from_email'];
		$from_name=$res_template['from_name'];
		$subject=$res_template['subject'];
		$input=$res_template['message'];
		//get technician details
		$to=$totech1['email'];
		$TechName=$totech1['first_name'].'&nbsp;'.$totech1['middle_name'].'&nbsp;'.$totech1['last_name'];
	    $email_body=str_replace(array('%TechName%','%WorkOrders%','%PurchaseOrder%'),array($TechName,$wonos,$ponos),$input);
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
		
		foreach($arrWonos as $valwo){
			$clientid = $dbf->getDataFromTable("work_order","client_id","wo_no='$valwo'");
			$resClients = $dbf->fetchSingle("clients","id='$clientid'");
			$toclient= $resClients['email'];
			$toclientname= $resClients['name'];
			$emailbody=str_replace(array('%ClientName%','%TechName%','%ContactPhone%'),array($toclientname,$TechName,$TechPhoneNo),$input1);
			//echo $emailbody;exit;
			@mail($toclient,$subject1,$emailbody,$headers);
		}
		/********Client Email************/
		############Email Sending End################
		################APP Notification ##########################
		$techexit=$dbf->countRows("technician_token","tech_id='$SrchTechnician'");
		if($techexit){
			//Get device token from technician_token table 
			foreach($dbf->fetch("technician_token","tech_id='$SrchTechnician' AND device_token!='' AND device_type!=''") as $key=>$value){	
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
		################APP Notification ##########################
		###############PREPARATION FOR SEND SMS#################
	    $msgtemplate=$dbf->getDataFromTable("sms_template","message","id=1");
	    $txaMessage=str_replace(array('%WorkOrder%'),array($wonos),$msgtemplate);
		//send sms if the sms_alert is on for that tech
		if($totech1['sms_alert'] =='1'){
			$TechPhoneNo=addslashes($totech1['contact_phone']);
			//REMOVE . ,white spaces,hyphen from the string
			$TechPhoneNo = preg_replace('/[\. -]/', '', $TechPhoneNo);
			$tosend="1".$TechPhoneNo; 
			 ####################Call to Text magic SMS GATEWAY API#####################
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
	  ##########################################################################
	  //print $response;
	  ###############PREPARATION FOR SEND SMS#################
		header("location:manage-job-board-assigned");exit;
	}
?>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />
<link rel="stylesheet" href="css/tablejob.css" type="text/css" />
<script type="text/javascript">
function check_all(){
  var chkval= $('input:checkbox[name=chkAll]:checked').val();
 //alert(chkval);
 if(chkval==1){
		$('input:checkbox[name=chkTech[]]').each(function() { 
        	 $(this).attr('checked', true);
   		 });
	}else{
		$('input:checkbox[name=chkTech[]]').each(function() { 
        	 $(this).attr('checked', false);
   		 });
	}
}
</script>
<body>
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
                        <div class="headerbg"><div style="float:left;">Assign Jobs To Technician</div>
                        </div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        	<form name="frmtechAssign" id="frmtechAssign" action="" method="post" onSubmit="return validate_techAssign();">
                            <input type="hidden" name="action" value="assign">
                        	<div style="width:100%;float:left;">
                             <div style="margin-top:10px;" align="center">
                             	<div class="formtextaddjoblong">Select Technician</div>
                                   <div class="textboxctech" >
                                   	<select name="SrchTechnician" id="SrchTechnician" class="selectboxsrch">
                                    	<option value="">--Select Tech--</option>
                                        <?php 
										$cond1 = "id>0 AND status=1";
										//condition for users
										if($implode_techs <>''){
											$cond1.=" AND FIND_IN_SET(id,'$implode_techs')";
										}
										foreach($dbf->fetch("technicians",$cond1." ORDER BY first_name ASC")as $tech){?>
                                        <option value="<?php echo $tech['id'];?>" <?php if($tech['id']==$_REQUEST['SrchTechnician']){echo 'selected';}?>><?php echo $tech['first_name'].'&nbsp;'.$tech['middle_name'].'&nbsp;'.$tech['last_name'];?></option>
                                        <?php }?>
                                    </select><br><label for="SrchTechnician" id="lblSrchTechnician" class="redText"></label>
                               </div>
                              <div class="textboxctech"><input type="submit" class="buttonText2" name="SearchRecord" value="Assign Tech"></div>
                               <div class="textboxctech"><label for="chkTech" id="lblAssign" class="redText"></label></div>
                               </div>
                               <div class="spacer"></div>
                               <div class="table">
                                <div class="table-head">
                                	<div class="column" data-label="Workorder Date"  style="width:10%;"><input type="checkbox" name="chkAll" id="checkAll" onClick="check_all();" value="1">&nbsp;Select All</div>
                                    <div class="column" data-label="WO NO" style="width:8%;">WO#</div>
                                    <div class="column" data-label="Customer Name"  style="width:10%;">Customer Name</div>
                                    <div class="column" data-label="Job Status" style="width:8%;">Order Status</div>  
                                    <div class="column" data-label="Service Type"  style="width:10%;">Service Type</div>
                                    <div class="column" data-label="Pickupcity"  style="width:9%;">Pickupcity</div>
                                    <div class="column" data-label="PickupState"  style="width:9%;">PickupState</div>
                                    <div class="column" data-label="Delivery City"  style="width:8%;">Delivery City</div>
                                    <div class="column" data-label="Delivery State"  style="width:8%;">DeliveryState</div>
                                    <div class="column" data-label="Client"  style="width:10%;">Client</div>
									<div class="column" data-label="Created Date"  style="width:10%;">Created Date</div>
                                </div>
                                <?php
									$cond = "c.state=st.state_code AND c.id=w.client_id AND s.id=w.service_id AND w.work_status='Open' AND w.approve_status='1'";
									//condition for users
								   if($implode_clients <>''){
										$cond.=" AND FIND_IN_SET(w.created_by,'$implode_clients')";
								   }
								   //echo $cond; 
								    $num=$dbf->countRows("state st,clients c,service s,work_order w",$cond." ORDER BY w.id DESC");
									foreach($dbf->fetch("state st,clients c,service s,work_order w",$cond." ORDER BY w.id DESC")as $assign_mulTech){
										//get pickupstate
										$pickupstate=$dbf->getDataFromTable("state","state_name","state_code='$assign_mulTech[pickup_state]'");
										//get client name
										if($assign_mulTech['created_by']<>0){
											$clientname=$dbf->getDataFromTable("clients","name","id='$assign_mulTech[created_by]'");
										}else{
											$clientname="COD";
										}
										?>
							    <div class="row">
                                    <div class="column" data-label="checkTech">
                                    <input type="checkbox" name="chkTech[]" id="chkTech" value="<?php echo $assign_mulTech['id']."_".$assign_mulTech['wo_no']."_".$assign_mulTech['purchase_order_no'];?>"></div>
                                    <div class="column" data-label="WO NO"><a href="view-job-board?src=assign&id=<?php echo $assign_mulTech['id'];?>" title="Click Here For Job Details" style="color:#333;"><?php echo $assign_mulTech['wo_no'];?></a></div>
                                    <div class="column" data-label="Customer Name"><?php echo $assign_mulTech['name'];?></div>                                    
                                    <div class="column" data-label="Job Status" style="color:#333; font-weight:bold;"><?php echo $assign_mulTech['work_status'];?></div>
                                    <div class="column" data-label="Service Type"><?php echo $assign_mulTech['service_name'];?></div>
                                    <div class="column" data-label="Pickupcity"><?php echo $assign_mulTech['pickup_city'];?></div>
                                    <div class="column" data-label="PickupState"><?php echo $pickupstate;?></div>
                                    <div class="column" data-label="Delivery City"><?php echo $assign_mulTech['city'];?></div>
                                    <div class="column" data-label="Delivery State"><?php echo $assign_mulTech['state_name'];?></div>
                                    <div class="column" data-label="Delivery State"><?php echo $clientname;?></div>
                                    <div class="column" data-label="Created Date"><?php echo date("d-M-Y",strtotime($assign_mulTech['created_date']));?></div>
                                                                  
                               </div>
                               <?php  }?>
                            </div>
                            <?php if($num == 0) {?><div class="noRecords" style="padding-left:40%;">No records founds!!</div>
                            <?php }?>
                         </div>
                         </form>
                        </div>
                        <div class="spacer"></div>
                    </div>
            	</div>
              <!-------------Main Body--------------->
         </div>
        <div class="spacer"></div>
        <?php include_once 'footer.php'; ?>
    </div>
</body>
</html>