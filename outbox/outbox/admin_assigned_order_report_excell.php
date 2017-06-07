<?php
ob_start();
session_start();
include_once('includes/ExportToExcel.class.php');
//Object initialization
$exp=new ExportToExcel();
$exp->exportWithPage("admin_assigned_order_report_excell_data.php","admin_assigned_report_details.xls");
?>