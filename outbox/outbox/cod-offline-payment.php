<?php 
ob_start();
session_start();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();
//page titlevariable
$pageTitle="Welcome To Out Of The Box";
include 'applicationtop.php';
//get amount
$amount = $_REQUEST['amount'];
$wono = $_REQUEST['wono'];
//client details
$resClientDetails = $dbf->fetchSingle("clients","id='$_REQUEST[cid]'");
//insert into cod payment history table
if($_REQUEST['action']=='offline'){
	$payment_date = date('Y-m-d');
	//insert into cod_payment_history table
	$stringh="wo_no='$_REQUEST[wono]',client_id='$_REQUEST[clientid]', transaction_id='', transaction_amount='$_REQUEST[amount]', payment_status='Completed', payment_date='$payment_date', created_date=now()";
	$dbf->insertSet("cod_payment_history",$stringh);
	//update work_order_bill table
	$dbf->updateTable("work_order_bill","payment_status='Completed',payment_date='$payment_date'","wo_no='$_REQUEST[wono]' AND created_by=0 AND client_id='$_REQUEST[clientid]'");
	
	header("Location:manage-cod-billings");
}
?>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />
<script type="text/javascript">
$(document).ready(function() {
    $('input:text,textarea,select,checkbox').focus(
    function(){
        $(this).css({'background-color' : '#EDE9E4'});
    });

    $('input:text,textarea,select,checkbox').blur(
    function(){
        $(this).css({'background-color' : '#FFFFFF'});
    });
});
</script>
<body onLoad="document.frmCkeckout.Amount.focus();">
    <div id="maindiv">
        <!-------------header--------------->
     	<?php include_once 'header.php';?>
   		<!-------------header--------------->
        <!-------------top menu--------------->
     	<?php include_once 'top-menu.php';?>
   		<!-------------top menu--------------->
         <div id="contentdiv">
                <!-------------Main Body--------------->
                <div class="rightcolumjobboard">
            		<div class="rightcoluminner">
                        <div class="headerbg">COD Offline Payment</div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        <!-----Table area start------->
                          <form name="frmCkeckout" id="frmCkeckout" action="" method="post" onSubmit="return validate_checkout();" autocomplete="off">
                        	<input type="hidden" name="action" value="offline"/>
                            <!-----billing div start--------->
                           	<div  class="divBilling">
                            <div class="innerBilling">
                                <div  class="formtextaddjoblong"> Amount:<span class="redText">*</span></div>
                                <div  class="textboxcBill">
                                	<input type="text" class="textboxjob" name="Amount" id="Amount" tabindex="1" value="<?php echo number_format($amount,2);?>" onKeyUp="return extractNumber(this,2);" readonly><br/><label for="Amount" id="lblAmount" class="redText"></label>
                                </div>&nbsp;$
                                <div class="spacer"></div>
                                <div  class="formtextaddjoblong">Name:<span class="redText">*</span></div>
                                <div  class="textboxcBill">
                                  <input type="text" class="textboxjob" name="ClientName" id="ClientName" tabindex="5" value="<?php echo $resClientDetails['name'];?>"><br/><label for="ClientName" id="lblClientName" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextaddjoblong">Email ID:<span class="redText">*</span></div>
                                <div  class="textboxcBill">
                                  <input type="text" class="textboxjob" name="ClientEmail" id="ClientEmail" tabindex="5" value="<?php echo $resClientDetails['email'];?>"><br/><label for="ClientEmail" id="lblClientEmail" class="redText"></label>
                                </div>
                                 <div class="spacer"></div>
                                 <div  class="formtextaddjoblong"><span class="invoicetext">Address</span>:<span class="redText">*</span></div>
                                <div  class="textboxcBill">
                                 <textarea class="textareajob" name="ClientAddress" id="ClientAddress" tabindex="6"><?php echo $resClientDetails['address'];?></textarea><br/><label for="ClientAddress" id="lblClientAddress" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextaddjoblong">City:</div>
                                <div  class="textboxcBill">
                                  <input type="text" class="textboxjob" name="ClientCity" id="ClientCity" tabindex="5" value="<?php echo $resClientDetails['city'];?>"><br/><label for="ClientCity" id="lblClientCity" class="redText"></label>
                                </div>
                                 <div class="spacer"></div>
                                <div  class="formtextaddjoblong">State:<span class="redText">*</span></div>
                                <div  class="textboxcBill">
                                 <select name="ClientState" id="ClientState" class="selectboxjob" tabindex="7">
                                    <option value="">--Select State--</option>
                                    <?php foreach($dbf->fetch("state","")as $CState){?>
                                    <option value="<?php echo $CState['state_code'];?>"<?php if($CState['state_code']==$resClientDetails['state']){echo'selected';}?>><?php echo $CState['state_name'];?></option>
                                    <?php }?>
                                </select><br/><label for="ClientState" id="lblClientState" class="redText"></label>
                                </div>
                                 <div class="spacer"></div>
                                 <div  class="formtextaddjoblong">Zip Code:<span class="redText">*</span></div>
                                <div  class="textboxcBill">
                                    <input type="text" class="textboxjob" name="ClientZip" id="ClientZip" tabindex="8" value="<?php echo $resClientDetails['zip_code'];?>"><br/><label for="ClientZip" id="lblClientZip" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div class="spacer"></div>
                                <div  class="formtextaddjoblong">&nbsp;</div>
                                <div class="textboxcBill">
                             	<input type="hidden" name="amount" value="<?php echo $amount;?>"/>
                                <input type="hidden" name="clientid" value="<?php echo $resClientDetails['id'];?>"/>
                                <input type="hidden" name="wono" value="<?php echo $_REQUEST['wono'];?>"/>
                                <input type="submit" name="submitbtn" id="submitbtn" class="buttonText" value="Pay Now" tabindex="9"/>
                                <input type="button" class="buttonText3" value="Back" onClick="javascript:window.location.href='admin-cod-billings?id=<?php echo $_REQUEST['wid'];?>'" tabindex="10">
                             </div>
                          	<div class="spacer"></div>
                           	</div>
                        	</div>
                            <!-----billing div end--------->
                           </form>
                           <!-----Table area end------->
                    	</div>
            	</div>
               </div>
              <!-------------Main Body--------------->
         </div>
        <div class="spacer"></div>
        <?php include_once 'footer.php'; ?>
  </div>
</body>
</html>