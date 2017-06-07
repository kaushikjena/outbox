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

if($_REQUEST['action']=="update"){
	 $count = $_REQUEST['cntRows'];
	 for($i=1;$i<=$count; $i++){
		$alert = 'alert'.$i;
		$alert = $_REQUEST[$alert]; 
		$alrtid = 'alrtid'.$i;
		$alrtid = $_REQUEST[$alrtid]; 
		$to_email='to_email'.$i;
		$to_email=$_REQUEST[$to_email];
		//update into alert email table
		$dbf->updateTable("admin_email_notification","to_email='$to_email',status='$alert'","id='$alrtid'");
	 }
	 header("Location:admin-manage-email-notification");exit; 
}
?>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />

<script type="text/javascript">
function send_email_admin(val){//alert(val);
	$.fancybox.showActivity();	
	var url="send-email-admin.php";
	$.post(url,{"choice":"show_email","id":val},function(res){//alert(res);			
		$.fancybox(res,{centerOnScroll:true,hideOnOverlayClick:false});				
	});
}
function updateAdminEmail(){
	$.fancybox.showActivity();	
	var url="send-email-admin.php";	
	var x=validate_admin_email();
	var admtempid=$('#admtemp_id').val();
	var emptempid=$('#emptemp_id').val();
	var fromemail=$('#fromemail').val();
	var fromname=$('#fromname').val();
	var subject=$('#subject').val();
	var message = CKEDITOR.instances['message'].getData();
	if(x){
	 	$.post(url,{"choice":"update_email","admtempid":admtempid,"emptempid":emptempid,"fromemail":fromemail,"fromname":fromname,"subject":subject,"message":message},function(res){ //alert(res);
		 if(res=='1'){
			$.fancybox("update Successfully",{centerOnScroll:true,hideOnOverlayClick:false});
		 }
	 	});
	}else{
		return false; 
	}
	
}
function validate_admin_email(){
	var emailExp = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;	
	if(document.frmEmailAdmin.fromemail.value == ''){
		document.getElementById('lblfromemail').innerHTML = 'This field is required';
		document.frmEmailAdmin.fromemail.focus();
		return false;
	}else{
		document.getElementById('lblfromemail').innerHTML = '';
	}
	if(!document.frmEmailAdmin.fromemail.value.match(emailExp)){
		document.getElementById('lblfromemail').innerHTML = "Required Valid Email ID.";
		document.frmEmailAdmin.fromemail.focus();
		return false;
	}
	else{
		document.getElementById('lblfromemail').innerHTML = '';
	}
	if(document.frmEmailAdmin.fromname.value==''){
		document.getElementById('lblfromname').innerHTML='This field is required';
		document.frmEmailAdmin.fromname.focus();
		return false;
	}else{
		document.getElementById('fromname').innerHTML='';
	}
	if(document.frmEmailAdmin.subject.value==''){
		document.getElementById('lblsubject').innerHTML='This field is required';
		document.frmEmailAdmin.subject.focus();
		return false;
	}else{
		document.getElementById('lblsubject').innerHTML='';
	}
	return true;	
}
/*function validateEmail(){
	var emailExp = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;	
	var count=$("#cntRows").val();
	for(var i=1;i<=count;i++){ 
		if(document.frmAlert.to_email+i.value == ''){
			document.getElementById('lblto_email'+i).innerHTML = 'This field is required';
			document.frmAlert.to_email+i.focus();
			return false;
		}else{
			document.getElementById('lblto_email'+i).innerHTML = '';
		}
		if(!document.frmAlert.to_email+i.value.match(emailExp)){
			document.getElementById('lblto_email'+i).innerHTML = "Required Valid Email ID.";
			document.frmAlert.to_email+i.focus();
			return false;
		}
		else{
			document.getElementById('to_email'+i).innerHTML = '';
		}
	}
}*/
</script>
<style type="text/css">
.textboxcAlert a{
	font-family:Tahoma, Geneva, sans-serif;
	font-size:12px;
	font-weight:bold;
	text-decoration:none;
	color:#ff9812;
}
.textboxcAlert a:hover{
	font-family:Tahoma, Geneva, sans-serif;
	font-size:12px;
	font-weight:bold;
	text-decoration:underline;
	color:#666;
}
</style>
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
                        <div class="headerbg">Admin Email Notifications</div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        	<!-----Table area start------->
                            <div  class="innertableAlert" style="width:800px;margin:0px;">
                            <div align="center"></div>
                            <div class="spacer"></div>
                         	  <form action="" name="frmAlert" id="frmAlert" method="post" onSubmit="return validateEmail();">
                              <input type="hidden" name="action" value="update">
                              <?php
							  $num=$dbf->countRows("admin_email_notification","");
                              foreach($dbf->fetch("admin_email_notification","")as $res){
							 ?>
                               <div  class="formtextaddAlert"><?php echo $res['email_name'];?></div>
                                <div class="textboxcAlert" style="width:400px;">
                                    <input type="radio" name="alert<?php echo $res['id'];?>" id="test<?php echo $res['id'];?>" value="0" <?php if($res['status']=='0'){echo 'checked';}?>> Off&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="radio"  name="alert<?php echo $res['id'];?>" id="test<?php echo $res['id'];?>" value="1" <?php if($res['status']=='1'){echo 'checked';}?>>On
                               &nbsp;
                               To Email: <input type="text" class="textbox" style="width:47%;" name="to_email<?php echo $res['id'];?>" id="to_email<?php echo $res['id'];?>" value="<?php echo $res['to_email'];?>"> 
                             <!--  <label for="to_email<?php //echo $res['id'];?>" id="lblto_email<?php //echo $res['id'];?>" class="redText"></label>    -->
                             &nbsp;&nbsp;<a  href="javascript:void(0);" onClick="send_email_admin('<?php echo $res['id'];?>');">Edit</a></div>
                               	<input type="hidden"  name="alrtid<?php echo $res['id'];?>" value="<?php echo $res['id'];?>"/>
                               <div class="spacer"></div>
                              <?php }?>
                                <div class="spacer"></div>
                               <div align="center">
                               <input type="submit" class="buttonText" value="Save Changes"/>&nbsp;
                               <input type="hidden" name="cntRows" id="cntRows" value="<?php echo $num;?>"/></div>
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