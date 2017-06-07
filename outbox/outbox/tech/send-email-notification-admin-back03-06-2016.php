<?php 
ob_start();
session_start();
include_once '../includes/class.Main.php';
//Object initialization
$dbf = new User();

if(isset($_REQUEST['choice']) && $_REQUEST['choice']=="show_email"){ 
$wo_no=$dbf->getDataFromTable("work_order","wo_no","id='$_REQUEST[woid]'");
$resTech = $dbf->fetchSingle("technicians t,assign_tech at","at.wo_no='$wo_no' AND t.id=at.tech_id");

//print'<pre>';print_r($resTech);exit;
$res_template=$dbf->fetchSingle("email_template","id='23'");
?>
<!--wysiwyg editor-->
<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
<!-- <script type="text/javascript" src="ckeditor/sample.js"></script>-->
<link rel="stylesheet" type="text/css" href="../ckeditor/sample.css" />
<!--wysiwyg editor-->
 <div id="maindiv">
     <div  style="margin:2px;">
          <!-------------Main Body--------------->
            <div class="technicianjobboard" style="width:700px;">
                <div class="rightcoluminner">
                    <div class="headerbg">Send Notification To Admin</div>
                    <div class="spacer"></div>
                    <div id="contenttable">
                        <!-----Table area start------->
                        <div  class="emailDiv">
                        <div class="spacer"></div>
                         <form action="" name="frmEmailTech" id="frmEmailTech" method="post" enctype="multipart/form-data">
                         	<div  class="formtextadd">From Email:<span class="redText">*</span></div>
                            <div  class="textboxc">
                             <input type="text" class="textboxjob" name="fromemail" id="fromemail" value="<?php echo $resTech['email'];?>" readonly="readonly"><br/><label for="fromemail" id="lblfromemail" class="redText"></label>
                            </div>
                            <div class="spacer"></div>
                            <div  class="formtextadd">From Name:<span class="redText">*</span></div>
                            <div  class="textboxc">
                               <input type="text" class="textboxjob" name="fromname" id="fromname" value="<?php echo $resTech['first_name'].'&nbsp;'.$resTech['middle_name'].'&nbsp;'.$resTech['last_name'];?>" readonly="readonly">
                               <br/><label for="fromname" id="lblfromname" class="redText"></label></div>
                            <div class="spacer"></div>
                            <div  class="formtextadd">Subject:<span class="redText">*</span></div>
                            <div  class="textboxc">
                            <input type="text" class="textboxjob" name="subject" id="subject" value="<?php echo $res_template['subject'];?>" readonly="readonly">
                            <br/><label for="subject" id="lblsubject" class="redText"></label>
                            </div>
                            <div class="spacer"></div>
                            <div  class="formtextadd">Message :<span class="redText">*</span></div>
                            <div  class="textboxc" style="width:500px; color:#093;">&nbsp;(You can change the contents except the variable name within %% symbol.)</div>
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
                             <input type="hidden" name="wono" id="wono" value="<?php echo $wo_no; ?>"/>
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
	########get tech name for subject line###############
	//Email Sending Starts here
		$admin_notification = $dbf->getDataFromTable("admin_email_notification","status","id=9");
		$admin_email = $dbf->getDataFromTable("admin_email_notification","to_email","id=9");
		if($admin_notification ==1){
			$fromemail=addslashes($_REQUEST['fromemail']);
			$fromname=addslashes($_REQUEST['fromname']);
			$subject=addslashes($_REQUEST['subject']);
			$message=$_REQUEST['message'];
			$resAdminDetails = $dbf->fetchSingle("admin","id='1'");
			$toAdminName=$resAdminDetails['name'];
			//$toEmail=$resAdminDetails['email'];
			$toEmail=$admin_email;
			$toTechName = ucfirst($resTech['first_name']).'&nbsp;'.$resTech['middle_name'].'&nbsp;'.$resTech['last_name'];
			//Email Sending Starts here
			$body=str_replace(array('%Administrator%','%WorkOrder%','%TechName%'),array($toAdminName,$wono,$toTechName),$message);
			//echo $body;exit;
			$headers = "MIME-Version: 1.0\n";
			$headers .= "Content-type: text/html; charset=UTF-8\n";
			$headers .= "From:".$fromname." <".$fromemail.">\n";
			//sleep(30);
			//echo $subject."=============".$fromemail."==========".$fromname."=============".$toEmail."==============".$body;exit;
			if(@mail($toEmail,$subject,$body,$headers)){
				$dbf->updateTable("work_order","reschedule_status=1","id='$woid'");
				echo 1;exit;
			}else{
				echo 0;exit;
			}
		}
}?>
    