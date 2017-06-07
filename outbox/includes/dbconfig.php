<?php
date_default_timezone_set('UTC');
# Define Server
define('DB_SERVER', 'localhost');

#Define user name
define('DB_USERNAME', 'root');
//define('DB_USERNAME', 'bletpkjv_outbox');
//define('DB_USERNAME', 'boxware_sysuser');

#Define password
define('DB_PASSWORD', '');
//define('DB_PASSWORD', 'outbox123!@#');
//define('DB_PASSWORD', 'champi0n');

#Define database name
define('DB_DATABASE', 'outbox');
//define('DB_DATABASE', 'bletpkjv_outbox');
//define('DB_DATABASE', 'boxware_sys');
$connection = mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD) or die('Oops connection error -> ' . mysql_error());
mysql_select_db(DB_DATABASE, $connection) or die('Database error -> ' . mysql_error());

//mysql_query("SET character_set_results=utf8", $connection);
//mb_language('uni');
//mb_internal_encoding('UTF-8');
//mysql_select_db(DB_DATABASE, $connection);
//mysql_query("set names 'utf8'",$connection);
# Class for Database
# To connecting to server
/*class DB_Class extends Pagination{
	function __construct(){
	}
}*/
#Define text magic api user name
define('TEXT_MAGIC_API_USER', 'debasisacharya');
#Define text magic api password
//define('TEXT_MAGIC_API_PASSWORD', 'MYugQUXyce');//live password
define('TEXT_MAGIC_API_PASSWORD', 'debasis112345'); //dummy password

?>