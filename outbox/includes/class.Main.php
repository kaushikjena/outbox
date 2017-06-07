<?php
error_reporting(1);
include_once 'dbconfig.php';
include_once 'class.Pagination.php';
# Involves Any User operations
class User extends Pagination{
	
	# Database connect
	/*function __construct(){
		$db = new DB_Class();
	}*/
	
	# Track IP Address for where user has been login
	# Created By : Kishor Singh
	# Created On : 27-Oct-2012
	function TrackIP(){
				
		$cr_date = date('Y-m-d H:i:s A');
		$u_id = $_SESSION['user_id'];
		$ip_addr = $_SERVER['REMOTE_ADDR'];
		
		//$string = "INSERT INTO users_login SET user_id='$u_id',created_date='$cr_date',ip_addr='$ip_addr'";
		//mysql_query($string);		
	}

//To get time difference between two times	
function getTimeDiff($dtime,$atime)
{
    $nextDay=$dtime>$atime?1:0;
    $dep=explode(':',$dtime);
    $arr=explode(':',$atime);


    $diff=abs(mktime($dep[0],$dep[1],0,date('n'),date('j'),date('y'))-mktime($arr[0],$arr[1],0,date('n'),date('j')+$nextDay,date('y')));

    //Hour

    $hours=floor($diff/(60*60));

    //Minute 

    $mins=floor(($diff-($hours*60*60))/(60));

    //Second

    $secs=floor(($diff-(($hours*60*60)+($mins*60))));

    if(strlen($hours)<2)
    {
        $hours="0".$hours;
    }

    if(strlen($mins)<2)
    {
        $mins="0".$mins;
    }

    if(strlen($secs)<2)
    {
        $secs="0".$secs;
    }

    //return $hours.':'.$mins.':'.$secs;
	return $hours.':'.$mins.' Hr';

}
	
	# TOTAL ROWS
	# Created By : Kishor Singh
	# Created On : 17-Sept-2011
	function countRows($tblName, $optCondition=""){
		if(trim($optCondition) != ""){
			$condition = " WHERE " . $optCondition;
		}else{
			$condition = "";
		}
		$sql="SELECT * FROM " . $tblName . $condition;
	    //echo $sql; exit;
		$result = mysql_query($sql);
		if(!$result){
			trigger_error("Problem selecting data");
		}
		$num=mysql_num_rows($result);//print $num;
		return $num;
	}
	
	# FETCH SINGLE ROW or specific Column FROM A TABLE
	# Created By : Kishor Singh
	# Created On : 17-Sept-2011
	function strRecordID($tblName,$field,$optCondition=""){
		if(trim($optCondition) != ""){
			$sql = "SELECT ".$field." from ".$tblName." WHERE " . $optCondition;
		}else{
			$sql = "SELECT ".$field." from ".$tblName;
		}
		//echo $sql;
		$result = mysql_query($sql);
		return mysql_fetch_assoc($result);
	}
	
	# FETCH SINGLE ROW FROM A TABLE
	# Created By : Kishor Singh
	# Created On : 17-Sept-2011
	function fetchSingle($tblName,$optCondition=""){
		if(trim($optCondition) != ""){
			$condition = " WHERE " . $optCondition;
		}else{
			$condition = "";
		}		
		$sql="SELECT * FROM " . $tblName . $condition; //print $sql;
		$result = mysql_query($sql);
		//return mysql_fetch_array($result);
		return mysql_fetch_assoc($result);
	}
	# FETCH SINGLE ROW FROM A TABLE
	# Created By : Kishor Singh
	# Created On : 17-Sept-2011
	function fetchSingle_culumn($tblName,$optCondition=""){
		if(trim($optCondition) != ""){
			$condition = " WHERE " . $optCondition;
		}else{
			$condition = "";
		}		
		$sql="SELECT `action_upload`,`action_view`,`action_delete`,`action_edit`,`action_add`,`action_approve`,`action_full`,`action_na` FROM " . $tblName . $condition;
		$result = mysql_query($sql);
		return mysql_fetch_assoc($result);
	}
	# INSERT TO TABLE USING SET METHOD
	# Created By : Kishor Singh
	# Created On : 14-May-2012
	function insertSet($tblName,$string){
		$condition = " WHERE " . $condition;
		//echo "INSERT INTO " . $tblName . " SET " .  $string;exit;
		$string = "INSERT INTO  " . $tblName . " SET " .  $string;
		$rs= mysql_query($string);
		if($rs){
			$lastId = mysql_insert_id();
			return $lastId;
		}else{
			return 0;
		}
	}
		
	# DELETE DATA FROM TABLE
	# Created By : Kishor Singh
	# Created On : 14-May-2012
	function deleteFromTable($tblName, $condition=""){
		if(trim($condition) != ""){
			$condition = " WHERE " . $condition;
		}else{
			$condition = "";
		}
		$rs= mysql_query("DELETE FROM " . $tblName . $condition);
	}
	
