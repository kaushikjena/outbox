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
$res_email=$dbf->fetchSingle("emails","id='$_REQUEST[id]'");
if($_REQUEST['action']=="update"){
	    $num=$dbf->countRows("emails","email='$_REQUEST[predefinedEmail]' AND id<>'$_REQUEST[hid]'");
		if($num>0){
			header("Location:edit-email?msg=002");exit;
		}else{
			$predefinedEmail=mysql_real_escape_string($_REQUEST['predefinedEmail']);
			$predefinedName=mysql_real_escape_string($_REQUEST['predefinedName']);
			$string="email='$predefinedEmail',name='$predefinedName',created_date=now()";
		}
		//update into Service table
		$dbf->updateTable("emails",$string,"id='$_REQUEST[hid]'");
		header("Location:manage-emails");exit;
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
			curphonevar = numval.replace(/[\\!"£$%^&*+_={};:'#~()¦\/<>?|`¬\]\[]/g,'');
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
<link rel="stylesheet" href="css/table.css" type="text/css" />
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
                        <div class="headerbg">EDIT EMAIL</div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        	<!-----Table area start------->
                            <div  class="innertable">
                            <div align="center"><?php if($_REQUEST['msg']=="002"){?><span class="redText">This email already exist!</span><?php }?></div>
                            <div class="spacer"></div>
                         	  <form action="" name="frmService" id="frmService" method="post" onSubmit="return validate_email();" enctype="multipart/form-data" autocomplete="off">
                              <input type="hidden" name="action" value="update">
                              <input type="hidden" name="hid" value="<?php echo $_REQUEST['id'];?>">
                                <div  class="formtextadd">Email<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="predefinedEmail" id="predefinedEmail" value="<?php echo $res_email['email']?>"><br/><label for="predefinedEmail" id="lblpredefinedEmail" class="redText"></label>
                               </div>
                               <div class="spacer"></div>
                               <div  class="formtextadd">Name<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="predefinedName" id="predefinedName" value="<?php echo $res_email['name']?>"><br/><label for="predefinedName" id="lblpredefinedName" class="redText"></label>
                               </div>
                              	<div class="spacer"></div>
                                 <div align="center">
                                 	<input type="submit" class="buttonText" value="Submit Form"/>
                                    <a href="manage-emails" style="text-decoration:none;"><input type="button" class="buttonText3" value="Back"/></a>
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