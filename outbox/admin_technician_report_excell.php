<?php
ob_start();
session_start();
include_once('includes/ExportToExcel.class.php');
//Object initialization
$exp=new ExportToExcel();
$exp->exportWithPage("admin_technician_report_excell_data.php","admin_technician_report.xls");
?>