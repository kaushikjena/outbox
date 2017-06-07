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
$resUser=$dbf->fetchSingle("users","id='$_REQUEST[id]'");
if($_REQUEST['action']=="update"){
	$ProfileUserName=addslashes($_POST['ProfileUserName']);
	$ProfileCity=addslashes($_POST['ProfileCity']);
	$ProfileState=addslashes($_POST['ProfileState']);
	
	//duplicate user Email Id Check
	$numuser = $dbf->countRows("users","email='$_REQUEST[ProfileEmailID]' AND id<>'$_REQUEST[hid]'");
	if($numuser > 0){
		header("Location:edit-user?msg=002&id=$_REQUEST[hid]");exit;
	}else{
		$ProfilePassword = base64_encode(base64_encode($_POST['ProfilePassword']));
		$file_name=$_FILES['ProfilePhoto']['name'];
		if($file_name <>''){
			$path="user_photo/";
			move_uploaded_file($_FILES['ProfilePhoto']['tmp_name'],$path.$file_name);
			$string="name='$ProfileUserName', user_type='user', email='$_POST[ProfileEmailID]', password='$ProfilePassword', mobile='$_POST[ProfileMobile]', city='$ProfileCity', state='$ProfileState', status='$_REQUEST[chkStatus]', user_photo='$file_name', created_date=now()";
		}else{
			$file_name="";
			$string="name='$ProfileUserName', user_type='user', email='$_POST[ProfileEmailID]', password='$ProfilePassword', mobile='$_POST[ProfileMobile]', city='$ProfileCity', state='$ProfileState', status='$_REQUEST[chkStatus]', created_date=now()";
		}
		//insert into Employee table
		$dbf->updateTable("users",$string,"id='$_REQUEST[hid]'");
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
	 //}
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
                        <div class="headerbg">EDIT USER</div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        	<!-----Table area start------->
                            <div class="viewimage"><img src="user_photo/<?php echo $resUser['user_photo'];?>" alt="User Photo" width="100" height="100"/></div>
                        	<div  class="innertable" style="float:right;">
                            <div align="center"><?php if($_REQUEST['msg']=="002"){?><span class="redText">This Email ID already exist!</span><?php } ?></div>
                            <div class="spacer"></div>
                         	  <form action="" name="frmProfile" id="frmProfile" method="post" onSubmit="return validate_edituser();" enctype="multipart/form-data" autocomplete="off">
                              <input type="hidden" name="action" value="update">
                              <input type="hidden" name="hid" value="<?php echo $_REQUEST['id'];?>">
                                <div  class="formtextadd">User Name:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="ProfileUserName" id="ProfileUserName" value="<?php echo $resUser['name']?>" onKeyPress="return onlyLetters(event)"><br/><label for="ProfileUserName" id="lblProfileUserName" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Email ID:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="ProfileEmailID" id="ProfileEmailID" value="<?php echo $resUser['email'];?>"><br/><label for="ProfileEmailID" id="lblProfileEmailID" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Password:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                   <input type="password" class="textbox" name="ProfilePassword" id="ProfilePassword" value="<?php echo  base64_decode(base64_decode($resUser['password']));?>"><br/><label for="ProfilePassword" id="lblProfilePassword" class="redText"></label>
                                </div>
                                 <div class="spacer"></div>
                                <div  class="formtextadd">Mobile No:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="ProfileMobile" id="ProfileMobile"  onKeyUp="validatephone(this);" maxlength="12" value="<?php echo $resUser['mobile'];?>"><br/><label for="ProfileMobile" id="lblProfileMobile" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">City:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="ProfileCity" id="ProfileCity" value="<?php echo $resUser['city'];?>" onKeyPress="return onlyLetters(event)"><br/><label for="ProfileCity" id="lblProfileCity" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">State:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                  <select class="selectbox" name="ProfileState" id="ProfileState">
                                  	<option value="">--Select State--</option>
                                    <?php foreach($dbf->fetch("state","") as $vstate){?>
                                    <option value="<?php echo $vstate['state_code'];?>" <?php if($resUser['state']==$vstate['state_code']){echo 'selected';}?>><?php echo $vstate['state_name'];?></option>
                                    <?php }?>
                                  </select>
                                   <br/><label for="ProfileState" id="lblProfileState" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Profile Photo:</div>
                                <div  class="textboxc">
                                    <input type="file" class="textbox" name="ProfilePhoto" id="ProfilePhoto"><br/><label for="ProfilePhoto" id="lblProfilePhoto" class="redText"></label>
                                </div>
                                 <div class="spacer"></div>
                                   <div  class="formtextadd">Active Status:</div>
                                <div  class="textboxc">
                                   <input type="checkbox" name="chkStatus" id="chkStatus" value="1" <?php if($resUser['status']=='1'){echo 'checked';}?>>
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
    </div>
</body>
</html>