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
//fetch data from client offline payment table
$resPayment=$dbf->fetchSingle("client_offline_payment","id='$_REQUEST[id]'");
if($_REQUEST['action']=="update"){
	$ChequeNo=mysql_real_escape_string($_POST['ChequeNo']);
	$BankName=mysql_real_escape_string($_POST['BankName']);
	$BankAddress=mysql_real_escape_string($_POST['BankAddress']);
	$ReceiveDate = date("Y-m-d",strtotime($_REQUEST['ReceiveDate']));
	//duplicate user Email Id Check
	$numuser = $dbf->countRows("client_offline_payment","cheque_no='$ChequeNo' AND id<>'$_REQUEST[hid]'");
	if($numuser > 0){
		header("Location:edit-receivable?msg=002&id=$_REQUEST[hid]");exit;
	}else{
		//insert into clients offline payment table
	 	$string="client_id='$_POST[ClientName]', cheque_no='$ChequeNo', cheque_receive_date='$ReceiveDate', cheque_amount='$_POST[ChequeAmount]', bank_name='$BankName', bank_address='$BankAddress', created_date=now()";
		$insid = $dbf->updateTable("client_offline_payment",$string,"id='$_REQUEST[hid]'");
		header("Location:manage-receivable");exit;
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
				<?php //include_once 'left-menu.php';?>
                <!-------------left menu--------------->
                <!-------------Main Body--------------->
                <div class="rightcolumjobboard">
            		<div class="rightcoluminner">
                        <div class="headerbg">Edit Receives(Offline Payment)</div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        	<!-----Table area start------->
                        	<div  class="innertable">
                            <div align="center"><?php if($_REQUEST['msg']=="002"){?><span class="redText">This Cheque No already exist!</span><?php } ?></div>
                            <div class="spacer"></div>
                         	 <form action="" name="frmReceive" id="frmReceive" method="post" onSubmit="return validate_receive();" enctype="multipart/form-data" autocomplete="off">
                     		 <input type="hidden" name="action" value="update"><input type="hidden" name="hid" value="<?php echo $resPayment['id'];?>"/>
                                <div  class="formtextadd">Client Name:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                   <select class="selectbox" name="ClientName" id="ClientName">
                                  	<option value="">--Select Client--</option>
                                    <?php foreach($dbf->fetch("clients","status='1' ORDER BY id ASC") as $vclient){?>
                                    <option value="<?php echo $vclient['id'];?>"<?php if($resPayment['client_id']==$vclient['id']){echo 'selected';}?>><?php echo $vclient['name'];?></option>
                                    <?php }?>
                                  </select>
                                  <br/><label for="ClientName" id="lblClientName" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Cheque No:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="ChequeNo" id="ChequeNo" value="<?php echo $resPayment['cheque_no'];?>"><br/><label for="ChequeNo" id="lblChequeNo" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Receive Date:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox datepick" name="ReceiveDate" id="ReceiveDate" value="<?php echo date("d-M-Y",strtotime($resPayment['cheque_receive_date']));?>" readonly><br/><label for="ReceiveDate" id="lblReceiveDate" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Cheque Amount:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="ChequeAmount" id="ChequeAmount" onKeyUp="return extractNumber(this,2);" value="<?php echo $resPayment['cheque_amount'];?>"><br/><label for="ChequeAmount" id="lblChequeAmount" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Bank Name:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                   <input type="text" class="textbox" name="BankName" id="BankName" value="<?php echo $resPayment['bank_name'];?>"><br/><label for="BankName" id="lblBankName" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Bank Address:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                   <textarea class="textarea" name="BankAddress" id="BankAddress"><?php echo $resPayment['bank_address'];?></textarea><br/><label for="BankAddress" id="lblBankAddress" class="redText"></label>
                                </div>
                                 <div class="spacer"></div>
                                 <div align="center">
                                  <input type="submit" class="buttonText" value="Submit Form"/>&nbsp;&nbsp;
                                  <input type="button" class="buttonText3" value="Back" onClick="javascript:window.location.href='manage-receivable'"/>
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