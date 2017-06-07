<?php 
ob_start();
session_start();
include_once 'includes/class.Main.php';

//Object initialization
$dbf = new User();
//page titlevariable
$pageTitle="Welcome To Out Of The Box";
include 'applicationtop.php';
//logout for users other than admin and user
if($_SESSION['usertype']!='admin' && $_SESSION['usertype']!='user'){
	header("location:logout");exit;
}
//$res_sms=$dbf->fetchSingle("sms_template","id='1'");
$res_sms=$dbf->fetchSingle("sms_template","id='$_REQUEST[id]'");
if($_REQUEST['action']=="update"){
	    $num=$dbf->countRows("sms_template","id='$_REQUEST[hid]'");
		if($num>0){
			$string="subject='$_REQUEST[txtSubject]',message='$_REQUEST[txaMessage]'";
			//update into sms_template table
			$dbf->updateTable("sms_template",$string,"id='$_REQUEST[hid]'");
		}else{
			$string="subject='$_REQUEST[txtSubject]',message='$_REQUEST[txaMessage]',created_date=now()";
			//insert into sms_template table
			$dbf->insertSet("sms_template",$string);
		}
		header("Location:tech-alert-sms?id=$_REQUEST[hid]&msg=01");exit;
}
?>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />
<body>
    <div id="maindiv">
        <!-------------header--------------->
     	<?php include_once 'header.php';?>
   		<!-------------header--------------->
        
        <!-------------top menu--------------->
     	<?php include_once 'top-menu.php';?>
   		<!-------------top menu--------------->
         <div id="contentdiv">
                <!-------------Left menu--------------->
				<?php include_once 'left-menu.php';?>
                <!-------------left menu--------------->
                <!-------------Main Body--------------->
                <div class="rightcolum">
            		<div class="rightcoluminner">
                        <div class="headerbg">Tech SMS Template</div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        	<!-----Table area start------->
                            <div  class="innertable">
                            <div align="center" class="greenText"><?php if($_REQUEST['msg']=='01'){echo 'SMS Template updated successfully';}?></div>
                            <div class="spacer"></div>
                         	  <form action="" name="frmModule" id="frmModule" method="post" onSubmit="return validate_techsms();" autocomplete="off">
                              <input type="hidden" name="action" value="update">
                              <input type="hidden" name="hid" value="<?php echo $res_sms['id'];?>">
                              <div  class="formtextadd">Subject<span class="redText">*</span></div>
                              <div  class="textboxc">
                                <input type="text" class="textboxjob" name="txtSubject" id="txtSubject" value="<?php echo $res_sms['subject']?>"><br/><label for="txtSubject" id="lbltxtSubject" class="redText"></label>
                               </div>
                              <div class="spacer"></div>
                              <div  class="formtextadd">Message<span class="redText">*</span></div>
                              <div  class="textboxc">
                                <textarea class="textareajob" name="txaMessage" id="txaMessage" style="height:80px;"><?php echo $res_sms['message']?></textarea><br/><label for="txaMessage" id="lbltxaMessage" class="redText"></label>
                              </div>
                              <div class="spacer"></div>
                              <div align="center"><input type="submit" class="buttonText" value="Submit Form"/>&nbsp;&nbsp; <input type="button" class="buttonText" value="Return Back" onClick="javascript:window.location.href='manage-tech-sms-template'"/></div>
                              </form>
                        	</div>
                            <!-----Table area start-------> 
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