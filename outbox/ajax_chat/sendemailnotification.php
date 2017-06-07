<?php
ob_start();
require_once(dirname(dirname(__FILE__))."/config.php");
require_once(dirname(dirname(__FILE__))."/libs/mysql.php");
//$cuser=$_POST['cuser'];
//eupdateLivestat($cuser);
echo emailnotification("Rajib","Hello! How are you?","rajibdebslg");
ob_flush();
?>