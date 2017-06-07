<?php 
ob_start();
session_start();
include_once '../includes/class.Main.php';
//Object initialization
$dbf = new User();
//page titlevariable
$pageTitle="Welcome To Out Of The Box";
include 'applicationtop-client.php';
if($_SESSION['userid']==''){
	header("location:logout");exit;
}
$viewClntPayhist=$dbf->fetchSingle("client_payment_history","id='$_REQUEST[id]'");
?>
<link rel="stylesheet" href="../css/innermain.css" type="text/css" />
<link rel="stylesheet" href="../css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="../css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="../css/respmenu.css" type="text/css" />
<link rel="stylesheet" href="../css/table.css" type="text/css" />
<body>
    <div id="maindiv">
        <!-------------header--------------->
     	<?php include_once 'header-client.php';?>
   		<!-------------header--------------->
        
        <!-------------top menu--------------->
     	<?php include_once 'client-top-menu.php';?>
   		<!-------------top menu--------------->
         <div id="contentdiv">
                <!-------------Left menu--------------->
				<?php //include_once 'left-menu.php';?>
                <!-------------left menu--------------->
                <!-------------Main Body--------------->
                <div class="rightcolumjobboard">
            		<div class="rightcoluminner">
                        <div class="headerbg">View Client Payments History</div>
                        <div class="spacer"></div>
                        <div id="contenttable"  style="border: 1px solid #666;">
                        <div  class="innertable">
                                <div class="spacer"></div>
                                <div  class="formtextadd">Transaction Id:</div>
                                <div  class="textboxview"><?php echo $viewClntPayhist['transaction_id'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Transaction Amount:</div>
                                <div  class="textboxview">$ <?php echo $viewClntPayhist['transaction_amount'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Payment Status:</div>
                                <div  class="textboxview"><?php echo $viewClntPayhist['payment_status'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Payment Date:</div>
                                <div  class="textboxview"><?php echo date("d-M-Y",strtotime($viewClntPayhist['payment_date']));?></div>
                                <div class="spacer"></div>
                                <div class="spacer"></div>
                                <div align="center"  style="padding-right:60px;"><input type="button" class="buttonText" value="Return Back" onClick="window.location='client-payment-history'"/>
                                 </div>
                        	</div>
                            <!-----Table area start-------> 
                        	<div class="spacer"></div>
                    	</div>
            	</div>
              <!-------------Main Body--------------->
         </div>
        <div class="spacer"></div>
        <?php include_once 'footer-client.php'; ?>
    </div>
</body>
</html>