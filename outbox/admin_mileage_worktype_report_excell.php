<?php
ob_start();
session_start();
include_once('includes/ExportToExcel.class.php');
//Object initialization
$exp=new ExportToExcel();
$exp->exportWithPage("admin_mileage_worktype_excell_data.php","admin_mileage_worktype_report.xls");
?>