	# UPDATE TABLE
	# Created By : Kishor Singh
	# Created On : 14-May-2012
	function updateTable($tblName,$string, $condition){
		
		$condition = " WHERE " . $condition;		
		//echo "UPDATE " . $tblName . " SET " .  $string . $condition;exit;		
		$rs= mysql_query("UPDATE " . $tblName . " SET " .  $string . $condition);		
	}

	# Returns data value if data exists in a table (suitable for integer or string data)
	# Created By : Kishor Singh
	# Created On : 14-May-2012
	function getDataFromTable($tblName, $fldName,  $optCondition){
		$defaultVal="";	
		if(trim($optCondition) != ""){
			$condition = $optCondition ;
		}else{
			$condition = "";
		}
		//echo "select " . $fldName . " from " . $tblName . " where " . $condition;
		$queryString = "select " . $fldName . " from " . $tblName . " where " . $condition;
		$rs = mysql_query($queryString);
	
		if((!($rs)) || (!($rec=mysql_fetch_array($rs)))){
			return $defaultVal;		//not found
		}else if(is_null($rec[0])){
			return $defaultVal;		//found
		}else{
			return $rec[0];			//found
		}
	}
	
	# FETCH ALL ROWS FROM A TABLE WITH ORDER BY
	# Created By : Kishor Singh
	# Created On : 16-Sept-2011
	function fetchOrder($tblName,$optCondition="",$orderby="",$field="",$groupby=""){
		if($field==""){
			$sql = "SELECT * FROM ".$tblName;
		}else{
			$sql = "SELECT ".$field." FROM ".$tblName;
		}
		if(trim($optCondition) != ""){
			$sql = $sql." WHERE " . $optCondition;
		}
		if($groupby != ""){
			$sql = $sql." group by " . $groupby;
		}
		if(trim($orderby) != "" ){
			$sql = $sql." order by " . $orderby;
		}
		//echo $sql;//exit;
		$result = mysql_query($sql);
		if(!$result){
			trigger_error("Problem selecting data");
		}
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
			$result_array[] = $row;
		}
		if(count($result_array)>0){
			return $result_array;	
		}else{
			$default_val=array();
			return $default_val;
		}
	}
	//FETCH ALL ROWS FROM A TABLE
	function fetch($tblName,$optCondition=""){
		if(trim($optCondition) != ""){
			$condition = " WHERE " . $optCondition;
		}else{
			$condition = "";
		}
		$sql="SELECT * FROM " . $tblName . $condition;
		//echo $sql;
		$result = mysql_query($sql);
		if(!$result){
		  trigger_error("Problem selecting data");
		}
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
		  $result_array[] = $row;
		}
		if(count($result_array)>0){
			return $result_array;	
		}else{
			 $default_val=array();
			 return $default_val;
		}
	}
	//end fetch function
	//FETCH ALL ROWS FROM A TABLE USING LEFT JOIN
	function leftJoin($tblName1,$tblName2,$tbl1Param,$tbl2Param,$optCondition=""){
		if(trim($optCondition) != ""){
			$condition = " WHERE " . $optCondition;
		}else{
			$condition = "";
		}
		$sql="SELECT DISTINCT ". $tblName1.".id FROM " . $tblName1 . " LEFT JOIN ". $tblName2 ." ON ".$tblName1.".".$tbl1Param."=".$tblName2.".".$tbl2Param. $condition;
		$result = mysql_query($sql);
		if(!$result){
			trigger_error("Problem selecting data");
		}
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
			$result_array[] = $row;
		}
		if(count($result_array)>0)
		{
			 return $result_array;	
		}else{
			 $default_val=array();
			 return $default_val;
		}
	}
	##############################################################
	############### Simple Query #################################
	##############################################################
	public function simpleQuery($sql){		
		$result=mysql_query($sql);
		if(!$result){
			trigger_error("Problem selecting data");
		}
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
			$result_array[] = $row;
		}
		if(count($result_array)>0){
			return $result_array;	
		}else{
			$default_val=array();
			return $default_val;
		}
	}
	###############END################
	# String cut to a limited words
	# Like Substring
	# Created By : Shakti Das
	# Created On : 17-Sept-2011
	function cut($string, $max_length){
		if (strlen($string) > $max_length){
			$string = substr($string, 0, $max_length);
			$pos = strrpos($string, " ");
			if($pos === false) {
					return substr($string, 0, $max_length)."...";
			}
				return substr($string, 0, $pos)."...";
		}else{
			return $string;
		}
	}
	
	# FIND URL OF THE SITE
	# Created By : Shakti Das
	# Created On : 17-Sept-2011
	function get_server(){
		$protocol = 'http';
		if (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443') {
			$protocol = 'https';
		}
		$host = $_SERVER['HTTP_HOST'];
		$baseUrl = $protocol . '://' . $host;
		if (substr($baseUrl, -1)=='/') {
			$baseUrl = substr($baseUrl, 0, strlen($baseUrl)-1);
		}
		return $baseUrl;
	}
	# GET LATITUDE AND LONGITUDE FROM ADDRESS
	# CREATED BY : SUKANTA RANASINGH
	# cREATED dATE :9-SEPT-2014
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
}
?>
