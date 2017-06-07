<?php 
ob_start();
session_start();
include_once '../includes/class.Main.php';
//Object initialization
$dbf = new User();
//page titlevariable
$pageTitle="Welcome To Out Of The Box";
include 'applicationtop-tech.php';
if($_SESSION['usertype']!='tech'){
	header("location:../logout");exit;
}
if($_REQUEST['action']=='change_password'){
	$password =base64_encode(base64_encode($_REQUEST['ProfilePassword'])); // Get password
	$string="password='$password'";
	if($_SESSION['usertype']=='tech'){
		$dbf->updateTable("technicians",$string,"id='$_SESSION[userid]'");
	}
	header("Location:tech-change-password?msg=001");exit;
}
?>
<link rel="stylesheet" href="../css/innermain.css" type="text/css" />
<link rel="stylesheet" href="../css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="../css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="../css/respmenu.css" type="text/css" />
<body>
    <div id="maindiv">
        <!-------------header--------------->
     	<?php include_once 'header-tech.php';?>
   		<!-------------header--------------->
        
        <!-------------top menu--------------->
     	<?php include_once 'tech-top-menu.php';?>
   		<!-------------top menu--------------->
         <div id="contentdiv">
                <!-------------Left menu--------------->
				<?php include_once 'tech-left-menu.php';?>
                <!-------------left menu--------------->
                <!-------------Main Body--------------->
                <div class="rightcolum">
            		<div class="rightcoluminner">
                        <div class="headerbg">TECHNICIAN CHANGE PASSWORD</div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        	<!-----Table area start------->
                        	<div  class="innertable">
                            <div align="center"><?php if($_REQUEST['msg']=="001"){?><span class="greenText">Password has been updated successfully.</span><?php } ?></div>
                            <div class="spacer"></div>
                              <form action="" name="frmPassword" id="frmPassword" method="post" onSubmit="return validate_password();" enctype="multipart/form-data">
                     		 	<input type="hidden" name="action" value="change_password">
                                <div  class="formtextadd">New Password:<span class="redText">*</span></div>
                                <div  class="textboxc">
                    			<input type="password" class="textbox" name="ProfilePassword" id="ProfilePassword" ><br/><label for="ProfilePassword" id="lblProfilePassword" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">ConfirmPassword:<span class="redText">*</span></div>
                                <div  class="textboxc">
                             	<input type="password" class="textbox" name="ProfileConfirmPassword" id="ProfileConfirmPassword"><br/>
                        		<label for="ProfileConfirmPassword" id="lblProfileConfirmPassword" class="redText"></label>
                               </div>
                               <div class="spacer"></div>
                                <div align="center">
                                 	<input type="submit" class="buttonText" value="Change Password"/>
                                </div>
                             </form>
                        	</div>
                            <!-----Table area start-------> 
                        	<div class="spacer"></div>
                    	</div>
            	</div>
              <!-------------Main Body--------------->
         </div>
        <div class="spacer"></div>
        <?php include_once '../footer.php'; ?>
    </div>
</body>
</html>