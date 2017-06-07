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
$res_editModule=$dbf->fetchSingle("alert_assignment","");
if($_REQUEST['action']=="update"){
	    $num=$dbf->countRows("alert_assignment","");
		if($num>0){
			$string="alert_days='$_REQUEST[daysno]'";
			//update into alert_assignment table
			$dbf->updateTable("alert_assignment",$string,"id='$_REQUEST[hid]'");
		}else{
			$string="alert_days='$_REQUEST[daysno]',created_date=now()";
			//update into alert_assignment table
			$dbf->insertSet("alert_assignment",$string);
		}
		header("Location:alert_assignment");exit;
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
                        <div class="headerbg">Alert Days</div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        	<!-----Table area start------->
                            <div  class="innertable">
                            <div align="center"></div>
                            <div class="spacer"></div>
                         	  <form action="" name="frmModule" id="frmModule" method="post" onSubmit="return validate_editDays();" autocomplete="off">
                              <input type="hidden" name="action" value="update">
                              <input type="hidden" name="hid" value="<?php echo $res_editModule['id'];?>">
                                <div  class="formtextadd">No. of days<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="daysno" id="daysno" value="<?php echo $res_editModule['alert_days']?>"><br/><label for="daysno" id="lbldaysno" class="redText"></label>
                               </div>
                              <div class="spacer"></div>
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
        <?php include_once 'footer.php'; ?>
    </div>
</body>
</html>