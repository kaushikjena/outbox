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
if(isset($_REQUEST['action']) && $_REQUEST['action']=="insert" && $_SERVER['REQUEST_METHOD']=='POST'){
	//$ProfilePassword=rand();
	$ProfilePassword=time();
	$ProfilePassword1=base64_encode(base64_encode($ProfilePassword));
	$ProfileUserName=mysql_real_escape_string(trim($_POST['ProfileUserName']));
	$ProfileCity=mysql_real_escape_string(trim($_POST['ProfileCity']));
	$ProfileState=mysql_real_escape_string(trim($_POST['ProfileState']));
	$ProfileEmailID =mysql_real_escape_string(trim($_POST['ProfileEmailID']));
	if($ProfileEmailID ==''){
		header("Location:add-client");exit;
	}elseif(filter_var($ProfileEmailID,FILTER_VALIDATE_EMAIL)== FALSE){
		header("Location:add-client");exit;
	}
	//duplicate user Email Id Check
	$numuser = $dbf->countRows("clients","email='$ProfileEmailID'");
	if($numuser > 0){
		header("Location:add-client?msg=002");exit;
	}else{
		//insert into clients table
		$ProfileContactName=mysql_real_escape_string(trim($_POST['ProfileContactName']));
		$ProfileAddress=mysql_real_escape_string(trim($_POST['ProfileAddress']));
		//get latitude and longitude
		$val = $dbf->getLnt($ProfileAddress.",".$ProfileCity.",".$ProfileState.",".$_POST['ProfileZipcode']);
		//string for insert into table
	 	$string="name='$ProfileUserName',contact_name='$ProfileContactName', user_type='client', email='$ProfileEmailID',password='$ProfilePassword1',phone_no='$_POST[ProfileMobile]',fax_no='$_POST[ProfileFaxno]',address='$ProfileAddress',city='$ProfileCity',state='$ProfileState',zip_code='$_POST[ProfileZipcode]',latitude='".$val['lat']."',longitude='".$val['lng']."',created_date=now(),created_by='$_SESSION[userid]'";
		$insid = $dbf->insertSet("clients",$string);
		//Email Sending Starts here
		  $res_template=$dbf->fetchSingle("email_template","id=7");
		  $from=$res_template['from_email'];
		  $from_name=$res_template['from_name'];  
		  $subject=$res_template['subject'];
		  $input=$res_template['message'];
		  $to=$ProfileEmailID;
		  $toName=ucfirst($_REQUEST['ProfileUserName']);
		  $body=str_replace(array('%Name%','%EmailID%','%Password%'),array($toName,$to,$ProfilePassword),$input);
		  $headers = "MIME-Version: 1.0\n";
		  $headers .= "Content-type: text/html; charset=UTF-8\n";
		  $headers .= "From:".$from_name." <".$from.">\n";
		  //echo $body;exit;
		  @mail($to,$subject,$body,$headers);
		//Email Sending End
		header("Location:manage-client");exit;
	}
}
?>
<script type="text/javascript">
$(document).ready(function(){
	$("form :input").each(function(){
	 if($(this).attr("id") !='SiteUrl'){
		  $(this).keyup(function(event){
			var xss =  $(this);
			var maintainplus = '';
			var numval = xss.val();
			curphonevar = numval.replace(/[\\!"£$%^&*+={};:'#~()¦\/<>?|`¬\]\[]/g,'');
			xss.val(maintainplus + curphonevar) ;
			var maintainplus = '';
			xss.focus;
		  });
	 }
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
                        <div class="headerbg">ADD CLIENTS</div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        	<!-----Table area start------->
                        	<div  class="innertable">
                            <div align="center"><?php if($_REQUEST['msg']=="002"){?><span class="redText">This Email ID already exist!</span><?php } ?></div>
                            <div class="spacer"></div>
                         	 <form action="" name="frmProfile" id="frmProfile" method="post" onSubmit="return validate_client();" enctype="multipart/form-data" autocomplete="off">
                     		 <input type="hidden" name="action" value="insert">
                                <div  class="formtextadd">Name:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="ProfileUserName" id="ProfileUserName" onKeyPress="return onlyLetters(event)"><br/><label for="ProfileUserName" id="lblProfileUserName" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Contact Name:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="ProfileContactName" id="ProfileContactName" onKeyPress="return onlyLetters(event)"><br/><label for="ProfileContactName" id="lblProfileContactName" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Email ID:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                   <input type="text" class="textbox" name="ProfileEmailID" id="ProfileEmailID"><br/><label for="ProfileEmailID" id="lblProfileEmailID" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Phone:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="ProfileMobile" id="ProfileMobile"  onKeyUp="validatephone(this);" maxlength="12"><br/><label for="ProfileMobile" id="lblProfileMobile" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Cell No:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="ProfileFaxno" id="ProfileFaxno" onKeyUp="return validatephone(this);" maxlength="12"><br/><label for="ProfileFaxno" id="lblProfileFaxno" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Address:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                   <textarea class="textarea" name="ProfileAddress" id="ProfileAddress"></textarea><br/><label for="ProfileAddress" id="lblProfileAddress" class="redText"></label>
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
                                    <?php foreach($dbf->fetch("state","id>0 ORDER BY state_code ASC") as $vstate){?>
                                    <option value="<?php echo $vstate['state_code'];?>"><?php echo $vstate['state_name'];?></option>
                                    <?php }?>
                                  </select>
                                  <br/><label for="ProfileState" id="lblProfileState" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Zip Code:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="ProfileZipcode" id="ProfileZipcode" maxlength="10"><br/><label for="ProfileZipcode" id="lblProfileZipcode" class="redText"></label>
                                </div>
                                <!--<div class="spacer"></div>
                                <div  class="formtextadd">Location:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                   <textarea class="textarea" name="ProfileLocation" id="ProfileLocation"></textarea><br/><label for="ProfileLocation" id="lblProfileLocation" class="redText"></label>
                                </div>-->
                                 <div class="spacer"></div>
                                 <div align="center">
                                 	<input type="submit" class="buttonText" value="Submit Form"/>&nbsp;&nbsp;
                                   <a href="manage-client" style="text-decoration:none;"><input type="button" class="buttonText3" value="Back"/></a>
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
    </div>
</body>
</html>