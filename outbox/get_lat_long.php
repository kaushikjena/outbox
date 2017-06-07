<?php
ini_set('memory_limit','-1'); // set memory limit upto 2 GB 
ini_set('max_execution_time','3600'); // set memory limit upto 1 hour
ini_set('max_input_time', '3600');
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();
function getLnt($zip){
	$url = "http://maps.googleapis.com/maps/api/geocode/json?address=
	".urlencode($zip)."&sensor=false";
	$result_string = file_get_contents($url);
	$result = json_decode($result_string, true);
	$result1[]=$result['results'][0];
	$result2[]=$result1[0]['geometry'];
	$result3[]=$result2[0]['location'];
	return $result3[0];
}
function getaddress($lat,$lng){
	$url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($lat).','.trim($lng).'&sensor=false';
	$json = @file_get_contents($url);
	$data=json_decode($json);
	$status = $data->status;
	if($status=="OK")
	return $data->results[0]->formatted_address;
	else
	return false;
}

/*$val = getLnt('92 BRIGHTON PARK DRIVE,SAINT CHARLES,MO');
echo "Tech->Latitude: ".$val['lat']."<br>";
echo "Tech->Longitude: ".$val['lng']."<br>";

$val = getLnt('RR 6 BOX 243,WHEELING,WV');
echo "client->Latitude: ".$val['lat']."<br>";
echo "client->Longitude: ".$val['lng']."<br>";
*/
foreach($dbf->fetch("clients","latitude=''") as $res){
	//if($res['latitude'] ==''){
		$val = getLnt($res['address'].",".$res['city'].",".$res['state'].",".$res['zip_code']);
		//$val = getLnt($res['address'].",".$res['city'].",".$res['state']);
		$string = "latitude='".$val['lat']."',longitude='".$val['lng']."'";
		$dbf->updateTable("clients",$string,"id='".$res['id']."'");
	//}
}

$lat= 20.2485285; //latitude
$lng= 85.8581787; //longitude
$address= getaddress($lat,$lng);
if($address){
	echo $address;
}else{
	echo "Not found";
}
?>