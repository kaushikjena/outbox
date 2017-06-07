<?php  
ob_start(); 
session_start(); 
include_once '../includes/class.Main.php'; 
//Object initialization 
$dbf = new User();  
//page titlevariable 
$pageTitle="Welcome To Out Of The Box"; 
include 'applicationtop-tech.php'; 
if($_SESSION['usertype']!='tech'){ 
	header("location:../logout");exit; 
} 
//echo $_REQUEST[id];
//Fetch details from work_order table 
$res_viewJobBoard=$dbf->fetchSingle("clients c,service s,work_order w","c.id=w.client_id AND w.service_id=s.id  AND w.id='$_REQUEST[id]'");

$resTech = $dbf->fetchSingle("assign_tech at,technicians tc","at.tech_id=tc.id AND at.wo_no='$res_viewJobBoard[wo_no]' AND tc.id='$_SESSION[userid]'");
//fetch from work order doc table
$workorderdoc = $dbf->getDataFromTable("workorder_doc","wo_document","workorder_id='$_REQUEST[id]'");
$countunread = $dbf->countRows("workorder_doc","workorder_id='$_REQUEST[id]' AND unread=0");
//$link to produce right url during Back button.
if($_REQUEST['src']== 'cal'){$link='tech_jobs_calendar';}elseif($_REQUEST['src']== 'wfp'){$link="tech-manage-job-board-wfp?g=$_REQUEST[hidk]";}elseif($_REQUEST['src']== 'hold'){$link="tech-manage-job-board-hold?g=$_REQUEST[hidk]";}elseif($_REQUEST['src']== 'pending'){$link="tech-manage-job-board-pending?g=$_REQUEST[hidk]";}else{$link="tech-manage-job-board?g=$_REQUEST[hidk]";}
?>
<script type="text/javascript">
function gmap(woid){
	$.fancybox.showActivity();
	var url="gmap-order.php";
	$.post(url,{"woid":woid},function(res){
		$.fancybox(res,{centerOnScroll:true,hideOnOverlayClick:false,onComplete : function(){initialize();}});
	});
}
function downLoadDocument(fname){
	window.location.href='../docdnd.php?file=workorder_doc/'+fname;
}
function add_notes(){
	var url="ajax_addnote_tech.php";
	var worid = $("#worid").val();
	var techNotes = $("#techNotes").val();
	if(techNotes !=''){
		$.post(url,{"id":worid,"techNotes":techNotes},function(res){		
			$("#resnotes").html(res);
			$("#techNotes").val('');			
		});
	}
}
function contact_customer(){
	var url="ajax_addcontact_tech.php";
	var worid = $("#worid").val();
	var attempt = $("#attempt").val();
	$.post(url,{"id":worid,"attempt":attempt},function(res){//alert(res);	
		$("#rescontacts").html(res);			
	});
}
/*********Function to print job************/
function print_doc(val,woid){
	if(val=='print'){
		 window.open("tech_job_board_print?id="+woid,'_blank');
    }
}
function UploadDocument(wono,woid){
	$.fancybox.showActivity();	
	var url="tech-upload-docs.php";
	$.post(url,{"wono":wono,"woid":woid},function(res){			
		$.fancybox(res,{centerOnScroll:true,hideOnOverlayClick:false,'onClosed':function () {
            location.reload(); }
		});				
	});
}
function viewDocument(fname,wono,woid){
	$.fancybox.showActivity();	
	var url="tech-view-docs.php";
	$.post(url,{"fname":fname,"wono":wono,"woid":woid},function(res){			
		$.fancybox(res,{centerOnScroll:true,hideOnOverlayClick:false,'onClosed':function () {
            location.reload(); }
		});
	});
}
function returnBack(wono,woid){
	UploadDocument(wono,woid);
}
function closeFancyBox(){
	$.fancybox.close();
	location.reload();
}
/*********Function to print job************/
$(document).ready(function() {
    $("#techNotes").focus();
	var window_height = $(window).height();
    var document_height = $(document).height();
    $('html,body').animate({ scrollTop: window_height + document_height }, 'slow', function (){ });    
});
//waiting for parts
function waiting_for_parts(){ 
	$.fancybox.showActivity();
	var url="tech_waiting_for_parts.php";
	var worid = $("#worid").val(); 
	$.post(url,{"choice":"view","type":"tech","id":worid},function(res){
		$.fancybox(res,{centerOnScroll:true,hideOnOverlayClick:false});
	});
}
//waiting for parts
function waiting_for_parts_insert(){
	var cond=validate_WaitingParts();
	if(cond){
		$.fancybox.showActivity();
		var url="tech_waiting_for_parts.php";
		var worid = $("#worid").val();
		var workorder_notes_id = $("#workorder_notes_id").val();
		if(workorder_notes_id!=''){
			workorder_notes_id=workorder_notes_id;
		}else{
			workorder_notes_id=''
		}
		var waiting_parts_comments = $("#waiting_parts_comments").val();
		if($("#chk_box").attr('checked')){var chk_box = 1;}else{var chk_box = 2;}
		$.post(url,{"choice":"insert","id":worid,"workorder_notes_id":workorder_notes_id,"waiting_parts_comments":waiting_parts_comments,"chk_box":chk_box},function(res){//alert(res)
			$("#reswfp").html(res);
			$.fancybox.close();
		});
	}else{
		return false;	
	}
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
<link rel="stylesheet" href="../css/innermain.css" type="text/css" />
<link rel="stylesheet" href="../css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="../css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="../css/respmenu.css" type="text/css" />
<body>
    <div id="maindiv">
        <!-------------header--------------->
     	<?php include_once 'header-tech.php';?>
   		<!-------------header--------------->
        
        <!-------------top menu--------------->
     	<?php include_once 'tech-top-menu.php';?>
   		<!-------------top menu--------------->
         <div id="contentdiv">
                <!-------------Main Body--------------->
                <div class="rightcolumjobboard">
            		<div class="rightcoluminner">
                        <div class="headerbg">
                        <div style="float:left;">VIEW TECH ORDER</div>
                        <div style="float:right;"><input type="button" class="buttonText2" value="Return Back" onClick="window.location='<?php echo $link;?>'"/>
                        <input type="button" class="buttonText2" value="Print" tabindex="40" onClick="print_doc('print','<?php echo $res_viewJobBoard['id'];?>');"/>
                        </div>
                        </div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        <!-----Table area start------->
                          <form name="createJob" id="createJob" action="" method="post" autocomplete="off">
                          <input type="hidden" name="worid" id="worid" value="<?php echo $res_viewJobBoard['id'];?>">
                        	 <div align="center"><?php if($_REQUEST['msg']=='002'){?><span class="redText">This Email ID already exist!</span><?php }?></div>
                            <!-----address div start--------->
                            <div  class="divAddress">
                            <div class="greenText" align="left">Work Order Details</div>
                            <div class="spacer"></div>
                            <div  class="formtextaddjob">WO#:</div>
                            <div  class="textboxjobview">&nbsp;<?php echo $res_viewJobBoard['wo_no'];?></div>
                            <div  class="formtextaddjoblong">Purchase Order:</div>
                            <div  class="textboxjobview">&nbsp;<?php echo $res_viewJobBoard['purchase_order_no'];?></div>
                            <div class="spacer"></div>
                            <div  class="formtextaddjob">Order Status:</div>
                            <div  class="textboxjobview">&nbsp;<?php echo $res_viewJobBoard['work_status'];?></div>
                            <div  class="formtextaddjoblong">Service Name:</div>
                            <div  class="textboxjobview">&nbsp;<?php echo $res_viewJobBoard['service_name'];?></div>
                            <div class="spacer"></div>
                            <div  class="formtextaddjob">Technician:</div>
                            <div  class="textboxjobview">&nbsp;<?php if($resTech<>''){?><?php echo $resTech['first_name'].'&nbsp;'.$resTech['middle_name'].'&nbsp;'.$resTech['last_name'];?><?php }else{?> Not Assigned<?php }?></div>
                            <div  class="formtextaddjoblong">Assigned Date:</div>
                            <div  class="textboxjobview">&nbsp;<?php if($resTech<>'' && $resTech['assign_date']<>'0000-00-00'){echo date("d-M-Y",strtotime($resTech['assign_date']));}?>
                            </div>
                           <div class="spacer"></div>
                           <div  class="formtextaddjob">Scheduled Date:</div>
                            <div  class="textboxjobview">&nbsp;<?php if($resTech<>'' && $resTech['start_date']<>'0000-00-00'){echo date("d-M-Y",strtotime($resTech['start_date'])).'&nbsp;&nbsp;&nbsp;'.$resTech['start_time'];}?>
                            </div>
                           <div class="spacer"></div>
                         </div>
                         	<!-----address div end--------->
                            <!-----purchase div start--------->
                           	<div  class="divPurchase">
                               <div class="greenText" align="left">Customer Information</div>
                                <div  class="formtextaddjob">Name:</div>
                                <div  class="textboxjobview"><?php echo $res_viewJobBoard['name'];?></div>
                                <div  class="formtextaddjoblong">Email Address:</div>
                                <div  class="textboxjobview"><?php echo $res_viewJobBoard['email'];?></div>
                                <div class="spacer"></div>
                                <div id="rescust">
                                <div  class="formtextaddjob">Address:</div>
                                <div  class="textboxjobview"><?php echo $res_viewJobBoard['address'];?></div>
                                <div  class="formtextaddjoblong">Contact Name:</div>
                                <div  class="textboxjobview"><?php echo $res_viewJobBoard['contact_name'];?></div>
                                 <div class="spacer"></div>
                                 <div  class="formtextaddjob">City:</div>
                                <div  class="textboxjobview"><?php echo $res_viewJobBoard['city'];?></div>
                                <div  class="formtextaddjoblong">State:</div>
                                 <?php $res_jobState=$dbf->getDataFromTable("state","state_name","state_code='$res_viewJobBoard[state]'");?>
                                <div  class="textboxjobview"><?php echo $res_jobState;?></div>
                                 <div class="spacer"></div>
                                 <div  class="formtextaddjob">Zip Code:</div>
                                <div  class="textboxjobview"><?php echo $res_viewJobBoard['zip_code'];?></div>
                                <div  class="formtextaddjoblong">Phone No:</div>
                                <div  class="textboxjobview"><?php echo $res_viewJobBoard['phone_no'];?></div>
                                 <div class="spacer"></div>
                                <div  class="formtextaddjob">Cell No:</div>
                                 <div  class="textboxjobview"><?php echo $res_viewJobBoard['fax_no'];?>&nbsp;</div>
                                <div  class="formtextaddjoblong"><a href="javascript:void(0);" onClick="gmap('<?php echo $res_viewJobBoard['id'];?>')" style="text-decoration:none;color:#F90;"><img src="../images/locater.jpg" width="21" height="24" align="absmiddle">Click For Map</a></div> 
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
                                 <div class="spacer"></div>
                            </div>
                            <!-----pickup div end--------->
                            <!-----note div start--------->
                            <div  class="divNotes">
                             <div class="spacer"></div>
                             <div class="greenText" align="left">Order Description:</div>
                             <div  class="textboxjobviewlarge">&nbsp;<?php echo $res_viewJobBoard['notes'];?></div>
                             <?php if($workorderdoc){ ?>
                             <div class="spacer"></div>
                             <div class="formtextaddjob">Upload Doc:</div>
                             <div  class="textboxcjob" style="width:500px;"><span class="orangeText"><a href="javascript:void(0);" onClick="UploadDocument('<?php echo $res_viewJobBoard['wo_no'];?>','<?php echo $res_viewJobBoard['id'];?>');">Click here to Upload and View Work Order Documents</a></span>&nbsp;<?php if($countunread){?><span class="noRecords">[<?php echo $countunread;?> unread docs]</span><?php }?></div>
                             <?php }else{?>
                             <div class="spacer"></div>
                             <?php }?>
                            </div>
                            <!-----note div end--------->
                            <!-----Parts div start--------->
                             <div class="spacer"></div>
                            <div  class="divPickup" style="margin-top:5px;">
                            	<div class="greenText" align="left">Parts/Item Tracking Info</div>
                            	<div class="spacer"></div>
                                <div  class="formtextaddjob">Parts Arrive:</div>
                                <div  class="textboxjobview">&nbsp;
								<?php if($res_viewJobBoard['parts_arrive']!='0000-00-00'){echo date('d-M-Y',strtotime($res_viewJobBoard['parts_arrive']));}else{ echo "";}?></div>
                                <div  class="formtextaddjoblong">Tracking No:</div>
                                <div  class="textboxjobview">&nbsp;<?php echo $res_viewJobBoard['tracking_number'];?></div>
                                <div  class="formtextaddjob">Carrier Company:</div>
                                <div  class="textboxjobview">&nbsp;<?php echo $res_viewJobBoard['carrier_company'];?></div>
                                <div  class="formtextaddjoblong">Serial Number:</div>
                                <div  class="textboxjobview">&nbsp;<?php echo $res_viewJobBoard['serial_number'];?></div>
                                 <div class="spacer"></div>
                                <div  class="formtextaddjob">Model Number:</div>
                                <div  class="textboxjobview">&nbsp;<?php echo $res_viewJobBoard['model_number'];?></div>
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
                                  <div align="left" class="jobheader clPrice1">Tech Price (<?php echo $resTech['pay_grade'];?>)</div>
                                  <div align="left" class="jobheader clPrice1">Total Price</div>
                                  <div style="clear:both;"></div>
                             </div>
                              <?php 
							  	$subtotal =0; 
								 $res_woservice = $dbf->fetch("equipment e,work_type wt,workorder_service ws","e.id=ws.equipment AND wt.id=ws.work_type AND ws.workorder_id='$_REQUEST[id]'");
								  foreach($res_woservice as $arrWorkservice){
									$TechPrice=$arrWorkservice['tech_price'];
									//$total = ($arrWorkservice['quantity']*$TechPrice);
									$total = $TechPrice;
									$subtotal = $subtotal+$total;
								?>
                           	  <div align="left" class="jobbody clService"><?php echo $arrWorkservice['worktype'];?></div>
                              <div align="left" class="jobbody clEquipment"><?php echo $arrWorkservice['equipment_name'];?></div>
                              <div align="left" class="jobbody clModel"><?php echo $arrWorkservice['model'];?></div>
                              <div align="left" class="jobbody clQunt"><?php echo $arrWorkservice['quantity'];?></div>
                              <div align="left" class="jobbody clPrice1">$ <?php echo number_format($TechPrice,2);?></div>
                              <div align="left" class="jobbody clPrice1">$ <?php echo number_format($total,2);?></div>
                              <div style="clear:both; height:5px;"></div>
                              <?php }?>
                               <div>
                              	<div class="orderSubtotal">Sub Total:</div><div class="orderSubPrice" id="SubTotal" style="width:16%; text-align:center;">$ <?php echo number_format($subtotal,2);?></div>
                                 <div style="clear:both;"></div>
                              </div>
                              <div style="clear:both; height:5px;"></div>
                            </div>
                            <!-----service div end--------->
                        	<div class="spacer"></div>
                            <!-----tech notes div start--------->
                            <div  class="divPickup">
                            <?php //fetch notes from work order notes table
								$resNotes=$dbf->fetchOrder("workorder_notes","workorder_id='$_REQUEST[id]' AND user_type='tech' AND customer_attempt=0 AND waiting_parts NOT IN(1,2)","created_date DESC");
							?>
                             <div class="greenText" align="left">Tech Notes:</div>
                             <div><textarea name="techNotes" id="techNotes"  class="textareaOrderNote" tabindex="24"></textarea><br/><label for="techNotes" id="lbltechNotes" class="redText"></label></div>
                             <div align="right" style="margin:5px;"><img src="../images/add_note.png" alt="Add Note" title="Add Note" style="cursor:pointer;" onClick="add_notes();"/></div>
                             <div id="resnotes">
                             <?php foreach($resNotes as $resn){
								 if($resn['user_type']=='tech'){
									 $unameTech = $dbf->fetchSingle("technicians","id='$resn[user_id]'");
									 $uname = $unameTech['first_name'].' '.$unameTech['middle_name'].' '.$unameTech['last_name'];
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
                            <!-----tech note div end--------->
                            <!-----tech note div start--------->
                           	<div  class="divNotes">
                            <div class="greenText" align="left">Tech Customer Contact:</div>
                            <div align="" style="margin:5px; vertical-align::middle; font-size:14px; font-weight:bold;">(Click if the Customer is not reachable)&nbsp;&nbsp;<img src="../images/contact_number.png" alt="Contact Customer" title="Contact Customer" style="cursor:pointer;" onClick="contact_customer();"/>&nbsp;&nbsp;&nbsp;(Click here for Waiting For Parts)<img src="../images/tools_preferences.png" alt="WFP" title="Waiting For Parts" style="cursor:pointer;width:35px;height:30px;" onClick="waiting_for_parts();"/></div>
                             <?php
                            //fetch data from woroderer_notes table
							$workorder_notes=$dbf->fetchSingle("workorder_notes","workorder_id='$_REQUEST[id]' AND (user_type='admin' OR user_type='user' OR user_type='tech')  AND waiting_parts!=0");
							if($workorder_notes['user_type']=='admin'){
								 $uname = $dbf->getDataFromTable("admin","name","id='$workorder_notes[user_id]'");
							 }elseif($workorder_notes['user_type']=='user'){
								 $uname = $dbf->getDataFromTable("users","name","id='$workorder_notes[user_id]'");
							 }elseif($workorder_notes['user_type']=='tech'){
								  $unameTech = $dbf->fetchSingle("technicians","id='$workorder_notes[user_id]'");
	 							  $uname = $unameTech['first_name'].' '.$unameTech['middle_name'].' '.$unameTech['last_name'];
							}
							?>
                            <div id="reswfp">
							<?php 
							if(!empty($workorder_notes)){
                            ?>
                              <div class="textareaNoteView">
                                 <div align="left"><?php echo $workorder_notes['wo_notes'];?></div>
                                 <div class="spacer" style="border-bottom:dashed 1px #ccc;"></div>
                                 <div align="right">WFP Note: By <?php echo $uname;?> on <?php echo date("d-M-Y g:i A",strtotime($workorder_notes['created_date']));?> for #<?php echo $res_viewJobBoard['wo_no'];?></div>
                             </div><div class="spacer"></div>
                            <?php }?>
                            </div>
                            <?php
                             //fetch notes from work order notes table
                            $resNotes2=$dbf->fetchOrder("workorder_notes","workorder_id='$_REQUEST[id]' AND user_type='tech' AND customer_attempt <>0","created_date DESC");
							$attempt = count($resNotes2)+1;
							?>
                            <div id="rescontacts">
                            <input type="hidden" name="attempt" id="attempt" value="<?php echo $attempt;?>"/>
							<?php 
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
                                 <div align="right"># <?php echo $resn['customer_attempt'];?> attempt By <?php echo $uname;?> on <?php echo date("d-M-Y g:i A",strtotime($resn['created_date']));?> for #<?php echo $res_viewJobBoard['wo_no'];?></div>
                             </div><div class="spacer"></div>
                            <?php } }?>
                            </div>
                            <div class="spacer" style="height:10px;"></div>
                            </div>
                            <!-----tech note div end--------->
                            <div class="spacer"></div>
                            <div align="center">
                         	 <input type="button" class="buttonText" value="Return Back" onClick="window.location.href='<?php echo $link;?>'"/>
                              <input type="button" class="buttonText3" value="Print" tabindex="40" onClick="print_doc('print','<?php echo $res_viewJobBoard['id'];?>');"/>
                             </div>
                          	<div class="spacer"></div>
                           </form>
                           <!-----Table area end------->
                    	</div>
            	</div>
               </div>
              <!-------------Main Body--------------->
         </div>
        <div class="spacer"></div>
        <?php include_once 'footer-tech.php'; ?>
  </div>
</body>
</html>