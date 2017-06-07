<?php 
ob_start();
session_start();
include_once '../includes/class.Main.php';
//Object initialization
$dbf = new User();
//page titlevariable
$pageTitle="Welcome To Out Of The Box";
include 'applicationtop-client.php';
if($_SESSION['usertype']!='client'){
	header("location:../logout");exit;
}
//get amount
$amount = $_REQUEST['amount'];
//client details
$resClientDetails = $dbf->fetchSingle("clients","id='$_SESSION[userid]'");
?>
<link rel="stylesheet" href="../css/innermain.css" type="text/css" />
<link rel="stylesheet" href="../css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="../css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="../css/respmenu.css" type="text/css" />
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
     	<?php include_once 'header-client.php';?>
   		<!-------------header--------------->
        
        <!-------------top menu--------------->
     	<?php include_once 'client-top-menu.php';?>
   		<!-------------top menu--------------->
         <div id="contentdiv">
                <!-------------Main Body--------------->
                <div class="rightcolumjobboard">
            		<div class="rightcoluminner">
                        <div class="headerbg">Credit Card Details</div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        <!-----Table area start------->
                          <form name="frmCkeckout" id="frmCkeckout" action="client-checkout-process" method="post" onSubmit="return validate_checkout();" autocomplete="off">
                        	<input type="hidden" name="action" value="checkout"/>
                            <!-----billing div start--------->
                           	<div  class="divBilling">
                            <div class="innerBilling">
                                <div  class="formtextaddjoblong"> Amount:<span class="redText">*</span></div>
                                <div  class="textboxcBill">
                                	<input type="text" class="textboxjob" name="Amount" id="Amount" tabindex="1" value="<?php echo number_format($amount,2);?>" onKeyUp="return extractNumber(this,2);" readonly><br/><label for="Amount" id="lblAmount" class="redText"></label>
                                </div>&nbsp;$
                                <div class="spacer"></div>
                                <div  class="formtextaddjoblong">Card Number :<span class="redText">*</span></div>
                                <div  class="textboxcBill">
                                    <input type="text" class="textboxjob" name="CardNumber" id="CardNumber" tabindex="2"><br/><label for="CardNumber" id="lblCardNumber" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextaddjoblong">Expiration Date:<span class="redText">*</span></div>
                                <div  class="textboxcBill">
                                	<div style="float:left; width:49%;">
                                    <select name="ExpMonth" id="ExpMonth" class="selectboxjob" tabindex="3">
                                        <option value="">--Select Month--</option>
										<?php for($i=1;$i<13;$i++){
                                              if($i==1){$month='(01) January';}
                                              if($i==2){$month='(02) February';}
                                              if($i==3){$month='(03) March';}
                                              if($i==4){$month='(04) April';}
                                              if($i==5){$month='(05) May';}
                                              if($i==6){$month='(06) June';}
                                              if($i==7){$month='(07) July';}
                                              if($i==8){$month='(08) August';}
                                              if($i==9){$month='(09) September';}
                                              if($i==10){$month='(10) October';}
                                              if($i==11){$month='(11) November';}
                                              if($i==12){$month='(12) December';}
                                         ?>
                                         <option value="<?php echo $i;?>"><?php echo $month;?></option>
      									<?php }?>
                                    </select>
                                    </div>
                                	<div style="float:right; width:49%;">
                                     <select name="ExpYear" id="ExpYear" class="selectboxjob" tabindex="4">
                                        <option value="">--Select Date--</option>
                                       <?php for($i=2013;$i<=(2013+48);$i++){?>
                                        <option value="<?php echo $i;?>"><?php echo $i;?></option>
                                        <?php }?>
                                    </select>
                                    </div>
                                  <br/><label for="ExpYear" id="lblExpYear" class="redText"></label> 
                                </div>
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
                                <input type="submit" name="submitbtn" id="submitbtn" class="buttonText" value="Pay By Authorize.Net" tabindex="9"/>
                                <input type="button" class="buttonText3" value="Back" onClick="javascript:window.location.href='client-workorder-billings'" tabindex="10">
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
        <?php include_once 'footer-client.php'; ?>
  </div>
</body>
</html>