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
if($_REQUEST['action']=="insert"){
	//Email sending starts here
	  $res_template=$dbf->fetchSingle("email_template","id=1");
      $from=$res_template['from_email'];
	  $from_name=$res_template['from_name'];  
      $subject=$res_template['subject'];
	  $input=$res_template['message'];
	  $to=$_REQUEST['ProfileEmailID'];
	  $toName=ucfirst($_REQUEST['ProfileUserName']);
	  $password=$_REQUEST['ProfilePassword'];
	  $email_body=str_replace(array('%Name%','%EmailID%','%Password%'),array($toName,$to,$password),$input);
	  $headers = "MIME-Version: 1.0\n";
	  $headers .= "Content-type: text/html; charset=UTF-8\n";
	  $headers .= "From:".$from_name." <".$from.">\n";
	  $body=$email_body;
	  //echo $body;exit;
	  @mail($to,$subject,$body,$headers);
     /*Email sending end*/
	$ProfileUserName=addslashes($_POST['ProfileUserName']);
	$ProfileCity=addslashes($_POST['ProfileCity']);
	$ProfileState=addslashes($_POST['ProfileState']);
	//duplicate user Email Id Check
	$numuser = $dbf->countRows("users","email='$_REQUEST[ProfileEmailID]'");
	if($numuser > 0){
		header("Location:add-user?msg=002");exit;
	}else{
		$ProfilePassword = base64_encode(base64_encode($_POST['ProfilePassword']));
		$file_name=$_FILES['ProfilePhoto']['name'];
		if($file_name <>''){
			$path="user_photo/";
			move_uploaded_file($_FILES['ProfilePhoto']['tmp_name'],$path.$file_name);
		}else{
			$file_name="";
		}
		//insert into users table
		$string="name='$ProfileUserName', user_type='user', email='$_POST[ProfileEmailID]', password='$ProfilePassword', mobile='$_POST[ProfileMobile]', city='$ProfileCity', state='$ProfileState',user_photo='$file_name', created_date=now()";
		$insid = $dbf->insertSet("users",$string);
		header("Location:manage-user");exit;
	}
}
?>
<script type="text/javascript">
$(document).ready(function(){
	$("form :input").each(function(){
	 //if($(this).attr("id") !='SiteUrl'){
		  $(this).keyup(function(event){
			var xss =  $(this);
			var maintainplus = '';
			var numval = xss.val();
			curphonevar = numval.replace(/[\\!"£$%^&*+={};:'#~()¦\/<>?|`¬\]\[]/g,'');
			xss.val(maintainplus + curphonevar) ;
			var maintainplus = '';
			xss.focus;
		  });
	// }
	});
});
</script>
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
				<?php //include_once 'left-menu.php';?>
                <!-------------left menu--------------->
                <!-------------Main Body--------------->
                <div class="rightcolumjobboard">
            		<div class="rightcoluminner">
                        <div class="headerbg">ADD USER</div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        	<!-----Table area start------->
                        	<div  class="innertable">
                            <div align="center"><?php if($_REQUEST['msg']=="002"){?><span class="redText">This Email ID already exist!</span><?php } ?></div>
                            <div class="spacer"></div>
                             <form action="" name="frmProfile" id="frmProfile" method="post" onSubmit="return validate_user();" enctype="multipart/form-data" autocomplete="off">
                     		<input type="hidden" name="action" value="insert">
                                <div  class="formtextadd">User Name:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="ProfileUserName" id="ProfileUserName" onKeyPress="return onlyLetters(event)"><br/><label for="ProfileUserName" id="lblProfileUserName" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Email ID:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="ProfileEmailID" id="ProfileEmailID"><br/><label for="ProfileEmailID" id="lblProfileEmailID" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Password:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                   <input type="password" class="textbox" name="ProfilePassword" id="ProfilePassword"><br/><label for="ProfilePassword" id="lblProfilePassword" class="redText"></label>
                                </div>
                                 <div class="spacer"></div>
                                <div  class="formtextadd">Mobile No:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="ProfileMobile" id="ProfileMobile"  onKeyUp="validatephone(this);" maxlength="12"><br/><label for="ProfileMobile" id="lblProfileMobile" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">City:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="ProfileCity" id="ProfileCity" onKeyPress="return onlyLetters(event)"><br/><label for="ProfileCity" id="lblProfileCity" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">State:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                   <select class="selectbox" name="ProfileState" id="ProfileState">
                                  	<option value="">--Select State--</option>
                                    <?php foreach($dbf->fetch("state","") as $vstate){?>
                                    <option value="<?php echo $vstate['state_code'];?>"><?php echo $vstate['state_name'];?></option>
                                    <?php }?>
                                  </select>
                                   <br/><label for="ProfileState" id="lblProfileState" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Profile Photo:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="file" class="textbox" name="ProfilePhoto" id="ProfilePhoto"><br/><label for="ProfilePhoto" id="lblProfilePhoto" class="redText"></label>
                                </div>
                                 <div class="spacer"></div>
                                 <div align="center">
                                 	<input type="submit" class="buttonText" value="Submit Form"/>
                                    <a href="manage-user" style="text-decoration:none;"><input type="button" class="buttonText3" value="Back"/></a>
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
        <?php include_once 'footer.php'; ?>
    </div>
</body>
</html>