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
	$WorkTypeName=mysql_real_escape_string($_REQUEST['WorkTypeName']);
	$num=$dbf->countRows("work_type","worktype='$WorkTypeName'");
	if($num>0){
		header("Location:add-worktype?msg=002");exit;
	}else{
		$string="worktype='$WorkTypeName',created_date=now()";
		//insert into work_type table
		$dbf->insertSet("work_type",$string);
		header("Location:manage-worktype");exit;
	}
}
?>
<script type="text/javascript">
$(document).ready(function(){
	$("form :input").each(function(){
		  $(this).keyup(function(event){
			var xss =  $(this);
			var maintainplus = '';
			var numval = xss.val();
			curphonevar = numval.replace(/[\\!"£$%^&*+_={};:'#~()¦\/<>?|`¬\]\[]/g,'');
			xss.val(maintainplus + curphonevar) ;
			var maintainplus = '';
			xss.focus;
		  });
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
                        <div class="headerbg">ADD WORK TYPE</div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        	<!-----Table area start------->
                            <div  class="innertable">
                             <div align="center"><?php if($_REQUEST['msg']=="002"){?><span class="redText">This work type already exist!</span><?php }?></div>
                            <div class="spacer"></div>
                         	  <form action="" name="frmWorktype" id="frmWorktype" method="post" onSubmit="return validate_worktype();" enctype="multipart/form-data" autocomplete="off">
                              <input type="hidden" name="action" value="insert">
                             	<div  class="formtextadd">WorkType Name<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="WorkTypeName" id="WorkTypeName"><br/><label for="WorkTypeName" id="lblWorkTypeName" class="redText"></label>
                               </div>
                              <div class="spacer"></div>
                                 <div align="center">
                                 	<input type="submit" class="buttonText" value="Submit Form"/>
                                    <a href="manage-worktype" style="text-decoration:none;"><input type="button" class="buttonText3" value="Back"/></a>
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