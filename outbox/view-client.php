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
				<?php //include_once 'left-menu.php';?>
                <!-------------left menu--------------->
                <!-------------Main Body--------------->
                <div class="rightcolumjobboard">
            		<div class="rightcoluminner">
                        <div class="headerbg">View Client</div>
                        <div class="spacer"></div>
                        <div id="contenttable"  style="border: 1px solid #666;">
                        <div  class="innertable">
                                <div class="spacer"></div>
                                <div  class="formtextadd">Client Name:</div>
                                <div  class="textboxview"><?php echo $res_viewClient['name'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Email ID:</div>
                                <div  class="textboxview"><?php echo $res_viewClient['email'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Contact No:</div>
                                <div  class="textboxview"><?php echo $res_viewClient['phone_no'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Cell No:</div>
                                <div  class="textboxview"><?php echo $res_viewClient['fax_no'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Address:</div>
                                <div  class="textboxview"><?php echo $res_viewClient['address'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">City:</div>
                                <div  class="textboxview"><?php echo $res_viewClient['city'];?></div>
                                <div class="spacer"></div>
                                <?php $res_StateClient=$dbf->getDataFromTable("state","state_name","state_code='$res_viewClient[state]'"); ?>
                                <div  class="formtextadd">State:</div>
                                <div  class="textboxview"><?php echo $res_StateClient;?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Zipcode:</div>
                                <div  class="textboxview"><?php echo $res_viewClient['zip_code'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Active Status:</div>
                                <div  class="textboxview"><?php if($res_viewClient['status']=='1'){echo 'Active';}else{echo 'Inactive';}?></div>
                                <div class="spacer"></div>
                                <div align="center"  style="padding-right:60px;"><input type="button" class="buttonText" value="Return Back" onClick="window.location='manage-client'"/>
                                 </div>
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