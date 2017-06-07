<?php
ob_start();
session_start();
include_once('../includes/ExportToExcel.class.php');
//Object initialization
$exp=new ExportToExcel();
$exp->exportWithPage("tech_report_excell_data.php","technician_detail.xls");
?>