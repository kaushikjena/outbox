<?php
ob_start();
//require_once(dirname(dirname(__FILE__))."/libs/mysql.php");
require_once(dirname(__FILE__)."/../libs/mysql.php");
//echo $con;
if(!isset($_SESSION)){session_start();}
$cuser=$_SESSION['live_chat']['cuser'];

echo getwritingstatus($cuser);
//mysql_close($conn);
ob_flush();
?>