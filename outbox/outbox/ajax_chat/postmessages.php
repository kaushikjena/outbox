<?php
ob_start();
require_once(dirname(dirname(__FILE__))."/libs/mysql.php");
//print_r($_POST);
$cuser=$_POST['cuser'];
$msgto=$_POST['msgto'];
$message=$_POST['message'];	
echo postMessage($cuser,$msgto,$message);
ob_flush();
?>