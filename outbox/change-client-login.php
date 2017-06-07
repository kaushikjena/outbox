<?php 
ob_start();
session_start();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();
//page titlevariable
$pageTitle="Welcome To Out Of The Box";
include 'applicationtop.php';
if($_SESSION['userid']==''){
	header("location:logout");exit;
}
$res_viewClient=$dbf->fetchSingle("clients","id='$_REQUEST[id]'");
if(isset($_REQUEST['action']) && $_REQUEST['action']=="changeLogin" && $_SERVER['REQUEST_METHOD']=='POST'){
	//print_r($_REQUEST);exit;
	$ProfileEmailID =mysql_real_escape_string($_POST['ProfileEmailID']);
	$ProfilePassword =mysql_real_escape_string($_POST['ProfilePassword']);
	$ProfilePassword = base64_encode(base64_encode($ProfilePassword));
	$NewPassword =mysql_real_escape_string($_POST['NewPassword']);
	$ConfirmPassword =mysql_real_escape_string($_POST['ConfirmPassword']);
	
	if($ProfileEmailID ==''){
		header("Location:change-client-login?id=$_REQUEST[hid]");exit;
	}elseif(filter_var($ProfileEmailID,FILTER_VALIDATE_EMAIL)== FALSE){
		header("Location:change-client-login?id=$_REQUEST[hid]");exit;
	}
	//duplicate user Email Id Check
	$numuser = $dbf->countRows("clients","email='$ProfileEmailID' AND password ='$ProfilePassword'");
	if($numuser > 0){
		if(($NewPassword <>'') && ($NewPassword == $ConfirmPassword)){
			$NewPassword = base64_encode(base64_encode($NewPassword));
			$string="email='$ProfileEmailID', password='$NewPassword'";
			$dbf->updateTable("clients",$string,"id='$_REQUEST[hid]'");
			
			$clientData = $dbf->fetchSingle("clients","id='$_REQUEST[hid]'");
			//Email Sending Starts here
			  $res_template=$dbf->fetchSingle("email_template","id=7");
			  $from=$res_template['from_email'];
			  $from_name=$res_template['from_name'];  
			  $subject=$res_template['subject'];
			  $input=$res_template['message'];
			  
			  $to=$clientData['email'];
			  $toName=ucfirst($clientData['name']);
			  
			  $body=str_replace(array('%Name%','%EmailID%','%Password%'),array($toName,$to,$ConfirmPassword),$input);
			  $headers = "MIME-Version: 1.0\n";
			  $headers .= "Content-type: text/html; charset=UTF-8\n";
			  $headers .= "From:".$from_name." <".$from.">\n";
			  //echo $body;exit;
			  @mail($to,$subject,$body,$headers);
			//Email Sending End
		}
		header("Location:manage-client");exit;
	}
}
?>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />
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
                <!-------------Left menu--------------->
				<?php //include_once 'left-menu.php';?>
                <!-------------left menu--------------->
                <!-------------Main Body--------------->
                <div class="rightcolumjobboard">
            		<div class="rightcoluminner">
                        <div class="headerbg">CHANGE CLIENT LOGIN</div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        	<!-----Table area start------->
                        	<div  class="innertable">
                            <div align="center"><?php if($_REQUEST['msg']=="002"){?><span class="redText">This Email ID already exist!</span><?php } ?></div>
                            <div class="spacer"></div>
                         	  <form action="" name="frmProfile" id="frmProfile" method="post" onSubmit="return validate_clientlogin();" enctype="multipart/form-data">
                     		  <input type="hidden" name="action" value="changeLogin">
                              <input type="hidden" name="hid" value="<?php echo $_REQUEST['id'];?>">
                                <div  class="formtextadd">Email ID:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                   <input type="text" class="textbox" name="ProfileEmailID" id="ProfileEmailID" value="<?php echo $res_viewClient['email'];?>"><br/><label for="ProfileEmailID" id="lblProfileEmailID" class="redText"></label>
                                </div>
                                 <div class="spacer"></div>
                                <div  class="formtextadd">Password<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="ProfilePassword" id="ProfilePassword" value="<?php echo base64_decode(base64_decode($res_viewClient['password']));?>" readonly><br/><label for="ProfilePassword" id="lblProfilePassword" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">New Password:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="password" class="textbox" name="NewPassword" id="NewPassword" value="" ><br/><label for="NewPassword" id="lblNewPassword" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Confirm Pwd:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="password" class="textbox" name="ConfirmPassword" id="ConfirmPassword" value=""><br/><label for="ConfirmPassword" id="lblConfirmPassword" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                 <div align="center">
                                 <input type="submit" class="buttonText" value="Change Login Credentials"/>
                                 <a href="manage-client" style="text-decoration:none;"><input type="button" class="buttonText3" value="Back"/></a></div>
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