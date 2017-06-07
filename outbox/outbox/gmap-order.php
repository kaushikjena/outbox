<?php
ob_clean();
session_start();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();

$woid = $_REQUEST['woid'];
$contentString="";
$radius = 100;
$resArrayMap = $dbf->fetchOrder("clients c,work_order wo","wo.client_id=c.id AND wo.id='$woid'","wo.id","wo.id,wo.wo_no,c.name,c.address,c.city,c.latitude,c.longitude");
foreach($resArrayMap as $resmap) {
	$latitude =$resmap['latitude']; $longitude =$resmap['longitude']; 
	$string = '<b><u>'.$resmap['wo_no'].'</u></b><br/> '.addslashes($resmap['name']).'<br/> '.addslashes($resmap['city']);
	$contentString.= "['".$string."'_".$resmap['latitude']."_".$resmap['longitude']."_'images/green-dot.png'_'".$resmap['wo_no']."'],";
	//query for find closest techs of the work order
	$qry = "SELECT id, first_name,middle_name,last_name,address,city,latitude, longitude, SQRT(POW(69.1 * (latitude - ".$resmap['latitude']."), 2) + POW(69.1 * (".$resmap['longitude']." - longitude) * COS(latitude / 57.3), 2)) AS distance FROM technicians WHERE status=1 HAVING distance < ".$radius." ORDER BY distance";
	$resArrayTech = $dbf->simpleQuery($qry);
	foreach($resArrayTech as $restech){
		$techname = $restech['first_name'].' '.$restech['middle_name'].' '.$restech['last_name'];
		$stringtech = '<b><u>'.$techname.'</u></b><br/> '.addslashes($restech['address']).'<br/> '.addslashes($restech['city']);
		$contentString.= "['".$stringtech."'_".$restech['latitude']."_".$restech['longitude']."_'images/pink-dot.png'_'".$techname."'],";
	}
	//print "<pre>";
	//print_r($resArrayTech);exit;
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

