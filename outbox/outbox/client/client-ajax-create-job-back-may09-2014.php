<?php 
ob_start();
session_start();
include_once '../includes/class.Main.php';
//Object initialization
$dbf = new User();
//logout for users other than client user
if($_SESSION['usertype']!='client'){
		header("location:../logout");exit;
	}
//calculation of workorder number
$qry = mysql_query("SELECT id FROM work_order ORDER BY id DESC LIMIT 1");
$res = mysql_fetch_array($qry);
$lastid = $res['id'];
if($lastid ==""){$count =0;}else{$count = $lastid;}
$workorderno = "BOX".(1000+$count+1);

if($_REQUEST['action']=="insert"){
	$PurchaseOrder=addslashes($_POST['PurchaseOrder']);
	$Pickuplocation=addslashes($_POST['Pickuplocation']);
	if($_REQUEST['CustomerEmailID']<>''){
    	$numuser=$dbf->countRows("clients","email='$_REQUEST[CustomerEmailID]'");
	}
	if($numuser>0){
		//header("Location:create-job?msg=002");exit;
		$clientid = $dbf->getDataFromTable("clients","id","email='$_REQUEST[CustomerEmailID]'");
		//insert into clients table
		$CustomerName=mysql_real_escape_string($_REQUEST['CustomerName']);
		$CustomercontactName=mysql_real_escape_string($_REQUEST['CustomercontactName']);
		$CustAddress=mysql_real_escape_string($_REQUEST['CustAddress']);
		$CustCity=mysql_real_escape_string($_REQUEST['CustCity']);
		$CustState=mysql_real_escape_string($_REQUEST['CustState']);
		$string="name='$CustomerName', user_type='client', email='$_REQUEST[CustomerEmailID]', contact_name='$CustomercontactName', address='$CustAddress', city='$CustCity', state='$CustState', fax_no='$_REQUEST[Faxno]', zip_code='$_REQUEST[CustZip]', phone_no='$_REQUEST[CustPhone]', alt_phone='$_REQUEST[altCustPhone]', status=0, created_date=now()";
		$dbf->updateTable("clients",$string,"id='$clientid'");
	}else{
		//insert into clients table
		$CustomerName=mysql_real_escape_string($_REQUEST['CustomerName']);
		$CustomercontactName=mysql_real_escape_string($_REQUEST['CustomercontactName']);
		$CustAddress=mysql_real_escape_string($_REQUEST['CustAddress']);
		$CustCity=mysql_real_escape_string($_REQUEST['CustCity']);
		$CustState=mysql_real_escape_string($_REQUEST['CustState']);
		$string="name='$CustomerName', user_type='client', email='$_REQUEST[CustomerEmailID]', contact_name='$CustomercontactName', address='$CustAddress', city='$CustCity', state='$CustState', fax_no='$_REQUEST[Faxno]', zip_code='$_REQUEST[CustZip]', phone_no='$_REQUEST[CustPhone]', alt_phone='$_REQUEST[altCustPhone]', status=0, created_date=now()";
		$clientid=$dbf->insertSet("clients",$string);
	}
		//check duplicate workorder no
		 $numwo=$dbf->countRows("work_order","wo_no='$_REQUEST[WorkOrder]'");
		 if($numwo>0){
			$qry = mysql_query("SELECT id FROM work_order ORDER BY id DESC LIMIT 1");
			$res = mysql_fetch_array($qry);
			$lastid = $res['id'];
			$WorkOrder = "BOX".(1000+$lastid+1);
		 }else{
			$WorkOrder=$_REQUEST['WorkOrder']; 
		 }
		//insert into work order table
		$Address=mysql_real_escape_string($_REQUEST['Address']);
		$City=mysql_real_escape_string($_REQUEST['City']);
		$notes=mysql_real_escape_string($_REQUEST['notes']);
		$string1="wo_no='$WorkOrder', purchase_order_no='$PurchaseOrder', service_id='$_REQUEST[cmbService]', client_id='$clientid', pickup_location='$Pickuplocation', pickup_address='$Address', pickup_city='$City', pickup_state='$_REQUEST[State]', pickup_zip_code='$_REQUEST[Zip]', pickup_phone_no='$_REQUEST[Phone]', pickup_alt_phone='$_REQUEST[altPhone]',job_status='$_REQUEST[JobStatus]', notes='$notes', created_by='$_SESSION[userid]', approve_status='0',created_date=now();";
		$woid=$dbf->insertSet("work_order",$string1);
		
		##########Insert Into workorder service table###########
		$cntwotype =$dbf->countRows("work_type","");
		for($i=1;$i<=$cntwotype;$i++){
			$Worktype='Worktype'.$i;
			$Worktype=$_REQUEST[$Worktype];
			$equipname='Equipment'.$i;
		    $equip=$_REQUEST[$equipname];
			$modelname='Model'.$i;
			$model=$_REQUEST[$modelname];
			$Quantityname='Quantity'.$i;
			$Quantity=$_REQUEST[$Quantityname];
			$Price='Price'.$i;
			$Price=$_REQUEST[$Price];
			$TechPrice='TechPrice'.$i;
			$TechPrice=$_REQUEST[$TechPrice];
			
			if($_REQUEST['techgrade']=='A'){
				$gradeA_price = $TechPrice;
			}elseif($_REQUEST['techgrade']=='B'){
				$gradeB_price = $TechPrice;
			}elseif($_REQUEST['techgrade']=='C'){
				$gradeC_price = $TechPrice;
			}elseif($_REQUEST['techgrade']=='D'){
				$gradeD_price = $TechPrice;
			}
			if($equip<>''){
				//insert into workorder service table
				$string2="workorder_id='$woid', service_id='$_REQUEST[cmbService]', equipment='$equip',work_type='$Worktype', model='$model', quantity='$Quantity',outbox_price='$Price',gradeA_price='$gradeA_price', gradeB_price='$gradeB_price', gradeC_price='$gradeC_price', gradeD_price='$gradeD_price', created_date=now()";
				$dbf->insertSet("workorder_service",$string2);
			}
		}
		##########Insert Into workorder service table###########
		###########Insert Into work order doc table#############
		//check for workorder doc
		if($_FILES['fileUpload']['name']<>''){
			$file_name=$WorkOrder.'_'.$_FILES['fileUpload']['name'];
			$path="../workorder_doc/";
			move_uploaded_file($_FILES['fileUpload']['tmp_name'],$path.$file_name);
			//insert into workorder doc table
			$string3="workorder_id='$woid', wo_document='$file_name',created_date=now()";
			$dbf->insertSet("workorder_doc",$string3);
		}
		###########Insert Into work order doc table#############
		###########Insert Into work order notes table#############
		if($_REQUEST['adminNotes']<>''){
			$adminNotes=mysql_real_escape_string($_REQUEST['adminNotes']);
			$string4="workorder_id='$woid', user_type='$_SESSION[usertype]', user_id='$_SESSION[userid]', wo_notes='$adminNotes',created_date=now()";
			$dbf->insertSet("workorder_notes",$string4);
		}
		###########Insert Into work order notes table#############
		//Email Sending Starts here
		$res_template=$dbf->fetchSingle("email_template","id='16'");
		$name=$res_template['from_name'];
		$to=$res_template['from_email'];
		$subject=$res_template['subject'];
		$input=$res_template['message'];
		//fetch client details
		$resClient=$dbf->fetchSingle("clients","id='$_SESSION[userid]'");
		$from=$resClient['email'];
		$clientName=ucfirst($resClient['name']);
		$body=str_replace(array('%Name%','%OrderNo%','%ClientName%'),array($name,$WorkOrder,$clientName),$input);
		$headers = "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=UTF-8\n";
		$headers .= "From:".$clientName." <".$from.">\n";
	    //echo $body;exit;
		@mail($to,$subject,$body,$headers);
		//Email Sending End
		header("Location:client-manage-job-board");exit;
}
//get technician details
$resTech = $dbf->fetchSingle("assign_tech at,technicians tc","at.tech_id=tc.id AND at.wo_no='$workorderno'");
//get client name
$resUserName=$dbf->getDataFromTable("clients","name","id='$_SESSION[userid]'");
?>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<script type="text/javascript">
$(document).ready(function() {
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
});
function showEquipment(servid){
	var url="../ajax_equipment.php";
	$.post(url,{"serviceid":servid},function(res){			
		$("#equipid").html(res);			
	});
}
function ShowPrice(id){
	var techgrade = $("#techgrade").val();
	var serviceid = $("#cmbService").val();
	var equipid = $("#Equipment"+id).val();
	var worktypeid = $("#Worktype"+id).val();
	var Quantity = $("#Quantity"+id).val();
	var url="../ajax_service_price.php";
	$.post(url,{"serviceid":serviceid,"equipid":equipid,"worktypeid":worktypeid,"techgrade":techgrade},function(res){	
	//alert(res);
		var result=res.split("_");
		$("#Price"+id).val(result[0]);
		var total = (result[0]*Quantity).toFixed(2);
		$("#Total"+id).val(total);
		$("#TechPrice"+id).val(result[1]);	
	});
}
function ShowTotalPrice(id){
	var price= $("#Price"+id).val();
	var Quantity= $("#Quantity"+id).val();
	var subtotal= (price*Quantity).toFixed(2);
	$("#Total"+id).val(subtotal);
}
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
<body onLoad="document.createJob.cmbClient.focus();">
    <div id="maindiv">
         <div  style="margin:2px;">
                <!-------------Main Body--------------->
                <div class="rightcolumjobboard">
            		<div class="rightcoluminner">
                        <div class="headerbg">ADD JOB</div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        <!-----Table area start------->
                          <form name="createJob" id="createJob" action="client-ajax-create-job" method="post" onSubmit="return validate_createjob();" autocomplete="off" enctype="multipart/form-data">
                        	<input type="hidden" name="action" value="insert"/>
                            <div align="center"><?php if($_REQUEST['msg']=='002'){?><span class="redText">This Email ID already exist!</span><?php }?></div>
                            <!-----address div start--------->
                            <div  class="divAddress">
                            <div class="greenText" align="left">Work Order Details</div>
                            <div class="spacer"></div>
                            <div  class="formtextaddjob">Client:</div>
                            <div  class="textboxcjob">
                               <input type="text" class="textboxjob" name="cmbClient" id="cmbClient" value="<?php echo $resUserName;?>" tabindex="1" readonly>
                            </div>
                            <div class="spacer"></div>
                            <div  class="formtextaddjob">WO#:</div>
                            <div  class="textboxcjob">
                               <input type="text" class="textboxjob" name="WorkOrder" id="WorkOrder" value="<?php echo $workorderno;?>" tabindex="1" readonly><br/><label for="WorkOrder" id="lblWorkOrder" class="redText"></label>
                            </div>
                            <div  class="formtextaddjoblong">Purchase Order:</div>
                            <div  class="textboxcjob">
                               <input type="text" class="textboxjob" name="PurchaseOrder" id="PurchaseOrder" tabindex="2"><br/><label for="PurchaseOrder" id="lblPurchaseOrder" class="redText"></label>
                            </div>
                            <div class="spacer"></div>
                            <div  class="formtextaddjob">Job Status:</div>
                            <div  class="textboxcjob">
                                <input type="text" class="textboxjob" name="JobStatus" id="JobStatus" value="Open" tabindex="3" readonly><br/><label for="JobStatus" id="lblJobStatus" class="redText"></label>
                            </div>
                            <div  class="formtextaddjoblong">Service Name:<span class="redText">*</span></div>
                            <div  class="textboxcjob">
                                <select name="cmbService" id="cmbService" class="selectboxjob" tabindex="4" onChange="showEquipment(this.value);">
                                    <option value="">--Select Service Name--</option>
                                    <?php foreach($dbf->fetch("service","id>0 ORDER BY service_name ASC")as $service){?>
                                    <option value="<?php echo $service['id'];?>" <?php if($service['id']==$_REQUEST['cmbService']){echo 'selected';}?>><?php echo $service['service_name'];?></option>
                                    <?php }?>
                                </select><br/><label for="cmbService" id="lblcmbService" class="redText"></label>
                            </div>
                          <div class="spacer" style="height:12px;"></div>
                         </div>
                         	<!-----address div end--------->
                            <!-----purchase div start--------->
                           	<div  class="divPurchase">
                               <div class="greenText" align="left">Customer Information</div>
                               <div id="rescust">
                                <div  class="formtextaddjob"> Name:<span class="redText">*</span></div>
                                <div  class="textboxcjob">
                                	<input type="text" class="textboxjob" name="CustomerName" id="CustomerName" tabindex="14"><br/><label for="CustomerName" id="lblCustomerName" class="redText"></label>
                                </div>
                                <div  class="formtextaddjoblong">Email Address:</div>
                                <div  class="textboxcjob">
                                    <input type="text" class="textboxjob" name="CustomerEmailID" tabindex="15" id="CustomerEmailID"><br/><label for="CustomerEmailID" id="lblCustomerEmailID" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                
                                <div  class="formtextaddjob">Address:<span class="redText">*</span></div>
                                <div  class="textboxcjob">
                                    <textarea class="textareajob" name="CustAddress" id="CustAddress" tabindex="16"></textarea><br/><label for="CustAddress" id="lblCustAddress" class="redText"></label>
                                </div>
                                <div  class="formtextaddjoblong"> Contact Name:<span class="redText">*</span></div>
                                <div  class="textboxcjob">
                                  <input type="text" class="textboxjob" name="CustomercontactName" id="ContactName" tabindex="17"><br/><label for="CustomercontactName" id="lblCustomercontactName" class="redText"></label>
                                </div>
                                 <div class="spacer"></div>
                                 <div  class="formtextaddjob">City:<span class="redText">*</span></div>
                                <div  class="textboxcjob">
                                    <input type="text" class="textboxjob" name="CustCity" id="CustCity" tabindex="18"><br/><label for="CustCity" id="lblCustCity" class="redText"></label>
                                </div>
                                <div  class="formtextaddjoblong">State:<span class="redText">*</span></div>
                                <div  class="textboxcjob">
                                 <select name="CustState" id="CustState" class="selectboxjob" tabindex="19">
                                    <option value="">--Select State--</option>
                                    <?php foreach($dbf->fetch("state","id>0 ORDER BY state_code ASC")as $CState){?>
                                    <option value="<?php echo $CState['state_code'];?>"><?php echo $CState['state_name'];?></option>
                                    <?php }?>
                                </select><br/><label for="CustState" id="lblCustState" class="redText"></label>
                                </div>
                                 <div class="spacer"></div>
                                 <div  class="formtextaddjob">Zip Code:<span class="redText">*</span></div>
                                <div  class="textboxcjob">
                                    <input type="text" class="textboxjob" name="CustZip" id="CustZip" tabindex="20"><br/><label for="CustZip" id="lblCustZip" class="redText"></label>
                                </div>
                                <div  class="formtextaddjoblong">Phone No:<span class="redText">*</span></div>
                                <div  class="textboxcjob">
                                    <input type="text" class="textboxjob" name="CustPhone" id="CustPhone" onKeyUp="return validatephone(this);" maxlength="12" tabindex="21"><br/><label for="CustPhone" id="lblCustPhone" class="redText"></label>
                                </div>
                                 <div class="spacer"></div>
                                <div  class="formtextaddjob">Fax No:</div>
                                 <div  class="textboxcjob">
                                    <input type="text" class="textboxjob" name="Faxno" id="Faxno" onKeyUp="return validatephone(this);" maxlength="12" tabindex="22"><br/><label for="Faxno" id="lblFaxno" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                            </div>
                        	</div>
                            <!-----purchase div end--------->
                            <!-----pickup div start--------->
                            <div  class="divPickup">
                            	<div class="greenText" align="left">Pick Up Information</div>
                            	<div class="spacer"></div>
                                <div  class="formtextaddjob">Location:</div>
                                <div  class="textboxcjob">
                                    <input type="text" class="textboxjob" name="Pickuplocation" id="Pickuplocation" tabindex="6"><br/><label for="Pickuplocation" id="lblPickuplocation" class="redText"></label>
                                </div>
                                <div  class="formtextaddjoblong">City:</div>
                                <div  class="textboxcjob">
                                    <input type="text" class="textboxjob" name="City" id="City" tabindex="7"><br/><label for="City" id="lblCity" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextaddjob">State:</div>
                                <div  class="textboxcjob">
                                <select name="State" id="State" class="selectboxjob" tabindex="8">
                                    <option value="">--Select State--</option>
                                    <?php foreach($dbf->fetch("state","id>0 ORDER BY state_code ASC")as $State){?>
                                    <option value="<?php echo $State['state_code'];?>"><?php echo $State['state_name'];?></option>
                                    <?php }?>
                                </select><br/><label for="State" id="lblState" class="redText"></label>
                                </div>
                                <div  class="formtextaddjoblong">Address:</div>
                                <div  class="textboxcjob">
                                    <textarea class="textareajob" name="Address" id="Address" tabindex="9"></textarea><br/><label for="Address" id="lblAddress" class="redText"></label>
                                </div>
                                 <div class="spacer"></div>
                                 <div  class="formtextaddjob">Zip Code:</div>
                                <div  class="textboxcjob">
                                    <input type="text" class="textboxjob" name="Zip" id="Zip" tabindex="10"><br/><label for="Zip" id="lblZip" class="redText"></label>
                                </div>
                                <div  class="formtextaddjoblong">Phone Number:</div>
                                <div  class="textboxcjob">
                                    <input type="text" class="textboxjob" name="Phone" id="Phone" onKeyUp="return validatephone(this)" maxlength="12" tabindex="11"><br/><label for="Phone" id="lblPhone" class="redText"></label>
                                </div>
                                 <div class="spacer"></div>
                                 <div  class="formtextaddjob">Alt Phone:</div>
                                <div  class="textboxcjob">
                                    <input type="text" class="textboxjob" name="altPhone" id="altPhone" onKeyUp="return validatephone(this);" maxlength="12" tabindex="12"><br/><label for="altPhone" id="lblaltPhone" class="redText"></label>
                                </div>
                               <div class="spacer" style="height:15px;"></div> 
                            </div>
                            <!-----pickup div end--------->
                            <!-----note div start--------->
                            <div  class="divNotes">
                            <div class="spacer"></div>
                             <div class="greenText" align="left">Job Description:<span class="redText">*</span></div>
                             <div><textarea name="notes" id="notes"  class="textareaOrder" tabindex="22"></textarea><br/><label for="notes" id="lblnotes" class="redText"></label></div>
                             <div class="spacer"></div>
                             <div  class="formtextaddjob">Upload Doc:</div>
                             <div  class="textboxcjob"><input type="file" name="fileUpload" id="fileUpload" tabindex="22"/>
                             </div>
                             <div class="spacer"></div>
                            </div>
                            <!-----note div end--------->
                            <div class="spacer"></div>
                           <!-----service div start--------->
                            <div  class="divService">
                            <div id="equipid">
                              <div>
                                  <div align="center" class="jobheader clService">Work Type</div>
                                  <div align="center" class="jobheader clEquipment">Equipment</div>
                                  <div align="center" class="jobheader clModel">Model</div>
                                  <div align="center" class="jobheader clQunt">Quantity</div>
                                  <div align="center" class="jobheader clPrice">Price/Rate</div>
                                  <div align="center" class="jobheader clPrice">Total</div>
                                  <div align="center" class="jobheader clPrice">Tech Price</div>
                                  <div style="clear:both;"></div>
                              </div>
                              <?php $cntwotype =$dbf->countRows("work_type","");
							  for($i=1; $i<=$cntwotype;$i++){
							  ?>
                              <div align="center" class="jobbody clService">
                              <select class="selectboxjob" name="Worktype<?php echo $i;?>" id="Worktype<?php echo $i;?>" tabindex="23" onChange="ShowPrice('<?php echo $i;?>');">
                              	<option value="">--Select Worktype--</option>
                                <?php foreach($dbf->fetch("work_type","id>0 ORDER BY worktype ASC") as $vawt){?>
                                <option value="<?php echo $vawt['id'];?>"><?php echo $vawt['worktype'];?></option>
                                <?php }?>
                              </select><br/><label for="worktype" id="lblWorktype<?php echo $i;?>" class="redText"></label>
                              </div>
                              <div align="center" class="jobbody clEquipment">
                              <select class="selectboxjob" name="Equipment<?php echo $i;?>" id="Equipment<?php echo $i;?>" tabindex="23" onChange="ShowPrice('<?php echo $i;?>');">
                              	<option value="">--Select Equipment--</option>
                                <?php foreach($dbf->fetch("equipment","id>0 ORDER BY equipment_name ASC") as $valeq){?>
                                <option value="<?php echo $valeq['id'];?>"><?php echo $valeq['equipment_name'];?></option>
                                <?php }?>
                              </select><br/><label for="Equipment1" id="lblEquipment<?php echo $i;?>" class="redText"></label>
                              </div>
                              <div align="center" class="jobbody clModel"><input type="text" class="textboxjob" name="Model<?php echo $i;?>" id="Model<?php echo $i;?>" tabindex="23"><br/><label for="Model" id="lblModel<?php echo $i;?>" class="redText"></label></div>
                              <div align="center" class="jobbody clQunt"><input type="text" class="textboxjob" name="Quantity<?php echo $i;?>" id="Quantity<?php echo $i;?>" onKeyPress="return onlyNumbers(event);" maxlength="2" tabindex="23" onBlur="ShowTotalPrice('<?php echo $i;?>');"><br/><label for="Quantity" id="lblQuantity<?php echo $i;?>" class="redText"></label></div>
                              <div align="center" class="jobbody clPrice"><input type="text" class="textboxjob" name="Price<?php echo $i;?>" id="Price<?php echo $i;?>" onKeyUp="return extractNumber(this,2);" tabindex="23" onBlur="ShowTotalPrice('<?php echo $i;?>');"></div>
                              <div align="center" class="jobbody clPrice"><input type="text" class="textboxjob" name="Total<?php echo $i;?>" id="Total<?php echo $i;?>" onKeyPress="return onlyNumbers(event);" tabindex="23" readonly></div>
                              <div align="center" class="jobbody clPrice"><input type="text" class="textboxjob" name="TechPrice<?php echo $i;?>" id="TechPrice<?php echo $i;?>" onKeyUp="return extractNumber(this,2);" tabindex="23"></div>
                              <div style="clear:both; height:5px;"></div>
                              <?php }?>
                            </div>
                            <div style="clear:both; height:5px;"></div>
                            </div>
                            <!-----service div end--------->
                        	<div class="spacer"></div>
                            <!-----admin notes div start--------->
                            <div  class="divPickup">
                             <div class="greenText" align="left">Admin Notes:</div>
                             <div><textarea name="adminNotes" id="adminNotes"  class="textareaOrderNote" tabindex="24"></textarea><br/><label for="adminNotes" id="lbladminNotes" class="redText"></label></div>
                             <div class="spacer" style="height:10px;"></div>
                            </div>
                            <!-----admin note div end--------->
                            <!-----tech note div start--------->
                           	<div  class="divNotes">
                             <div class="greenText" align="left">Tech Notes:</div>
                             <div><span class="redText">Tech Notes will be displayed here.</span></div>
                             <div class="spacer" style="height:10px;"></div>
                            </div>
                            <!-----tech note div end--------->
                            <div class="spacer"></div>
                            <div align="center">
                                <input type="submit" name="submitbtn" id="submitbtn" class="buttonText" value="Submit Form" tabindex="39"/>
                                <?php if($_REQUEST['src']=='disp'){?>
                                   <a href="manage-job-board-dispatch" style="text-decoration:none;"><input type="button" class="buttonText3" value="Back" tabindex="40"/></a>
                                <?php }else{ ?>
                                   <input type="button" class="buttonText3" value="Close" tabindex="40" onClick="closeFancyBox();"/>
                                <?php } ?>
                             </div>
                          	<div class="spacer"></div>
                           </form>
                           <!-----Table area end------->
                    	</div>
            	</div>
               </div>
            <!-------------Main Body--------------->
        </div>
  </div>
</body>
</html>