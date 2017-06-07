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
$res_version=$dbf->fetchSingle("versions","id='$_REQUEST[id]'");
if($_REQUEST['action']=="update"){
	if($_REQUEST['hid']==""){
		$num=$dbf->countRows("versions","name='$_REQUEST[txtName]'");
		if($num==0){
			$string="name='$_REQUEST[txtName]',title='$_REQUEST[txaTitle]',created_date=now()";
			//insert into sms_template table
			$dbf->insertSet("versions",$string);
		}
	}else{
		$num=$dbf->countRows("versions","name='$_REQUEST[txtName]' AND id !='$_REQUEST[hid]'");
		if($num==0){
			$string="name='$_REQUEST[txtName]',title='$_REQUEST[txaTitle]'";
			//update into sms_template table
			$dbf->updateTable("versions",$string,"id='$_REQUEST[hid]'");
		}else{
			
		}
	}
	header("Location:manage-versions");exit;
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
                        <div class="headerbg">Add Version</div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        	<!-----Table area start------->
                            <div  class="innertable">
                            <div align="center" class="greenText"><?php if($_REQUEST['msg']=='01'){echo 'Version added successfully';}?></div>
                            <div class="spacer"></div>
                         	  <form action="" name="frmModule" id="frmModule" method="post" onSubmit="return validate_version();" autocomplete="off">
                              <input type="hidden" name="action" value="update">
                              <input type="hidden" name="hid" value="<?php echo $res_version['id'];?>">
                              <div  class="formtextadd">Name<span class="redText">*</span></div>
                              <div  class="textboxc">
                                <input type="text" class="textboxjob" name="txtName" id="txtName" value="<?php echo $res_version['name']?>"><br/><label for="txtName" id="lbltxtName" class="redText"></label>
                               </div>
                              <div class="spacer"></div>
                              <div  class="formtextadd">Title<span class="redText">*</span></div>
                              <div  class="textboxc">
                                <textarea class="textareajob" name="txaTitle" id="txaTitle" style="height:80px;"><?php echo $res_version['title']?></textarea><br/><label for="txaTitle" id="lbltxaTitle" class="redText"></label>
                              </div>
                              <div class="spacer"></div>
                              <div align="center"><input type="submit" class="buttonText" value="Submit Form"/> &nbsp;&nbsp; <input type="button" class="buttonText" value="Return Back" onClick="javascript:window.location.href='manage-versions'"/></div>
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