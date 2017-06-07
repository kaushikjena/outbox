<?php
ob_start();
session_start();
include_once('includes/ExportToExcel.class.php');
//Object initialization
//print "<pre>";print_r($_REQUEST);exit;
$exp=new ExportToExcel();
$exp->exportWithPage("admin_technician_ws_report_excell_data.php","admin_technician_workstatus_detail.xls");
?>