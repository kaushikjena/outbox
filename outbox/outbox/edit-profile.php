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
if($_SESSION['usertype']=='admin'){
	$res_admin=$dbf->fetchSingle("admin","id='$_SESSION[userid]'");
}elseif($_SESSION['usertype']=='user'){
	$res_admin=$dbf->fetchSingle("users","id='$_SESSION[userid]'");
}
if($_REQUEST['action']=="update"){
	$user_name=addslashes($_POST['ProfileUserName']);
	if($_SESSION['usertype']=='admin'){
		$string="name='$user_name', email='$_POST[ProfileEmailID]', mobile='$_REQUEST[ProfileMobile]', city='$_POST[ProfileCity]', state='$_POST[ProfileState]',site_url='$_POST[SiteUrl]', created_date=now()";
		$dbf->updateTable("admin",$string,"id='$_SESSION[userid]'");
	}elseif($_SESSION['usertype']=='user'){
		$string="name='$user_name', email='$_POST[ProfileEmailID]', mobile='$_REQUEST[ProfileMobile]', city='$_POST[ProfileCity]', state='$_POST[ProfileState]', created_date=now()";
		$dbf->updateTable("users",$string,"id='$_SESSION[userid]'");
	}
	header("Location:edit-profile?msg=001");
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
				<?php include_once 'left-menu.php';?>
                <!-------------left menu--------------->
                <!-------------Main Body--------------->
                <div class="rightcolum">
            		<div class="rightcoluminner">
                        <div class="headerbg">EDIT PROFILE</div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        	<!-----Table area start------->
                        	<div  class="innertable">
                            <div align="center"><?php if($_REQUEST['msg']=="001"){?><span class="greenText">Profile has been updated successfully.</span><?php } ?></div>
                            <div class="spacer"></div>
                         	  <form action="" name="frmProfile" id="frmProfile" method="post" onSubmit="return validate_profile();" enctype="multipart/form-data" autocomplete="off">
                              <input type="hidden" name="action" value="update">
                                <div  class="formtextadd">User Name:<span class="redText">*</span></div>
                                <div  class="textboxc">
                    			<input type="text" class="textbox" name="ProfileUserName" id="ProfileUserName" value="<?php echo stripslashes($res_admin['name']);?>"><br/><label for="ProfileUserName" id="lblProfileUserName" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Email ID:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="ProfileEmailID" id="ProfileEmailID" value="<?php echo $res_admin['email'];?>"><br/><label for="ProfileEmailID" id="lblProfileEmailID" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Mobile No:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="ProfileMobile" id="ProfileMobile" value="<?php echo $res_admin['mobile'];?>" onKeyUp="return validatephone(this);" maxlength="12"><br/><label for="ProfileMobile" id="lblProfileMobile" class="redText"></label>           
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">City:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="ProfileCity" id="ProfileCity" value="<?php echo $res_admin['city'];?>"><br/><label for="ProfileCity" id="lblProfileCity" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">State:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                <?php if($_SESSION['usertype']=='admin'){?>
                                    <input type="text" class="textbox" name="ProfileState" id="ProfileState" value="<?php echo $res_admin['state'];?>"><br/><label for="ProfileState" id="lblProfileState" class="redText"></label>
                                <?php }elseif($_SESSION['usertype']=='user'){?>
                                  <select class="selectbox" name="ProfileState" id="ProfileState">
                                  	<option value="">--Select State--</option>
                                    <?php foreach($dbf->fetch("state","") as $userState){?>
                                    <option value="<?php echo $userState['state_code'];?>" <?php if($res_admin['state']==$userState['state_code']){echo 'selected';}?>><?php echo $userState['state_name'];?></option>
                                    <?php }?>
                                  </select>
                                  <br/><label for="ProfileState" id="lblProfileState" class="redText"></label>
                                <?php }?>
                                </div>
                                <div class="spacer"></div>
                                <?php if($_SESSION['usertype']=="admin"){ ?>
                                <div  class="formtextadd">Site URL:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                  <input type="text" class="textbox" name="SiteUrl" id="SiteUrl" value="<?php echo $res_admin['site_url'];?>"><br/><label for="SiteUrl" id="lblSiteUrl" class="redText"></label>                                </div>
                                <div class="spacer"></div>
                                <?php }?>
                                 <div align="center">
                                 	<input type="submit" class="buttonText" value="Update Profile"/>
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