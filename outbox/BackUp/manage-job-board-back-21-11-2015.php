<?php 
	ob_start("ob_gzhandler");
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
	###########Delete record from work order Table###############
	if($_REQUEST['action']=='delete'){	
	    $dbf->deleteFromTable("workorder_service","workorder_id='$_REQUEST[id]'");
		$dbf->deleteFromTable("work_order","id='$_REQUEST[id]'");
		header("Location:manage-job-board");exit;
	}
	###########Delete record from work order Table###############
	###########Send Email To Customer############################
	if($_REQUEST['action']=='email'){
		//get customer details from table
		$resCust = $dbf->strRecordID("work_order w,clients c","c.name,c.email,w.wo_no","w.client_id=c.id AND w.id='$_REQUEST[id]'");	
	   //Email Sending Starts here
	   //Email send to customer as the order is posted in the system.
		$res_template=$dbf->fetchSingle("email_template","id='3'");
		$from=$res_template['from_email'];
		$fromName=$res_template['from_name'];
		$subject=$res_template['subject'];
		$input=$res_template['message'];
		$toName=ucfirst($resCust['name']);
		$to=$resCust['email'];
		$WorkOrder = $resCust['wo_no'];
		$body=str_replace(array('%Name%','%OrderNo%'),array($toName,$WorkOrder),$input);
		$headers = "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=UTF-8\n";
		$headers .= "From:".$fromName." <".$from.">\n";
	   	//echo $body;exit;
		if($to){
			if(@mail($to,$subject,$body,$headers)){
				###########Track user activity in work order notes table#############
				$adminNotes="Notification send to customer from this work order.";
				$strnotes="workorder_id='$_REQUEST[id]', user_type='$_SESSION[usertype]', user_id='$_SESSION[userid]', wo_notes='$adminNotes',created_date=now()";
				$dbf->insertSet("workorder_notes",$strnotes);
				###########Track user activity in work order notes table#############
				header("Location:manage-job-board?msg=005");exit;
			}else{
				header("Location:manage-job-board?msg=002");exit;
			}
		}else{
			header("Location:manage-job-board");exit;
		}
		/*Email sending end*/
	}
	###########Send Email To Customer############################
	$_SESSION['requesto']=$_SESSION['requesto']?$_SESSION['requesto']:array();
	if(isset($_REQUEST['schaction']) && $_REQUEST['schaction'] =='filtersch'){
		if($_REQUEST['page']=='OpenBoard'){
			$_SESSION['requesto']['search']['srchDate']=$_REQUEST['search']['srchDate'];
			$_SESSION['requesto']['page']=$_REQUEST['page'];
		}elseif($_REQUEST['page']=='EditFilter'){
			$_SESSION['requesto']=$_REQUEST;
		}
	}
