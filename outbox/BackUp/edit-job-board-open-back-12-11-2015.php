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
$res_editJobBoard = array(); $resTech = array();
//Fetch details from work_order table 
$res_editJobBoard=$dbf->fetchSingle("clients c,service s,work_order w","c.id=w.client_id AND w.service_id=s.id AND w.id='$_REQUEST[id]'");
if(empty($res_editJobBoard)){
	header("location:manage-job-board");exit;
}
//get technician details
$resTech = $dbf->fetchSingle("assign_tech at,technicians tc","at.tech_id=tc.id AND at.wo_no='$res_editJobBoard[wo_no]' AND tc.status=1");
//fetch from work order doc table
//$workorderdoc = $dbf->getDataFromTable("workorder_doc","wo_document","workorder_id='$_REQUEST[id]'");
##########Update Preparation Start ###########
if(isset($_REQUEST['action']) && $_REQUEST['action']=="update" && $_SERVER['REQUEST_METHOD']=='POST'){
	/*print'<pre>';
	print_r($_REQUEST);exit;*/
	$PurchaseOrder=mysql_real_escape_string(trim($_POST['PurchaseOrder']));
	$Pickuplocation=mysql_real_escape_string(trim($_POST['Pickuplocation']));
	//prepare request strings
	$CustomerEmailID=mysql_real_escape_string(trim($_REQUEST['CustomerEmailID']));
	$CustomerName=mysql_real_escape_string(trim($_REQUEST['CustomerName']));
	$CustomercontactName=mysql_real_escape_string(trim($_REQUEST['CustomercontactName']));
	$CustAddress=mysql_real_escape_string(trim($_REQUEST['CustAddress']));
	$CustCity=mysql_real_escape_string(trim($_REQUEST['CustCity']));
	$CustState=mysql_real_escape_string(trim($_REQUEST['CustState']));
	//get latitude and longitude
	$val = $dbf->getLnt($CustAddress.",".$CustCity.",".$CustState.",".$_REQUEST['CustZip']);
	//$val = $dbf->getLnt($_REQUEST['CustZip']);
	//print_r($val);exit;
	if($_REQUEST['cmbService'] ==''){
		header("Location:edit-job-board-open?id=$_REQUEST[worid]");exit;
	}
	//condition if customer id is exist
	if($_REQUEST['custid']){
		//assign customer id
		$clientid = $_REQUEST['custid'];
		//update clients table
		//string for update table
		$string="name='$CustomerName', user_type='customer', email='$CustomerEmailID', contact_name='$CustomercontactName', address='$CustAddress', city='$CustCity', state='$CustState',fax_no='$_REQUEST[Faxno]',zip_code='$_REQUEST[CustZip]', phone_no='$_REQUEST[CustPhone]', alt_phone='$_REQUEST[altCustPhone]',latitude='".$val['lat']."',longitude='".$val['lng']."', updated_date=now(),updated_by='$_SESSION[userid]'";
		$dbf->updateTable("clients",$string,"id='$clientid'");
	}else{
		if($_REQUEST['clientid']){
			//update clients table
			$string="name='$CustomerName', user_type='customer', email='$CustomerEmailID', contact_name='$CustomercontactName', address='$CustAddress', city='$CustCity', state='$CustState', fax_no='$_REQUEST[Faxno]', zip_code='$_REQUEST[CustZip]', phone_no='$_REQUEST[CustPhone]', alt_phone='$_REQUEST[altCustPhone]',latitude='".$val['lat']."',longitude='".$val['lng']."', status=0, updated_date=now(),updated_by='$_SESSION[userid]'";
			$dbf->updateTable("clients",$string,"id='$_REQUEST[clientid]'");
			$clientid=$_REQUEST['clientid'];
		}else{
			//insert into clients table
			$string="name='$CustomerName', user_type='customer', email='$CustomerEmailID', contact_name='$CustomercontactName', address='$CustAddress', city='$CustCity', state='$CustState', fax_no='$_REQUEST[Faxno]', zip_code='$_REQUEST[CustZip]', phone_no='$_REQUEST[CustPhone]', alt_phone='$_REQUEST[altCustPhone]',latitude='".$val['lat']."',longitude='".$val['lng']."', status=0, created_date=now(),created_by='$_SESSION[userid]'";
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
	$Address=mysql_real_escape_string($_REQUEST['Address']);
	$City=mysql_real_escape_string($_REQUEST['City']);
	$State=mysql_real_escape_string($_REQUEST['State']);
	$notes=mysql_real_escape_string($_REQUEST['notes']);
	$string1="wo_no='$_REQUEST[WorkOrder]', purchase_order_no='$PurchaseOrder',   service_id='$_REQUEST[cmbService]',client_id='$clientid', pickup_location='$Pickuplocation', pickup_address='$Address', pickup_city='$City', pickup_state='$State', pickup_zip_code='$_REQUEST[Zip]', pickup_phone_no='$_REQUEST[Phone]', pickup_alt_phone='$_REQUEST[altPhone]',tracking_number='$_REQUEST[track_no]',carrier_company='$_REQUEST[carrier_company]',parts_arrive='$_REQUEST[cmbpartsArrive]', work_status='$_REQUEST[cmbStatus]', notes='$notes', created_by='$_REQUEST[cmbClient]', updated_date=now(), updated_by='$_SESSION[userid]'";
	$dbf->updateTable("work_order",$string1,"id='$_REQUEST[worid]'");
	$woid = $_REQUEST['worid'];
	##########Insert Into workorder service table###########
	$cntwotype =$dbf->countRows("work_type","");
	
	if($_REQUEST['Worktype1']!="" && $_REQUEST['hid1']==""){
		$dbf->deleteFromTable("workorder_service","workorder_id='$_REQUEST[worid]'");
	}
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
			//$dbf->deleteFromTable("workorder_service","workorder_id='$_REQUEST[worid]'");
			if($equip<>''){
				//insert into workorder service table
				$string2="workorder_id=$woid, service_id='$_REQUEST[cmbService]', equipment='$equip',work_type='$Worktype', model='$model', quantity='$Quantity', outbox_price='$Price', tech_price='$TechPrice',created_date=now()";
			   $dbf->insertSet("workorder_service",$string2);
			 }
		}else{
			if($equip<>''){
				//update into workorder service table
				$string2="workorder_id=$woid, service_id='$_REQUEST[cmbService]', equipment='$equip',work_type='$Worktype', model='$model', quantity='$Quantity', outbox_price='$Price', tech_price='$TechPrice', created_date=now()";
			   $dbf->updateTable("workorder_service",$string2,"id='$hid'");
			}
	  }
	}
	##########Insert Into workorder service table###########
	###########Track user activity in work order notes table#############
	$adminNotes="This work order is updated.";
	$strnotes="workorder_id='$woid', user_type='$_SESSION[usertype]', user_id='$_SESSION[userid]', wo_notes='$adminNotes',created_date=now()";
	$dbf->insertSet("workorder_notes",$strnotes);
	###########Track user activity in work order notes table#############
	header("Location:edit-job-board-open?id=$_REQUEST[worid]&msg=001&hidk=$_REQUEST[hidk]");exit;
	
}
##########Update Preparation End ###########
?>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />
<style type="text/css">
	/* Easy CSS Tooltip - by Koller Juergen [www.kollermedia.at] 
	* {font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px; }*/
	a:hover {text-decoration:none;} /*BG color is a must for IE6*/
	a.tooltip span {display:none; padding:2px 3px 0px 5px; margin-left:6px; margin-top:-70px; width:280px;border-radius:5px;
	-moz-border-radius:5px;}
	a.tooltip:hover span{display:inline; position:absolute; border:3px solid  #ff9812; background:#EEEEEE; color:#000;border-radius:6px;-moz-border-radius:6px;}
</style>
<script type="text/javascript">
$(document).ready(function() {
	//auto population by email
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
function showTechInstruction(){
	var clientid = $("#cmbClient").val();
	var url="ajax-client-delete.php";
	$.post(url,{"choice":"fetch_instruction","clientid":clientid},function(res){//alert(res);			
		$("#techInstruction").html(res);		
	});
	
}
function showEquipment(){
	var servid = $("#cmbService").val();
	var worid = $("#worid").val();
	var clientid = $("#cmbClient").val();
	var url="ajax_equipment.php";
	$.post(url,{"serviceid":servid,"id":worid,"clientid":clientid},function(res){			
		$("#equipid").html(res);			
	});
}
function ShowPrice(id){
	var clientid = $("#cmbClient").val();
	var techgrade = $("#techgrade").val();
	var serviceid = $("#cmbService").val();
	var equipid = $("#Equipment"+id).val();
	var worktypeid = $("#Worktype"+id).val();
	var Quantity = $("#Quantity"+id).val();
	var url="ajax_service_price.php";
	$.post(url,{"serviceid":serviceid,"equipid":equipid,"worktypeid":worktypeid,"techgrade":techgrade,"clientid":clientid},function(res){	
	//alert(res);
		var result=res.split("_");
		$("#Price"+id).val(result[0]);
		var total = (result[0]*Quantity).toFixed(2);
		$("#Total"+id).val(total);
		$("#TechPrice"+id).val(result[1]);
		/**********show subtotal price**********/
		ShowSubTotalPrice();
		/**********show subtotal price**********/
	});
}
function ShowTotalPrice(id){
	var price= $("#Price"+id).val();
	var Quantity= $("#Quantity"+id).val();
	var total= (price*Quantity).toFixed(2);
	$("#Total"+id).val(total);
	/**********show subtotal price**********/
	ShowSubTotalPrice();
	/**********show subtotal price**********/
}
function ShowSubTotalPrice(){
	var subtotal =0; var subtechprice = 0;
	var count= $("#hidCount").val();
	for(var i=1; i<=(count-1);i++){
		var total = $("#Total"+i).val();
		var techprice = $("#TechPrice"+i).val();
		total=total.replace(/,/g,'');
		techprice=techprice.replace(/,/g,'');
		if(Number(parseFloat(total))){
			subtotal = (subtotal+parseFloat(total));
		}
		if(Number(parseFloat(techprice))){
			subtechprice = (subtechprice+parseFloat(techprice));
		}
	}
	//alert(subtotal);
	subtotal = subtotal.toFixed(2);
	subtechprice = subtechprice.toFixed(2);
	$("#SubTotal").html("$ "+subtotal);
	$("#SubTechPrice").html("$ "+subtechprice);
}
function ShowTechnicians(wo_id){
	$.fancybox.showActivity();	
	var url="assign-technician-edit.php";
	var wono = $("#WorkOrder").val();
	var implode_techs = $("#implode_techs").val();
	$.post(url,{"choice":"assign_job","wono":wono,"wo_id":wo_id,"implode_techs":implode_techs},function(res){			
	$.fancybox(res,{centerOnScroll:true,hideOnOverlayClick:false});				
	});
}
function validate_assigntech(){
	if(document.AssignTech.cmbTechnician.value == ''){
		document.getElementById('lblcmbTechnician').innerHTML = 'This field is required';
		document.AssignTech.cmbTechnician.focus();
		return false;
	}else{
		document.getElementById('lblcmbTechnician').innerHTML = '';
	}
	var chk=$('input:checkbox[name=chkWO]:checked').length;
	if(chk == 0){
		document.getElementById('lblchkWO').innerHTML = 'Please select checkbox';
		return false;
	}else{
		document.getElementById('lblchkWO').innerHTML = '';
	}
	return true;	
}
function insert_data(){
	$.fancybox.showActivity();	
	var url="assign-technician-edit.php";	
	var x=validate_assigntech();
	var cmbTechnician=$('#cmbTechnician').val();
	var StartDate=$('#StartDate').val();
	var chkWO=$('#chkWO').val();
	var work_id=$('#work_id').val();
	if(x){
		$.post(url,{"choice":"data_insert","cmbTechnician":cmbTechnician,"chkWO":chkWO,"work_id":work_id,"StartDate":StartDate},function(res){			
		 if(res=='1'){
			window.location.href="edit-job-board-open?id="+work_id; 
		 }else{			
			 $.fancybox(res,{centerOnScroll:true,hideOnOverlayClick:false});			
		 }
		});
	}else{
		return false; 
	}
}
function downLoadDocument(fname){
	window.location.href='docdnd.php?file=workorder_doc/'+fname;
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
//function to copy work order
function create_copy_order(wo_id){
	var r =confirm("Are you sure you want to create duplicate order.");
	if(r){
		var src = $("#src").val();
		$.post("ajax-copy-workorder-open",{"action":"insert","id":wo_id},function(res){			
			//alert(res);	
			if(res){
				window.location.href='manage-job-board';
			}
		});
	}else{
		return false;
	}
}
/*********Function to schedule job************/
function Set_Workstatus(wo_id){
	$.fancybox.showActivity();	
	var url="schedule-technician.php";
	var wono = $("#WorkOrder").val();
	$.post(url,{"choice":"assign_job","wono":wono,"wo_id":wo_id},function(res){			
		$.fancybox(res,{centerOnScroll:true,hideOnOverlayClick:false});				
	});
}
function validate_assigntech1(){
	if(document.AssignTech.cmbTechnician.value == ''){
		document.getElementById('lblcmbTechnician').innerHTML = 'This field is required';
		document.AssignTech.cmbTechnician.focus();
		return false;
	}else{
		document.getElementById('lblcmbTechnician').innerHTML = '';
	}
	var chk=$('input:checkbox[name=chkWO]:checked').length;
	if(chk == 0){
		document.getElementById('lblchkWO').innerHTML = 'Please select checkbox';
		return false;
	}else{
		document.getElementById('lblchkWO').innerHTML = '';
	}
	return true;	
}
function update_data(){
	$.fancybox.showActivity();	
	var url="schedule-technician.php";	
	var x=validate_assigntech1();
	var cmbTechnician=$('#cmbTechnician').val();
	var StartDate=$('#StartDate').val();
	var StartTime=$('#StartTime').val();
	var EndTime=$('#EndTime').val();
	var chkWO=$('#chkWO').val();
	var work_id=$('#work_id').val();
	if(x){
	 	$.post(url,{"choice":"data_update","cmbTechnician":cmbTechnician,"StartDate":StartDate,"StartTime":StartTime,"EndTime":EndTime,"chkWO":chkWO,"wo_id":work_id},function(res){
		 if(res=='1'){
			window.location.href="edit-job-board-open?id="+work_id; 
		 }else{			
			 $.fancybox(res,{centerOnScroll:true,hideOnOverlayClick:false});			
		 }
	 	});
	}else{
		return false; 
	}
	
}
/*********Function to schedule job************/
/*********Function to print job************/
function UploadDocument(){
	$.fancybox.showActivity();	
	var url="admin-upload-docs.php";
	var woid = $("#worid").val();
	var wono = $("#WorkOrder").val();
	$.post(url,{"wono":wono,"woid":woid},function(res){			
		$.fancybox(res,{centerOnScroll:true,hideOnOverlayClick:false});				
	});
}
function viewDocument(fname){
	$.fancybox.showActivity();	
	var url="admin-view-docs.php";
	$.post(url,{"fname":fname},function(res){			
		$.fancybox(res,{centerOnScroll:true,hideOnOverlayClick:false});				
	});
}
function deleteDocument(fname){
	$.fancybox.showActivity();	
	var url="admin-delete-docs.php";
	var woid = $("#woid").val();
	$.post(url,{"fname":fname,"woid":woid},function(res){			
		$.fancybox(res,{centerOnScroll:true,hideOnOverlayClick:false});				
	});
}
function returnBack(){
	UploadDocument();
}
function closeFancyBox(){
	$.fancybox.close();
}
/*********Function to print job************/
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
	   curphonevar = numval.replace(/[\\!"£$%^&*+={};:'~()¦<>?|`¬\]\[]/g,'');
	   xss.val(maintainplus + curphonevar) ;
	   var maintainplus = '';
	   xss.focus;
    });
  //}
 });
});
function gmap(woid){
	$.fancybox.showActivity();
	var url="gmap-order.php";	
	$.post(url,{"woid":woid},function(res){
		    $.fancybox(res,{centerOnScroll:true,hideOnOverlayClick:false,onComplete : function(){initialize();}});
	    });
  }
