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
	###########Cancel record from work order Table###############
	if(isset($_REQUEST['action']) && $_REQUEST['action']=='cancel'){	
		$dbf->updateTable("work_order","work_status='Cancelled',updated_date=now(),updated_by='".$_SESSION['userid']."'","id='".$_REQUEST['id']."'");
		//fetch work order details for email sending 
		$fetch_data=$dbf->strRecordID("work_order wo,clients c,service s","wo.wo_no, wo.purchase_order_no, c.name, s.service_name","wo.client_id=c.id AND wo.service_id=s.id AND wo.id='$_REQUEST[id]'");
		$wo_no=$fetch_data['wo_no'];
		$purchase_order=$fetch_data['purchase_order_no'];
		$customer_name=$fetch_data['name'];
		$service_name=$fetch_data['service_name'];
		########get client name for subject line###############
		$clientName =$dbf->getDataFromTable("work_order w,clients c","c.name","w.created_by=c.id AND w.id='$_REQUEST[id]'");
		$clientName = $clientName ? $clientName :"COD";
		########get client name for subject line###############
		//fetch tech details
		$resTech =$dbf->strRecordID("assign_tech at,technicians tc","tc.first_name, tc.middle_name, tc.last_name,tc.email, at.start_date, at.start_time","at.tech_id=tc.id AND at.wo_no='$fetch_data[wo_no]'");
		$startdate = $resTech['start_date'];
		$starttime = $resTech['start_time'];
		//fetch admin details for email sending
		$res_admin=$dbf->strRecordID("admin","email","id='1'");
		$startdate = ($startdate<>'0000-00-00')? date("d-M-Y",strtotime($startdate)):'';
		#############Start-Email Send to Technician################
		$toemail = $resTech['email'];
		$techname = $resTech['first_name'].'&nbsp;'.$resTech['middle_name'].'&nbsp;'.$resTech['last_name'];
		$resEmailTech =$dbf->fetchSingle("email_template","id='24'");
		$input=$resEmailTech['message'];
		$subject=$resEmailTech['subject']."==".$clientName."==".$wo_no;
		$from_name=$resEmailTech['from_name'];
		//replace email template
		$email_body = str_replace(array('%Name%','%Wo_No%','%PurchaseOrder%','%CustomerName%'),array($techname,$wo_no,$purchase_order,$customer_name),$input);
		$headers = "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=UTF-8\n";
		$headers .= "From:".$from_name."<".$res_admin['email'].">\n";
		//echo $toemail."==".$subject.'=='.$headers.'=='.$email_body;exit;
		@mail($toemail,$subject,$email_body,$headers);
		#############End-Email Send to Technician################
		###########Track user activity in work order notes table#############
		$adminNotes="This order is cancelled.";
		$strnotes="workorder_id='$_REQUEST[id]', user_type='$_SESSION[usertype]', user_id='$_SESSION[userid]', wo_notes='$adminNotes',created_date=now()";
		$dbf->insertSet("workorder_notes",$strnotes);
		###########Track user activity in work order notes table#############
		header("Location:manage-job-board-dispatch");exit;
	}
	###########Cancel record from work order Table###############
	$_SESSION['requestp']= $_SESSION['requestp']?$_SESSION['requestp']:array();
	if(isset($_REQUEST['schaction']) && $_REQUEST['schaction'] =='filtersch'){
		if($_REQUEST['page']=='DispatchBoard'){
			$_SESSION['requestp']['search']['srchDate']=$_REQUEST['search']['srchDate'];
			$_SESSION['requestp']['page']=$_REQUEST['page'];
		}elseif($_REQUEST['page']=='EditFilter'){
			$_SESSION['requestp']=$_REQUEST;
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
</style>
<script  type="text/javascript" src="js/dragtable.js"></script>
<!--<script  type="text/javascript" src="js/sorttable.js"></script>-->
<script type="text/javascript">
function SubmitFields(){
	$("#schaction").val("filtersch");
	$("#SrchFrm").submit();
}
function ClearFields(){
	$('#FromDate').val("");
	$('#ToDate').val("");
	$.post("unset-session.php",{"src":"disp"},function(res){
		$("#schaction").val("");
		$("#SrchFrm").submit();
	});
}
function Show_Workstatus(wono){
	$.fancybox.showActivity();
	var url="technician_workstatus.php";
	$.post(url,{"choice":"workstatus","wono":wono},function(res){			
		$.fancybox(res,{centerOnScroll:true,hideOnOverlayClick:false});				
	});
}
/*********Function to expand and collapse group************/
function funHide(clss,id){
	//alert(id);
	$('.'+clss).hide();
	$('#e'+id).show();
	$('#c'+id).hide();
}
function funShow(clss,id){
	//alert(id);
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
/*********Function to go edit filter search page************/
function edit_filter(){
	window.location.href="edit-filter-search";
}
/*********Function to chnage invoiced status************/
function redirecttodispatch(){
	window.location.href='manage-job-board-dispatch';
}
function make_invoiced(){
	$.fancybox.showActivity();
	var url="ajax-ready-to-invoice.php";
	$.post(url,{"choice":"view"},function(res){
		$.fancybox(res,{centerOnScroll:true,hideOnOverlayClick:false});
	});
}
function change_invoiced(){
	$.fancybox.showActivity();	
	var url="ajax-ready-to-invoice.php";
	var x=check_validation();
	if(x){
		var orderArray = new Array();
		$('input:checkbox[name="chkOrder[]"]:checked').each(function() { 
			 if($(this).attr('checked', true)){
				  orderArray.push($(this).val());
			 }
		 });
		 //alert(orderArray);
		$.post(url,{"choice":"make_invoice","orderArray":orderArray},function(res){
			if(res =='1'){
				var message = "<font style='font-size:14px;color:#090;font-weight:bold;'>The orders status changed into Invoiced.</font>";
			}else if(res=='2'){
				var message = "<font style='font-size:14px;color:#F00;font-weight:bold;'>Sorry!!! Unable to change status.</font>";
			}
			$.fancybox(message,{centerOnScroll:true,hideOnOverlayClick:false,'onClosed': function(){redirecttodispatch();}});
		});
	}else{
		return false;
	}
}
function check_validation(){
	var chklength=$('input:checkbox[name="chkOrder[]"]:checked').length;
	if(chklength==0){
		alert("Please select at least one order.");
		return false;
	}else{
		return true;
	}
}
function check_all(){
  var chkval= $('input:checkbox[name=chkAll]:checked').val();
 //alert(chkval);
 if(chkval==1){
		$('input:checkbox[name="chkOrder[]"]').each(function() { 
			 $(this).attr('checked', true);
		 });
	}else{
		$('input:checkbox[name="chkOrder[]"]').each(function() { 
			 $(this).attr('checked', false);
		 });
	}
}
function input_invoice_number(){
	$.fancybox.showActivity();
	var url="ajax-input-invoice-number.php";
	$.post(url,{"choice":"view"},function(res){
		$.fancybox(res,{centerOnScroll:true,hideOnOverlayClick:false});
	});
}
function update_invoice_number(){
	$.fancybox.showActivity();	
	var url="ajax-input-invoice-number.php";
	var orderArray = new Array();
	var finalorderArray = new Array();
	$('input:hidden[name="chkOrder[]"]').each(function() { 
		 orderArray.push($(this).val());
	 });
	$.each(orderArray, function( i, v ){
		var invoiceno = $("#InvoiceNumber"+v).val();
		if(invoiceno !=''){
			var data = v + "==" + invoiceno;
			finalorderArray.push(data);
		}
	});
	//alert(finalorderArray);
	$.post(url,{"choice":"update_invoice_number","finalorderArray":finalorderArray},function(res){
		if(res =='1'){
			var message = "<font style='font-size:14px;color:#090;font-weight:bold;'>The Invoice number updated successfully.</font>";
		}else if(res=='2'){
			var message = "<font style='font-size:14px;color:#F00;font-weight:bold;'>Sorry!!! Unable to update invoice number.</font>";
		}
		$.fancybox(message,{centerOnScroll:true,hideOnOverlayClick:false,'onClosed': function(){redirecttodispatch();}});
	});
	
}
/*********Function to chnage invoiced status************/
/*********Function to redirect page************/
function redirectPage(id,page,k){
	$("#hid").val(id);
	$("#hidk").val(k);
	document.frmRedirect.action=page;
	document.frmRedirect.submit();
}
function cancel_order(id,page){
	var r =confirm("Are you sure you want to cancel this order?");
	if(r){
		window.location.href=page+"?action=cancel&id="+id;
	}else{
		return false;
	}
}
/*********Function to redirect page************/
/*********Function to print job************/
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
	 	$.post(url,{"choice":"send_email","wono":wono,"woid":woid,"fromemail":fromemail,"fromname":fromname,"subject":subject,"message":message},function(res){//alert(res);
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
</script>
	<form name="frmRedirect" id="frmRedirect" action="" method="post"> 
    	<input type="hidden" name="id" id="hid" value=""/>
        <input type="hidden" name="src" value="disp"/>
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
							##########RETRIVE THE SEARCH CONDITION FROM SESSION##########
							//print "<pre>";
							//print_r($_SESSION['requestp']);								
							$FromDate=$_SESSION['requestp']['search']['srchDate']['FromDate'];
							$ToDate=$_SESSION['requestp']['search']['srchDate']['ToDate'];
							$srchCust=$_SESSION['requestp']['search']['srchCust'];
							$srchClient=$_SESSION['requestp']['search']['srchClient'];
							$srchTechnician=$_SESSION['requestp']['search']['srchTechnician'];
							$srchService=$_SESSION['requestp']['search']['srchService'];
							$srchStatus=$_SESSION['requestp']['search']['srchStatus'];								
							$srchWono=$_SESSION['requestp']['search']['srchWono'];
							$srchPurchaseNo=$_SESSION['requestp']['search']['srchPurchaseNo'];
							$columnGroup=$_SESSION['requestp']['search']['columnGroup'];
							$columnOrder=$_SESSION['requestp']['search']['columnOrder'];
							$orderType=$_SESSION['requestp']['search']['orderType'];
							##########RETRIVE THE SEARCH CONDITION FROM SESSION##########
							########insert string  for filter_search table###############
							$insert= "user_type='$_SESSION[usertype]',user_id='$_SESSION[userid]', page_name='dispatchboard'";
							########Search condition start here ###############
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
							if($srchTechnician !=''){
								$implode_srchTechnician =implode(",",$srchTechnician);
								$sch=$sch."FIND_IN_SET(t.id,'$implode_srchTechnician') AND ";
								$insert.=",techs='$implode_srchTechnician'";
							}else{
								$insert.=",techs='$implode_srchTechnician'";
							}
							if($srchService !=''){
								$implode_srchService =implode(",",$srchService);
								$sch=$sch."FIND_IN_SET(s.id,'$implode_srchService') AND ";
								$insert.=",services='$implode_srchService'";
							}else{
								$insert.=",services='$implode_srchService'";
							}
							if($srchStatus !=''){
								$implode_srchStatus =implode(",",$srchStatus);
								$sch=$sch."FIND_IN_SET(w.work_status,'$implode_srchStatus') AND ";
								$insert.=",status='$implode_srchStatus'";
							}else{
								$insert.=",status='$implode_srchStatus'";
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
								$sch=$sch."at.start_date >= '$fromdt' AND ";
							}
							if($FromDate =='' && $ToDate !=''){
								$sch=$sch."at.start_date <= '$todt' AND ";
							}
							if(($FromDate !='') && ($ToDate !='')){
								$sch=$sch."at.start_date BETWEEN '$fromdt' AND '$todt' AND ";
							}	
							$insert.=",from_date='$fromdt',to_date='$todt'";
							
							if($columnGroup !=''){
								$groupby =  "GROUP BY ".implode(",",$columnGroup);
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
						   if($_SESSION['requestp']['schaction'] =='filtersch'){
							   $count_filter_search =$dbf->countRows("filter_search","user_type='$_SESSION[usertype]' AND user_id='$_SESSION[userid]' AND page_name='dispatchboard'");
							   if($count_filter_search){
									$dbf->updateTable("filter_search",$insert.",updated_date=now()","user_type='$_SESSION[usertype]' AND user_id='$_SESSION[userid]'  AND page_name='dispatchboard'");
							   }else{
									$dbf->insertSet("filter_search",$insert.",created_date=now()");
							   }
						   }
						   ########insert or update  filter_search table###############
						   $sch=substr($sch,0,-5);
						   if($sch!=''){
							 $cond="w.approve_status='1' AND at.start_date <>'0000-00-00' AND ".$sch;
							 if($srchStatus==''){$cond.=" AND w.work_status IN('Dispatched','In Progress','Completed','Ready to Invoice','Invoiced','Assigned','Scheduled')";}
						   }elseif($sch==''){
							 $cond="(w.work_status='Dispatched' OR w.work_status='Assigned' OR w.work_status='Scheduled') AND w.approve_status='1' AND at.start_date=CURDATE()";
						   }
						   //condition for users
						   if($implode_clients <>''){
								$cond.=" AND FIND_IN_SET(w.created_by,'$implode_clients')";
						   }
						   if($implode_techs <>''){
								$cond.=" AND FIND_IN_SET(at.tech_id,'$implode_techs')";
						   }
						  //echo $cond;
						  ########Search condition end here ###############
						  //count total orders
						 $qry = "SELECT count(w.id) as numres FROM work_order w JOIN clients c ON w.client_id = c.id JOIN state st ON st.state_code=c.state JOIN service s ON w.service_id=s.id LEFT JOIN assign_tech at ON at.wo_no=w.wo_no JOIN technicians t ON at.tech_id=t.id WHERE ".$cond;
						  $resTotal = $dbf->simpleQuery($qry);
						  //print_r($resTotal);
						  $num=$resTotal[0]['numres'];
						  ?>
                        <div class="headerbg">
                        	<div style="float:left; width:30%;">Dispatch Order Board</div>
                            <div style="float:left;width:30%; text-align:center;">Total : <?php echo $num;?> Orders</div>
                        	<div style="float:right; width:40%; text-align:right;">
                            <input type="button" class="buttonText2" value="Edit Filter" onClick="edit_filter();"/> 
                            <input type="button" class="buttonText2" value="Create Order" onClick="add_job();"/>
                            <input type="button" class="buttonText2" value="Make Invoiced" onClick="make_invoiced();"/>
                            <input type="button" class="buttonText2" value="Input Invoice#" onClick="input_invoice_number();"/>
                            </div>
                        </div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                            <form name="SrchFrm" id="SrchFrm" action="" method="post">
                            <input type="hidden" name="schaction"  id="schaction" value=""/>
                            <input type="hidden" name="page" value="DispatchBoard"/>
                               <div align="center">
                                  <div  class="formtextaddsrch"align="center">From:</div>
                                  <div class="textboxcsrch">
                                      <input type="text" class="textboxsrch datepick" name="search[srchDate][FromDate]" id="FromDate" value="<?php echo $_SESSION['requestp']['search']['srchDate']['FromDate'];?>" readonly>
                                  </div>
                                  <div  class="formtextaddsrch"align="center">To:</div>
                                  <div class="textboxcsrch">
                                      <input type="text" class="textboxsrch datepick" name="search[srchDate][ToDate]" id="ToDate" value="<?php echo $_SESSION['requestp']['search']['srchDate']['ToDate'];?>" readonly>
                                  </div>
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
                                            <th width="6%">WO#</th>
                                            <th width="8%">PO#</th>
                                            <th width="8%">ServiceType</th>
                                            <th width="8%">OrderStatus</th>
                                            <th width="7%">CustomerName</th>
                                            <th width="6%">CustPhone</th>
                                            <th width="8%">DeliveryState</th>
                                            <th width="6%">DeliveryCity</th>
                                            <th width="6%">Client</th>
                                            <th width="6%">OrderTotal</th>
                                            <th width="6%">TechPay</th>
                                            <th width="7%">TechName</th>
                                            <th width="7%">StartDate & Time</th>
                                            <th width="11%" style="text-align:center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                     <tr style="background-color:#f9f9f9;">
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
                                        <td class="hiderow">&nbsp;</td>
                                    </tr>
                              		<?php 
										//group array
										$qry = "SELECT st.state_name, c.state, count(w.id) as numres FROM work_order w JOIN clients c ON w.client_id = c.id JOIN state st ON st.state_code=c.state JOIN service s ON w.service_id=s.id LEFT JOIN assign_tech at ON at.wo_no=w.wo_no JOIN technicians t ON at.tech_id=t.id WHERE ".$cond."GROUP BY c.state ORDER BY c.state ASC ";
										$resGrArray=$dbf->simpleQuery($qry);
										//group by state loop
										foreach($resGrArray as $k=>$sgRes){
										$Cls="g$k";	
										$numres = $sgRes['numres'];
									?>
									<tr style="background-color:#f9f9f9;" class="ho">
                                    	<td valign="top" class="grheading">
                                        <div class="divgr">
                                		<a href="javascript:void(0);" onClick="funShow('<?php echo $Cls;?>','<?php echo $k;?>');" id="e<?php echo $k;?>" <?php if($k==0){?>style="display:none;" <?php }?> class="hoa"><img  src="images/plus.gif" height="13" width="13"/>&nbsp;<?php echo $numres;?> Jobs in &nbsp;<span style="color:#ff9812;"><?php echo $sgRes['state_name'];?></span> assigned to Tech</a> 
                                		<a href="javascript:void(0);" onClick="funHide('<?php echo $Cls;?>','<?php echo $k;?>');" id="c<?php echo $k;?>" <?php if($k!=0){?>style="display:none;" <?php }?> class="hob"><img  src="images/minus.gif" height="13" width="13"/>&nbsp;<?php echo $numres;?> Jobs in &nbsp;<span style="color:#ff9812;"><?php echo $sgRes['state_name'];?></span> assigned to Tech</a>
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
                                        <td class="hiderow">&nbsp;</td>
                                    </tr>				
								    <?php
									$qry = "SELECT st.state_name, c.name, c.city, c.phone_no, w.id, w.wo_no, w.purchase_order_no, w.service_id,w.work_status,w.created_by, s.service_name, t.first_name, t.middle_name, t.last_name, at.start_date, at.start_time ,wb.payment_status,cl.name as clientname FROM work_order w JOIN clients c ON w.client_id = c.id JOIN state st ON st.state_code=c.state JOIN service s ON w.service_id=s.id LEFT JOIN assign_tech at ON at.wo_no=w.wo_no JOIN technicians t ON at.tech_id=t.id LEFT JOIN work_order_bill wb ON w.wo_no=wb.wo_no LEFT JOIN clients cl ON w.created_by=cl.id WHERE c.state='".$sgRes['state']."' AND " .$cond." ".$groupby." ORDER BY ".$orderby." ".$orderType;
									$resArray=$dbf->simpleQuery($qry);
									//print "<pre>";
									//print_r($resArray);
									foreach($resArray as $key=>$res_JobBoard) { 
										if($res_JobBoard['work_status']=='Completed' || $res_JobBoard['work_status']=='Invoiced'){
											//check for payment completed work orders
											if($res_JobBoard['payment_status']<>'Completed'){
												$color="#090";	
											}else{
												$color="#0FCBFF";
											}
										}else{
											//$rcolor='';
											$color='#F00';
										}
										
										if($res_JobBoard['created_by']<>'0'){
											$clientname=$res_JobBoard['clientname'];
										}else{
											$clientname="COD";
										}
										//calculating order billing total and tech pay
										$subtotal=0; $techsubtotal=0;
										$res_woservice = $dbf->fetchOrder("equipment e,work_type wt,workorder_service ws","e.id=ws.equipment AND wt.id=ws.work_type AND ws.service_id='".$res_JobBoard['service_id']."' AND ws.workorder_id='".$res_JobBoard['id']."'","","ws.quantity,ws.outbox_price,ws.tech_price","");
										//print_r($res_woservice);
										foreach($res_woservice as $resServicePrice){
											$total = ($resServicePrice['quantity']*$resServicePrice['outbox_price']);
											$subtotal = $subtotal+$total; 
											$price=$resServicePrice['tech_price'];
											//$techtotal = ($resServicePrice['quantity']*$price);
											$techsubtotal = $techsubtotal+$price;
										}
										//latest notes by tech
										 $LatestNotesSts=$dbf->getDataFromTable("workorder_notes","read_status","workorder_id='".$res_JobBoard['id']."' AND user_type='tech' ORDER BY id DESC LIMIT 1");
										 //echo $LatestNotesSts;
										 $techClass = ($LatestNotesSts=='1')?"coltext-unread":"coltext";
								 ?>
                                  <tr class="<?php echo $Cls;?> ro" <?php if($k!=0){?> style="display:none;"<?php }?>>
                                    <td data-title="WO#" class="coltext">
                                    <a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_JobBoard['id'];?>','view-job-board','<?php echo $k;?>');" title="Click Here For Job Details" style="color:<?php echo $color;?>" class="tooltip"><?php echo $res_JobBoard['wo_no'];?><span><?php include 'admin_notes.php';?></span></a></td>
                                    <td data-title="PO#"><?php echo $res_JobBoard['purchase_order_no'];?></td>
                                    <td data-title="ServiceType"><?php echo $res_JobBoard['service_name'];?></td>
                                    <td data-title="WorkStatus" style="font-weight:bold;" id="workstatus" class="coltext"><?php if($res_JobBoard['work_status']<>''){?><a href="javascript:void(0);" onClick="Show_Workstatus('<?php echo $res_JobBoard['wo_no'];?>')" title="Click Here To See WorkStatus"><?php echo $res_JobBoard['work_status'];?></a><?php } else{echo 'Not Started';}?></td>
                                    <td data-title="CustomerName"><?php echo $dbf->cut($res_JobBoard['name'],15);?></td>
                                    <td data-title="CustPhone"><?php echo $res_JobBoard['phone_no'];?></td>
                                    <td data-title="DeliveryState"><?php echo $res_JobBoard['state_name'];?></td>
                                    <td data-title="DeliveryCity"><?php echo $res_JobBoard['city'];?></td>
                                    <td data-title="Client"><?php echo $clientname;?></td>
                                    <td data-title="OrderBillingTotal">$ <?php echo number_format($subtotal,2);?></td>                                    <td data-title="TechPay">$ <?php echo number_format($techsubtotal,2);?></td>
                                    <td data-title="TechName" class="<?php echo $techClass;?>"><a href="javascript:void(0);" class="tooltip" style="position:relative;"><?php echo $res_JobBoard['first_name'].' '.$res_JobBoard['middle_name'].' '.$res_JobBoard['last_name'];?><span style="right:110%; bottom:0;"><?php include 'tech_notes.php';?></span></a></td>
                                    <td data-title="StartDate"><?php if($res_JobBoard['start_date']<>'0000-00-00'){echo date("d-M-Y",strtotime($res_JobBoard['start_date']));}else{echo 'None';}?> &nbsp;<?php if($res_JobBoard['start_time']){echo $res_JobBoard['start_time'];}else{echo 'None';}?></td>
                                    <td data-title="Action"><a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_JobBoard['id'];?>','edit-job-board','<?php echo $k;?>');"><img src="images/edit.png" title="Edit" alt="Edit"/></a>&nbsp;<a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_JobBoard['id'];?>','view-job-board','<?php echo $k; ?>');"><img src="images/view.png" title="View" alt="View"/></a>&nbsp;<a href="javascript:void();" onClick="cancel_order('<?php echo $res_JobBoard['id'];?>','manage-job-board-dispatch');"><img src="images/cancel_round.png" title="Cancel" alt="Cancel"/></a>&nbsp;<a href="javascript:void(0);"  onClick="print_doc('print','<?php echo $res_JobBoard['id'];?>');" ><img src="images/print.png" alt="Print"  title="Print Workorder"></a>&nbsp;<a href="javascript:void(0);" onClick="print_doc('pdf','<?php echo $res_JobBoard['id'];?>');"><img src="images/pdf.png" style="width:16px; height:16px;" title="Export to PDF"/></a>&nbsp;<a href="javascript:void(0);" onClick="send_email_tech('<?php echo $res_JobBoard['id'];?>','<?php echo $res_JobBoard['wo_no'];?>');"><img src="images/email_go.png" title="Email To Tech" alt="Email"></a></td>
                               </tr>
                               <?php }
								}
							   ?>
                        	  </tbody>
                            </table>
                              <!-----Table area end------->
                            <?php if($num == 0){?><div class="noRecords" align="center">No records founds!!</div><?php }?>
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