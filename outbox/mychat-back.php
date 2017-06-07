<?php
//require_once(dirname(__FILE__)."/libs/mysql.php");
$site_url = $dbf->getDataFromTable("admin","site_url","id=1");
//$path = str_replace("192.168.0.114","localhost",$site_url);//replace 192.168.0.114 for localhost
$path = $site_url;//replace 192.168.0.114 for localhost
//select username from login view table
$LoginUserName=$dbf->getDataFromTable("login_view","username","id='$_SESSION[userid]' AND user_type='$_SESSION[usertype]'");
//print_r($resLoginUser);
$_SESSION['live_chat']['cuser']=$LoginUserName;	//assign user name in session
$cuser=$_SESSION['live_chat']['cuser'];//assign in cuser file
//select login user chart status
$LoginUserStatus= $dbf->getDataFromTable("mychat_online_users","status","username='$cuser' AND user_type='$_SESSION[usertype]'");
if($LoginUserStatus >0)
	$_SESSION['live_chat']['myAvailability']=$LoginUserStatus;
else
	$_SESSION['live_chat']['myAvailability']=1;
?>
<script type="text/javascript">
var curuser="<?php echo $_SESSION['live_chat']['cuser']; ?>";
//var ajaxpath="<?php //echo 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/"; ?>";
var ajaxpath="<?php echo $path."/"; ?>";
</script>
<!--mychart js added -->
<script src="<?php echo $site_url;?>/js/dragndrop.js" type="text/javascript"></script>
<script src="<?php echo $site_url;?>/js/mychat.js" type="text/javascript"></script>
<link href="<?php echo $site_url;?>/css/dragndrop.css" media="screen" type="text/css" rel="stylesheet">
<link href="<?php echo $site_url;?>/css/mychat.css" media="all" rel="stylesheet" type="text/css">
<!--mychart js added-->
<!-- chat js initialization -->
<script type="text/javascript">
/*  Added by sukanta fr chat script */
var pageTitle="";
$(document).ready(function(){
	initializeChat();
   /*$('<audio id="notification_sound"><source src="sounds/notify.mp3" type="audio/mpeg"><source src="sounds/notify.wav" type="audio/wav"></audio>').appendTo('body');*/
	$("#cmb_availability").val('<?php echo $_SESSION['live_chat']['myAvailability']; ?>');
	myavailability=<?php echo $_SESSION['live_chat']['myAvailability']; ?>;
	pageTitle=$('title').html();
});
</script>        
<!-- chat js initialization -->
<!---  Username list  start -->
<div id="chatbox_userlist" class="chatboxmyfriends _chartposition">
	<div class="div_min_chatbox" id="chat_box_friend_list" style="cursor:pointer">
		<div class="chatboxhead" style="background-color:#063457 !important">
			<div class="chatboxtitle">&nbsp;&nbsp; <?php echo $_SESSION['live_chat']['cuser']; ?>&nbsp;&nbsp;</div>
			<div class="chatboxoptions" style="float:right;"><a class="frnd_list_minimize" style="font-size:18px;z-index:10010;text-decoration:none;"> - </a></div>
			<div class="chatboxoptions" id="chatbox_settings" style="z-index:10005;display:block;width:30px;text-align:center;float:right;"><img src="<?php echo $site_url;?>/images/gear.png"></div>
			<div class="chatboxdropdown">
				<div class="cb_options"> 
				<select id="cmb_availability" class="mychatselect" onChange="javascript:setavailability();">
					<option value="1" selected>Friends</option>
					<option value="3">Custom</option> 
					<option value="0">Turn off</option>                                                                        
				</select>
				<img id="custom_avl_setting" src="<?php echo $site_url;?>/images/gearblack.png" height="13" width="13" style="display:<?php if($_SESSION['live_chat']['myAvailability']==3){echo "block";}else{echo "none";} ?>;" />
				</div>
				<div class="cb_options">
					<div class="chatbox_close_all" style="cursor:pointer;">Close all window</div>
				</div>
			</div>
			<br clear="all">
		</div>
	</div>
	<div class="chatboxbodyarea" id="chatbox_userlist_area" style="display:none;">
		<div class="chatboxcontent" id="favorites_list"  style="height:400px !important;">
		<?php 
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
			<?php }	?>
		</div>
	</div>
