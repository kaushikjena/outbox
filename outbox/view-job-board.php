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
//Fetch details from work_order table 
$res_viewJobBoard=$dbf->fetchSingle("clients c,service s,work_order w","c.id=w.client_id AND w.service_id=s.id AND w.id='$_REQUEST[id]'");
//update work order notes read status
$dbf->updateTable("workorder_notes","read_status=0","workorder_id='$_REQUEST[id]' AND user_type='tech'");
//get tech details
$resTech = $dbf->fetchSingle("assign_tech at,technicians tc","at.tech_id=tc.id AND at.wo_no='$res_viewJobBoard[wo_no]'");
//fetch from work order doc table
$workorderdoc = $dbf->countRows("workorder_doc","workorder_id='$_REQUEST[id]'");
//get client name
if($res_viewJobBoard['created_by']<>0){
	$clientname =$dbf->getDataFromTable("clients","name","id='$res_viewJobBoard[created_by]'");
}else{
	$clientname="COD";
}		
	//redirection of view page according to request source
	if($_REQUEST['src']=='disp'){
		$link="manage-job-board-dispatch?g=$_REQUEST[hidk]";
	}elseif($_REQUEST['src']=='disp_cmpltd'){
		$link="manage-job-board-completed?g=$_REQUEST[hidk]";
	}elseif($_REQUEST['src']=='assigned'){
		$link="manage-job-board-assigned?g=$_REQUEST[hidk]";
	}elseif($_REQUEST['src']=='wfp'){
		$link="manage-job-board-wfp?g=$_REQUEST[hidk]";
	}elseif($_REQUEST['src']=='hold'){
		$link="manage-job-board-hold?g=$_REQUEST[hidk]";
	}elseif($_REQUEST['src']=='pending'){
		$link="manage-job-board-pending?g=$_REQUEST[hidk]";
	}elseif($_REQUEST['src']=='unapprv'){
		$link="unapprove-job";
	}elseif($_REQUEST['src']=='cal'){
		$link="jobs_calendar";
	}elseif($_REQUEST['src']=='assign'){
		$link="assign_job_technician";
	}elseif($_REQUEST['src']=='clnt'){
		$link="manage-client-billings";
	}elseif($_REQUEST['src']=='cod'){
		$link="manage-cod-billings";
	}elseif($_REQUEST['src']=='tech'){
		$link="manage-technician-payments";
	}elseif($_REQUEST['src']=='srch'){
		$link="manage-job-search";
	}else{$link="manage-job-board?g=$_REQUEST[hidk]";}
	
