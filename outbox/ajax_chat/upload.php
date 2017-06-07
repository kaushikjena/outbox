<?php
require_once(dirname(__FILE__)."/../libs/mysql.php");
//get site url.
$site_url = $dbf->getDataFromTable("admin","site_url","id=1");
if(!isset($_SESSION)){session_start();}
$cuser=$_SESSION['live_chat']['cuser'];

$to_user=$_POST['to_user'];
if ($_FILES["file"]["error"] > 0 ){
	echo "Error: " . $_FILES["file"]["error"] . "<br>";
}else{
	/*$temp = explode(".", $_FILES["file"]["name"]);
	$extension = end($temp);
	$file_name=time().".".$extension ;*/
	
	$fname=$cuser."_".$to_user."_".$_FILES["file"]["name"];
	move_uploaded_file($_FILES["file"]["tmp_name"],"../uploads/".$fname);
	$fp=fopen("./e.txt","a");
	fwrite($fp,"../uploads/".$_FILES["file"]["name"]);
	fclose($fp);
	$message='<a href="'.$site_url.'/uploads/'.$fname.'" target="_new">New File: '.$_FILES["file"]["name"].'</a>';
	postMessage($cuser,$to_user,$message);
}

?>