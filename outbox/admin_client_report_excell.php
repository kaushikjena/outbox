<?php
ob_start();
session_start();
include_once('includes/ExportToExcel.class.php');
//Object initialization
$exp=new ExportToExcel();
$exp->exportWithPage("admin_client_report_excell_data.php","admin_client_detail.xls");
?>