<?php 
ob_start();
session_start();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();
if(isset($_REQUEST['choice']) && $_REQUEST['choice']=="show_email"){ 
  //Get Data From Admin Email Notification table and send Email to Admin
  $resAdminEmail=$dbf->fetchSingle("admin_email_notification","id='$_REQUEST[id]'");
  if($resAdminEmail['id']==1){
      $res_template=$dbf->fetchSingle("email_template","id=16");
  }else if($resAdminEmail['id']==2){
      $res_template=$dbf->fetchSingle("email_template","id=8");
  }else if($resAdminEmail['id']==3){
      $res_template=$dbf->fetchSingle("email_template","id=10");
  }else if($resAdminEmail['id']==4){
	  $res_template=$dbf->fetchSingle("email_template","id=11");
  }else if($resAdminEmail['id']==5){
      $res_template=$dbf->fetchSingle("email_template","id=14");
  }else if($resAdminEmail['id']==6){
      $res_template=$dbf->fetchSingle("email_template","id=10");
  }else if($resAdminEmail['id']==7){
      $res_template=$dbf->fetchSingle("email_template","id=19");
  }else if($resAdminEmail['id']==8){
      $res_template=$dbf->fetchSingle("email_template","id=20");
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
            <div class="technicianjobboard" style="width:900px;">
                <div class="rightcoluminner">
                    <div class="headerbg">Send Notification To Admin</div>
                    <div class="spacer"></div>
                    <div id="contenttable">
                        <!-----Table area start------->
                        <div  class="emailDiv">
                        <div class="spacer"></div>
                         <form action="" name="frmEmailAdmin" id="frmEmailAdmin" method="post" enctype="multipart/form-data">
                         	<div  class="formtextadd">From Email:<span class="redText">*</span></div>
                            <div  class="textboxc">
                             <input type="text" class="textboxjob" name="fromemail" id="fromemail" value="<?php echo $res_template['from_email'];?>"><br/><label for="fromemail" id="lblfromemail" class="redText"></label>
                            </div>
                            <div class="spacer"></div>
                            <div  class="formtextadd">From Name:<span class="redText">*</span></div>
                            <div  class="textboxc">
                               <input type="text" class="textboxjob" name="fromname" id="fromname" value="<?php echo $res_template['from_name'];?>">
                               <br/><label for="fromname" id="lblfromname" class="redText"></label></div>
                            <div class="spacer"></div>
                            <div  class="formtextadd">Subject:<span class="redText">*</span></div>
                            <div  class="textboxc">
                            <input type="text" class="textboxjob" name="subject" id="subject" value="<?php echo $res_template['subject'];?>">
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
                                       extraPlugins : 'autogrow',
                                        autoGrow_maxHeight : 400
                                        //height :300,
                                        //width : 800
                                    });
                            </script>
                            </div>
                            <div class="spacer"></div>
                             <div align="center">
                             <input type="hidden" name="admtemp_id" id="admtemp_id" value="<?php echo  $resAdminEmail['id']; ?>"/>
                             <input type="hidden" name="emptemp_id" id="emptemp_id" value="<?php echo  $res_template['id']; ?>"/>
                             <input type="button" class="buttonText" value="Update" onClick="updateAdminEmail();"/></div>
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
<?php }else if(isset($_REQUEST['choice'])&& $_REQUEST['choice']=="update_email"){
	ob_clean();//print "<pre>";print_r($_REQUEST);exit;
	$admtempid=addslashes($_REQUEST['admtempid']);
	$emptempid=addslashes($_REQUEST['emptempid']);
	$fromemail=addslashes($_REQUEST['fromemail']);
	$fromname=addslashes($_REQUEST['fromname']);
	$subject=addslashes($_REQUEST['subject']);
	$message=addslashes($_REQUEST['message']);
	$stringemltmp="from_email='$fromemail',from_name='$fromname',subject='$subject',message='$message',created_date=now()";
	$dbf->updateTable("email_template",$stringemltmp,"id='$emptempid'");
	echo 1;exit;
}?>
    