?>
<body>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />
<link rel="stylesheet" href="css/no_more_table.css" type="text/css" />
<style type="text/css">
	/* Easy CSS Tooltip - by Koller Juergen [www.kollermedia.at] 
	* {font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px; }*/
	a:hover {text-decoration:none;} /*BG color is a must for IE6*/
	a.tooltip span {display:none; padding:2px 3px 0px 5px; margin-left:6px; margin-top:-70px; width:280px;border-radius:5px;
	-moz-border-radius:5px;}
	a.tooltip:hover span{display:inline; position:absolute; border:3px solid  #ff9812; background:#EEEEEE; color:#000;border-radius:6px;-moz-border-radius:6px;}
	/* for second tooltips*/
	a.tooltip1 span {display:none; padding:2px 3px 0px 5px; margin-left:-65px; margin-top:-70px; width:150px;border-radius:5px;
	-moz-border-radius:5px;}
	a.tooltip1:hover span{display:inline; position:absolute; border:3px solid  #ff9812; background:#EEEEEE; color:#000;border-radius:6px;-moz-border-radius:6px;}
</style>
<script  type="text/javascript" src="js/dragtable.js"></script>
<!--<script  type="text/javascript" src="js/sorttable.js"></script>-->
<script type="text/javascript">
function ShowTechnicians(id){
	$.fancybox.showActivity();	
	var url="assign-technician.php";
	var wono = $("#WorkOrder"+id).val();
	var implode_techs = $("#implode_techs").val();
	$.post(url,{"choice":"assign_job","wono":wono,"wo_id":id,"implode_techs":implode_techs},function(res){			
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
	var url="assign-technician.php";	
	var x=validate_assigntech();
	var cmbTechnician=$('#cmbTechnician').val();
	var StartDate=$('#StartDate').val();
	var chkWO=$('#chkWO').val();
	var work_id=$('#work_id').val();
	if(x){
	 	$.post(url,{"choice":"data_insert","cmbTechnician":cmbTechnician,"chkWO":chkWO,"work_id":work_id,"StartDate":StartDate},function(res){//alert(res);
		 if(res=='1'){
			window.location.href="manage-job-board"; 
		 }else{			
			 $.fancybox(res,{centerOnScroll:true,hideOnOverlayClick:false});			
		 }
	 	});
	}else{
		return false; 
	}
}
function SubmitFields(){
	$("#schaction").val("filtersch");
	document.SrchFrm.submit();
}
function ClearFields(){
	$('#FromDate').val("");
	$('#ToDate').val("");
	$.post("unset-session.php",{"src":"open"},function(res){
		$("#schaction").val("");
		document.SrchFrm.submit();
	});
	
}
function edit_filter(){
	window.location.href="edit-filter-search-open";
}
/*********Function to expand and collapse group************/
function funHide(clss,id){
	$('.'+clss).hide();
	$('#e'+id).show();
	$('#c'+id).hide();
}
function funShow(clss,id){
	$('.'+clss).show();
	$('#c'+id).show();
	$('#e'+id).hide();
}
function funHide1(clss,clss2){
	//$('.'+clss).hide();
	$('.'+clss2).hide();
	$('.hoa').show();
	$('.hob').hide();
	$('#expand').show();
	$('#colapse').hide();
}
function funShow1(clss,clss2){
	$('.'+clss).show();
	$('.'+clss2).show();
	$('.hoa').hide();
	$('.hob').show();
	$('#colapse').show();
	$('#expand').hide();
}
/*********Function to expand and collapse group************/
/*********Function to show create job************/
function add_job(){
	$.fancybox.showActivity();
	var url="ajax-create-job.php";
	var implode_clients = $("#implode_clients").val();
	$.post(url,{"choice":"create","implode_clients":implode_clients},function(res){
		$.fancybox(res,{centerOnScroll:true,hideOnOverlayClick:false});
	});
}
function closeFancyBox(){
	$.fancybox.close();
}
/*********Function to show create job************/
/*********Function to redirect page************/
function redirectPage(id,page,k){
	//alert(k);
	$("#hid").val(id);
	$("#hidk").val(k);
	document.frmRedirect.action=page;
	document.frmRedirect.submit();
}
/*********Function to redirect page************/
function gmap(woid){
	var url="gmap-order.php";	
	$.post(url,{"woid":woid},function(res){
		$.fancybox.showActivity();
		//alert(res);
		//setTimeout( function() {
		  $.fancybox(res,{centerOnScroll:true,hideOnOverlayClick:false,onComplete : function(){initialize();}});
		//},1000);
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
	/*var YourFunction = function() {
		this.init = function() {
			alert("You have clicked a marker");
		}
		this.init();
	}
	function userfunction(){
		//alert("hello");
		infowindow.close();
		//ShowTechnicians(1);
		$.fancybox.close();
	}*/
</script>
<!-- Requied for Map --->
	<form name="frmRedirect" id="frmRedirect" action="" method="post"> 
    	<input type="hidden" name="id" id="hid" value=""/>
        <input type="hidden" name="hidk" id="hidk" value=""/>
    </form>
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
                    	<?php
						  ##########STORE AND RETRIVE THE SEARCH CONDITION FROM SESSION##########
							//print "<pre>";
							//print_r($_SESSION['requesto']);							
							$FromDate=$_SESSION['requesto']['search']['srchDate']['FromDate'];
							$ToDate=$_SESSION['requesto']['search']['srchDate']['ToDate'];
							$srchCust=$_SESSION['requesto']['search']['srchCust'];
							$srchClient=$_SESSION['requesto']['search']['srchClient'];
							$srchService=$_SESSION['requesto']['search']['srchService'];
							$srchWono=$_SESSION['requesto']['search']['srchWono'];
							$srchPurchaseNo=$_SESSION['requesto']['search']['srchPurchaseNo'];
							$columnGroup=$_SESSION['requesto']['search']['columnGroup'];
							$columnOrder=$_SESSION['requesto']['search']['columnOrder'];
							$orderType=$_SESSION['requesto']['search']['orderType'];
						   ##########STORE AND RETRIVE THE SEARCH CONDITION FROM SESSION##########
						   ########insert string  for filter_search table###############
							$insert= "user_type='$_SESSION[usertype]',user_id='$_SESSION[userid]', page_name='openboard'";
						   #############Search Conditions#####################
							$sch="";
							$fromdt= $FromDate?date("Y-m-d",strtotime($FromDate)):'';
							$todt= $ToDate?date("Y-m-d",strtotime($ToDate)):'';
							
							if($srchCust !=''){
								$implode_srchCust =implode(",",$srchCust);
								$sch=$sch."FIND_IN_SET(c.id,'$implode_srchCust') AND ";
								$insert.=",customers='$implode_srchCust'";
							}else{
								$insert.=",customers='$implode_srchCust'";
							}
							if($srchClient !=''){
								$implode_srchClient =implode(",",$srchClient);
								$sch=$sch."FIND_IN_SET(w.created_by,'$implode_srchClient') AND ";
								$insert.=",clients='$implode_srchClient'";
							}else{
								$insert.=",clients='$implode_srchClient'";
							}
							if($srchService !=''){
								$implode_srchService =implode(",",$srchService);
								$sch=$sch."FIND_IN_SET(s.id,'$implode_srchService') AND ";
								$insert.=",services='$implode_srchService'";
							}else{
								$insert.=",services='$implode_srchService'";
							}
							if($srchWono !=''){
								$implode_srchWono =implode(",",$srchWono);
								$sch=$sch."FIND_IN_SET(w.wo_no,'$implode_srchWono') AND ";
								$insert.=",wonos='$implode_srchWono'";
							}else{
								$insert.=",wonos='$implode_srchWono'";
							}
							if($srchPurchaseNo !=''){
								$implode_srchPurchaseNo =implode(",",$srchPurchaseNo);
								$sch=$sch."FIND_IN_SET(w.purchase_order_no,'$implode_srchPurchaseNo') AND ";
								$insert.=",purchasenos='$implode_srchPurchaseNo'";
							}else{
								$insert.=",purchasenos='$implode_srchPurchaseNo'";
							}
							if($FromDate !='' && $ToDate ==''){
								$sch=$sch."w.created_date >= '$fromdt' AND ";
							}
							if($FromDate =='' && $ToDate !=''){
								$sch=$sch."w.created_date <= '$todt' AND ";
							}
							if(($FromDate !='') && ($ToDate !='')){
								$sch=$sch."w.created_date BETWEEN '$fromdt' AND '$todt' AND ";
							}
							$insert.=",from_date='$fromdt',to_date='$todt'";
							
							if($columnGroup !=''){
								$groupby = implode(",",$columnGroup);
								$insert.=",column_group='$groupby'";
							}else{
								$insert.=",column_group='$groupby'";
							}
							if($columnOrder !=''){
								$orderby = implode(",",$columnOrder);
								$insert.=",column_order='$orderby'";
							}else{
								$orderby = "w.id";
								$insert.=",column_order='$orderby'";
							}
							if($orderType !=''){
								$orderType =$orderType[0];
								$insert.=",order_type='$orderType'";
							}else{
								$orderType ="DESC";
								$insert.=",order_type='$orderType'";
							}
							//echo $insert;
							########insert or update  filter_search table###############
						   if($_SESSION['requesto']['schaction'] =='filtersch'){
							   $count_filter_search =$dbf->countRows("filter_search","user_type='$_SESSION[usertype]' AND user_id='$_SESSION[userid]' AND page_name='openboard'");
							   if($count_filter_search){
									$dbf->updateTable("filter_search",$insert.",updated_date=now()","user_type='$_SESSION[usertype]' AND user_id='$_SESSION[userid]' AND page_name='openboard'");
							   }else{
									$dbf->insertSet("filter_search",$insert.",created_date=now()");
							   }
						   }
						   ########insert or update  filter_search table###############
						   $sch=substr($sch,0,-5);
						   if($sch!=''){
							 $cond="c.state=st.state_code AND c.id=w.client_id AND w.service_id=s.id AND w.work_status='Open' AND w.approve_status='1' AND ".$sch;
						   }
						   elseif($sch==''){
							 $cond="c.state=st.state_code AND c.id=w.client_id AND w.service_id=s.id AND w.work_status='Open' AND w.approve_status='1'";
						   }
						   //condition for users
						   if($implode_clients <>''){
								$cond.=" AND FIND_IN_SET(w.created_by,'$implode_clients')";
						   }
						   //echo $cond;
						   #############Search Conditions#####################
						   //count total orders
						   $num=$dbf->countRows("state st,clients c,service s,work_order w",$cond); 
						  ?>
                        <div class="headerbg">
                        	<div style="float:left; width:30%;">Open Order Board</div>
                        	<div style="float:left;width:30%; text-align:center;">Total : <?php echo $num;?> Orders</div>
                        	<div style="float:right; width:40%; text-align:right;">
                            <input type="button" class="buttonText2" value="Edit Filter" onClick="edit_filter();"/>
                            <input type="button" class="buttonText2" value="Create Order" onClick="add_job();"/></div>
                        </div>
                        <?php if($_REQUEST['msg']=='005'){ ?>
							<div align="center" style="color:green;font-weight:bold;">Email send successfully!</div> 
						<?php }elseif($_REQUEST['msg']=='002'){ ?>
                        	<div align="center" style="color:red;font-weight:bold;">Email sending failed!</div> 
                        <?php }else{ ?>
                        	<div class="spacer"></div>
                        <?php }?>
                        <div id="contenttable">
                            <form name="SrchFrm" id="SrchFrm" action="" method="post">
                            <input type="hidden" name="schaction"  id="schaction" value=""/>
                            <input type="hidden" name="page" value="OpenBoard"/>
                              <div style="margin-bottom:5px;" align="center">
                                    <div  class="formtextaddsrch"align="center">From:</div>
                                    <div class="textboxcsrch">
                                    <input type="text" class="textboxsrch datepick" name="search[srchDate][FromDate]" id="FromDate" value="<?php echo $_SESSION['requesto']['search']['srchDate']['FromDate'];?>" readonly></div>
                                    <div  class="formtextaddsrch"align="center">To:</div>
                                    <div class="textboxcsrch">
                                    <input type="text" class="textboxsrch datepick" name="search[srchDate][ToDate]" id="ToDate" value="<?php echo $_SESSION['requesto']['search']['srchDate']['ToDate'];?>" readonly></div>
                                    <div style="float:left; width:200px;">
                                    <input type="button" class="buttonText2" name="SearchRecord" value="Filter Orders" onClick="SubmitFields();">
                                    <input type="button" class="buttonText2" name="Reset" value="Reset Filter" onClick="ClearFields();">
                                   </div>
                              </div>
                            </form>
                          	<div class="spacer"></div>
                              <div id="sortTable">
                              <!-----Table area start------->
                                <table id="no-more-tables" class="draggable">
                                    <thead>
                                        <tr>
                                            <th width="7%">WO#</th>
                                            <th width="9%">CustomerName</th>
                                            <th width="8%">CreatedDate</th>
                                            <th width="8%">OrderStatus</th>
                                            <th width="8%">ServiceType</th>
                                            <th width="7%">Pickupcity</th>
                                            <th width="6%">PickupState</th>
                                            <th width="8%">DeliveryCity</th>
                                            <th width="8%">DeliveryState</th>
                                            <th width="8%">DeliveryPhone</th>
                                            <th width="9%">Client</th>
                                            <th width="5%">Assign</th>
                                            <th width="9%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    	<tr>
                                            <td valign="top" class="grheading">
                                            <div class="divgr">
                                            <a href="javascript:void(0);" onClick="funShow1('ho','ro');" id="expand" style="display:none;"><img  src="images/expand.png" height="21" width="73" alt="Expand All" /></a> 
                                			<a href="javascript:void(0);" onClick="funHide1('ho','ro');" id="colapse" ><img  src="images/collapse.png"  height="21" width="73" alt="Collapse All"/></a>
                                            </div>
                                            </td>
                                            <td class="hiderow">&nbsp;</td>
                                            <td class="hiderow">&nbsp;</td>
                                            <td class="hiderow">&nbsp;</td>
                                            <td class="hiderow">&nbsp;</td>
                                            <td class="hiderow">&nbsp;</td>
                                            <td class="hiderow">&nbsp;</td>
                                            <td class="hiderow">&nbsp;</td>
                                            <td class="hiderow">&nbsp;</td>
                                            <td class="hiderow">&nbsp;</td>
                                            <td class="hiderow">&nbsp;</td>
                                            <td class="hiderow">&nbsp;</td>
                                            <td class="hiderow">&nbsp;</td>
                                        </tr>
                                     <?php
										$resGrArray=$dbf->fetchOrder("state st,clients c,service s,work_order w",$cond,"c.state ASC","","c.state");
										//group by state loop
										foreach($resGrArray as $k=>$sgRes){
										$Cls="g$k";	
										$numres = $dbf->countRows("state st,clients c,service s,work_order w","c.state='$sgRes[state]' AND " .$cond);
									  ?>
										<tr style="background-color:#f9f9f9;" class="ho">
                                            <td valign="top" class="grheading">
                                            <div class="divgr">
                                            <a href="javascript:void(0);" onClick="funShow('<?php echo $Cls;?>','<?php echo $k;?>');" id="e<?php echo $k;?>" <?php if($k==0){?>style="display:none;" <?php }?> class="hoa"><img  src="images/plus.gif" height="13" width="13"/>&nbsp;<?php echo $numres;?> Open Jobs in <span style="color:#ff9812;"><?php echo $sgRes['state_name'];?></span> needs to be assigned</a> 
                                			<a href="javascript:void(0);" onClick="funHide('<?php echo $Cls;?>','<?php echo $k;?>');" id="c<?php echo $k;?>" <?php if($k!=0){?>style="display:none;" <?php }?> class="hob"><img  src="images/minus.gif" height="13" width="13"/>&nbsp;<?php echo $numres;?> Open Jobs in <span style="color:#ff9812;"><?php echo $sgRes['state_name'];?></span> needs to be assigned</a>
                                            </div>
                                            </td>
                                            <td class="hiderow">&nbsp;</td>
                                            <td class="hiderow">&nbsp;</td>
                                            <td class="hiderow">&nbsp;</td>
                                            <td class="hiderow">&nbsp;</td>
                                            <td class="hiderow">&nbsp;</td>
                                            <td class="hiderow">&nbsp;</td>
                                            <td class="hiderow">&nbsp;</td>
                                            <td class="hiderow">&nbsp;</td>
                                            <td class="hiderow">&nbsp;</td>
                                            <td class="hiderow">&nbsp;</td>
                                            <td class="hiderow">&nbsp;</td>
                                            <td class="hiderow">&nbsp;</td>
                                        </tr>
                                        <?php 
											$resArray=$dbf->fetchOrder("state st,clients c,service s,work_order w","c.state='$sgRes[state]' AND " .$cond,$orderby." ".$orderType,"st.state_name,c.*,s.service_name,w.*",$groupby);
											foreach($resArray as $key=>$res_JobBoard) { 
											$pickupstate = $dbf->getDataFromTable("state","state_name","state_code='$res_JobBoard[pickup_state]'");
											if($res_JobBoard['work_status']=='Open'){$color='#333';}	
											//get client name
											if($res_JobBoard['created_by']<>0){
												$clientname =$dbf->getDataFromTable("clients","name","id='$res_JobBoard[created_by]'");
											}else{
												$clientname="COD";
											}							
										?>   
                                    	<tr class="<?php echo $Cls;?> ro" <?php if($k!=0){?> style="display:none;" <?php } ?>>
                                        	<input type="hidden" id="WorkOrder<?php echo $res_JobBoard['id'];?>" value="<?php echo $res_JobBoard['wo_no'];?>"/>
                                            <td data-title="WO#" class="coltext"><a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_JobBoard['id'];?>','view-job-board','<?php echo $k;?>');" title="Click Here For Job Details" style="color:<?php echo $color;?>" class="tooltip"><?php echo $res_JobBoard['wo_no'];?><span><?php include 'admin_notes.php';?></span></a></td>
                                            <td data-title="CustomerName"><?php echo $dbf->cut($res_JobBoard['name'],15);?></td>
                                            <td data-title="CreatedDate"><?php echo date("d-M-Y",strtotime($res_JobBoard['created_date']));?></td>
                                            <td data-title="OrderStatus" class="coltext" style="color:<?php echo $color;?>"><?php echo $res_JobBoard['work_status'];?></td>
                                            <td data-title="ServiceType"><?php echo $res_JobBoard['service_name'];?></td>
                                            <td data-title="Pickupcity"><?php echo $res_JobBoard['pickup_city'];?></td>
                                            <td data-title="PickupState"><?php echo $pickupstate;?></td>
                                            <td data-title="DeliveryCity"><?php echo $res_JobBoard['city'];?></td>
                                            <td data-title="DeliveryState"><?php echo $res_JobBoard['state_name'];?></td>
                                            <td data-title="DeliveryPhone"><?php echo $res_JobBoard['phone_no'];?></td>
                                            <td data-title="Client" class="coltext"><?php echo $clientname;?></td>
                                            <td data-title="Assign" class="coltext"><a href="javascript:void(0);" onClick="ShowTechnicians('<?php echo $res_JobBoard['id'];?>');" title="Click Here To Assign Tech">Assign</a></td>
                                            <td data-title="Action"><a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_JobBoard['id'];?>','edit-job-board-open','<?php echo $k; ?>');"><img src="images/edit.png" title="Edit" alt="Edit"/></a>&nbsp;<a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_JobBoard['id'];?>','view-job-board-open','<?php echo $k;?>');"><img src="images/view.png" title="View" alt="View"/></a>&nbsp;<a href="manage-job-board?action=delete&id=<?php echo $res_JobBoard['id'];?>" onClick="return confirm('Are you sure you want to delete this record ?')"><img src="images/delete.png" title="delete" alt="delete"></a>&nbsp;<a href="manage-job-board?action=email&id=<?php echo $res_JobBoard['id'];?>" onClick="return confirm('Are you sure you want to send email ?')"><img src="images/email_go.png" title="Email To Customer" alt="Email"></a>&nbsp;<a href="javascript:void(0);" onClick="gmap('<?php echo $res_JobBoard['id'];?>')" style="text-decoration:none;color:#F90;"><img src="images/Map-Marker-Pink.png" alt="map locator" title="Click For Map"></a></td>
                                        </tr>
                                         <?php } 
											}
										?> 
                                    </tbody>
                               </table>
                              <!-----Table area start-------> 
                            <?php if($num == 0) {?><div class="noRecords" align="center">No records founds!!</div><?php }?>
                         	 </div>
                      </div>
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
<?php if($_REQUEST['g']){
	echo "<script>funShow('g'+'".$_REQUEST['g']."','".$_REQUEST['g']."')</script>";
	if($_REQUEST['g']>0){
		echo "<script>funHide('g0','0')</script>";
	}
}?>