</script>
<!-- Requied for Map --->
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<script type="text/javascript">
    var markers = [];
	var map;
	var infowindow;
	//initialize(); 
    function initialize() {
		var mapOptions = {
			zoom: 8,
			//center: new google.maps.LatLng(40.714364, -74.005972),
			mapTypeId: google.maps.MapTypeId.ROADMAP
		}
		 map = new google.maps.Map(document.getElementById("googlemap"), mapOptions);
		var contentString = $("#contentString").val();		
		//alert(contentString);
		var matches = contentString.match(/\[(.*?)\]/g);
		var locations = [];
		for(var i=0; i< matches.length; i++){
			var strArray =new Array();
			var rplaceString=matches[i].replace(/'/g, '').replace(/\[/, '').replace(/]/, '');			
			strArray=rplaceString.split("_");
			locations.push(strArray);
		}
		//alert(locations.toString());
		var marker, i;
		infowindow = new google.maps.InfoWindow();        
		google.maps.event.addListener(map, 'click', function() {												
			infowindow.close();				
		});  
					      
		for (i = 0; i < locations.length; i++) {
			//alert(locations[i][1]);
			map.setCenter(new google.maps.LatLng(locations[i][1], locations[i][2])); 
			marker = new google.maps.Marker({
				position: new google.maps.LatLng(locations[i][1], locations[i][2]),
				map: map,
				icon: locations[i][3],
				title:locations[i][4]
			});    
			google.maps.event.addListener(marker, 'click', (function(marker, i) {
				return function() {
					infowindow.setContent(locations[i][0]);
					infowindow.open(map, marker);
				}
			})(marker, i));        
			markers.push(marker);
		} 
		//clearMarkers();  //this function is used to clear marker initially
	}
	//google.maps.event.addDomListener(window, 'load', initialize);
	function myClick(id){
		showMarkers();
		google.maps.event.trigger(markers[id], 'click');
	}
	//Extra function from google map
	// Add a marker to the map and push to the array.
	function addMarker(location) {
	  var marker = new google.maps.Marker({
		position: location,
		map: map
	  });
	  markers.push(marker);
	}
	// Sets the map on all markers in the array.
	function setAllMap(map) {
	  for (var i = 0; i < markers.length; i++) {
		markers[i].setMap(map);
	  }
	}
	// Removes the markers from the map, but keeps them in the array.
	function clearMarkers() {
	  setAllMap(null);
	}
	// Shows any markers currently in the array.
	function showMarkers() {
	  setAllMap(map);
	}
	// Deletes all markers in the array by removing references to them.
	function deleteMarkers() {
	  clearMarkers();
	  markers = [];
	}
</script>
<!-- Requied for Map --->
<body onLoad="document.createJob.cmbClient.focus();">
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
                    <form name="createJob" id="createJob" action="" method="post" onSubmit="return validate_createjob();" autocomplete="off" enctype="multipart/form-data">
                        <div class="headerbg">
                        	<div style="float:left;">EDIT ORDER</div>
                            <div style="float:right;">
                            	<?php if($_REQUEST['src']=='unapprv'){$path="unapprove-job";}else{$path="manage-job-board?g=$_REQUEST[hidk]";} ?>
                                <input type="submit" name="submitbtn" id="submitbtn" class="buttonText2" value="Submit Form" tabindex="39"/>
                                <input type="button" class="buttonText2" value="Back" tabindex="40" onClick="document.location.href='<?php echo $path;?>'"/>
                                <input type="button" class="buttonText2" value="Credit Card Info" tabindex="41" onClick="save_credit_card_info('<?php echo $res_editJobBoard['id'];?>');"/>
                                 <input type="button" class="buttonText2" value="Create The Second Order" tabindex="41" onClick="create_copy_order('<?php echo $res_editJobBoard['id'];?>');"/>
                             </div>
                        </div>
                        <?php if($_REQUEST['msg']=='001'){ ?>
							<div align="center" style="color:green;font-weight:bold;">Records updated successfully</div> 
						<?php }else{ ?>
                             <div class="spacer"></div>
                        <?php } ?>
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
                            <div  class="formtextaddjob">Client:</div>
                            <div  class="textboxcjob">
                            <select name="cmbClient" id="cmbClient" class="selectboxjob" tabindex="1" onChange="showEquipment(),showTechInstruction();">
                                <option value="">--Select Client--</option>
                                <?php 
								$cond = "status='1'";
								//condition for users
								if($implode_clients <>''){
									$cond.=" AND FIND_IN_SET(id,'$implode_clients')";
								}
								foreach($dbf->fetch("clients",$cond." ORDER BY name ASC")as $Client){?>
                                <option value="<?php echo $Client['id'];?>" <?php if($res_editJobBoard['created_by']==$Client['id']){echo 'selected';}?>><?php echo $Client['name'];?></option>
                                <?php }?>
                            </select>
                            </div>
                            <div  class="formtextaddjoblong">Order Status:</div>
                            <div  class="textboxcjob">
                            <select name="cmbStatus" id="cmbStatus" class="selectboxjob" tabindex="1">
                            	<option value="">--Select Order Status--</option>
                                <option value="Open" <?php if($res_editJobBoard['work_status']=='Open'){echo 'selected';}?>> Open </option>
                                <option value="Assigned" <?php if($res_editJobBoard['work_status']=='Assigned'){echo 'selected';}?>>Assigned </option>
                                <option value="Scheduled" <?php if($res_editJobBoard['work_status']=='Scheduled'){echo 'selected';}?>>Scheduled </option>
                                <option value="Dispatched" <?php if($res_editJobBoard['work_status']=='Dispatched'){echo 'selected';}?>>Dispatched</option>
                                <option value="In Progress" <?php if($res_editJobBoard['work_status']=='In Progress'){echo 'selected';}?>>In Progress</option>
                                <option value="Completed" <?php if($res_editJobBoard['work_status']=='Completed'){echo 'selected';}?>>Completed</option>
                                <option value="Ready to Invoice" <?php if($res_editJobBoard['work_status']=='Ready to Invoice'){echo 'selected';}?>>Ready to Invoice</option>
                                <option value="Invoiced" <?php if($res_editJobBoard['work_status']=='Invoiced'){echo 'selected';}?>>Invoiced</option>
                                <option value="Cancelled" <?php if($res_editJobBoard['work_status']=='Cancelled'){echo 'selected';}?>> Cancelled </option>
                            </select><br/><label for="cmbStatus" id="lblcmbStatus" class="redText"></label>
                            </div>
                            <div class="spacer"></div>
                            <div  class="formtextaddjob">WO#:</div>
                            <div  class="textboxcjob">
                             <input type="text" class="textboxjob" name="WorkOrder" id="WorkOrder" value="<?php echo $res_editJobBoard['wo_no'];?>"  tabindex="1" readonly><br/><label for="WorkOrder" id="lblWorkOrder" class="redText"></label>
                            </div>
                            <div  class="formtextaddjoblong">Purchase Order#:</div>
                            <div  class="textboxcjob">
                             <input type="text" class="textboxjob" name="PurchaseOrder" id="PurchaseOrder" value="<?php echo $res_editJobBoard['purchase_order_no'];?>" tabindex="2"><br/><label for="PurchaseOrder" id="lblPurchaseOrder" class="redText"></label>
                            </div>
                            <div class="spacer"></div>
                            <div  class="formtextaddjob">Service Name:<span class="redText">*</span></div>
                            <div  class="textboxcjob">
                            <select name="cmbService" id="cmbService" class="selectboxjob" tabindex="4" onChange="showEquipment();">
                                <option value="">--Select Service Name--</option>
                                <?php foreach($dbf->fetch("service","id>0 ORDER BY service_name ASC")as $service){?>
                                <option value="<?php echo $service['id'];?>" <?php if($service['id']==$res_editJobBoard['service_id']){echo 'selected';}?>><?php echo $service['service_name'];?></option>
                                <?php }?>
                            </select><br/><label for="cmbService" id="lblcmbService" class="redText"></label>
                            </div>
                            <div  class="formtextaddjoblong">Technician:</div>
                            <div  class="textboxcjob"><span class="formtext">
                            <?php if(!empty($resTech)){?>
								<a href="javascript:void(0);" onClick="ShowTechnicians('<?php echo $_REQUEST[id];?>');" tabindex="5" title="Click Here To Assign Tech" class="tooltip"><?php echo $resTech['first_name'].'&nbsp;'.$resTech['middle_name'].'&nbsp;'.$resTech['last_name'];?> <span><?php include 'tech_details.php';?></span></a> 
								<?php }else{?>
                            	<a href="javascript:void(0);" onClick="ShowTechnicians('<?php echo $_REQUEST[id];?>');" tabindex="5" title="Click Here To Assign Tech">Not Assigned</a>
							<?php }?>
                            </span></div>
                            <div class="spacer"></div>
                            <div  class="formtextaddjob">Assigned Date:</div>
                            <div  class="textboxcjob">
                                <input type="text" class="textboxjob" name="AssignedDate" id="AssignedDate" readonly value="<?php if(!empty($resTech)){echo date("d-M-Y",strtotime($resTech['assign_date']));}?>">
                            </div>
                            <input type="hidden" name="techgrade" id="techgrade" value="<?php echo $resTech['pay_grade'];?>"/>							<div  class="formtextaddjoblong">Scheduled Date:</div>
                            <div  class="textboxcjob">
                                <input type="text" class="textboxjob" name="ScheduledDate" id="ScheduledDate" readonly value="<?php if(!empty($resTech) && ($resTech['start_date']<>'0000-00-00')){echo date("d-M-Y",strtotime($resTech['start_date']));}?>">
                            </div>
                            <div class="spacer"></div>
                            <div  class="formtextaddjob">Time Window:</div>
                            <div  class="textboxcjob"><span class="formtext">
                            <?php if($resTech<>''){?>
								<a href="javascript:void(0);" onClick="Set_Workstatus('<?php echo $res_editJobBoard['id'];?>')" tabindex="6" title="View Time Window">Tech Time Window</a><?php }else{?> <a href="javascript:void(0);" tabindex="6" title="View Time Window">Tech Time Window</a> <?php }?></span> </div>
                           <div class="spacer" style="height:12px;"></div>
                         </div>
                         	<!-----address div end--------->
                            <!-----purchase div start--------->
                           	<div  class="divPurchase">
                               <div class="greenText" align="left">Customer Information <span style="color:#666; font-weight:normal;">(Choose auto suggestion to avoid customer redundancy)</span></div>
                               <div id="rescust">
                                <div  class="formtextaddjob"> Name:<span class="redText">*</span></div>
                                <div  class="textboxcjob">
                                	<input type="text" class="textboxjob" name="CustomerName" id="CustomerName" value="<?php echo $res_editJobBoard['name'];?>" tabindex="14" onKeyPress="return onlyLetters(event)"><br/><label for="CustomerName" id="lblCustomerName" class="redText"></label>
                                </div>
                                <div  class="formtextaddjoblong">Email Address:</div>
                                <div  class="textboxcjob">
                                    <input type="text" class="textboxjob" name="CustomerEmailID" id="CustomerEmailID" value="<?php echo $res_editJobBoard['email'];?>" tabindex="15"><br/><label for="CustomerEmailID" id="lblCustomerEmailID" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextaddjob">Address:<span class="redText">*</span></div>
                                <div  class="textboxcjob">
                                    <textarea class="textareajob" name="CustAddress" id="CustAddress" tabindex="16"><?php echo $res_editJobBoard['address'];?></textarea><br/><label for="CustAddress" id="lblCustAddress" class="redText"></label>
                                </div>
                                <div  class="formtextaddjoblong"> Contact Name:<span class="redText">*</span></div>
                                <div  class="textboxcjob">
                                  <input type="text" class="textboxjob" name="CustomercontactName" id="ContactName" value="<?php echo $res_editJobBoard['contact_name'];?>" tabindex="17" onKeyPress="return onlyLetters(event)"><br/><label for="CustomercontactName" id="lblCustomercontactName" class="redText"></label>
                                </div>
                                 <div class="spacer"></div>
                                 <div  class="formtextaddjob">City:<span class="redText">*</span></div>
                                <div  class="textboxcjob">
                                    <input type="text" class="textboxjob" name="CustCity" id="CustCity" value="<?php echo $res_editJobBoard['city'];?>" tabindex="18" onKeyPress="return onlyLetters(event)"><br/><label for="CustCity" id="lblCustCity" class="redText"></label>
                                </div>
                                <div  class="formtextaddjoblong">State:<span class="redText">*</span></div>
                                <div  class="textboxcjob">
                                    <select name="CustState" id="CustState" class="selectboxjob" tabindex="19">
                                    <option value="">--Select State--</option>
                                    <?php foreach($dbf->fetch("state","id>0 ORDER BY state_code ASC")as $CState){?>
                                    <option value="<?php echo $CState['state_code'];?>" <?php if($res_editJobBoard['state']==$CState['state_code']){echo 'selected';}?>><?php echo $CState['state_name'];?></option>
                                    <?php }?>
                                </select><br/><label for="CustState" id="lblCustState" class="redText"></label>
                                </div>
                                 <div class="spacer"></div>
                                 <div  class="formtextaddjob">Zip Code:<span class="redText">*</span></div>
                                <div  class="textboxcjob">
                                    <input type="text" class="textboxjob" name="CustZip" id="CustZip" value="<?php echo $res_editJobBoard['zip_code'];?>" tabindex="20" maxlength="12"><br/><label for="CustZip" id="lblCustZip" class="redText"></label>
                                </div>
                                <div  class="formtextaddjoblong">Phone No:<span class="redText">*</span></div>
                                <div  class="textboxcjob">
                                    <input type="text" class="textboxjob" name="CustPhone" id="CustPhone" value="<?php echo $res_editJobBoard['phone_no'];?>" onKeyUp="return validatephone(this);" maxlength="12" tabindex="21"><br/><label for="CustPhone" id="lblCustPhone" class="redText"></label>
                                </div>
                                 <div class="spacer"></div>
                                <div  class="formtextaddjob">Cell No:</div>
                                 <div  class="textboxcjob">
                                    <input type="text" class="textboxjob" name="Faxno" id="Faxno" value="<?php echo $res_editJobBoard['fax_no'];?>" onKeyUp="return validatephone(this);" maxlength="12" tabindex="22"><br/><label for="Faxno" id="lblFaxno" class="redText"></label>
                                </div>
                                <div  class="formtextaddjoblong"><a href="javascript:void(0);" onClick="gmap('<?php echo $res_editJobBoard['id'];?>')" style="text-decoration:none;color:#F90;"><img src="images/locater.jpg" alt="" width="21" height="24" align="absmiddle">Click For Map</a></div>
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
                                <select name="cmbpartsArrive" id="cmbpartsArrive" class="selectboxjob" tabindex="13">
                                    <option value="">--Select Parts Arrive--</option>
                                    <option value="twodays" <?php if($res_editJobBoard['parts_arrive']=='twodays'){echo 'selected';}?>>2 Days</option>
                                    <option value="threedays" <?php if($res_editJobBoard['parts_arrive']=='threedays'){echo 'selected';}?>>3 Days</option>
                                    <option value="fourdays" <?php if($res_editJobBoard['parts_arrive']=='fourdays'){echo 'selected';}?>>4 Days</option>
                                    <option value="Partsarrive" <?php if($res_editJobBoard['parts_arrive']=='Partsarrive'){echo 'selected';}?>>Parts have Arrived</option>
                                 </select><br/><label for="cmbaltParts" id="lblcmbaltParts" class="redText"></label>  
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextaddjob">Tracking No:</div>
                                <div  class="textboxcjob">
                                    <input type="text" class="textboxjob" name="track_no" id="track_no" value="<?php echo $res_editJobBoard['tracking_number'];?>" tabindex="14"><br/><label for="track_no" id="lbltrack_no" class="redText"></label>
                                </div>
                                <div  class="formtextaddjoblong">Carrier Company:</div>
                                <div  class="textboxcjob">
                                    <input type="text" class="textboxjob" name="carrier_company" id="carrier_company" value="<?php echo $res_editJobBoard['carrier_company'];?>" tabindex="15"><br/><label for="carrier_company" id="lblcarrier_company" class="redText"></label>
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
                             <div><textarea name="notes" id="notes"  class="textareaOrder" tabindex="22" style="min-height:140px;"><?php echo $res_editJobBoard['notes'];?></textarea><br/><label for="notes" id="lblnotes" class="redText"></label></div>
                             <div class="spacer"></div>
                             <div  class="formtextaddjob">Upload Doc:</div>
                             	<div  class="textboxcjob" style="width:300px;"><span class="orangeText"><a href="javascript:void(0);" onClick="UploadDocument();">Click here to Upload and View Work Order Documents</a></span></div>
                               	<div class="spacer"></div>
                            </div>
                            <!-----note div end--------->
                            <div class="spacer"></div>
                            <!-----Tech Instruction start--------->
                            <?php  
							$techInstruction = $dbf->getDataFromTable("clients","tech_instruction","id='$res_editJobBoard[created_by]'");
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
                                  <div align="center" class="jobheader clPrice">Price/Rate</div>
                                  <div align="center" class="jobheader clPrice">Total</div>
                                  <div align="center" class="jobheader clPrice">Tech Price</div>
                                  <div style="clear:both;"></div>
                              </div>
                             <?php
							 $subtotal =0;  $subTechPrice =0; 
							 $res_woservice = $dbf->fetch("workorder_service","workorder_id='$_REQUEST[id]'");
							 $arrWorkservice =array();$i=1;
							 $cntwotype =$dbf->countRows("work_type","status=1");
							 $cntwotype=floor($cntwotype/2);
							  for($j=0; $j<$cntwotype;$j++){
								  $arrWorkservice= $res_woservice[$j];
								  $total = ($arrWorkservice['quantity']*$arrWorkservice['outbox_price']);
								  $TechPrice=$arrWorkservice['tech_price'];
								  $subtotal = $subtotal+$total;
								  $subTechPrice = $subTechPrice+$TechPrice;
							  ?>
                              <div align="center" class="jobbody clService">
                              <input type="hidden" name="hid<?php echo $i;?>" value="<?php echo $arrWorkservice['id'];?>">
                              <select class="selectboxjob" name="Worktype<?php echo $i;?>" id="Worktype<?php echo $i;?>" tabindex="23" onChange="ShowPrice('<?php echo $i;?>');">
                              	<option value="">--Select Worktype--</option>
                                <?php foreach($dbf->fetch("work_type","id>0 AND status=1 ORDER BY worktype ASC") as $vawt){?>
                                <option value="<?php echo $vawt['id'];?>" <?php if($arrWorkservice['work_type']==$vawt['id']){echo 'selected';}?>><?php echo $vawt['worktype'];?></option>
                                <?php }?>
                              </select><br/><label for="worktype" id="lblWorktype<?php echo $i;?>" class="redText"></label>
                              </div>
                              <div align="center" class="jobbody clEquipment">
                              <select class="selectboxjob" name="Equipment<?php echo $i;?>" id="Equipment<?php echo $i;?>" tabindex="23" onChange="ShowPrice('<?php echo $i;?>');">
                              	<option value="">--Select Equipment--</option>
                                <?php foreach($dbf->fetch("equipment","id>0 AND service_id='$res_editJobBoard[service_id]' ORDER BY equipment_name ASC") as $valeq){?>
                                <option value="<?php echo $valeq['id'];?>" <?php if($arrWorkservice['equipment']==$valeq['id']){echo 'selected';}?>><?php echo $valeq['equipment_name'];?></option>
                                <?php }?>
                              </select><br/><label for="Equipment1" id="lblEquipment<?php echo $i;?>" class="redText"></label>
                              </div>
                              <div align="center" class="jobbody clModel"><input type="text" class="textboxjob" name="Model<?php echo $i;?>" id="Model<?php echo $i;?>" value="<?php echo $arrWorkservice['model'];?>" tabindex="23"><br/><label for="Model" id="lblModel<?php echo $i;?>" class="redText"></label></div>
                              <div align="center" class="jobbody clQunt"><input type="text" class="textboxjob" name="Quantity<?php echo $i;?>" id="Quantity<?php echo $i;?>" value="<?php echo $arrWorkservice['quantity'];?>" onKeyPress="return onlyNumbers(event);" maxlength="2" tabindex="23" onBlur="ShowTotalPrice('<?php echo $i;?>');"><br/><label for="Quantity" id="lblQuantity<?php echo $i;?>" class="redText"></label></div>
                              <div align="center" class="jobbody clPrice"><input type="text" class="textboxjob" name="Price<?php echo $i;?>" id="Price<?php echo $i;?>" value="<?php echo $arrWorkservice['outbox_price'];?>" onKeyUp="return extractNumber(this,2);" tabindex="23" onBlur="ShowTotalPrice('<?php echo $i;?>');" maxlength="10"></div>
                              <div align="center" class="jobbody clPrice"><input type="text" class="textboxjob" name="Total<?php echo $i;?>" id="Total<?php echo $i;?>" value="<?php if($total)echo number_format($total,2);?>" onKeyPress="return onlyNumbers(event);" tabindex="23" readonly></div>
                              <div align="center" class="jobbody clPrice"><input type="text" class="textboxjob" name="TechPrice<?php echo $i;?>" id="TechPrice<?php echo $i;?>" value="<?php echo $TechPrice;?>" onKeyUp="return extractNumber(this,2);" onBlur="ShowSubTotalPrice();" tabindex="23" maxlength="10"></div>
                              <div style="clear:both; height:5px;"></div>
                              <?php $i++;}?>
                              <div>
                              	<input type="hidden" id="hidCount" value="<?php echo $i;?>"/>
                              	<div class="orderSubtotal">Sub Total:</div><div class="orderSubPrice" id="SubTotal">$ <?php echo number_format($subtotal,2);?></div><div class="orderSubPrice" id="SubTechPrice">$ <?php echo number_format($subTechPrice,2);?></div>
                                 <div style="clear:both;"></div>
                              </div>
                              </div>
                              <div style="clear:both; height:5px;"></div>
                            </div>
                            <!-----service div end--------->
                            <div class="spacer"></div>
                            <div style="float:left;width:49%;">
                            <!-----admin notes div start--------->
                            <div  class="divPickup" style="width:100%;">
                            <?php //fetch notes from work order notes table
								$resNotes=$dbf->fetchOrder("workorder_notes","workorder_id='$_REQUEST[id]' AND (user_type='admin' OR user_type='user' OR user_type='client') AND customer_attempt=0 AND waiting_parts NOT IN(1,2)","created_date DESC");?>
                             <div class="greenText" align="left">Admin Notes:</div>
                             <div><textarea name="adminNotes" id="adminNotes"  class="textareaOrderNote" tabindex="24"></textarea><br/><label for="adminNotes" id="lbladminNotes" class="redText"></label></div>
                             <div align="right" style="margin:5px;"><img src="images/add_note.png" alt="Add Note" title="Add Note" style="cursor:pointer;" onClick="add_notes();"/></div>
                             <div id="resnotes">
                             <?php foreach($resNotes as $resn){
								 if($resn['user_type']=='admin'){
									 $uname = $dbf->getDataFromTable("admin","name","id='$resn[user_id]'");
								 }elseif($resn['user_type']=='user'){
									  $uname = $dbf->getDataFromTable("users","name","id='$resn[user_id]'");
								 }elseif($resn['user_type']=='client'){
									  $uname = $dbf->getDataFromTable("clients","name","id='$resn[user_id]'");
								 }
							  ?>
                             <div class="textareaNoteView">
                                 <div align="left"><?php echo $resn['wo_notes'];?></div>
                                 <div class="spacer" style="border-bottom:dashed 1px #ccc;"></div>
                                 <div align="right">By <?php echo $uname;?> on <?php echo date("d-M-Y g:i A",strtotime($resn['created_date']));?> for #<?php echo $res_editJobBoard['wo_no'];?></div>
                             </div> <div class="spacer"></div>
                             <?php }?>
                             </div>
                             <div class="spacer" style="height:10px;"></div>
                            </div>
                            <!-----admin note div end--------->
                            </div>
                            <div class="spacer"></div>
                            <div align="center">
                                <input type="submit" name="submitbtn" id="submitbtn" class="buttonText" value="Submit Form" tabindex="39"/>                
                                <input type="button" class="buttonText3" value="Back" tabindex="40" onClick="document.location.href='<?php echo $path;?>'"/>
                                <input type="hidden" name="src" id="src" value="<?php echo $_REQUEST['src'];?>"/>
                                <input type="button" class="buttonText3" value="Credit Card Info" tabindex="41" onClick="save_credit_card_info('<?php echo $res_editJobBoard['id'];?>');"/>
                                <input type="button" class="buttonText3" value="Create The Second Order" tabindex="41" onClick="create_copy_order('<?php echo $res_editJobBoard['id'];?>');"/>
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
        <?php include_once 'footer.php'; ?>
  </div>
</body>
</html>