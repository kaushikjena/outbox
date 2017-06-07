<?php
//JSON.stringify(array)
//json_decode($_POST['jsondata']);

// 0: Not available, 1: Private, 2: Public, 3: Custom
if(!isset($_SESSION)){session_start();}
$_SESSION['live_chat']['owinlist']=json_decode($_POST['owinlist']);

//setavailability
?>