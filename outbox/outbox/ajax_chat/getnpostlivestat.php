<?php
ob_start();
require_once(dirname(dirname(__FILE__))."/libs/mysql.php");
if(!isset($_SESSION)){session_start();}
$cuser=$_SESSION['live_chat']['cuser'];
$livestat=$_SESSION['live_chat']['myAvailability'];
updateLivestat($cuser,$livestat);
echo getavailablefriends($cuser);
//mysql_close($conn);
ob_flush();
?>