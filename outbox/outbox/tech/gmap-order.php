<?php
ob_clean();
session_start();
include_once '../includes/class.Main.php';
//Object initialization
$dbf = new User();

$woid = $_REQUEST['woid'];
$contentString="";
$resArrayMap = $dbf->fetchOrder("clients c,work_order wo","wo.client_id=c.id AND wo.id='$woid'","wo.id","wo.id,wo.wo_no,c.name,c.address,c.city,c.latitude,c.longitude");
foreach($resArrayMap as $resmap) {
	$latitude =$resmap['latitude']; $longitude =$resmap['longitude']; 
	$string = '<b><u>'.$resmap['wo_no'].'</u></b><br/> '.addslashes($resmap['name']).'<br/> '.addslashes($resmap['city']);
	$contentString.= "['".$string."'_".$resmap['latitude']."_".$resmap['longitude']."_'../images/green-dot.png'_'".$resmap['wo_no']."'],";
}
?>

<style type="text/css">
.divMap{
	width:700px;
	border:solid 1px #999;
}
#googlemap img{ max-width: none;}

</style>
<!----map section start----->
<div id="did" class="divMap">
	<input type="hidden" id="lat" value="<?php echo $latitude;?>"/><input type="hidden" id="long" value="<?php echo $longitude;?>"/>
	<input type="hidden" name="contentString" id="contentString" value="<?php echo $contentString;?>"/>
    <div id="googlemap" style="width:100%; height: 565px;"></div>
</div>
<!----map section start----->

