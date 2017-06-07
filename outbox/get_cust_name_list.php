<?php
ob_start();
session_start();
include 'includes/class.Main.php';
$dbf = new User();
$q = strtolower($_GET["q"]);
//echo $q;exit;
if (!$q) return;
$return_arr = array();
$sql = "select id,name from clients where name LIKE '$q%' AND status=0 AND user_type='customer'";
$resd = mysql_query($sql);
while($rs = mysql_fetch_assoc($resd)) {
	$cname = $rs['name'];$cid = $rs['id'];
	echo "$cname|$cid\n";
}
?>