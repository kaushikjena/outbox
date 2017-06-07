<?php
ob_start();
session_start();
include_once('../includes/ExportToExcel.class.php');
//Object initialization
$exp=new ExportToExcel();
$exp->exportWithPage("client_report_excell_data.php","client_report.xls");
?>