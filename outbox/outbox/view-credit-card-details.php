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
$res_viewCredit=$dbf->strRecordID("cod_credit_card_info ccd,work_order wo,clients c","c.name,wo.wo_no,ccd.card_type,ccd.card_number,ccd.expiry_month,ccd.expiry_year,ccd.ccv_number,ccd.card_holder_name,ccd.zip_code","ccd.id='$_REQUEST[id]' AND ccd.workorder_id=wo.id AND c.user_type='customer' AND c.id=ccd.client_id");
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
                        <div class="headerbg">View Credit Card Details</div>
                        <div class="spacer"></div>
                        <div id="contenttable"  style="border: 1px solid #666;">
                        <div  class="innertable">
                                <div class="spacer"></div>
                                <div  class="formtextadd">Customer Name:</div>
                                <div  class="textboxview"><?php echo $res_viewCredit['name'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Wo Number:</div>
                                <div  class="textboxview"><?php echo $res_viewCredit['wo_no'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Card Type:</div>
                                <div  class="textboxview"><?php echo $res_viewCredit['card_type'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Card Number:</div>
                                <div  class="textboxview"><?php echo $res_viewCredit['card_number'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Expiration Date:</div>
                                <div  class="textboxview"><?php echo $res_viewCredit['expiry_month']."/".$res_viewCredit['expiry_year'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">CCV Number:</div>
                                <div  class="textboxview"><?php echo $res_viewCredit['ccv_number'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Card Holder Name:</div>
                                <div  class="textboxview"><?php echo $res_viewCredit['card_holder_name'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Zip Code:</div>
                                <div  class="textboxview"><?php echo $res_viewCredit['zip_code'];?></div>
                                <div class="spacer"></div>
                                <div align="center"  style="padding-right:60px;"><input type="button" class="buttonText" value="Return Back" onClick="window.location='credit-card-details'"/>
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