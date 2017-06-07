<?php 
ob_start();
session_start();
include_once '../includes/class.Main.php';
//Object initialization
$dbf = new User();
//page titlevariable
$pageTitle="Welcome To Out Of The Box";
include 'applicationtop-client.php';
if($_SESSION['usertype']!='client'){
	header("location:../logout");exit;
}
$res_viewClient=$dbf->fetchSingle("clients","id='$_SESSION[userid]'");
if($_REQUEST['action']=="update"){
	$ProfileUserName=addslashes($_POST['ProfileUserName']);
	$ProfileCity=addslashes($_POST['ProfileCity']);
	$ProfileState=addslashes($_POST['ProfileState']);
	//duplicate client Email Id Check
	$numuser = $dbf->countRows("clients","email='$_REQUEST[ProfileEmailID]' AND id<>'$_REQUEST[hid]'");
	if($numuser > 0){
		header("Location:client-edit-profile?msg=002&id='$_REQUEST[hid]'");exit;
	}else{
		//update into clients table
	 	$string="name='$ProfileUserName',contact_name='$_POST[ProfileContactName]', user_type='client', email='$_POST[ProfileEmailID]', phone_no='$_POST[ProfileMobile]', fax_no='$_POST[ProfileFaxno]', address='$_POST[ProfileAddress]', city='$ProfileCity', state='$ProfileState', zip_code='$_POST[ProfileZipcode]', created_date=now()";
		$insid = $dbf->updateTable("clients",$string,"id='$_REQUEST[hid]'");
		header("Location:client-edit-profile?msg=001");exit;
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
	 //}
	});
});
</script>
<link rel="stylesheet" href="../css/innermain.css" type="text/css" />
<link rel="stylesheet" href="../css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="../css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="../css/respmenu.css" type="text/css" />
<link rel="stylesheet" href="../css/table.css" type="text/css" />
<body>
    <div id="maindiv">
        <!-------------header--------------->
     	<?php include_once 'header-client.php';?>
   		<!-------------header--------------->
        
        <!-------------top menu--------------->
     	<?php include_once 'client-top-menu.php';?>
   		<!-------------top menu--------------->
         <div id="contentdiv">
                <!-------------Left menu--------------->
				<?php include_once 'client-left-menu.php';?>
                <!-------------left menu--------------->
                <!-------------Main Body--------------->
                <div class="rightcolum">
            		<div class="rightcoluminner">
                        <div class="headerbg">CLIENT EDIT PROFILE</div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        	<!-----Table area start------->
                        	<div  class="innertable">
                            <div align="center"><?php if($_REQUEST['msg']=="002"){?><span class="redText">This Email ID already exist!</span><?php } ?></div>
                            <div align="center"><?php if($_REQUEST['msg']=="001"){?><span class="greenText">Profile has been updated successfully.</span><?php } ?></div>
                            <div class="spacer"></div>
                         	  <form action="" name="frmProfile" id="frmProfile" method="post" onSubmit="return validate_editclient();" enctype="multipart/form-data" autocomplete="off">
                     		  <input type="hidden" name="action" value="update">
                              <input type="hidden" name="hid" value="<?php echo $_SESSION['userid'];?>">
                                <div  class="formtextadd">Name:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="ProfileUserName" id="ProfileUserName" value="<?php echo $res_viewClient['name'];?>"><br/><label for="ProfileUserName" id="lblProfileUserName" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Contact Name:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="ProfileContactName" id="ProfileContactName" value="<?php echo $res_viewClient['contact_name'];?>"><br/><label for="ProfileContactName" id="lblProfileContactName" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Email ID:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                   <input type="text" class="textbox" name="ProfileEmailID" id="ProfileEmailID" value="<?php echo $res_viewClient['email'];?>"><br/><label for="ProfileEmailID" id="lblProfileEmailID" class="redText"></label>
                                </div>
                                 <div class="spacer"></div>
                                <div  class="formtextadd">Phone:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="ProfileMobile" id="ProfileMobile"  onKeyUp="return validatephone(this);" maxlength="12" value="<?php echo $res_viewClient['phone_no'];?>"><br/><label for="ProfileMobile" id="lblProfileMobile" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Fax No:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="ProfileFaxno" id="ProfileFaxno" value="<?php echo $res_viewClient['fax_no'];?>" onKeyUp="return validatephone(this);" maxlength="12"><br/><label for="ProfileFaxno" id="lblProfileFaxno" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Address:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                   <textarea class="textarea" name="ProfileAddress" id="ProfileAddress"><?php echo $res_viewClient['address'];?></textarea><br/><label for="ProfileAddress" id="lblProfileAddress" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">City:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="ProfileCity" id="ProfileCity" value="<?php echo $res_viewClient['city'];?>"><br/><label for="ProfileCity" id="lblProfileCity" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">State:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <select class="selectbox" name="ProfileState" id="ProfileState">
                                    <option>--select state--</option>
                                    <?php foreach($dbf->fetch("state","")as $vstate){?>
                                    <option value="<?php echo $vstate['state_code'];?>" <?php if($res_viewClient['state']==$vstate['state_code']){echo 'selected';}?>><?php echo $vstate['state_name'];?></option>
                                    <?php }?>
                                    </select>
                                    <br/><label for="ProfileState" id="lblProfileState" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Zip Code:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="ProfileZipcode" id="ProfileZipcode" value="<?php echo $res_viewClient['zip_code'];?>"><br/><label for="ProfileZipcode" id="lblProfileZipcode" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <!--<div  class="formtextadd">Location:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <textarea class="textarea" name="ProfileLocation" id="ProfileLocation"><?php //echo $res_viewClient['location'];?></textarea><br/><label for="ProfileLocation" id="lblProfileLocation" class="redText"></label>
                                </div>
                                 <div class="spacer"></div>-->
                                 <div align="center">
                                 	<input type="submit" class="buttonText" value="Submit Form"/>
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
        <?php include_once 'footer-client.php'; ?>
    </div>
</body>
</html>