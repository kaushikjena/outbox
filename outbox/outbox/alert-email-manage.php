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
		//update into alert email table
		$dbf->updateTable("alert_email","status='$alert'","id='$alrtid'");
	 }
	 header("Location:alert-email-manage");exit; 
}
?>
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
                        <div class="headerbg">Alert Emails</div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        	<!-----Table area start------->
                            <div  class="innertableAlert">
                            <div align="center"></div>
                            <div class="spacer"></div>
                         	  <form action="" name="frmAlert" id="frmAlert" method="post">
                              <input type="hidden" name="action" value="update">
                              <?php
							  $num=$dbf->countRows("alert_email","");
                              foreach($dbf->fetch("alert_email","")as $res){
							 ?>
                               <div  class="formtextaddAlert"><?php echo $res['alert_email_name'];?></div>
                                <div class="textboxcAlert">
                                    <input type="radio" name="alert<?php echo $res['id'];?>" id="test<?php echo $res['id'];?>" value="0" <?php if($res['status']=='0'){echo 'checked';}?>> Off&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="radio"  name="alert<?php echo $res['id'];?>" id="test<?php echo $res['id'];?>" value="1" <?php if($res['status']=='1'){echo 'checked';}?>>On
                               </div>
                               	<input type="hidden" name="alrtid<?php echo $res['id'];?>" value="<?php echo $res['id'];?>"/>
                               <div class="spacer"></div>
                               <?php }?>
                                <div class="spacer"></div>
                               <div align="center">
                               <input type="submit" class="buttonText" value="Save Changes"/>&nbsp;
                               <input type="hidden" name="cntRows" value="<?php echo $num;?>"/></div>
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