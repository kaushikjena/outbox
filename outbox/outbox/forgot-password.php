<?php 
ob_start();
session_start();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();
//page titlevariable
$pageTitle="Welcome To Out Of The Box";

if($_REQUEST['action']=="forgotpassword")
{	
	//for sql injection
	$useremail=$_REQUEST['ForgotEmail'];
	$useremail = stripslashes($useremail);
	$useremail = mysql_real_escape_string($useremail);
	
	$num=$dbf->countRows('admin',"email='$useremail'");
	if($num>0){
		$res_forget=$dbf->fetchSingle('admin',"email='$useremail'");
		//$email_confirm=$res_forget['email_confirm'];
		$active_status=$res_forget['status'];
		if($active_status==1){
			$password= base64_decode(base64_decode($res_forget['password']));
			/*Email sending start*/
			$res_template=$dbf->fetchSingle("email_template","id='2'");
			$from=$res_template['from_email'];
			$from_name=$res_template['from_name'];
			$subject=$res_template['subject'];
			$input=$res_template['message'];
			$to=$_REQUEST['ForgotEmail'];
			$toName=ucfirst($res_forget['user_type']);
			$email_body = str_replace(array('%Name%','%Password%'),array($toName,$password ),$input);
			$headers = "MIME-Version: 1.0\n";
			$headers .= "Content-type: text/html; charset=UTF-8\n";
			$headers .= "From:".$from_name." <".$from.">\n";
			$body=$email_body;
			//echo $body;exit;
			@mail($to,$subject,$body,$headers);
			/*Email sending end*/
			header("Location:forgot-password?msg=001");exit;
		}else{
			header("Location:forgot-password?msg=003");exit;
		}
	}else{
		header("Location:forgot-password?msg=002");exit;
	}
}
?>
<?php  include_once "applicationtop.php";?>
<link rel="stylesheet" href="css/main.css" type="text/css" />
<link rel="stylesheet" href="css/medium.css" type="text/css" />
<link rel="stylesheet" href="css/narrow.css" type="text/css" />
<link rel="stylesheet" href="css/narrower.css" type="text/css" />
<body>
<div>
	<div>
	   <div class="logo"><img src="images/logo1.png" /></div>
	</div>
	<div class="spacer"></div>
     <form action="" method="post" name="frmForgot" id="frmForgot" onSubmit="return validate_forgotpassword();">
      <input type="hidden" name="action" value="forgotpassword"/>
        <div class="logbg">
          <div class="logleft"><img src="images/logboxbg.png" /></div>
          <div class="logright">
              <?php if($_REQUEST['msg']=='001'){?><p class="greenText">Your password has been send to your email id.You can check your email now.</p><?php }?>
              <?php if($_REQUEST['msg']=='002'){ ?><p class="redText">You have enter invalid email id !</p><?php }?>
              <?php if($_REQUEST['msg']=='003'){ ?><p class="redText">Your account may be blocked or not approved by admin !!!</p><?php }?>
            <div class="idtext">Email ID</div>
            <div class="tfcon">
              <input type="text" class="logintxtbox" name="ForgotEmail" id="ForgotEmail"><br/>
              <label for="ForgotEmail" id="lblForgotEmail" class="redText"></label>
            </div>
            <div class="spacer"></div><div class="spacer"></div>
            <div class="subcon">
            
              <div class="spacer"></div>
              <div class="subbtn"><input type="image" src="images/submit.png" /></div>
              <div class="pswtext"><img src="images/cancel.png" style="cursor:pointer;"  onClick="window.location.href='index.php'"></div>
            </div>
            <div class="spacer"></div>
            <div class="spacer" style="padding-top:20px;"></div>
          </div>
      </div>
  </form>
<div class="spacer"></div>
<?php include_once 'footer.php'; ?>
</div>
</body>
</html>
