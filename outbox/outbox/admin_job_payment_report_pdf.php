<?php
ob_start();
session_start();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();
include_once 'mpdf/mpdf.php'; 
if($_SERVER['HTTP_HOST'] == "box-ware.com"){
	$baseUrl="https://" . $_SERVER['HTTP_HOST']."/sys/outbox/";//Creating Base Url for SERVER
}elseif($_SERVER['HTTP_HOST'] == "bletprojects.com"){
	$baseUrl="http://" . $_SERVER['HTTP_HOST']."/outbox/";//Creating Base Url for SERVER
}else{
	$baseUrl="http://" . $_SERVER['HTTP_HOST'] ."/outbox/";//Creating Base Url for local
}
$creatorlist=implode(",",$_REQUEST['srchClient']);
$filename = "admin_job_payment_report.pdf";
//$filepath="pdf_reports/".$filename;
$htmlFile = $baseUrl."admin_job_payment_report_pdf_data.php?srchCust=$_REQUEST[srchCust]&srchClient=$creatorlist&srchTechnician=$_REQUEST[srchTechnician]&srchService=$_REQUEST[srchService]&FromDate=$_REQUEST[FromDate]&ToDate=$_REQUEST[ToDate]&hidaction=$_REQUEST[hidaction]";
ini_set('memory_limit', '-1');
$html=file_get_contents($htmlFile);
//$mpdf = new mPDF('c','A4','','' , 0 , 0 , 0 , 0 , 0 , 0);
$mpdf = new mPDF('utf-8', 'A4-L');
$mpdf->SetDisplayMode('fullpage');
$mpdf->list_indent_first_level = 0;
$mpdf->WriteHTML($html);
//$mpdf->Output();
$mpdf->Output($filename,'D');
?>	