</div>
<!---  Username list end -->
<!-- Chatbox container div -->
<div id="chat_bar" class="_chart_bar_class"></div>
<!-- End Chatbox container div -->	
<!-- Sample chatbox, will not be shown in web page, but it's important --> 
<div class="div_chatbox_samp" id="div_chatbox_samp" style="display:none;">
	<div id="chatbox_JSRPLABLUNAME" data-username="JSRPLABLUNAME" class="chatbox" data-auto-scroll="1" data-int-id="0" ondrop='javascript:handleFileUpload()'>
		<div class="div_min_chatbox" style="cursor:pointer">
			<div class="chatboxhead">
				<div class="chatboxtitle" data-username="JSRPLABLUNAME">JSRPLABLUNAME</div>
				<div class="chatboxoptions"><a class="chatbox_close"> X </a></div>
				<br clear="all">
			</div>
		</div>
		<div class="chatboxbodyarea splash_drag_drop" style="position:absolute;display:none;">
			<h1 style="margin-top:25px;text-align:center;">Drag & Drop files here</h1>
		</div>
		<div class="chatboxbodyarea">
			<div class="histmessages" style="width:95%;" data-username="JSRPLABLUNAME" data-fmsgid="0" data-lmsgid="-1"></div>

			<div class="div_full_hist" style="height:12px;width:90%;text-align:right;font-size:10px;background-color:#FFF;" data-username="JSRPLABLUNAME" data-fmsgid="0" data-lmsgid="-1">
				<a href="chathistory.php?friend=JSRPLABLUNAME" onclick="window.open(this.href, 'Chat history', 'scrollbars,width=800,height=600'); return false">View full history</a>
			</div>
			<div class="chatboxcontent" data-username="JSRPLABLUNAME" data-scrolled="0">
			</div>
			<div class="writing_notification" id="chatbox_writing_stat_JSRPLABLUNAME">JSRPLABLUNAME is writing ...</div>
			<div class="chatboxinput">
				<textarea class="chatboxtextarea" data-to-user="JSRPLABLUNAME"></textarea>
				<div style="width:20px;height:50px;float:right;position:absolute; bottom:7px;right:8px;" id="test1">
					<div class="input_smiley"><img src="<?php echo $site_url;?>/images/smiley.png" /></div>
					<div class="input_image" style=""><img src="<?php echo $site_url;?>/images/camera.png" style="width:90%;" /></div>                    
				</div>
			</div>
			<div class="div_smiley">
			<?php for($i=1;$i<85;$i++){	?>
				<img class="img_smileys" src="<?php echo $path;?>/images/smiley/<?php echo $i; ?>.gif">
			<?php }	?>
			 </div>
		</div>
	 </div>
</div>
<!-- End chatbox div -->     
<div class="choose_users" id="frm_choose_user" style="display:none;z-index:10100;">
	<div class="user_table">
	<div style="position:absolute;height:25px;background:#073356;width:100%;">
	<h3 style="width:100%;text-align:center;color:#FFF;font-size:11px;margin-top:2px;">CHOOSE USERS</h3>
	</div> 
	<form name="frm_custom_avalability" method="post" id="frm_custom_avalability" action="#">       
	<?php
		//get userlist from mychat online users table
		$usernameArray=$dbf->fetchOrder("login_view lv,mychat_online_users mou","lv.username=mou.username AND mou.status=1 AND mou.username<>'$cuser'","","mou.username,mou.user_type,lv.id as userid");
		$usernameArray = !empty($usernameArray)? $usernameArray:array();
		//print_r($usernameArray);
		$i=1;
	 ?>			
		<table class="userlist">
			<tr>
		 <?php
		 foreach($usernameArray as $vuser){
			$user=$vuser['username'];
		 ?>
            <td><input class="checkbox_su" type="checkbox" name="chkbox_users[]" style="margin-right:10px;padding-top:0px;margin-top:0px;" value="<?php echo $user;?>"><span class="chkbox_username"><?php echo $user;?></span></td>
    	<?php
            if($i++%4==0){  echo "</tr><tr>"; }
		 }
		 ?>
		</tr>
	</table>
	<input type="hidden" value="1" name="cht_frm_submit">
	<input type="hidden" value="<?php echo $_SESSION['live_chat']['cuser']; ?>" name="uname">        
	<div style="position:absolute;bottom:0;height:25px;background:#073356;width:100%;z-index:11000;">
	<input type="button" class="popupbuttons" onClick="javascript:cancelChoose();" value="CANCEL" style="z-index:11001;">
	<input type="button" disabled="disabled" id="frm_submit" class="popupbuttons" value="SELECT" style="z-index:11001;">
	</div>
	</form>
	</div>
</div>
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
function getActualvariation($user){
	$sql="SELECT `username` FROM `user_login` WHERE username = '$user'";	
	if($result=mysql_query($sql)){
		$row=mysql_fetch_assoc($result);
		return $row['username'];	
	}	
}
?>
<script type="text/javascript">
function cancelChoose(){
	$("#cmb_availability").val(myavailability);
	$('.choose_users').hide();
	return true;
}
function CustChoose(){
//myoldavailability
	myoldavailability=myavailability;
	myavailability=$("#cmb_availability").val();	
	$('#custom_avl_setting').show();
	$.ajax({
		type: 'GET',
		url: ajaxpath+'ajax_chat/setavailability.php',
		dataType: 'html',
		data:  {'status':$("#cmb_availability").val()},
		cache: false, 
		success:function(html){					
		},
		error:function(jqXHR, textStatus){
		}
	});	
	$('.choose_users').hide();
}
</script>
<script>
// Attach a submit handler to the form
//input.checkbox:checked
$(document).ready(function(){
	//    $('#checkbox1').change(function() {
	$(".checkbox_su").change(function(){
		//alert($('#frm_custom_avalability').find('input.checkbox_su:checked').length);
		if($('#frm_custom_avalability').find('input.checkbox_su:checked').length<=0){
			//    $("#rbutton_"+i).prop("disabled",true);
			$('#frm_submit').prop("disabled",true);
		}else{
			$('#frm_submit').removeAttr("disabled");
		}
	});
});
$( "#frm_submit" ).click(function( event ) {	
	url = "<?php echo 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/ajax_chat/custoavl.php" ?>";
	// Send the data using post
	var term = $( "#frm_custom_avalability" ).serialize();
	//alert(term);
	var posting = $.post( url, term);
	// Put the results in a div
	posting.done(function( data ) {
		//alert(data);
		$("#frm_choose_user").hide();
		CustChoose();		
	});
	posting.fail(function( data ) {
		alert(data);	
	});
});
setTimeout(autoPopOpenWin,500);
</script>
