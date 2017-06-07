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
	header("location:../logout");exit;
}
//Fetch details from work_order table 
$res_editJobBoard=$dbf->fetchSingle("clients c,service s,work_order w","c.id=w.client_id AND w.service_id=s.id AND w.id='$_REQUEST[id]'");
if($res_editJobBoard ==''){
	header("Location:client-manage-job-board");exit;
}
$resTech = $dbf->fetchSingle("assign_tech at,technicians tc","at.tech_id=tc.id AND at.wo_no='$res_editJobBoard[wo_no]'");
//fetch from work order doc table
$workorderdoc = $dbf->getDataFromTable("workorder_doc","wo_document","workorder_id='$_REQUEST[id]'");

if($_REQUEST['action']=="update"){
	//prepare request string
	$PurchaseOrder=mysql_real_escape_string(trim($_POST['PurchaseOrder']));
	$Pickuplocation=mysql_real_escape_string(trim($_POST['Pickuplocation']));
	$CustomerName=mysql_real_escape_string(trim($_REQUEST['CustomerName']));
	$CustomercontactName=mysql_real_escape_string(trim($_REQUEST['CustomercontactName']));
	$CustAddress=mysql_real_escape_string(trim($_REQUEST['CustAddress']));
	$CustCity=mysql_real_escape_string(trim($_REQUEST['CustCity']));
	$CustState=mysql_real_escape_string(trim($_REQUEST['CustState']));
	//get latitude and longitude
	$val = $dbf->getLnt($CustAddress,$CustCity,$CustState.' '.$_REQUEST['CustZip']);
	//condition if customer id is exist
	if($_REQUEST['custid']){
		//assign customer id
		$clientid = $_REQUEST['custid'];
		//update  clients table
		//string for update table
		$string="name='$CustomerName', user_type='customer', email='$_REQUEST[CustomerEmailID]', contact_name='$CustomercontactName', address='$CustAddress', city='$CustCity', state='$CustState', fax_no='$_REQUEST[Faxno]', zip_code='$_REQUEST[CustZip]', phone_no='$_REQUEST[CustPhone]', alt_phone='$_REQUEST[altCustPhone]',latitude='".$val['lat']."',longitude='".$val['lng']."',status=0,updated_date=now(),updated_by='$_SESSION[userid]'";
		$dbf->updateTable("clients",$string,"id='$clientid'");
	}else{
		if($_REQUEST['clientid']){
			//update clients table
			$string="name='$CustomerName', user_type='customer', email='$_REQUEST[CustomerEmailID]', contact_name='$CustomercontactName', address='$CustAddress', city='$CustCity', state='$CustState', fax_no='$_REQUEST[Faxno]', zip_code='$_REQUEST[CustZip]', phone_no='$_REQUEST[CustPhone]', alt_phone='$_REQUEST[altCustPhone]',latitude='".$val['lat']."',longitude='".$val['lng']."', status=0, updated_date=now(),updated_by='$_SESSION[userid]'";
			$dbf->updateTable("clients",$string,"id='$_REQUEST[clientid]'");
			$clientid=$_REQUEST['clientid'];
		}else{
			//insert into clients table
			//string for insert into table
			$string="name='$CustomerName', user_type='customer', email='$_REQUEST[CustomerEmailID]', contact_name='$CustomercontactName', address='$CustAddress', city='$CustCity', state='$CustState',fax_no='$_REQUEST[Faxno]',zip_code='$_REQUEST[CustZip]', phone_no='$_REQUEST[CustPhone]', alt_phone='$_REQUEST[altCustPhone]',latitude='".$val['lat']."',longitude='".$val['lng']."',status=0,created_date=now(),created_by='$_SESSION[userid]'";
			$clientid=$dbf->insertSet("clients",$string);
		}
	}
	//check for new service id 
	$srvid = $dbf->getDataFromTable("work_order","service_id","id='$_REQUEST[worid]'");
	if($srvid <> $_REQUEST['cmbService']){
		//delete from work order service table
		$dbf->deleteFromTable("workorder_service","workorder_id='$_REQUEST[worid]'");
	}
	//update into work order table
	$PartsArrival=$_REQUEST['partsArrive']?date("Y-m-d",strtotime($_REQUEST['partsArrive'])):'';
	$string1="wo_no='$_REQUEST[WorkOrder]', purchase_order_no='$PurchaseOrder',   service_id='$_REQUEST[cmbService]',client_id='$clientid', pickup_location='$Pickuplocation', pickup_address='$_REQUEST[Address]', pickup_city='$_REQUEST[City]', pickup_state='$_REQUEST[State]', pickup_zip_code='$_REQUEST[Zip]', pickup_phone_no='$_REQUEST[Phone]',pickup_alt_phone='$_REQUEST[altPhone]',tracking_number='$_REQUEST[track_no]',carrier_company='$_REQUEST[carrier_company]',parts_arrive='$PartsArrival',work_status='$_REQUEST[JobStatus]', notes='$_REQUEST[notes]',updated_date=now(),updated_by='$_SESSION[userid]'";
	$dbf->updateTable("work_order",$string1,"id='$_REQUEST[worid]'");
	$woid = $_REQUEST['worid'];
	##########Insert Into workorder service table###########
	$cntwotype =$dbf->countRows("work_type","");
	for($i=1;$i<=$cntwotype;$i++){
		$hid='hid'.$i;
		$hid=$_REQUEST[$hid];
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
		//check duplicate entry
		$num=$dbf->countRows("workorder_service","id='$hid'");
		if($num==0){
			if($equip<>''){
				//insert into workorder service table
				$string2="workorder_id='$woid', service_id='$_REQUEST[cmbService]', equipment='$equip', work_type='$Worktype', model='$model', quantity='$Quantity', outbox_price='$Price', tech_price='$TechPrice', created_date=now()";
			   $dbf->insertSet("workorder_service",$string2);
			 }
		}else{
			if($equip<>''){
				//update into workorder service table
				 $string2="workorder_id='$woid', service_id='$_REQUEST[cmbService]', equipment='$equip', work_type='$Worktype', model='$model', quantity='$Quantity', outbox_price='$Price', tech_price='$TechPrice', created_date=now()";
			   $dbf->updateTable("workorder_service",$string2,"id='$hid'");
			}
	  }
	}
	##########Insert Into workorder service table###########
	###########Update work order doc table#############
	if($_FILES['fileUpload']['name']<>''){
		$file_name=$_REQUEST['WorkOrder'].'_'.$_FILES['fileUpload']['name'];
		$path="../workorder_doc/";
		$docimg=$dbf->getDataFromTable("workorder_doc","wo_document","workorder_id='$woid'");
		if($docimg<>''){
			unlink($path.$docimg);
			move_uploaded_file($_FILES['fileUpload']['tmp_name'],$path.$file_name);
			//update workorder doc table
			$string3="wo_document='$file_name'";
			$dbf->updateTable("workorder_doc",$string3,"workorder_id='$woid'");
			
		}else{
			move_uploaded_file($_FILES['fileUpload']['tmp_name'],$path.$file_name);
			//insert into workorder doc table
			$string3="workorder_id=$woid, wo_document='$file_name',created_date=now(),created_user='$_SESSION[userid]', user_type='$_SESSION[usertype]'";
			$dbf->insertSet("workorder_doc",$string3);
		}
	}
	###########Update Into work order doc table#############
	header("Location:client-edit-job-board?id=$_REQUEST[worid]&msg=suc&hidk=$_REQUEST[hidk]");exit;
}
?>
<link rel="stylesheet" href="../css/innermain.css" type="text/css" />
<link rel="stylesheet" href="../css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="../css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="../css/respmenu.css" type="text/css" />
<script type="text/javascript">
$(document).ready(function(){
	//autopopulation by email
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
function showEquipment(servid){
	var worid = $("#worid").val();
	var url="ajax_equipment.php";
	$.post(url,{"serviceid":servid,"id":worid},function(res){
		$('#equipid').html(res);
	});
}
function ShowPrice(id){
	var clientid = $("#hidClient").val();
	var techgrade = $("#techgrade").val();
	var serviceid = $("#cmbService").val();
	var equipid = $("#Equipment"+id).val();
	var worktypeid = $("#Worktype"+id).val();
	var Quantity = $("#Quantity"+id).val();
	var url="../ajax_service_price.php";
	$.post(url,{"serviceid":serviceid,"equipid":equipid,"worktypeid":worktypeid,"techgrade":techgrade,"clientid":clientid},function(res){	
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
function downLoadDocument(fname){
	window.location.href='../docdnd.php?file=workorder_doc/'+fname;
}
function add_notes(){
	var url="ajax_addnote.php";
	var worid = $("#worid").val();
	var adminNotes = $("#adminNotes").val();
	if(adminNotes !=''){
		$.post(url,{"id":worid,"adminNotes":adminNotes},function(res){			
			$("#resnotes").html(res);
			$("#adminNotes").val('');			
		});
	}
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
$(document).ready(function(){
	$("form :input").each(function(){
	 //if($(this).attr("id") !='loginid'){
		  $(this).keyup(function(event){
			var xss =  $(this);
			var maintainplus = '';
			var numval = xss.val();
			curphonevar = numval.replace(/[\\!"£$%^&*+={};:'#~()¦\<>?|`¬\]\[]/g,'');
			xss.val(maintainplus + curphonevar) ;
			var maintainplus = '';
			xss.focus;
		  });
	 //}
	});
});
</script>
<body>
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
                     <form name="createJob" id="createJob" action="" method="post" onSubmit="return validate_createjob();" autocomplete="off" enctype="multipart/form-data">
                        <div class="headerbg">
                        	<div style="float:left;">CLIENT EDIT ORDER</div>
                            <div style="float:right;">
                            	<input type="submit" name="submitbtn" id="submitbtn" class="buttonText2" value="Submit Form" tabindex="38"/>
                                <input type="button" class="buttonText2" value="Back" onClick="javascript:window.location.href='client-manage-job-board?g=<?php echo $_REQUEST['hidk']?>'" tabindex="39"></div>
                        </div>
                        <?php if($_REQUEST['msg']=='suc'){?>
                        <div align="center" style="color:green;font-weight:bold;">Records updated successfully</div>
                        <?php }else{?>
                        <div class="spacer"></div>
                        <?php }?>
                        <div id="contenttable">
                        <!-----Table area start------->
                        	<input type="hidden" name="action" value="update"/>
                            <input type="hidden" name="clientid" value="<?php echo $res_editJobBoard['client_id'];?>">
                            <input type="hidden" name="worid" id="worid" value="<?php echo $res_editJobBoard['id'];?>">
                            <input type="hidden" name="id" value="<?php echo $res_editJobBoard['id'];?>">
                            <input type="hidden" name="hidk" id="hidk" value="<?php echo $_REQUEST['hidk'];?>">
                            <div align="center"><?php if($_REQUEST['msg']=='002'){?><span class="redText">This Email ID already exist!</span><?php }?></div>
                            <!-----address div start--------->
                            <div  class="divAddress">
                            <div class="greenText" align="left">Work Order Details</div>
                            <div class="spacer"></div>
                            <div  class="formtextaddjob">Client:</div>
                            <div  class="textboxcjob">
                            <input type="text" class="textboxjob" name="cmbClient" id="cmbClient" value="<?php echo $resUserName;?>" tabindex="1" readonly>
                            <input type="hidden" name="hidClient" id="hidClient" value="<?php echo $_SESSION['userid'];?>">
                            </div>
                            <div class="spacer"></div>
                            <div  class="formtextaddjob">WO#:</div>
                            <div  class="textboxcjob">
                               <input type="text" class="textboxjob" name="WorkOrder" id="WorkOrder" value="<?php echo $res_editJobBoard['wo_no'];?>" readonly tabindex="1"><br/><label for="WorkOrder" id="lblWorkOrder" class="redText"></label>
                            </div>
                            <div  class="formtextaddjoblong">Purchase Order:</div>
                            <div  class="textboxcjob">
                               <input type="text" class="textboxjob" name="PurchaseOrder" id="PurchaseOrder" value="<?php echo $res_editJobBoard['purchase_order_no'];?>" tabindex="2"><br/><label for="PurchaseOrder" id="lblPurchaseOrder" class="redText"></label>
                            </div>
                            <div class="spacer"></div>
                            <div  class="formtextaddjob">Order Status:</div>
                            <div  class="textboxcjob">
                                <input type="text" class="textboxjob" name="JobStatus" id="JobStatus" value="<?php echo $res_editJobBoard['work_status'];?>" readonly tabindex="3"><br/><label for="JobStatus" id="lblJobStatus" class="redText"></label>
                            </div>
                            <div  class="formtextaddjoblong">Service Name:<span class="redText">*</span></div>
                            <div  class="textboxcjob">
                                <select name="cmbService" id="cmbService" class="selectboxjob" style="height:28px;width:99%" tabindex="4" onChange="showEquipment(this.value);">
                                    <option value="">--Select Service Name--</option>
                                    <?php foreach($dbf->fetch("service","id")as $service){?>
                                    <option value="<?php echo $service['id'];?>" <?php if($service['id']==$res_editJobBoard['service_id']){echo 'selected';}?>><?php echo $service['service_name'];?></option>
                                    <?php }?>
                                </select><br/><label for="cmbService" id="lblcmbService" class="redText"></label>
                            </div>
                            <div class="spacer" style="height:12px;"></div>
                           </div>
                         	<!-----address div end--------->
                            <!-----purchase div start--------->
                           	<div  class="divPurchase">
                               <div class="greenText" align="left">Customer Information <span style="color:#666; font-weight:normal;">(Choose auto suggestion to avoid customer redundancy)</span></div>
                               <div id="rescust">
                                <div  class="formtextaddjob"> Name:<span class="redText">*</span></div>
                                <div  class="textboxcjob">
                                	<input type="text" class="textboxjob" name="CustomerName" id="CustomerName" value="<?php echo $res_editJobBoard['name'];?>" tabindex="13"><br/><label for="CustomerName" id="lblCustomerName" class="redText"></label>
                                </div>
                                <div  class="formtextaddjoblong">Email Address:</div>
                                <div  class="textboxcjob">
                                    <input type="text" class="textboxjob" name="CustomerEmailID" id="CustomerEmailID" value="<?php echo $res_editJobBoard['email'];?>" tabindex="14"><br/><label for="CustomerEmailID" id="lblCustomerEmailID" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextaddjob">Address:<span class="redText">*</span></div>
                                <div  class="textboxcjob">
                                    <textarea class="textareajob" name="CustAddress" id="CustAddress" tabindex="15"><?php echo $res_editJobBoard['address'];?></textarea><br/><label for="CustAddress" id="lblCustAddress" class="redText"></label>
                                </div>
                                <div  class="formtextaddjoblong"> Contact Name:<span class="redText">*</span></div>
                                <div  class="textboxcjob">
                                  <input type="text" class="textboxjob" name="CustomercontactName" id="ContactName" value="<?php echo $res_editJobBoard['contact_name'];?>" tabindex="16"><br/><label for="CustomercontactName" id="lblCustomercontactName" class="redText"></label>
                                </div>
                                 <div class="spacer"></div>
                                 <div  class="formtextaddjob">City:<span class="redText">*</span></div>
                                <div  class="textboxcjob">
                                    <input type="text" class="textboxjob" name="CustCity" id="CustCity" value="<?php echo $res_editJobBoard['city'];?>" tabindex="17" onKeyPress="return onlyLetters(event)"><br/><label for="CustCity" id="lblCustCity" class="redText"></label>
                                </div>
                                <div  class="formtextaddjoblong">State:<span class="redText">*</span></div>
                                <div  class="textboxcjob">
                                <select name="CustState" id="CustState" class="selectboxjob" tabindex="18">
                                 <option value="">--Select State--</option>
                                 <?php foreach($dbf->fetch("state","id>0 ORDER BY state_code ASC")as $CState){?>
                                 <option value="<?php echo $CState['state_code'];?>"<?php if($CState['state_code']==$res_editJobBoard['state']){echo 'selected';}?>><?php echo $CState['state_name'];?></option>
                                 <?php }?>
                                </select>
                                 <br/><label for="CustState" id="lblCustState" class="redText"></label>
                                </div>
                                 <div class="spacer"></div>
                                 <div  class="formtextaddjob">Zip Code:<span class="redText">*</span></div>
                                <div  class="textboxcjob">
                                    <input type="text" class="textboxjob" name="CustZip" id="CustZip" value="<?php echo $res_editJobBoard['zip_code'];?>" tabindex="19" maxlength="12"><br/><label for="CustZip" id="lblCustZip" class="redText"></label>
                                </div>
                                <div  class="formtextaddjoblong">Phone No:<span class="redText">*</span></div>
                                <div  class="textboxcjob">
                                    <input type="text" class="textboxjob" name="CustPhone" id="CustPhone" value="<?php echo $res_editJobBoard['phone_no'];?>" onKeyUp="return validatephone(this);" tabindex="20"><br/><label for="CustPhone" id="lblCustPhone" class="redText"></label>
                                </div>
                                 <div class="spacer"></div>
                                <div  class="formtextaddjob">Cell No:</div>
                                 <div  class="textboxcjob">
                                    <input type="text" class="textboxjob" name="Faxno" id="Faxno" value="<?php echo $res_editJobBoard['fax_no'];?>" onKeyUp="return validatephone(this);" tabindex="21"><br/><label for="Faxno" id="lblFaxno" class="redText"></label>
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
                                    <input type="text" class="textboxjob" name="Pickuplocation" id="Pickuplocation" value="<?php echo $res_editJobBoard['pickup_location'];?>" tabindex="6"><br/><label for="Pickuplocation" id="lblPickuplocation" class="redText"></label>
                                </div>
                                <div  class="formtextaddjoblong">City:</div>
                                <div  class="textboxcjob">
                                    <input type="text" class="textboxjob" name="City" id="City" value="<?php echo $res_editJobBoard['pickup_city'];?>" tabindex="7" onKeyPress="return onlyLetters(event)"><br/><label for="City" id="lblCity" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextaddjob">State:</div>
                                <div  class="textboxcjob">
                                 <select name="State" id="State" class="selectboxjob" tabindex="8">
                                    <option value="">--Select State--</option>
                                    <?php foreach($dbf->fetch("state","id>0 ORDER BY state_code ASC")as $State){?>
                                    <option value="<?php echo $State['state_code'];?>"<?php if($res_editJobBoard['pickup_state']==$State['state_code']){echo 'selected';}?>><?php echo $State['state_name'];?></option>
                                    <?php }?>
                                 </select><br/><label for="State" id="lblState" class="redText"></label>
                                </div>
                                <div  class="formtextaddjoblong">Address:</div>
                                <div  class="textboxcjob">
                                    <textarea class="textareajob" name="Address" id="Address" tabindex="9"><?php echo $res_editJobBoard['pickup_address'];?></textarea><br/><label for="Address" id="lblAddress" class="redText"></label>
                                </div>
                                 <div class="spacer"></div>
                                 <div  class="formtextaddjob">Zip Code:</div>
                                <div  class="textboxcjob">
                                    <input type="text" class="textboxjob" name="Zip" id="Zip" value="<?php echo $res_editJobBoard['pickup_zip_code'];?>" tabindex="10" maxlength="12"><br/><label for="Zip" id="lblZip" class="redText"></label>
                                </div>
                                <div  class="formtextaddjoblong">Phone Number:</div>
                                <div  class="textboxcjob">
                                    <input type="text" class="textboxjob" name="Phone" id="Phone" value="<?php echo $res_editJobBoard['pickup_phone_no'];?>" onKeyUp="return validatephone(this);" maxlength="12" tabindex="11"><br/><label for="Phone" id="lblPhone" class="redText"></label>
                                </div>
                                 <div class="spacer"></div>
                                 <div  class="formtextaddjob">Alt Phone:</div>
                                <div  class="textboxcjob">
                                    <input type="text" class="textboxjob" name="altPhone" id="altPhone" value="<?php echo $res_editJobBoard['pickup_alt_phone'];?>" onKeyUp="return validatephone(this);" maxlength="12" tabindex="12"><br/><label for="altPhone" id="lblaltPhone" class="redText"></label>
                                </div>
                                <div  class="formtextaddjoblong">Parts Arrive:</div>
                                <div  class="textboxcjob">
                                <input type="text" class="textboxjob datepick" name="partsArrive" id="partsArrive" readonly value="<?php if($res_editJobBoard['parts_arrive']!='0000-00-00'){echo date('d-M-Y',strtotime($res_editJobBoard['parts_arrive']));}else{echo "";}?>"><br/><label for="partsArrive" id="lblpartsArrive" class="redText"></label>  
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextaddjob">Tracking No:</div>
                                <div  class="textboxcjob">
                                    <input type="text" class="textboxjob" name="track_no" id="track_no" value="<?php echo $res_editJobBoard['tracking_number'];?>" tabindex="10"><br/><label for="track_no" id="lbltrack_no" class="redText"></label>
                                </div>
                                <div  class="formtextaddjoblong">Carrier Company:</div>
                                <div  class="textboxcjob">
                                    <input type="text" class="textboxjob" name="carrier_company" id="carrier_company" value="<?php echo $res_editJobBoard['carrier_company'];?>"  tabindex="11"><br/><label for="carrier_company" id="lblcarrier_company" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextaddjob">Serial Number:</div>
                                <div  class="textboxcjob">
                                   <input type="text" class="textboxjob" name="serial_number" id="serial_number" tabindex="16" value="<?php echo $res_editJobBoard['serial_number'];?>"><br/><label for="serialNumber" id="lblserialNumber" class="redText"></label>
                                </div>
                                <div  class="formtextaddjoblong">Model Number:</div>
                                <div  class="textboxcjob">
                                    <input type="text" class="textboxjob" name="model_number" id="model_number"  tabindex="17" value="<?php echo $res_editJobBoard['model_number'];?>"><br/><label for="modelNumber" id="lblmodelNumber" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                            </div>
                            <!-----pickup div end--------->
                            <!-----note div start--------->
                            <div  class="divNotes">
                            <div class="spacer"></div>
                             <div class="greenText" align="left">Order Description:<span class="redText">*</span></div>
                             <div><textarea name="notes" id="notes"  class="textareaOrder" tabindex="22" style="min-height:100px;"><?php echo $res_editJobBoard['notes'];?></textarea><br/><label for="notes" id="lblnotes" class="redText"></label></div>
                             <div class="spacer"></div>
                             <div  class="formtextaddjob">Upload Doc:</div>
                             	<div  class="textboxcjob"><input type="file" name="fileUpload" id="fileUpload" tabindex="22"/>
                             	</div>
                             	<div style="float:right; padding-right:5px;"><span class="formtext"><a href="javascript:void(0);" onClick="downLoadDocument('<?php echo $workorderdoc;?>');"><?php echo $workorderdoc;?></a></span></div>
                               <div class="spacer"></div>
                            </div>
                            <!-----note div end--------->
                            <div class="spacer"></div>
                            <!-----Tech Instruction start--------->
                            <?php 
							$techInstruction = $dbf->getDataFromTable("clients","tech_instruction","id='$_SESSION[userid]'");
							$techInstruction = ($techInstruction !='')?$techInstruction :"No instruction given by client.";
							?>
                            <div  class="divService">
                            	<div class="greenText" align="left">Tech Instruction:</div>
                             	<div style="color:#C69; font-weight:bold;" id="techInstruction"><?php echo $techInstruction;?></div>
                                <div class="spacer"></div>
                            </div>
                            <!-----Tech Instruction start--------->
                            <div class="spacer"></div>
                           <!-----service div start--------->
                            <div  class="divService">
                              <div id="equipid">	
                              <div>
                                  <div align="center" class="jobheader clService">Work Type</div>
                                  <div align="center" class="jobheader clEquipment">Equipment</div>
                                  <div align="center" class="jobheader clModel">Model</div>
                                  <div align="center" class="jobheader clQunt">Quantity</div>
                                  <div align="center" class="jobheader clPrice1">Price/Rate</div>
                                  <div align="center" class="jobheader clPrice1">Total</div>
                                  <div style="clear:both;"></div>
                              </div>
                             <?php 
							 $res_woservice = $dbf->fetch("workorder_service","workorder_id='$_REQUEST[id]'");
							 $arrWorkservice =array();$i=1;
							 $cntwotype =$dbf->countRows("work_type","status=1");
							 $cntwotype=floor($cntwotype/2);
							  for($j=0; $j<$cntwotype;$j++){
								  $arrWorkservice= $res_woservice[$j];
								  $total = ($arrWorkservice['quantity']*$arrWorkservice['outbox_price']);
							  ?>
                              <div align="center" class="jobbody clService">
                              <input type="hidden" name="hid<?php echo $i;?>" value="<?php echo $arrWorkservice['id'];?>">
                              <select class="selectboxjob" name="Worktype<?php echo $i;?>" id="Worktype<?php echo $i;?>" tabindex="23" onChange="ShowPrice('<?php echo $i;?>');">
                              	<option value="">--Select Worktype--</option>
                                <?php foreach($dbf->fetch("work_type","id>0 AND status=1 ORDER BY id ASC") as $vawt){?>
                                <option value="<?php echo $vawt['id'];?>" <?php if($arrWorkservice['work_type']==$vawt['id']){echo 'selected';}?>><?php echo $vawt['worktype'];?></option>
                                <?php }?>
                              </select><br/><label for="worktype" id="lblWorktype<?php echo $i;?>" class="redText"></label>
                              </div>
                              <div align="center" class="jobbody clEquipment">
                              <select class="selectboxjob" name="Equipment<?php echo $i;?>" id="Equipment<?php echo $i;?>" tabindex="23" onChange="ShowPrice('<?php echo $i;?>');">
                              	<option value="">--Select Equipment--</option>
                                <?php foreach($dbf->fetch("equipment","id>0 AND service_id='$res_editJobBoard[service_id]' ORDER BY id ASC") as $valeq){?>
                                <option value="<?php echo $valeq['id'];?>" <?php if($arrWorkservice['equipment']==$valeq['id']){echo 'selected';}?>><?php echo $valeq['equipment_name'];?></option>
                                <?php }?>
                              </select><br/><label for="Equipment1" id="lblEquipment<?php echo $i;?>" class="redText"></label>
                              </div>
                              <div align="center" class="jobbody clModel"><input type="text" class="textboxjob" name="Model<?php echo $i;?>" id="Model<?php echo $i;?>" value="<?php echo $arrWorkservice['model'];?>" tabindex="23"><br/><label for="Model" id="lblModel<?php echo $i;?>" class="redText"></label></div>
                              <div align="center" class="jobbody clQunt"><input type="text" class="textboxjob" name="Quantity<?php echo $i;?>" id="Quantity<?php echo $i;?>" value="<?php echo $arrWorkservice['quantity'];?>" onKeyPress="return onlyNumbers(event);" maxlength="2" tabindex="23" onBlur="ShowTotalPrice('<?php echo $i;?>');"><br/><label for="Quantity" id="lblQuantity<?php echo $i;?>" class="redText"></label></div>
                              <div align="center" class="jobbody clPrice1"><input type="text" class="textboxjob" name="Price<?php echo $i;?>" id="Price<?php echo $i;?>" value="<?php echo $arrWorkservice['outbox_price'];?>" onKeyUp="return extractNumber(this,2);" tabindex="23" onBlur="ShowTotalPrice('<?php echo $i;?>');"></div>
                              <div align="center" class="jobbody clPrice1"><input type="text" class="textboxjob" name="Total<?php echo $i;?>" id="Total<?php echo $i;?>" value="<?php if($total)echo number_format($total,2);?>" onKeyPress="return onlyNumbers(event);" tabindex="23" readonly></div>
                              <div style="clear:both; height:5px;"></div>
                              <?php $i++;}?>
                              </div>
                              <div style="clear:both; height:5px;"></div>
                            </div>
                           <!-----service div end--------->
                            <div class="spacer"></div>
                            <div align="center">
                                <input type="submit" name="submitbtn" id="submitbtn" class="buttonText" value="Submit Form" tabindex="38"/>
                                <input type="button" class="buttonText3" value="Back" onClick="javascript:window.location.href='client-manage-job-board'" tabindex="39">
                             </div>
                          	<div class="spacer"></div>
                           <!-----Table area end------->
                    	</div>
                        </form>
            		</div>
               </div>
              <!-------------Main Body--------------->
         </div>
        <div class="spacer"></div>
        <?php include_once 'footer-client.php'; ?>
  </div>
</body>
</html>