if(!isset($_REQUEST['id']) && $_REQUEST['id']==''){
	header("location:$link");exit;
}
?>
<script type="text/javascript">
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
/*********Function to schedule job************/
function Set_Workstatus(wo_id){
	$.fancybox.showActivity();	
	var url="schedule-technician.php";
	var wono = $("#wono").val();
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
	if(document.AssignTech.StartDate.value==''){
		document.getElementById('lblStartDate').innerHTML='This field is required';
		document.AssignTech.StartDate.focus();
		return false;
	}else{
		document.getElementById('lblStartDate').innerHTML='';
	}
	if(document.AssignTech.StartTime.value==''){
		document.getElementById('lblStartTime').innerHTML='This field is required';
		document.AssignTech.StartTime.focus();
		return false;
	}else{
		document.getElementById('lblStartTime').innerHTML='';
	}
	if(document.AssignTech.EndTime.value==''){
		document.getElementById('lblStartTime').innerHTML='This field is required';
		document.AssignTech.EndTime.focus();
		return false;
	}else{
		document.getElementById('lblStartTime').innerHTML='';
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
				window.location.href="view-job-board?id="+work_id+"&src=disp"; 
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
	var wono = $("#wono").val();
	$.post(url,{"wono":wono,"woid":woid},function(res){			
		$.fancybox(res,{centerOnScroll:true,hideOnOverlayClick:false,"onClosed": function(){ showdocnos(woid); }});				
	});
}
function viewDocument(fname){
	$.fancybox.showActivity();	
	var url="admin-view-docs.php";
	var woid = $("#worid").val();
	$.post(url,{"fname":fname},function(res){			
		$.fancybox(res,{centerOnScroll:true,hideOnOverlayClick:false,"onClosed": function(){ showdocnos(woid); }});				
	});
}
function deleteDocument(fname){
	$.fancybox.showActivity();	
	var url="admin-delete-docs.php";
	var woid = $("#worid").val();
	$.post(url,{"fname":fname,"woid":woid},function(res){			
		$.fancybox(res,{centerOnScroll:true,hideOnOverlayClick:false,"onClosed": function(){ showdocnos(woid); }});				
	});
}
function showdocnos(woid){
	var url="ajax-change-status.php";
	$.post(url,{"choice":"docsno","woid":woid},function(res){//alert(res);
		$("#spanDocs").html(res);
	});
}
function returnBack(){
	UploadDocument();
}
function closeFancyBox(){
	$.fancybox.close();
}
function print_doc(val,woid){
	if(val=='print'){
		window.open("admin_job_board_print.php?id="+woid,'_blank');
    }else if(val=='pdf'){
		window.location.href="admin_job_board_pdf.php?id="+woid;
    }
}
/*********Function to print job************/
/**********Send Notification to assigned Tech*********/
function send_email_tech(woid,wono){
	$.fancybox.showActivity();	
	var url="send-email-technician.php";
	$.post(url,{"choice":"show_email","wono":wono,"woid":woid},function(res){			
		$.fancybox(res,{centerOnScroll:true,hideOnOverlayClick:false});				
	});
}
function sendEmail(){
	$.fancybox.showActivity();	
	var url="send-email-technician.php";	
	var x=validate_assig_email();
	var fromemail=$('#fromemail').val();
	var fromname=$('#fromname').val();
	var subject=$('#subject').val();
	var message = CKEDITOR.instances['message'].getData();
	//alert(message);
	var woid=$('#woid').val();
	var wono=$('#wono').val();
	if(x){
	 	$.post(url,{"choice":"send_email","wono":wono,"woid":woid,"fromemail":fromemail,"fromname":fromname,"subject":subject,"message":message},function(res){ 
		 if(res=='1'){
			$.fancybox("Email Send Successfully",{centerOnScroll:true,hideOnOverlayClick:false});
		 }else{			
			 $.fancybox("Email Sending failed",{centerOnScroll:true,hideOnOverlayClick:false});			
		 }
	 	});
	}else{
		return false; 
	}
}
function showMessage(val){
	var url="send-email-technician.php";
	$.post(url,{"choice":"show_message","id":val},function(res){
		CKEDITOR.instances['message'].setData(res)	;	
	});
}
/**********Send Notification to assigned Tech*********/
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

<body>
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
                        <div class="headerbg">
                        <div style="float:left;">VIEW ORDER</div>
                        <div style="float:right;"><input type="button" class="buttonText2" value="Return Back" onClick="window.location='<?php echo $link;?>'"/>
                        <input type="button" class="buttonText2" value="Print" tabindex="40" onClick="print_doc('print','<?php echo $res_viewJobBoard['id'];?>');"/>
                         <input type="button" class="buttonText2" value="PDF" tabindex="40" onClick="print_doc('pdf','<?php echo $res_viewJobBoard['id'];?>');"/>
                         <input type="button" class="buttonText2" value="Notification" tabindex="40" onClick="send_email_tech('<?php echo $res_viewJobBoard['id'];?>','<?php echo $res_viewJobBoard['wo_no'];?>');"/>
                        </div>
                        </div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        <!-----Table area start------->
                        	<input type="hidden" name="worid" id="worid" value="<?php echo $res_viewJobBoard['id'];?>">
                            <input type="hidden" name="wono" id="wono" value="<?php echo $res_viewJobBoard['wo_no'];?>">
                            <!-----address div start--------->
                            <div  class="divAddress">
                            <div class="greenText" align="left">Work Order Details</div>
                            <div  class="formtextaddjob">Client:</div>
                            <div  class="textboxjobview">&nbsp;<?php echo $clientname;?></div>
                            <div  class="formtextaddjoblong">Order Status:</div>
                            <div  class="textboxjobview">&nbsp;<?php echo $res_viewJobBoard['work_status'];?></div>
                            <div class="spacer"></div>
                            <div  class="formtextaddjob">WO#:</div>
                            <div  class="textboxjobview">&nbsp;<?php echo $res_viewJobBoard['wo_no'];?></div>
                            <div  class="formtextaddjoblong">Purchase Order#:</div>
                            <div  class="textboxjobview">&nbsp;<?php echo $res_viewJobBoard['purchase_order_no'];?></div>
                            <div class="spacer"></div>
                            <div  class="formtextaddjob">Service Type:</div>
                            <div  class="textboxjobview">&nbsp;<?php echo $res_viewJobBoard['service_name'];?></div>
                            <div  class="formtextaddjoblong">Technician:</div>
                            <div  class="textboxjobview">&nbsp;
							<?php if($resTech<>''){?>
							<a href="javascript:void(0);"class="tooltip" style="color:#666; text-decoration:none;"><?php echo $resTech['first_name'].'&nbsp;'.$resTech['middle_name'].'&nbsp;'.$resTech['last_name'];?><span><?php include 'tech_details.php';?></span></a>
							<?php }else{?> Not Assigned<?php }?></div>
                            <div class="spacer"></div>
                            <div  class="formtextaddjob">Assigned Date:</div>
                            <div  class="textboxjobview">&nbsp;<?php if($resTech<>'' && $resTech['assign_date']<>'0000-00-00'){echo date("d-M-Y",strtotime($resTech['assign_date']));}?></div>
                            <div  class="formtextaddjoblong">Scheduled Date:</div>
                            <div  class="textboxjobview">&nbsp;<?php if($resTech<>'' && $resTech['start_date']<>'0000-00-00'){echo date("d-M-Y",strtotime($resTech['start_date']));}?></div>
                           <div class="spacer"></div>                           
                            <div  class="formtextaddjob">Time Window:</div>
                            <div  class="textboxjobview"><span class="formtext">
                            <?php if($resTech<>''){?>
									<a href="javascript:void(0);" onClick="Set_Workstatus('<?php echo $res_viewJobBoard['id'];?>')" title="View Time Window">Tech Time Window</a> <?php }else{?> <a href="javascript:void(0);" title="View Time Window">Tech Time Window</a> <?php }?></span> </div>
                            <div  class="formtextaddjoblong">Invoice#:</div>
                            <div  class="textboxjobview">&nbsp;<?php echo $res_viewJobBoard['invoice_no'];?></div>
                           <div class="spacer"></div>
                         </div>
                         	<!-----address div end--------->
                            <!-----purchase div start--------->
                           	<div  class="divPurchase">
                               <div class="greenText" align="left">Customer Information</div>
                                <div  class="formtextaddjob"> Name:</div>
                                <div  class="textboxjobview">&nbsp;<?php echo $res_viewJobBoard['name'];?></div>
                                <div  class="formtextaddjoblong">Email Address:</div>
                                <div  class="textboxjobview">&nbsp;<?php echo $res_viewJobBoard['email'];?></div>
                                <div class="spacer"></div>
                                <div id="rescust">
                                <div  class="formtextaddjob">Address:</div>
                                <div  class="textboxjobview">&nbsp;<?php echo $res_viewJobBoard['address'];?></div>
                                <div  class="formtextaddjoblong"> Contact Name:</div>
                                <div  class="textboxjobview">&nbsp;<?php echo $res_viewJobBoard['contact_name'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextaddjob">City:</div>
                                <div  class="textboxjobview">&nbsp;<?php echo $res_viewJobBoard['city'];?></div>
                                <?php $res_jobState=$dbf->getDataFromTable("state","state_name","state_code='$res_viewJobBoard[state]'");?>
                                <div  class="formtextaddjoblong">State:</div>
                                <div  class="textboxjobview">&nbsp;<?php echo $res_jobState;?></div>
                                <div class="spacer"></div>
                                <div  class="formtextaddjob">Zip Code:</div>
                                <div  class="textboxjobview">&nbsp;<?php echo $res_viewJobBoard['zip_code'];?></div>
                                <div  class="formtextaddjoblong">Phone No:</div>
                                <div  class="textboxjobview">&nbsp;<?php echo $res_viewJobBoard['phone_no'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextaddjob">Cell No:</div>
                                <div  class="textboxjobview">&nbsp;<?php echo $res_viewJobBoard['fax_no'];?>&nbsp;</div>
                                <div  class="formtextaddjoblong"><a href="javascript:void(0);" onClick="gmap('<?php echo $res_viewJobBoard['id'];?>')" style="text-decoration:none;color:#F90;"><img src="images/locater.jpg" alt="" width="21" height="24" align="absmiddle">Click For Map</a></div>
                                <div class="spacer"></div>
                            </div>
                        	</div>
                            <!-----purchase div end--------->
                            <!-----pickup div start--------->
                            <div  class="divPickup">
                            	<div class="greenText" align="left">Pick Up Information</div>
                            	<div class="spacer"></div>
                                <div  class="formtextaddjob">Location:</div>
                                <div  class="textboxjobview">&nbsp;<?php echo $res_viewJobBoard['pickup_location'];?></div>
                                <div  class="formtextaddjoblong">City:</div>
                                <div  class="textboxjobview">&nbsp;<?php echo $res_viewJobBoard['pickup_city'];?></div>
                                <div class="spacer"></div>
                                <?php $res_jobStatePick=$dbf->getDataFromTable("state","state_name","state_code='$res_viewJobBoard[pickup_state]'");?>
                                <div  class="formtextaddjob">State:</div>
                                <div  class="textboxjobview">&nbsp;<?php echo $res_jobStatePick;?></div>
                                <div  class="formtextaddjoblong">Address:</div>
                                <div  class="textboxjobview">&nbsp;<?php echo $res_viewJobBoard['pickup_address'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextaddjob">Zip Code:</div>
                                <div  class="textboxjobview">&nbsp;<?php echo $res_viewJobBoard['pickup_zip_code'];?></div>
                                <div  class="formtextaddjoblong">Phone Number:</div>
                                <div  class="textboxjobview">&nbsp;<?php echo $res_viewJobBoard['pickup_phone_no'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextaddjob">Alt Phone:</div>
                                <div  class="textboxjobview">&nbsp;<?php echo $res_viewJobBoard['pickup_alt_phone'];?></div>
                            </div>
                            <!-----pickup div end--------->
                            <!-----note div start--------->
                            <div  class="divNotes">
                             <div class="spacer"></div>
                             <div class="greenText" align="left">Order Description:</div>
                             <div  class="textboxjobviewlarge">&nbsp;<?php echo $res_viewJobBoard['notes'];?></div>
                             <div class="spacer"></div>
                             <div  class="formtextaddjob">Upload Doc:</div>
                            <div  class="textboxcjob" style="width:500px;"><span class="orangeText"><a href="javascript:void(0);" onClick="UploadDocument();">Click here to Upload and View Work Order Documents</a></span>&nbsp;&nbsp;&nbsp;<span class="orangeText" id="spanDocs">(<?php echo $workorderdoc;?> Docs)</span></div>
                            <div class="spacer"></div>
                            </div>
                            <!-----note div end--------->
                            <!-----Parts div start--------->
                            <div class="spacer"></div>
                            <div  class="divPickup">
                            	<div class="greenText" align="left">Parts/Item Tracking Info</div>
                            	<div  class="formtextaddjob">Parts Arrive:</div>
                                <div  class="textboxjobview">&nbsp;<?php if($res_viewJobBoard['parts_arrive']<>'0000-00-00'){echo date('d-M-Y',strtotime($res_viewJobBoard['parts_arrive']));}?></div>
                                <div  class="formtextaddjoblong">Tracking No:</div>
                                <div  class="textboxjobview">&nbsp;<?php echo $res_viewJobBoard['tracking_number'];?></div>
                                 <div class="spacer"></div>
                                <div  class="formtextaddjob">Carrier Company:</div>
                                <div  class="textboxjobview">&nbsp;<?php echo $res_viewJobBoard['carrier_company'];?></div>
                                <div  class="formtextaddjoblong">Serial Number:</div>
                                <div  class="textboxjobview">&nbsp;<?php echo $res_viewJobBoard['serial_number'];?></div>
                                 <div class="spacer"></div>
                                <div  class="formtextaddjob">Model Number:</div>
                                <div  class="textboxjobview">&nbsp;<?php echo $res_viewJobBoard['model_number'];?></div>
                                <div class="spacer" style="height:10px;"></div>
                            </div>
                            <!-----Parts div end--------->
                            <div class="spacer"></div>
                            <!-----Tech Instruction start--------->
                            <?php 
							if($res_viewJobBoard['created_by']<>0){ 
							$techInstruction = $dbf->getDataFromTable("clients","tech_instruction","id='$res_viewJobBoard[created_by]'");
							$techInstruction = ($techInstruction !='')?$techInstruction :"No instruction given by client.";
							?>
                            <div  class="divService">
                            	<div class="greenText" align="left">Tech Instruction:</div>
                             	<div style="color:#C69; font-weight:bold;" id="techInstruction"><?php echo $techInstruction;?></div>
                                <div class="spacer"></div>
                            </div>
                            <?php }?>
                            <!-----Tech Instruction start--------->
                        	<div class="spacer"></div>
                            <!-----service div start--------->
                            <div  class="divService">
                             <div>
                                  <div align="left" class="jobheader clService">Work Type</div>
                                  <div align="left" class="jobheader clEquipment">Equipment</div>
                                  <div align="left" class="jobheader clModel">Model</div>
                                  <div align="left" class="jobheader clQunt">Quantity</div>
                                  <div align="left" class="jobheader clPrice">Price/Rate</div>
                                  <div align="left" class="jobheader clPrice">Total Price</div>
                                  <div align="left" class="jobheader clPrice">Tech Price</div>
                                  <div style="clear:both;"></div>
                             </div>
                              <?php
							  	 $subtotal =0;  $subTechPrice =0; 
								 $res_woservice = $dbf->fetch("equipment e,work_type wt,workorder_service ws","e.id=ws.equipment AND wt.id=ws.work_type AND ws.workorder_id='$_REQUEST[id]'");
								  foreach($res_woservice as $arrWorkservice){
									  $total = ($arrWorkservice['quantity']*$arrWorkservice['outbox_price']);
									  $TechPrice=$arrWorkservice['tech_price'];
								  	  $subtotal = $subtotal+$total;
								  	  $subTechPrice = $subTechPrice+$TechPrice;
								?>
                           	  <div align="left" class="jobbody clService"><?php echo $arrWorkservice['worktype'];?></div>
                              <div align="left" class="jobbody clEquipment"><?php echo $arrWorkservice['equipment_name'];?></div>
                              <div align="left" class="jobbody clModel"><?php echo $arrWorkservice['model'];?></div>
                              <div align="left" class="jobbody clQunt"><?php echo $arrWorkservice['quantity'];?></div>
                              <div align="left" class="jobbody clPrice"><?php echo $arrWorkservice['outbox_price'];?></div>
                              <div align="left" class="jobbody clPrice"><?php echo number_format($total,2);?></div>
                              <div align="left" class="jobbody clPrice"><?php echo number_format($TechPrice,2);?></div>
                              <div style="clear:both; height:5px;"></div>
                              <?php }?>
                               <div>
                              	<input type="hidden" id="hidCount" value="<?php echo $i;?>"/>
                              	<div class="orderSubtotal">Sub Total:</div><div class="orderSubPrice" id="SubTotal">$ <?php echo number_format($subtotal,2);?></div><div class="orderSubPrice" id="SubTechPrice">$ <?php echo number_format($subTechPrice,2);?></div>
                                 <div style="clear:both;"></div>
                              </div>
                              <div style="clear:both; height:5px;"></div>
                            </div>
                            <!-----service div end--------->
                        	<div class="spacer"></div>
                            <div style="float:left;width:49%;">
                            <!-----admin notes div start--------->
                            <div  class="divPickup" style="width:100%;">
                            <?php //fetch notes from work order notes table
								$resNotes=$dbf->fetchOrder("workorder_notes","workorder_id='$_REQUEST[id]' AND (user_type='admin' OR user_type='user' OR user_type='client') AND customer_attempt=0 AND waiting_parts NOT IN(1,2)","created_date DESC");
							?>
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
                                 <div align="right">By <?php echo $uname;?> on <?php echo date("d-M-Y g:i A",strtotime($resn['created_date']));?> for #<?php echo $res_viewJobBoard['wo_no'];?></div>
                             </div> <div class="spacer"></div>
                             <?php }?>
                             </div>
                             <div class="spacer" style="height:10px;"></div>
                            </div>
                            <!-----admin note div end--------->
                             <!-----Customer attempted Button div start--------->
                           	<div class="divPickup" style="width:100%;margin-top:5px;">
                            <div class="greenText" align="left">Admin Customer Contact:</div>
                            <div align="" style="margin:5px; vertical-align:middle; font-size:14px; font-weight:bold;"></div>
                            <?php
                             //fetch notes from work order notes table
                            $resNotes2=$dbf->fetchOrder("workorder_notes","workorder_id='$_REQUEST[id]' AND (user_type='admin' OR user_type='user') AND customer_attempt <>0","created_date DESC");
							$attempt = count($resNotes2)+1;
							?>
                            <div id="rescontacts">
                            <input type="hidden" name="attempt" id="attempt" value="<?php echo $attempt;?>"/>
							<?php 
							if(!empty($resNotes2)){
                            foreach($resNotes2 as $resn){
                            ?>
                             <div class="textareaNoteView">
                                 <div align="left"><?php echo $resn['wo_notes'];?></div>
                                 <div class="spacer" style="border-bottom:dashed 1px #ccc;"></div>
                                 <div align="right"># <?php echo $resn['customer_attempt'];?> attempt By <?php echo "Admin";?> on <?php echo date("d-M-Y g:i A",strtotime($resn['created_date']));?> for #<?php echo $res_viewJobBoard['wo_no'];?></div>
                             </div><div class="spacer"></div>
                            <?php } }else{?>
                            <span class="redText">Admin Customer Contact are not available.</span>
                            <?php } ?>
                            </div>
                            <div class="spacer" style="height:10px;"></div>
                            </div>
                            <!----Customer attempted Button div end--------->
                            </div>
                            <div style="float:right;width:49%;">
                            <!-----parts needed div start--------->
                            <?php if($res_viewJobBoard['parts_needed_comments'] !=''){?>
                           	<div  class="divNotes" style="width:100%;">
                            <div class="greenText" align="left">Parts Needed Comments:</div>
                             <div class="textareaNoteView">
                                 <div align="left"><?php echo $res_viewJobBoard['parts_needed_comments'];?></div>
                             </div><div class="spacer"></div>
                            </div>
                            <div class="spacer"></div>
                            <?php }?>
                            <!-----tech note div start--------->
                           	<div  class="divNotes"  style="width:100%;">
                            <div class="greenText" align="left">Tech Notes:</div>
                            <?php
                             //fetch notes from work order notes table
                            $resNotes2=$dbf->fetchOrder("workorder_notes","workorder_id='$_REQUEST[id]' AND user_type='tech'","created_date DESC");
                            if(!empty($resNotes2)){
                            foreach($resNotes2 as $resn){
                              if($resn['user_type']=='tech'){
                                 $unameTech = $dbf->fetchSingle("technicians","id='$resn[user_id]'");
                                 $uname = $unameTech['first_name'].' '.$unameTech['middle_name'].' '.$unameTech['last_name'];
                             }
                            ?>
                             <div class="textareaNoteView">
                                 <div align="left"><?php echo $resn['wo_notes'];?></div>
                                 <div class="spacer" style="border-bottom:dashed 1px #ccc;"></div>
                                 <div align="right">By <?php echo $uname;?> on <?php echo date("d-M-Y g:i A",strtotime($resn['created_date']));?> for #<?php echo $res_viewJobBoard['wo_no'];?></div>
                             </div><div class="spacer"></div>
                            <?php }
							}else{
							?>
                            <span class="redText">Tech notes are not available.</span>
                            <div class="spacer"></div>
                            <?php }?>
                            </div>
                            <!-----tech note div end--------->
                            <div class="spacer"></div>
                           
                            <!-----Work status notes--------->
                            <div class="divNotes" style="width:100%;">
                            <div class="greenText" align="left">Order Completion Notes:</div>
                            <?php  
                            //fetch work status comments from work_order_tech table
							//$workid="BOX".(1000+$_REQUEST['id']);
                            $resWork2=$dbf->fetchOrder("work_order_tech","wo_no='$res_viewJobBoard[wo_no]'","created_date DESC");
                            if(!empty($resWork2)){
                            foreach($resWork2 as $resn){
								$unameTech = $dbf->fetchSingle("technicians","id='$resn[tech_id]'");
                                $uname = $unameTech['first_name'].' '.$unameTech['middle_name'].' '.$unameTech['last_name'];
                            ?>
                             <div class="textareaNoteView">
                                 <div align="left"><?php echo $resn['notes'];?></div>
                                 <div class="spacer" style="border-bottom:dashed 1px #ccc;"></div>
                                 <div align="right">By <?php echo $uname;?> on <?php echo date("d-M-Y g:i A",strtotime($resn['created_date']));?> for #<?php echo $res_editJobBoard['wo_no'];?></div>
                             </div><div class="spacer"></div>
                            <?php }
							}else{
							?>
                            <span class="redText">Order Completion notes are not available.</span>
                            <div class="spacer"></div>
                            <?php }?>
                            </div>
                            <!-----end of work status notes--->
                            </div>
                            <div class="spacer"></div>
                            <div class="spacer"></div>
                            <div align="center">
                            <input type="button" class="buttonText" value="Return Back" onClick="window.location='<?php echo $link;?>'"/>
                            <input type="button" class="buttonText3" value="Print" tabindex="40" onClick="print_doc('print','<?php echo $res_viewJobBoard['id'];?>');"/>
                             <input type="button" class="buttonText3" value="PDF" tabindex="40" onClick="print_doc('pdf','<?php echo $res_viewJobBoard['id'];?>');"/>
                              <input type="button" class="buttonText3" value="Notification" tabindex="40" onClick="send_email_tech('<?php echo $res_viewJobBoard['id'];?>','<?php echo $res_viewJobBoard['wo_no'];?>');"/>
                            </div>
                          	<div class="spacer"></div>
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