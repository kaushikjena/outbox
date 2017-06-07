<?php 
ob_start();
session_start();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();
if(isset($_REQUEST['choice']) && $_REQUEST['choice']=="show_email"){ 
$resTech = $dbf->fetchSingle("technicians t,assign_tech at","at.wo_no='$_REQUEST[wono]' AND t.id=at.tech_id");
$res_template=$dbf->fetchSingle("email_template","id='18'");
if($_SESSION['usertype']=='admin'){
	$resUser=$dbf->strRecordID("admin","name,email","id='$_SESSION[userid]'");
}elseif($_SESSION['usertype']=='user'){
	$resUser=$dbf->strRecordID("users","name,email","id='$_SESSION[userid]'");
}
?>
<!--wysiwyg editor-->
<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
<!-- <script type="text/javascript" src="ckeditor/sample.js"></script>-->
<link rel="stylesheet" type="text/css" href="ckeditor/sample.css" />
<!--wysiwyg editor-->
 <div id="maindiv">
     <div  style="margin:2px;">
          <!-------------Main Body--------------->
            <div class="technicianjobboard" style="width:700px;">
                <div class="rightcoluminner">
                    <div class="headerbg">Send Notification To Tech</div>
                    <div class="spacer"></div>
                    <div id="contenttable">
                        <!-----Table area start------->
                        <div  class="emailDiv">
                        <div class="spacer"></div>
                         <form action="" name="frmEmailTech" id="frmEmailTech" method="post" enctype="multipart/form-data">
                         	<div  class="formtextadd">To Tech:</div>
                            <div  class="textboxc">
                            <input type="text" class="textboxjob" name="tname" id="tname" value="<?php echo $resTech['first_name'].'&nbsp;'.$resTech['middle_name'].'&nbsp;'.$resTech['last_name'];?>" disabled>
                            </div>
                            <div class="spacer"></div>
                            <div  class="formtextadd">From Email:<span class="redText">*</span></div>
                            <div  class="textboxc">
                             <input type="text" class="textboxjob" name="fromemail" id="fromemail" value="<?php echo $resUser['email'];?>"><br/><label for="fromemail" id="lblfromemail" class="redText"></label>
                            </div>
                            <div class="spacer"></div>
                            <div  class="formtextadd">From Name:<span class="redText">*</span></div>
                            <div  class="textboxc">
                               <input type="text" class="textboxjob" name="fromname" id="fromname" value="<?php echo $resUser['name'];?>">
                               <br/><label for="fromname" id="lblfromname" class="redText"></label></div>
                            <div class="spacer"></div>
                            <div  class="formtextadd">Subject:<span class="redText">*</span></div>
                            <div  class="textboxc">
                            <select name="subject" id="subject" class="selectboxjob" onChange="showMessage(this.value);">
                            	<option value="">-- Select Subject --</option>
                                <?php foreach($dbf->fetchOrder("emails","","id","id,subject") as $val){?>
                                <option value="<?php echo $val['id'];?>"><?php echo $val['subject'].'==='.$_REQUEST['wono'];?></option>
                                <?php }?>
                            </select>
                            <br/><label for="subject" id="lblsubject" class="redText"></label>
                            </div>
                            <div class="spacer"></div>
                            <div  class="formtextadd">Message :<span class="redText">*</span></div>
                            <div  class="textboxc">&nbsp;</div>
                            <div class="spacer"></div>
                            <div>
                            <textarea name="message"  id="message"><?php echo $res_template['message'];?></textarea>
                            <script type="text/javascript">
                                CKEDITOR.replace( 'message', {
                                   //extraPlugins : 'autogrow',
                                    autoGrow_maxHeight : 400,
									toolbar:[['Bold','Italic','Underline','Strike'],
									['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
									['Undo','Redo'],['Styles','Format','Font','FontSize'],
									['TextColor','BGColor']]
                                    //height :300,
                                    //width : 800
                                });
                            </script>
                            </div>
                            <div class="spacer"></div>
                             <div align="center">
                             <input type="hidden" name="woid" id="woid" value="<?php echo $_REQUEST['woid']; ?>"/>
                             <input type="hidden" name="wono" id="wono" value="<?php echo $_REQUEST['wono']; ?>"/>
                             <input type="button" class="buttonText" value="Send Email" onClick="sendEmail();"/></div>
                            </form>
                        </div>
                        <!-----Table area start-------> 
                        <div class="spacer"></div>
                    </div>
            </div>
           </div>
          <!-------------Main Body--------------->
     </div>
  </div>	
<?php }else if(isset($_REQUEST['choice'])&& $_REQUEST['choice']=="send_email"){
	ob_clean();//print "<pre>";print_r($_REQUEST);exit;
	$resTech = $dbf->fetchSingle("technicians t,assign_tech at","at.wo_no='$_REQUEST[wono]' AND t.id=at.tech_id");
	$wono=addslashes($_REQUEST['wono']);
	$woid=addslashes($_REQUEST['woid']);
	########get client name for subject line###############
	$clientName =$dbf->getDataFromTable("work_order w,clients c","c.name","w.created_by=c.id AND w.id='$woid'");
	$clientName = $clientName ? $clientName :"COD";
	########get client name for subject line###############
	$fromemail=addslashes($_REQUEST['fromemail']);
	$fromname=addslashes($_REQUEST['fromname']);
	$subject = $dbf->getDataFromTable("emails","subject","id='$_REQUEST[subject]'");
	$subject=addslashes($subject)."==".$clientName."==".$wono;
	$message=$_REQUEST['message'];
	/*if((strpos($message,'CarrierComany') &&  strpos($message,'TrackNo'))!== false) {
          echo 'true';}else{ echo 'false';}exit;*/
	$to=$resTech['email'];
	$toName = $resTech['first_name'].'&nbsp;'.$resTech['middle_name'].'&nbsp;'.$resTech['last_name'];
	//Email Sending Starts here
	if((strpos($message,'CarrierCompany') &&  strpos($message,'TrackingNo'))!== false){
		#########################FETCH CARRIER INFO,TRACK NO AND ARRIVAL DATE##########################
		$carrierComp=$dbf->getDataFromTable("work_order","carrier_company","id='$woid'");
		$trackNo=$dbf->getDataFromTable("work_order","tracking_number","id='$woid'");
		$resparts=$dbf->getDataFromTable("work_order","parts_arrive","id='$woid'");
		$partsArrive=($resparts!='0000-00-00')?date('d-M-Y',strtotime($resparts)):'';
		###############################################################################################
		$body=str_replace(array('%Name%','%CarrierCompany%','%TrackingNo%','%ArriveDate%'),array($toName,$carrierComp,$trackNo,$partsArrive),$message);	
	}else if(strpos($message,'CustomerCity') && strpos($message,'CustomerPhoneNo')){
		$customerId=$dbf->getDataFromTable("work_order","client_id","id='$woid'");
		$customerCity=$dbf->getDataFromTable("clients","city","id='$customerId' AND user_type='customer'");
		$customerPhno=$dbf->getDataFromTable("clients","phone_no","id='$customerId' AND user_type='customer'");
		//$customerPhno=$dbf->getDataFromTable("clients c","c.phone_no","w.id='$woid' AND c.user_type='customer'");
		$body=str_replace(array('TechName','CustomerCity','CustomerPhoneNo'),array($toName,$customerCity,$customerPhno),$message);
	}else{
		$body=str_replace(array('%Name%'),array($toName),$message);
	}
	//echo $body;exit;
	$headers = "MIME-Version: 1.0\n";
	$headers .= "Content-type: text/html; charset=UTF-8\n";
	$headers .= "From:".$fromname." <".$fromemail.">\n";
	//sleep(30);
	//echo $subject."==============".$body;exit;
	if(@mail($to,$subject,$body,$headers)){
		###########Track user activity in work order notes table#############
		$adminNotes= $subject." Notification send to tech from this work order.";
		$strnotes="workorder_id='$woid', user_type='$_SESSION[usertype]', user_id='$_SESSION[userid]', wo_notes='$adminNotes',created_date=now()";
		$dbf->insertSet("workorder_notes",$strnotes);
		###########Track user activity in work order notes table#############
		echo 1;exit;
	}else{
		echo 0;exit;
	}
	//Email Sending Starts here	
}elseif(isset($_REQUEST['choice'])&& $_REQUEST['choice']=="show_message"){
	if($_REQUEST['id']){
		$resmessage=$dbf->getDataFromTable("emails","message","id='$_REQUEST[id]'");
	}else{
		$resmessage=$dbf->getDataFromTable("email_template","message","id='18'");
	}
	echo $resmessage;exit;
}?>
    