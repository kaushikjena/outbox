<?php
if(!isset($_SESSION)){session_start();}
$owinlist=$_SESSION['live_chat']['owinlist'];
$result=array();
$result['count']=count($owinlist);
$result['list']=$owinlist;
echo json_encode($result);
?>