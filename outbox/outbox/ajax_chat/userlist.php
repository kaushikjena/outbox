<?php
require_once(dirname(__FILE__)."/../libs/mysql.php");
$site_url = $dbf->getDataFromTable("admin","site_url","id=1");
// 0: Not available, 1: Private, 2: Public, 3: Custom
if(!isset($_SESSION)){session_start();}
$cuser=$_SESSION['live_chat']['cuser'];

//get userlist from mychat online users table
$usernameArray=$dbf->fetchOrder("login_view lv,mychat_online_users mou","lv.username=mou.username AND mou.status=1 AND mou.username<>'$cuser'","","mou.username,mou.user_type,lv.id as userid");
$usernameArray = !empty($usernameArray)? $usernameArray:array();
//print_r($usernameArray);

foreach($usernameArray as $vuser){
	$user=$vuser['username'];
?>
		<div class="chatbox_friends" id="chatbox_friends_<?php echo $user; ?>" data-username="<?php echo $user; ?>">
			<div class="usr_thumbnail"><img class="img_stretched" src="<?php echo $site_url;?>/<?php echo getimage($vuser['userid'],$vuser['user_type']);?>" /></div>
			<div class="frndName"><?php echo $user; ?></div>
			<div class="statuslight" id="<?php echo $user; ?>_status_id" data-username="<?php echo $user; ?>">
				<div class="greyLight"></div>
			</div>
		</div>
<?php }?>
<?php 
function getimage($user,$usertype){
	$imagepath="";
	if($usertype == 'admin'){
		$table = "admin"; $column="user_photo"; $folder ="user_photo";
	}elseif($usertype == 'user'){
		$table = "users"; $column="user_photo";$folder ="user_photo";
	}elseif($usertype == 'client'){
		$table = "clients"; $column="client_image";$folder ="";
	}elseif($usertype == 'tech'){
		$table = "technicians"; $column="tech_image";$folder ="tech_image";
	}
	$sql="SELECT ".$column." FROM ".$table." WHERE id='$user'";
	$result =mysql_query($sql);
	if($row=mysql_fetch_assoc($result)){
		$path = $row[$column];
		if($path){
			$imagepath = $folder."/".$path;
			return $imagepath;
		}else{
			$imagepath = "images/user.png";
			return $imagepath;
		}
	}
	return $imagepath;
	
}
?>