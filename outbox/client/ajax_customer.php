<?php
ob_start();
session_start();
include '../includes/class.Main.php';

$dbf = new User();
$resCust=$dbf->fetchSingle("clients","id='$_REQUEST[cust_id]'");
?>
    
    <div  class="formtextaddjob"> Name:<span class="redText">*</span></div>
    <div  class="textboxcjob">
        <input type="text" class="textboxjob" name="CustomerName" id="CustomerName" value="<?php echo $resCust['name'];?>"><br/><label for="CustomerName" id="lblCustomerName" class="redText"></label>
    </div>
    <div  class="formtextaddjoblong">Email Address:</div>
    <div  class="textboxcjob">
        <input type="text" class="textboxjob" name="CustomerEmailID" id="CustomerEmailID" value="<?php echo $resCust['email'];?>"><br/><label for="CustomerEmailID" id="lblCustomerEmailID" class="redText"></label>
    </div>
    <div class="spacer"></div>
    <div  class="formtextaddjob">Address:<span class="redText">*</span></div>
    <div  class="textboxcjob">
        <textarea class="textareajob" name="CustAddress" id="CustAddress"><?php echo $resCust['address'];?></textarea><br/><label for="CustAddress" id="lblCustAddress" class="redText"></label>
    </div>
    <div  class="formtextaddjoblong"> Contact Name:</div>
    <div  class="textboxcjob">
      <input type="text" class="textboxjob" name="CustomercontactName" id="ContactName" value="<?php echo $resCust['contact_name'];?>"><br/><label for="CustomercontactName" id="lblCustomercontactName" class="redText"></label>
    </div>
     <div class="spacer"></div>
     <div  class="formtextaddjob">City:<span class="redText">*</span></div>
    <div  class="textboxcjob">
        <input type="text" class="textboxjob" name="CustCity" id="CustCity" value="<?php echo $resCust['city'];?>"><br/><label for="CustCity" id="lblCustCity" class="redText"></label>
    </div>
    <div  class="formtextaddjoblong">State:<span class="redText">*</span></div>
    <div  class="textboxcjob">
    <select name="CustState" id="CustState" class="selectboxjob">
      <option value="">--Select State--</option>
      <?php foreach($dbf->fetch("state","id>0 ORDER BY state_code ASC")as $CState){?>
      <option value="<?php echo $CState['state_code'];?>" <?php if($resCust['state']==$CState['state_code']){echo 'selected';}?>><?php echo $CState['state_name'];?></option>
        <?php }?>
    </select><br/><label for="CustState" id="lblCustState" class="redText"></label>
    </div>
     <div class="spacer"></div>
     <div  class="formtextaddjob">Zip Code:<span class="redText">*</span></div>
    <div  class="textboxcjob">
        <input type="text" class="textboxjob" name="CustZip" id="CustZip" value="<?php echo $resCust['zip_code'];?>"><br/><label for="CustZip" id="lblCustZip" class="redText"></label>
    </div>
    <div  class="formtextaddjoblong">Phone No:<span class="redText">*</span></div>
    <div  class="textboxcjob">
        <input type="text" class="textboxjob" name="CustPhone" id="CustPhone" onKeyPress="return onlyNumbers(event);" value="<?php echo $resCust['phone_no'];?>"><br/><label for="CustPhone" id="lblCustPhone" class="redText"></label>
    </div>
     <div class="spacer"></div>
    <div  class="formtextaddjob">Fax No:</div>
     <div  class="textboxcjob">
        <input type="text" class="textboxjob" name="Faxno" id="Faxno" onKeyPress="return onlyNumbers(event);"value="<?php echo $resCust['fax_no'];?>"><br/><label for="Faxno" id="lblFaxno" class="redText"></label>
    </div><input type="hidden" name="custid" id="custid" value="<?php echo $resCust['id'];?>"/>
    <div class="spacer"></div>
    
<script type="text/javascript">
$(document).ready(function() {
	//autopopulation by emailid
	 $("#CustomerEmailID").autocomplete("get_cust_email_list.php", {
        width: 200,
        matchContains: true,
        selectFirst: false
    });
	$("#CustomerEmailID").result(function(event, data, formatted) {//alert(data[1]);
		$.post("ajax_customer.php",{"cust_id":data[1]},function(res){
			$("#rescust").html(res);
		})
	});	
	//autopopulation by name
	$("#CustomerName").autocomplete("get_cust_name_list.php", {
        width: 200,
        matchContains: true,
        selectFirst: false
    });
	$("#CustomerName").result(function(event, data, formatted) {//alert(data[1]);
		$.post("ajax_customer.php",{"cust_id":data[1]},function(res){
			$("#rescust").html(res);
		})
	});	
});
</script>