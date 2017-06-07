<?php 
ob_start();
session_start();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();
if($_REQUEST['choice']=="ready_to_pay"){
	if(isset($_REQUEST['ready_to_pay_val']) && !empty($_REQUEST['ready_to_pay_val'])){
		//update table
		$dbf->updateTable("work_order","ready_to_pay='1'","wo_no='$_REQUEST[wo_no]'");	
		//check whether ready_to_bill is set or not.
		$num_ready=$dbf->countRows("work_order","ready_to_bill='1' AND wo_no='$_REQUEST[wo_no]'");
		if($num_ready>0){print 1;exit;}else{print 2;exit;}
	}
}

/*if($_REQUEST['choice']=="ready_error"){
	print "<font style='font-size:14px;'>You need to check <b>Ready To Bill</b> to make the Order Ready to Invoice</font>";exit;
}*/

if($_REQUEST['choice']=="ready_to_bill"){
	if(isset($_REQUEST['ready_to_bill_val']) && !empty($_REQUEST['ready_to_bill_val'])){
		//update table
		$dbf->updateTable("work_order","ready_to_bill='1'","wo_no='$_REQUEST[wo_no]'");	
		//check whether ready_to_pay is set or not.
		$num_bill=$dbf->countRows("work_order","ready_to_pay='1' AND wo_no='$_REQUEST[wo_no]'");
		if($num_bill>0){print 1;exit;}else{print 2;exit;}
	}
}

/*if($_REQUEST['choice']=="bill_error"){
	print "<font style='font-size:14px;'>You need to check <b>Ready To Pay</b> to make the Order Ready to Invoice</font>";exit;
}*/

if($_REQUEST['choice']=="ready_all"){
	//checking both ready_to_bill  and ready_to_bill column are set.
	$num_ready_pay=$dbf->countRows("work_order","ready_to_pay='1' AND wo_no='$_REQUEST[wo_no]'");
	$num_ready_bill=$dbf->countRows("work_order","ready_to_bill='1' AND wo_no='$_REQUEST[wo_no]'");
	if($num_ready_pay>0 && $num_ready_bill>0){
		//update work_status
		$dbf->updateTable("work_order","work_status='Ready to Invoice'","wo_no='$_REQUEST[wo_no]'");
		print 3;exit;	
	}
}
/*
if($_REQUEST['choice']=="all"){
	print "<font style='font-size:14px;'>You have successfully make the Order <b>Ready To Invoice</b></font>";exit;
}*/