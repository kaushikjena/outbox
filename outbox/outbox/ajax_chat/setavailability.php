<?php
//require_once(dirname(dirname(__FILE__))."/libs/mysql.php");
require_once(dirname(__FILE__)."/../libs/mysql.php");
// 0: Not available, 1: Private, 2: Public, 3: Custom
if(!isset($_SESSION)){session_start();}
$cuser=$_SESSION['live_chat']['cuser'];
$_SESSION['live_chat']['myAvailability']=$_GET['status'];

updateLivestat($cuser,$_GET['status']);
//mysql_close($conn);
//setavailability
?>