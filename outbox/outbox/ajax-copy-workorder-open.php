<?php 
ob_clean();
session_start();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();
##########Insert Preparation Start ###########
if(isset($_REQUEST['action']) && $_REQUEST['action']=="insert" && $_SERVER['REQUEST_METHOD']=='POST'){
	//fetch data from work order table
	$fetchArray = $dbf->fetchSingle("work_order","id='$_REQUEST[id]'");
	//$WorkOrder = $fetchArray['wo_no']."-A"; 
	$wono = strrpos($fetchArray['wo_no'], '-')? substr($fetchArray['wo_no'],0,strrpos($fetchArray['wo_no'], '-')):$fetchArray['wo_no'];
	$countno =$dbf->countRows("work_order","wo_no LIKE '$wono%'");
	$WorkOrder = ($countno==1)? $wono ."-A":$wono."-".$countno."A";
	$cntwo=$dbf->countRows("work_order","wo_no='$WorkOrder'");
	if($cntwo==0){
		##########Insert Into workorder table###########
		$Address=mysql_real_escape_string($fetchArray['pickup_address']);
		$City=mysql_real_escape_string($fetchArray['pickup_city']);
		$notes=mysql_real_escape_string($fetchArray['notes']);
		$string="wo_no='$WorkOrder', purchase_order_no='$fetchArray[purchase_order_no]',service_id='$fetchArray[service_id]', client_id='$fetchArray[client_id]', pickup_location='$fetchArray[pickup_location]',pickup_address='$Address', pickup_city='$City', pickup_state='$fetchArray[pickup_state]', pickup_zip_code='$fetchArray[pickup_zip_code]', pickup_phone_no='$fetchArray[pickup_phone_no]',pickup_alt_phone='$fetchArray[pickup_alt_phone]',tracking_number='$fetchArray[tracking_number]',carrier_company='$fetchArray[carrier_company]',parts_arrive='$fetchArray[parts_arrive]', work_status='$fetchArray[work_status]', notes='$notes',created_date=now(), created_by='$fetchArray[created_by]'";
		$woid=$dbf->insertSet("work_order",$string);
		##########Insert Into workorder table###########
		
		##########Insert Into Workorder Service Table############################
		$cntwoservice=$dbf->countRows("workorder_service","workorder_id='$_REQUEST[id]'");
		foreach($dbf->fetchOrder("workorder_service","workorder_id='$_REQUEST[id]'")as $val){
			$string2="workorder_id='$woid',service_id='$val[service_id]',equipment='$val[equipment]',work_type='$val[work_type]', model='$val[model]',quantity='$val[quantity]',outbox_price='$val[outbox_price]',tech_price='$val[tech_price]',item_notes='$val[item_notes]', created_date=now()";
			$dbf->insertSet("workorder_service",$string2);	
		}
	}
	###########################################################################
	
	########################################################################
	if($woid){
		###########Track user activity in work order notes table#############
		$adminNotes="One duplicate copy of this order is created.";
		$strnotes="workorder_id='$_REQUEST[id]', user_type='$_SESSION[usertype]', user_id='$_SESSION[userid]', wo_notes='$adminNotes',created_date=now()";
		$dbf->insertSet("workorder_notes",$strnotes);
		###########Track user activity in work order notes table#############
	}
	echo "1";exit;
}
##########Insert Preparation End ###########
?>
