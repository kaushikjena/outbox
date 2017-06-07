<?php
ob_start();
session_start();
ini_set('memory_limit','-1'); // set memory limit upto 2 GB 
ini_set('max_execution_time','3600'); // set memory limit upto 1 hour
ini_set('max_input_time', '3600');
ini_set("post_max_size", "256M");
ini_set("upload_max_filesize", "256M");

include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();

$error = "";
$fileElementName = $_POST['file_element'];   
$folder = $_POST['folder'];
	
if(!empty($_FILES[$fileElementName]['error'])){
	
	switch($_FILES[$fileElementName]['error']){
		case '1':
			$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
			break;
		case '2':
			$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
			break;
		case '3':
			$error = 'The uploaded file was only partially uploaded';
			break;
		case '4':
			$error = 'No file was uploaded.';
			break;

		case '6':
			$error = 'Missing a temporary folder';
			break;
		case '7':
			$error = 'Failed to write file to disk';
			break;
		case '8':
			$error = 'File upload stopped by extension';
			break;
		case '999':
		default:
			$error = 'No error code avaiable';
	}
}elseif(empty($_FILES[$fileElementName]['tmp_name']) || $_FILES[$fileElementName]['tmp_name'] == 'none'){
	$error = 'No file was uploaded..';
}else{
	$woid =	$_REQUEST['woid'];
	$fnam = $_FILES[$fileElementName]['name'];
	$path = $folder;
	$size = @filesize($_FILES[$fileElementName]['tmp_name']);
	$file_name = $_REQUEST['wono']."_".date('dhihis')."_".$fnam;
	
	//for security reason, we force to remove uploaded file
	move_uploaded_file($_FILES[$fileElementName]['tmp_name'], $folder.$file_name);
	//@unlink($_FILES[$fileElementName]['tmp_name']);

	//insert into workorder doc table
	$string="workorder_id='$woid', wo_document='$file_name',created_date=now(),created_user='$_SESSION[userid]', user_type='$_SESSION[usertype]',unread=0";
	$insertid = $dbf->insertSet("workorder_doc",$string);
	###########Track user activity in work order notes table#############
	if($insertid){
		$adminNotes="A new document is uploaded to this order.";
		$strnotes="workorder_id='$woid', user_type='$_SESSION[usertype]', user_id='$_SESSION[userid]', wo_notes='$adminNotes',created_date=now()";
		$dbf->insertSet("workorder_notes",$strnotes);
	}
	###########Track user activity in work order notes table#############
}

$res = new stdClass();
$res->error = $error;
$res->filename = $fnam;
$res->path = $path;
$res->size = sprintf("%.2fMB", $size/1048576);
$res->dt = date('Y-m-d H:i:s');
$res->user_data = $_POST['user_data'];
echo json_encode($res);	
	
?>