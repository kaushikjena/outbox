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
$res_editJobBoard=$dbf->fetchSingle("clients c,service s,work_order w","c.id=w.client_id AND w.service_id=s.id AND w.id='$_REQUEST[id]'");
if($res_editJobBoard ==''){
	header("location:manage-job-board");exit;
}
$resTech = $dbf->fetchSingle("assign_tech at,technicians tc","at.tech_id=tc.id AND at.wo_no='$res_editJobBoard[wo_no]' AND tc.status=1");
//Fetch details from work_order table 
$res_viewJobBoard=$dbf->fetchSingle("clients c,service s,work_order w","c.id=w.client_id AND w.service_id=s.id  AND w.id='$_REQUEST[id]'");
//fetch from work order doc table
$workorderdoc = $dbf->getDataFromTable("workorder_doc","wo_document","workorder_id='$_REQUEST[id]'");
##########Update Preparation Start ###########
if(isset($_REQUEST['action']) && $_REQUEST['action']=="update" && $_SERVER['REQUEST_METHOD']=='POST'){
	/*print'<pre>';
	print_r($_REQUEST);exit;*/
	$PurchaseOrder=mysql_real_escape_string(trim($_POST['PurchaseOrder']));
	$Pickuplocation=mysql_real_escape_string(trim($_POST['Pickuplocation']));
	$CustomerEmailID=mysql_real_escape_string(trim($_REQUEST['CustomerEmailID']));
	$CustomerName=mysql_real_escape_string(trim($_REQUEST['CustomerName']));
	
	if($_REQUEST['cmbService'] ==''){
		header("Location:edit-job-board?id=$_REQUEST[worid]");exit;
	}
	if($CustomerEmailID<>''){
    	$numuser=$dbf->countRows("clients","email='$CustomerEmailID'");
	}else{
		$numuser=$dbf->countRows("clients","name='$CustomerName'");
	}
	if($numuser>0){
		if($CustomerEmailID<>''){
			$clientid = $dbf->getDataFromTable("clients","id","email='$CustomerEmailID'");
		}else{
			$clientid = $dbf->getDataFromTable("clients","id","name='$CustomerName'");
		}
		//update clients table
		$CustomercontactName=mysql_real_escape_string(trim($_REQUEST['CustomercontactName']));
		$CustAddress=mysql_real_escape_string(trim($_REQUEST['CustAddress']));
		$CustCity=mysql_real_escape_string(trim($_REQUEST['CustCity']));
		$CustState=mysql_real_escape_string(trim($_REQUEST['CustState']));
		//get latitude and longitude
		$val = $dbf->getLnt($CustAddress,$CustCity,$CustState.' '.$_REQUEST['CustZip']);
		//string for update table
		$string="name='$CustomerName', user_type='client', email='$CustomerEmailID', contact_name='$CustomercontactName', address='$CustAddress', city='$CustCity', state='$CustState',fax_no='$_REQUEST[Faxno]',zip_code='$_REQUEST[CustZip]', phone_no='$_REQUEST[CustPhone]', alt_phone='$_REQUEST[altCustPhone]',latitude='".$val['lat']."',longitude='".$val['lng']."', updated_date=now(),updated_by='$_SESSION[userid]'";
		$dbf->updateTable("clients",$string,"id='$clientid'");
	}else{
		//insert into clients table
		$CustomercontactName=mysql_real_escape_string(trim($_REQUEST['CustomercontactName']));
		$CustAddress=mysql_real_escape_string(trim($_REQUEST['CustAddress']));
		$CustCity=mysql_real_escape_string(trim($_REQUEST['CustCity']));
		$CustState=mysql_real_escape_string(trim($_REQUEST['CustState']));
		//get latitude and longitude
		$val = $dbf->getLnt($CustAddress,$CustCity,$CustState.' '.$_REQUEST['CustZip']);
		
		if($_REQUEST['clientid']){
			$string="name='$CustomerName', user_type='client', email='$CustomerEmailID', contact_name='$CustomercontactName', address='$CustAddress', city='$CustCity', state='$CustState',fax_no='$_REQUEST[Faxno]',zip_code='$_REQUEST[CustZip]', phone_no='$_REQUEST[CustPhone]', alt_phone='$_REQUEST[altCustPhone]',latitude='".$val['lat']."',longitude='".$val['lng']."', status=0, updated_date=now(),updated_by='$_SESSION[userid]'";
			$dbf->updateTable("clients",$string,"id='$_REQUEST[clientid]'");
			$clientid=$_REQUEST['clientid'];
		}else{
			$string="name='$CustomerName', user_type='client', email='$CustomerEmailID', contact_name='$CustomercontactName', address='$CustAddress', city='$CustCity', state='$CustState',fax_no='$_REQUEST[Faxno]',zip_code='$_REQUEST[CustZip]', phone_no='$_REQUEST[CustPhone]', alt_phone='$_REQUEST[altCustPhone]',latitude='".$val['lat']."',longitude='".$val['lng']."', status=0, created_date=now(),created_by='$_SESSION[userid]'";
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
	$string1="wo_no='$_REQUEST[WorkOrder]', purchase_order_no='$PurchaseOrder',   service_id='$_REQUEST[cmbService]',client_id='$clientid', pickup_location='$Pickuplocation', pickup_address='$Address', pickup_city='$City', pickup_state='$State', pickup_zip_code='$_REQUEST[Zip]', pickup_phone_no='$_REQUEST[Phone]', pickup_alt_phone='$_REQUEST[altPhone]', work_status='$_REQUEST[cmbStatus]', notes='$notes', created_by='$_REQUEST[cmbClient]', updated_date=now(), updated_by='$_SESSION[userid]'";
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
		
		if($_REQUEST['techgrade']=='A'){
			$gradeA_price = $TechPrice;
		}elseif($_REQUEST['techgrade']=='B'){
			$gradeB_price = $TechPrice;
		}elseif($_REQUEST['techgrade']=='C'){
			$gradeC_price = $TechPrice;
		}elseif($_REQUEST['techgrade']=='D'){
			$gradeD_price = $TechPrice;
		}
		//check duplicate entry
		$num=$dbf->countRows("workorder_service","id='$hid'");
		if($num==0){
			if($equip<>''){
				//insert into workorder service table
				$string2="workorder_id=$woid, service_id='$_REQUEST[cmbService]', equipment='$equip',work_type='$Worktype', model='$model', quantity='$Quantity', outbox_price='$Price', gradeA_price='$gradeA_price', gradeB_price='$gradeB_price', gradeC_price='$gradeC_price', gradeD_price='$gradeD_price', created_date=now()";
			   $dbf->insertSet("workorder_service",$string2);
			 }
		}else{
			if($equip<>''){
				//update into workorder service table
				$string2="workorder_id=$woid, service_id='$_REQUEST[cmbService]', equipment='$equip',work_type='$Worktype', model='$model', quantity='$Quantity', outbox_price='$Price', gradeA_price='$gradeA_price', gradeB_price='$gradeB_price', gradeC_price='$gradeC_price', gradeD_price='$gradeD_price', created_date=now()";
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
	##########Redirection of page ##################
	if($_REQUEST['src']=='disp'){
		header("Location:edit-job-board?id=$_REQUEST[worid]&msg=001&src=disp");exit;
	}elseif($_REQUEST['src']=='assigned'){
		header("Location:edit-job-board?id=$_REQUEST[worid]&msg=001&src=assigned");exit;
	}else{
		header("Location:edit-job-board?id=$_REQUEST[worid]&msg=001");exit;
	}
	##########Redirection of page ##################
}
##########Update Preparation End ###########
?>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />
<script type="text/javascript">
$(document).ready(function() {
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
	var url="ajax_equipment.php";
	var worid = $("#worid").val();
	$.post(url,{"serviceid":servid,"id":worid},function(res){			
		$("#equipid").html(res);			
	});
}
function ShowPrice(id){
	var techgrade = $("#techgrade").val();
	var serviceid = $("#cmbService").val();
	var equipid = $("#Equipment"+id).val();
	var worktypeid = $("#Worktype"+id).val();
	var Quantity = $("#Quantity"+id).val();
	var url="ajax_service_price.php";
	$.post(url,{"serviceid":serviceid,"equipid":equipid,"worktypeid":worktypeid,"techgrade":techgrade},function(res){	
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
			window.location.href="edit-job-board?id="+work_id+"&src=disp"; 
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
		$.post("ajax-copy-workorder",{"action":"insert","id":wo_id},function(res){			
			//alert(res);	
			if(res){
				if(src =='disp'){
					window.location.href='manage-job-board-dispatch';
				}else if(src =='assigned'){
					window.location.href='manage-job-board-assigned';
				}else{
					window.location.href='manage-job-board';
				}
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
	/*if(document.AssignTech.StartDate.value==''){
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
	}*/
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
			window.location.href="edit-job-board?id="+work_id+"&src=disp"; 
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

/* add admin customer contact button*/
function contact_customer(){
	var url="ajax_addcontact.php";
	var worid = $("#worid").val();
	var attempt = $("#attempt").val();
	$.post(url,{"id":worid,"attempt":attempt},function(res){//alert(res);	
		$("#rescontacts").html(res);			
	});
}
//waiting for parts
function waiting_for_parts(){
	$.fancybox.showActivity();
	var url="waiting_for_parts.php";
	var worid = $("#worid").val();
	$.post(url,{"choice":"view","id":worid},function(res){
		$.fancybox(res,{centerOnScroll:true,hideOnOverlayClick:false});
	});
}
//waiting for parts
function waiting_for_parts_insert(){
	var cond=validate_WaitingParts();
	if(cond){
		$.fancybox.showActivity();
		var url="waiting_for_parts.php";
		var worid = $("#worid").val();
		var workorder_notes_id = $("#workorder_notes_id").val();
		if(workorder_notes_id!=''){
			workorder_notes_id=workorder_notes_id;
		}else{
			workorder_notes_id=''
		}
		var waiting_parts_comments = $("#waiting_parts_comments").val();
		if($("#chk_box").attr('checked')){var chk_box = 1;}else{var chk_box = 2;}
		$.post(url,{"choice":"insert","id":worid,"workorder_notes_id":workorder_notes_id,"waiting_parts_comments":waiting_parts_comments,"chk_box":chk_box},function(res){
			$("#reswfp").html(res);
			$.fancybox.close();
		});
	}else{
		return false;	
	}
}
/**********Send Notification to assigned Tech*********/
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

//ready to pay
function ready_to_pay_fun(wo_no){ //alert(wo_no)
	var ready_to_pay_val=$('#ready_to_pay').val();
	var url="ready_to_pay_ajax.php";
	$.post(url,{"choice":"ready_to_pay","wo_no":wo_no,"ready_to_pay_val":ready_to_pay_val},function(res){
		if(res==2){
			$.fancybox.showActivity();
			$('#ready_to_pay').attr('checked');
			$('#ready_to_pay').attr('disabled','disabled');
			//to add notes at admin notes
			var urlajax="ready_to_addadminnotes_ajax.php";
			$.post(urlajax,{"choice":"pay_admin_notes","wo_no":wo_no},function(res){	//alert(res);		
				$("#resnotes").html(res);		
			});
			//
			$.fancybox("<font style='font-size:14px;'>You need to check <b>Ready To Bill</b> to make the Order Ready to Invoice</font>",{centerOnScroll:true,HideOnOverlayClick:true});
		}else if(res==1){
			$.fancybox.showActivity();
			$.post(url,{"choice":"ready_all","wo_no":wo_no},function(res){
				if(res==3){
					$.fancybox.showActivity();
					$('#ready_to_pay').attr('checked');
			        $('#ready_to_pay').attr('disabled','disabled');
					$('#cmbStatus option[value="Ready to Invoice"]').prop('selected', true);
					//to add notes at admin notes
					var urlajax="ready_to_addadminnotes_ajax.php";
					$.post(urlajax,{"choice":"pay_admin_notes","wo_no":wo_no},function(res){//	alert(res);			
						$("#resnotes").html(res);		
					});
					$.fancybox("<font style='font-size:14px;'>You have successfully make the Order <b>Ready To Invoice</b></font>",{centerOnScroll:true,HideOnOverlayClick:true});
				}
			});
		}
	});
}
//ready to bill.
function ready_to_bill_fun(wo_no){ 
	var ready_to_bill_val=$('#ready_to_bill').val();
	var url="ready_to_pay_ajax.php";
	$.post(url,{"choice":"ready_to_bill","wo_no":wo_no,"ready_to_bill_val":ready_to_bill_val},function(res){//alert(res);
		if(res==2){
			$.fancybox.showActivity();
			$('#ready_to_bill').attr('checked');
			$('#ready_to_bill').attr('disabled','disabled');
			//to add notes at admin notes
			var urlajax="ready_to_addadminnotes_ajax.php";
			$.post(urlajax,{"choice":"bill_admin_notes","wo_no":wo_no},function(res){			
				$("#resnotes").html(res);		
			});
			//show error message
			$.fancybox("<font style='font-size:14px;'>You need to check <b>Ready To Pay</b> to make the Order Ready to Invoice</font>",{centerOnScroll:true,HideOnOverlayClick:true});
			
		}else if(res==1){
			$.fancybox.showActivity();
			$.post(url,{"choice":"ready_all","wo_no":wo_no},function(res){
				if(res==3){
					$.fancybox.showActivity();
					$('#ready_to_bill').attr('checked');
			        $('#ready_to_bill').attr('disabled','disabled');
					$('#cmbStatus option[value="Ready to Invoice"]').prop('selected', true);
					//to add notes at admin notes
					var urlajax="ready_to_addadminnotes_ajax.php";
					$.post(urlajax,{"choice":"bill_admin_notes","wo_no":wo_no},function(res){	//alert(res);		
						$("#resnotes").html(res);		
					});
					//showing confirm message
					$.fancybox("<font style='font-size:14px;'>You have successfully make the Order <b>Ready To Invoice</b></font>",{centerOnScroll:true,HideOnOverlayClick:true});
				}
			});
		}
	});
}
</script>
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
                        	<div style="float:left;">EDIT JOB BOARD</div>
                            <div style="float:right;">
								<?php //if($res_editJobBoard['work_status']!='Invoiced'){	?>
                                <input type="submit" name="submitbtn" id="submitbtn" class="buttonText2" value="Submit Form" tabindex="39"/> <?php //} ?>
								<?php if($_REQUEST['src']=='disp'){$path="manage-job-board-dispatch";}elseif($_REQUEST['src']=='assigned'){$path="manage-job-board-assigned";}else{$path="manage-job-board";} ?>
                                <input type="button" class="buttonText2" value="Back" tabindex="40" onClick="document.location.href='<?php echo $path;?>'"/>
                                <input type="button" class="buttonText2" value="Print" tabindex="41" onClick="print_doc('print','<?php echo $res_editJobBoard['id'];?>');"/>
                                <input type="button" class="buttonText2" value="PDF" tabindex="41" onClick="print_doc('pdf','<?php echo $res_editJobBoard['id'];?>');"/>
                                <input type="button" class="buttonText2" value="Notification" tabindex="41" onClick="send_email_tech('<?php echo $res_editJobBoard['id'];?>','<?php echo $res_editJobBoard['wo_no'];?>');"/>
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
                            <div align="center"><?php if($_REQUEST['msg']=='002'){?><span class="redText">This Email ID already exist!</span><?php }?></div>
                            <!-----address div start--------->
                            <div  class="divAddress">
                            <div class="greenText" align="left">Work Order Details</div>
                            <div  class="formtextaddjob">Client:</div>
                            <div  class="textboxcjob">
                            <select name="cmbClient" id="cmbClient" class="selectboxjob" tabindex="1">
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
                                <option value="Assigned" <?php if($res_editJobBoard['work_status']=='Assigned'){echo 'selected';}?>> Assigned </option>
                                 <option value="Cancelled" <?php if($res_editJobBoard['work_status']=='Cancelled'){echo 'selected';}?>> Cancelled </option>
                                <option value="Dispatched" <?php if($res_editJobBoard['work_status']=='Dispatched'){echo 'selected';}?>>Dispatched</option>
                                <option value="In Progress" <?php if($res_editJobBoard['work_status']=='In Progress'){echo 'selected';}?>>In Progress</option>
                                <option value="Completed" <?php if($res_editJobBoard['work_status']=='Completed'){echo 'selected';}?>>Completed</option>
                                <option value="Ready to Invoice" <?php if($res_editJobBoard['work_status']=='Ready to Invoice'){echo 'selected';}?>>Ready to Invoice</option>
                                <option value="Invoiced" <?php if($res_editJobBoard['work_status']=='Invoiced'){echo 'selected';}?>>Invoiced</option>
                            </select><br/><label for="cmbStatus" id="lblcmbStatus" class="redText"></label>
                            </div>
                            <div class="spacer"></div>
                            <div  class="formtextaddjob">WO#:</div>
                            <div  class="textboxcjob">
                             <input type="text" class="textboxjob" name="WorkOrder" id="WorkOrder" value="<?php echo $res_editJobBoard['wo_no'];?>"  tabindex="1" readonly><br/><label for="WorkOrder" id="lblWorkOrder" class="redText"></label>
                            </div>
                            <div  class="formtextaddjoblong">Purchase Order:</div>
                            <div  class="textboxcjob">
                             <input type="text" class="textboxjob" name="PurchaseOrder" id="PurchaseOrder" value="<?php echo $res_editJobBoard['purchase_order_no'];?>" tabindex="2"><br/><label for="PurchaseOrder" id="lblPurchaseOrder" class="redText"></label>
                            </div>
                            <div class="spacer"></div>
                            <div  class="formtextaddjob">Service Name:<span class="redText">*</span></div>
                            <div  class="textboxcjob">
                            <select name="cmbService" id="cmbService" class="selectboxjob" tabindex="4" onChange="showEquipment(this.value);">
                                <option value="">--Select Service Name--</option>
                                <?php foreach($dbf->fetch("service","id")as $service){?>
                                <option value="<?php echo $service['id'];?>" <?php if($service['id']==$res_editJobBoard['service_id']){echo 'selected';}?>><?php echo $service['service_name'];?></option>
                                <?php }?>
                            </select><br/><label for="cmbService" id="lblcmbService" class="redText"></label>
                            </div>
                            <div  class="formtextaddjoblong">Technician:</div>
                            <div  class="textboxcjob"><span class="formtext">
                            <?php if($resTech<>''){
								if($res_editJobBoard['work_status']=='Assigned'){?><a href="javascript:void(0);" onClick="ShowTechnicians('<?php echo $_REQUEST['id'];?>');" tabindex="5" title="Click Here To Assign Tech"><?php echo $resTech['first_name'].'&nbsp;'.$resTech['middle_name'].'&nbsp;'.$resTech['last_name'];?> </a><?php }else{?><a href="javascript:void(0);"  tabindex="5" title="Click Here To Assign Tech"><?php echo $resTech['first_name'].'&nbsp;'.$resTech['middle_name'].'&nbsp;'.$resTech['last_name'];?> </a> <?php } }else{?>
                            <a href="javascript:void(0);" onClick="ShowTechnicians('<?php echo $_REQUEST['id'];?>');" tabindex="5" title="Click Here To Assign Tech">Not Assigned</a><?php }?></span></div>
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
                            <?php if($resTech<>''){
								if($res_editJobBoard['work_status']=='Assigned'){?><a href="javascript:void(0);" onClick="Set_Workstatus('<?php echo $res_editJobBoard['id'];?>')" tabindex="6" title="View Time Window">Tech Time Window</a><?php }else{?><a href="javascript:void(0);"tabindex="6" title="View Time Window">Tech Time Window</a><?php } }else{?> <a href="javascript:void(0);" tabindex="6" title="View Time Window">Tech Time Window</a> <?php }?></span> </div>
                           <div class="spacer" style="height:12px;"></div>
                         </div>
                         	<!-----address div end--------->
                            <!-----purchase div start--------->
                           	<div  class="divPurchase">
                               <div class="greenText" align="left">Customer Information</div>
                               <div id="rescust">
                                <div  class="formtextaddjob"> Name:<span class="redText">*</span></div>
                                <div  class="textboxcjob">
                                	<input type="text" class="textboxjob" name="CustomerName" id="CustomerName" value="<?php echo $res_editJobBoard['name'];?>" tabindex="14"><br/><label for="CustomerName" id="lblCustomerName" class="redText"></label>
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
                                  <input type="text" class="textboxjob" name="CustomercontactName" id="ContactName" value="<?php echo $res_editJobBoard['contact_name'];?>" tabindex="17"><br/><label for="CustomercontactName" id="lblCustomercontactName" class="redText"></label>
                                </div>
                                 <div class="spacer"></div>
                                 <div  class="formtextaddjob">City:<span class="redText">*</span></div>
                                <div  class="textboxcjob">
                                    <input type="text" class="textboxjob" name="CustCity" id="CustCity" value="<?php echo $res_editJobBoard['city'];?>" tabindex="18"><br/><label for="CustCity" id="lblCustCity" class="redText"></label>
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
                                    <input type="text" class="textboxjob" name="CustZip" id="CustZip" value="<?php echo $res_editJobBoard['zip_code'];?>" tabindex="20"><br/><label for="CustZip" id="lblCustZip" class="redText"></label>
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
                                    <input type="text" class="textboxjob" name="City" id="City" value="<?php echo $res_editJobBoard['pickup_city'];?>" tabindex="7"><br/><label for="City" id="lblCity" class="redText"></label>
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
                                    <input type="text" class="textboxjob" name="Zip" id="Zip" value="<?php echo $res_editJobBoard['pickup_zip_code'];?>" tabindex="10"><br/><label for="Zip" id="lblZip" class="redText"></label>
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
                               <div class="spacer"></div>
                            </div>
                            <!-----pickup div end--------->
                            <!-----note div start--------->
                            <div  class="divNotes">
                            <div class="spacer"></div>
                             <div class="greenText" align="left">Job Description:<span class="redText">*</span></div>
                             <div><textarea name="notes" id="notes"  class="textareaOrder" tabindex="22"><?php echo $res_editJobBoard['notes'];?></textarea><br/><label for="notes" id="lblnotes" class="redText"></label></div>
                             <div class="spacer" style="height:10px;"></div>
                             <div  class="formtextaddjob">Upload Doc:</div>
                             <div  class="textboxcjob" style="width:300px;"><span class="formtext"><a href="javascript:void(0);" onClick="UploadDocument();">Click here to Upload and View Work Order Documents</a></span></div>
                             <div class="spacer" style="height:10px;"></div>
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
                             <?php
							 $subtotal =0;  $subTechPrice =0;
							 $res_woservice = $dbf->fetch("workorder_service","workorder_id='$_REQUEST[id]'");
							 $arrWorkservice =array();$i=1;
							 $cntwotype =$dbf->countRows("work_type","");
							  for($j=0; $j<$cntwotype;$j++){
								  $arrWorkservice= $res_woservice[$j];
								  $total = ($arrWorkservice['quantity']*$arrWorkservice['outbox_price']);
								  if($resTech['pay_grade']=='A'){
									$TechPrice=$arrWorkservice['gradeA_price'];
								  }elseif($resTech['pay_grade']=='B'){
									$TechPrice=$arrWorkservice['gradeB_price'];
								  }elseif($resTech['pay_grade']=='C'){
									$TechPrice=$arrWorkservice['gradeC_price'];
								  }elseif($resTech['pay_grade']=='D'){
									$TechPrice=$arrWorkservice['gradeD_price'];
								  }
								  $subtotal = $subtotal+$total;
								  $subTechPrice = $subTechPrice+$TechPrice;
							  ?>
                              <div align="center" class="jobbody clService">
                              <input type="hidden" name="hid<?php echo $i;?>" value="<?php echo $arrWorkservice['id'];?>">
                              <select class="selectboxjob" name="Worktype<?php echo $i;?>" id="Worktype<?php echo $i;?>" tabindex="23" onChange="ShowPrice('<?php echo $i;?>');">
                              	<option value="">--Select Worktype--</option>
                                <?php foreach($dbf->fetch("work_type","id>0 ORDER BY id ASC") as $vawt){?>
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
                            <!-----Ready to Pay div start--------->
                           	<div class="divPickup" style="width:100%;margin-bottom:5px;">
                            <div class="greenText" align="left">Admin Ready Invoice:</div>
                            <?php $ready_data=$dbf->strRecordID("work_order","ready_to_pay,ready_to_bill","wo_no='$res_editJobBoard[wo_no]'"); ?>
                            <div align="" style="margin:5px; vertical-align:middle; font-size:14px; font-weight:bold;"><input type="checkbox" name="ready_to_pay" id="ready_to_pay" <?php if($ready_data['ready_to_pay']==1){echo 'checked'.'  '.'disabled=disabled';}?> onClick="ready_to_pay_fun('<?php echo $res_editJobBoard[wo_no];?>');" /> <label for="ready_to_pay">Ready to Pay</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="ready_to_bill" id="ready_to_bill" <?php if($ready_data['ready_to_bill']==1){echo 'checked'.'  '.'disabled';}?>  onClick="ready_to_bill_fun('<?php echo $res_editJobBoard[wo_no];?>');" /> <label for="ready_to_bill">Ready to Bill</label></div>
                            <div class="spacer" style="height:10px;"></div>
                            </div>
                            <!----Ready to Pay div end--------->
                            <!-----admin notes div start--------->
                            <div  class="divPickup" style="width:100%;">
                            <?php //fetch notes from work order notes table
								$resNotes=$dbf->fetchOrder("workorder_notes","workorder_id='$_REQUEST[id]' AND (user_type='admin' OR user_type='user' OR user_type='client') AND customer_attempt=0 AND waiting_parts NOT IN(1,2)","created_date DESC");   //print "<pre>";print_r($resNotes);exit;
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
                                 <div align="right">By <?php echo $uname;?> on <?php echo date("d-M-Y g:i A",strtotime($resn['created_date']));?> for #<?php echo $res_editJobBoard['wo_no'];?></div>
                             </div> <div class="spacer"></div>
                             <?php }?>
                             </div>
                             <div class="spacer" style="height:10px;"></div>
                            </div>
                            <!-----admin note div end--------->
                            <!-----Customer attempted Button div start--------->
                           	<div class="divPickup" style="width:100%;margin-top:5px;">
                            <div class="greenText" align="left">Admin Customer Contact:</div>
                            <div align="" style="margin:5px; vertical-align:middle; font-size:14px; font-weight:bold;">(Click if the Customer is not reachable)&nbsp;&nbsp;<img src="images/contact_number.png" alt="Contact Customer" title="Contact Customer" style="cursor:pointer;" onClick="contact_customer();"/>&nbsp;&nbsp;&nbsp;&nbsp;(Click here for Waiting For Parts)<img src="images/tools_preferences.png" alt="WFP" title="Waiting For Parts" style="width:35px;height:30px;cursor:pointer;" onClick="waiting_for_parts();"/></div>
                            <?php
                             ##############fetch waiting for parts notes#####################
							$workorder_notes=$dbf->fetchSingle("workorder_notes","workorder_id='$_REQUEST[id]' AND (user_type='admin' OR user_type='tech') AND waiting_parts!=0");
							if($workorder_notes['user_type']=='admin'){
								 $uname = $dbf->getDataFromTable("admin","name","id='$workorder_notes[user_id]'");
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
                                 <div align="right">WFP Note: By <?php echo $uname;?> on <?php echo date("d-M-Y g:i A",strtotime($workorder_notes['created_date']));?> for #<?php echo $res_editJobBoard['wo_no'];?></div>
                             </div><div class="spacer"></div>
                            <?php }?>
                            </div>
                            <?php
                             #######fetch customer contact notes from work order notes table###########
                            $resNotes2=$dbf->fetchOrder("workorder_notes","workorder_id='$_REQUEST[id]' AND user_type='admin' AND customer_attempt <>0","created_date DESC");
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
                            <?php } }?>
                            </div>
                            <div class="spacer" style="height:10px;"></div>
                            </div>
                            <!----Customer attempted Button div end--------->
                            </div>
                            <div style="float:right;width:49%">
                            <!-----tech note div start--------->
                           	<div  class="divNotes" style="width:100%;">
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
                                 <div align="right">By <?php echo $uname;?> on <?php echo date("d-M-Y g:i A",strtotime($resn['created_date']));?> for #<?php echo $res_editJobBoard['wo_no'];?></div>
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
                            <div class="greenText" align="left">Work Status Comments:</div>
                            <?php  
                            //fetch work status comments from work_order_tech table
							// $workid="BOX".(1000+$_REQUEST['id']);
                            $resWork2=$dbf->fetchOrder("work_order_tech","wo_no='$res_editJobBoard[wo_no]'","created_date DESC");
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
                            <span class="redText">Work status comments are not available.</span>
                            <div class="spacer"></div>
                            <?php }?>
                            </div>
                            <!-----end of work status notes--->
                            </div>
                            <div class="spacer"></div>
                            <div align="center">
                                <?php //if($res_editJobBoard['work_status']!='Invoiced'){	?>
                                <input type="submit" name="submitbtn" id="submitbtn" class="buttonText" value="Submit Form" tabindex="39"/>                 <?php //} ?>
                                <input type="button" class="buttonText3" value="Back" tabindex="40" onClick="document.location.href='<?php echo $path;?>'"/>
                                <input type="hidden" name="src" id="src" value="<?php echo $_REQUEST['src'];?>"/>
                                <input type="button" class="buttonText3" value="Print" tabindex="40" onClick="print_doc('print','<?php echo $res_editJobBoard['id'];?>');"/>
                                 <input type="button" class="buttonText3" value="PDF" tabindex="40" onClick="print_doc('pdf','<?php echo $res_editJobBoard['id'];?>');"/>
                                 <input type="button" class="buttonText3" value="Notification" tabindex="40" onClick="send_email_tech('<?php echo $res_editJobBoard['id'];?>','<?php echo $res_editJobBoard['wo_no'];?>');"/>
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