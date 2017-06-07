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
if(isset($_REQUEST['action']) && $_REQUEST['action']=="update" && $_SERVER['REQUEST_METHOD']=='POST'){
	$ProfileUserName=mysql_real_escape_string($_POST['ProfileUserName']);
	$ProfileCity=mysql_real_escape_string($_POST['ProfileCity']);
	$ProfileState=mysql_real_escape_string($_POST['ProfileState']);
	$ProfileEmailID =mysql_real_escape_string($_POST['ProfileEmailID']);
	if($ProfileEmailID ==''){
		header("Location:edit-client?id=$_REQUEST[hid]");exit;
	}elseif(filter_var($ProfileEmailID,FILTER_VALIDATE_EMAIL)== FALSE){
		header("Location:edit-client?id=$_REQUEST[hid]");exit;
	}
	//duplicate user Email Id Check
	$numuser = $dbf->countRows("clients","email='$ProfileEmailID' AND id<>'$_REQUEST[hid]'");
	if($numuser > 0){
		header("Location:edit-client?msg=002&id=$_REQUEST[hid]");exit;
	}else{
		//insert into clients table
		$ProfileContactName=mysql_real_escape_string($_POST['ProfileContactName']);
		$ProfileAddress=mysql_real_escape_string($_POST['ProfileAddress']);
		//get latitude and longitude
		$val = $dbf->getLnt($ProfileAddress.",".$ProfileCity.",".$ProfileState.",".$_POST['ProfileZipcode']);
		//string for update table
	 	$string="name='$ProfileUserName',contact_name='$ProfileContactName',email='$ProfileEmailID', phone_no='$_POST[ProfileMobile]', fax_no='$_POST[ProfileFaxno]', address='$ProfileAddress', city='$ProfileCity', state='$ProfileState', zip_code='$_POST[ProfileZipcode]', status='$_POST[chkStatus]',latitude='".$val['lat']."',longitude='".$val['lng']."', updated_date=now(),updated_by='$_SESSION[userid]'";
		$dbf->updateTable("clients",$string,"id='$_REQUEST[hid]'");
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
                        <div class="headerbg">EDIT CLIENT</div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        	<!-----Table area start------->
                        	<div  class="innertable">
                            <div align="center"><?php if($_REQUEST['msg']=="002"){?><span class="redText">This Email ID already exist!</span><?php } ?></div>
                            <div class="spacer"></div>
                         	  <form action="" name="frmProfile" id="frmProfile" method="post" onSubmit="return validate_editclient();" enctype="multipart/form-data" autocomplete="off">
                     		  <input type="hidden" name="action" value="update">
                              <input type="hidden" name="hid" value="<?php echo $_REQUEST['id'];?>">
                                <div  class="formtextadd">Name:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="ProfileUserName" id="ProfileUserName" value="<?php echo $res_viewClient['name'];?>" onKeyPress="return onlyLetters(event)"><br/><label for="ProfileUserName" id="lblProfileUserName" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Contact Name:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="ProfileContactName" id="ProfileContactName" value="<?php echo $res_viewClient['contact_name'];?>" onKeyPress="return onlyLetters(event)"><br/><label for="ProfileContactName" id="lblProfileContactName" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Email ID:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                   <input type="text" class="textbox" name="ProfileEmailID" id="ProfileEmailID" value="<?php echo $res_viewClient['email'];?>"><br/><label for="ProfileEmailID" id="lblProfileEmailID" class="redText"></label>
                                </div>
                                 <div class="spacer"></div>
                                <div  class="formtextadd">Phone:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="ProfileMobile" id="ProfileMobile"  onKeyUp="validatephone(this);" maxlength="12" value="<?php echo $res_viewClient['phone_no'];?>"><br/><label for="ProfileMobile" id="lblProfileMobile" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Cell No:<span class="redText">*</span></div>
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
                                    <input type="text" class="textbox" name="ProfileCity" id="ProfileCity" value="<?php echo $res_viewClient['city'];?>" onKeyPress="return onlyLetters(event)"><br/><label for="ProfileCity" id="lblProfileCity" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">State:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                 <select class="selectbox" name="ProfileState" id="ProfileState">
                                  	<option value="">--Select State--</option>
                                    <?php foreach($dbf->fetch("state","id>0 ORDER BY state_code ASC") as $vstate){?>
                                    <option value="<?php echo $vstate['state_code'];?>" <?php if($res_viewClient['state']==$vstate['state_code']){echo 'selected';}?>><?php echo $vstate['state_name'];?></option>
                                    <?php }?>
                                 </select>
                                 <br/><label for="ProfileState" id="lblProfileState" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Zip Code:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="ProfileZipcode" id="ProfileZipcode" value="<?php echo $res_viewClient['zip_code'];?>" maxlength="10"><br/><label for="ProfileZipcode" id="lblProfileZipcode" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Active Status:</div>
                                <div  class="textboxc">
                                    <input type="checkbox"  name="chkStatus" id="chkStatus" value="1"<?php if( $res_viewClient['status']=='1'){echo 'checked';}?>>
                                </div>
                                <!--<div  class="formtextadd">Location:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <textarea class="textarea" name="ProfileLocation" id="ProfileLocation"><?php //echo $res_viewClient['location'];?></textarea><br/><label for="ProfileLocation" id="lblProfileLocation" class="redText"></label>
                                </div>
                                 <div class="spacer"></div>-->
                                 <div align="center">
                                 	<input type="submit" class="buttonText" value="Submit Form"/>
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
</body>
</html>