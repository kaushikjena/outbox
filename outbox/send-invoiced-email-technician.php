<?php 
ob_start();
session_start();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();
if(isset($_REQUEST['choice'])&& $_REQUEST['choice']=="send_email"){
	ob_clean();//print "<pre>";print_r($_REQUEST);exit;
	$resTech=$dbf->fetchSingle("state st,technicians t","t.state=st.state_code AND t.id='$_REQUEST[tid]'");
	$fromemail="admin@box-ware.com";
	$fromname="Out Of The Box";
	$subject="Weekly Invoiced Notification";
	//include email body
	include "invoiced-email-body.php";
	$to=$resTech['email'];
	$toName = $resTech['first_name'].'&nbsp;'.$resTech['middle_name'].'&nbsp;'.$resTech['last_name'];
	//Email Sending Starts here
	$headers = "MIME-Version: 1.0\n";
	$headers .= "Content-type: text/html; charset=UTF-8\n";
	$headers .= "From:".$fromname." <".$fromemail.">\n";
	//echo $body;exit;
	if(@mail($to,$subject,$body,$headers)){
		echo 1;exit;
	}else{
		echo 0;exit;
	}
}?>
    