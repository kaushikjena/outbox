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
	    $ProfileJobtypeName=mysql_real_escape_string($_REQUEST['ProfileJobtypeName']);
	    $num=$dbf->countRows("job_type","jobtype='$ProfileJobtypeName'");
		if($num>0){
			header("Location:manage-jobtype?msg=002");exit;
		}else{
			$string="jobtype='$ProfileJobtypeName',created_date=now()";
			//insert into job_type table
			$dbf->insertSet("job_type",$string);
			header("Location:manage-jobtype");exit;
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
			curphonevar = numval.replace(/[\\!"£$%^&*+_={};:'#~()¦\/<>?|`¬\]\[]/g,'');
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
				<?php include_once 'left-menu.php';?>
                <!-------------left menu--------------->
                <!-------------Main Body--------------->
                <div class="rightcolum">
            		<div class="rightcoluminner">
                        <div class="headerbg">ADD JOB TYPE</div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        	<!-----Table area start------->
                            <div  class="innertable">
                            <div align="center"></div>
                            <div class="spacer"></div>
                         	  <form action="" name="frmJobtype" id="frmJobtype" method="post" onSubmit="return validate_Jobtype();" enctype="multipart/form-data" autocomplete="off">
                              <input type="hidden" name="action" value="insert">
                             	<div  class="formtextadd">Jobtype Name<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="ProfileJobtypeName" id="ProfileJobtypeName"><br/><label for="ProfileJobtypeName" id="lblProfileJobtypeName" class="redText"></label>
                               </div>
                              <div class="spacer"></div>
                                 <div align="center">
                                 	<input type="submit" class="buttonText" value="Submit Form"/>
                                    <a href="manage-jobtype" style="text-decoration:none;"><input type="button" class="buttonText3" value="Back"/></a>
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