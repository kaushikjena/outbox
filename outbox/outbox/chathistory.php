<?php
require_once(dirname(__FILE__)."/libs/mysql.php");
$site_url = $dbf->getDataFromTable("admin","site_url","id=1");
if(!isset($_SESSION)){session_start();}
$cuser=$_SESSION['live_chat']['cuser'];
$friend=$_GET['friend'];
$limit=10;
if(!isset($_GET['page']))
	$page=1;
else
	$page=$_GET['page'];

if(!isset($_GET['range']))
	$range="";
else
	$range=$_GET['range'];

$additional_cond="";
if($range!=""){
	if($range=="1W")
		$start_time= strtotime("-1 weeks");
	if($range=="1M")
		$start_time= strtotime("-1 months");
	if($range=="6M")
		$start_time= strtotime("-6 months");
	if($range=="1Y")
		$start_time= strtotime("-1 years");
	$end_time=time();
	//query
	$sql="Select * from mychat_messages where ((to_user='".$friend."' and from_user='".$cuser."') OR (to_user='".$cuser."' and from_user='".$friend."')) AND datetime > ".$start_time." AND datetime < ".$end_time." ORDER BY mychat_messages.datetime  DESC Limit ".(($page-1)*$limit).",".$limit;
	//query for count
	 $sql_count="Select count(*) as num from mychat_messages where ((to_user='".$friend."' and from_user='".$cuser."') OR (to_user='".$cuser."' and from_user='".$friend."')) AND datetime > ".$start_time." AND datetime < ".$end_time."";
}else{
	//query
	$sql="Select * from mychat_messages where (to_user='".$cuser."' and from_user='".$friend."') or (to_user='".$friend."' and from_user='".$cuser."') order by id DESC limit ".(($page-1)*$limit).",".$limit;
	//query for count
	$sql_count="Select count(*) as num from mychat_messages where (to_user='".$cuser."' and from_user='".$friend."') or (to_user='".$friend."' and from_user='".$cuser."')";
}

//echo $sql;
//echo "<br/>".$sql_count;
$result=mysql_query($sql);
echo mysql_error();
$numrow=mysql_num_rows($result);
?>
<html>
<style type="text/css">
.img_smileys{
	vertical-align:middle;
	height:19px;
	width:19px;
	margin:auto 3px auto 3px;
	cursor:pointer;
	z-index:1500;
}
.chathistory{
	width:80%;
	height:auto;
	overflow:hidden;
	margin:auto auto auto auto;
	border:#999 thin solid;
}
.messagebody{
	width:100%;
	height:auto;
	min-height:30px;
	overflow:hidden;
	margin:10px auto 10px auto;
	border-bottom:#D2C4FF 1px solid;
	display:block;
	padding-bottom:5px;
	padding-top:5px;	
	col-span:3;
}
.time{
	width:auto;
	padding-left:5px;
	padding-right:5px;
	line-height:15px;
	height:15px;	
	float:left;
	font-size:10px;
	color:#333;
	vertical-align:middle;
	col-span:1;
}
.messages{
	width:auto;
	padding-left:15px;
	padding-right:5px;
	line-height:18px;
	height:auto;
	overflow:hidden;		
	float:left;
}
.username{
	width:auto;
	float:left;
	padding-left:15px;
	padding-right:5px;
	color:#039;	
	vertical-align:middle;
	line-height:14px;
	font-size:14px;
	overflow:hidden;
	height:auto;
	col-span:1;
}
ul.pagination{
	width:50%;
	height:auto;
	overflow:hidden;
	float:left;
	margin-left:7%;
}
ul.historictspan{
	width:auto;
	height:35px;
	overflow:hidden;
	float:right;
	margin-right:7%;
}
div.pagination {
	padding: 3px;
	margin: 3px;
}

div.pagination a {
	padding: 2px 5px 2px 5px;
	margin: 2px;
	border: 1px solid #AAAADD;
	border-radius:5px;
	text-decoration: none; /* no underline */
	color: #000099;
}
div.pagination a:hover, div.pagination a:active {
	border: 1px solid #000099;
	border-radius:5px;
	color: #000;
}
div.pagination span.current {
	padding: 2px 5px 2px 5px;
	margin: 2px;
	border: 1px solid #000099;
	border-radius:5px;
	font-weight: bold;
	background-color: #000099;
	color: #FFF;
}
div.pagination span.disabled {
	padding: 2px 5px 2px 5px;
	margin: 2px;
	border: 1px solid #EEE;
	color: #DDD;
}
ul.historictspan a {
	display:block;
	padding: 2px 5px 2px 5px;
	text-decoration: none; /* no underline */
	color: #000099;
	margin-top: 5px;
	margin-bottom: 5px;
	width:auto;
	float:left;
	border: 1px solid #AAAADD;
	border-radius:5px;
}
ul.historictspan a.currentts{
	font-weight: bold;
	background-color: #000099;
	color: #FFF;		 
} 
</style>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript">
var ajaxpath="<?php echo $site_url."/"; ?>";
var sfind = [
			':dance:','\\\o\\/','\\\:D\\/',':d\\/',
			':star:','\\(\\*\\)',
			':phone:',':mobile:',':mp:',':ph:',
			":finger:", ":bandit:", ":drunk:", 
			":smoking:", ":smoke:", ":ci:",
			":toivo:", ":rock:", ":headbang:", ":banghead:",
			":bug:",":fubar:",":poolparty:",
			":swear:",":tmi:",":heidy:",
			":mooning:",':\\)',':=\\)',
			':-\\)',':\\(',':=\\(',
			':-\\(',
			'8\\)','8=\\)','8-\\)','B\\)','B=\\)','B-\\)',':cool',
			';\\)',';-\\)',
			';\\(',':-\\(',';\\(',';-\\(',';=\\(',':=\\(',
			':sweat','\\(:\\|',
			':\\|',':=\\|',':-\\|',
			':\\*',':=\\*',':-\\*',
			':blush:',':\\$',':-\\$',':\\=\\=\\$', 
			':\\^\\)',
			'\\|-\\)','I-\\)','i\\=\\)',':snooze:',':sleepy:',
			'\\|\\(','\\|-\\(','\\|\\=\\(',
			':inlove:',
			'\\]:\\)','\\>:\\)',':grin:',
			':talk:',
			':yawn:','\\|-\\(\\)',
			':puke:',':\\&',':-\\&',':=\\&',
			':doh',
			':\\@',':\\-@',':\\=@','x\\(','x-\\(','x=\\(','X\\(','X-\\(','X-\\(',
			':wasntme:',
			':party:',
			':mm:',
			'8-I','8-\\|','8\\|','B\\|','8=\\|','B=\\|',':nerd:',   //image 40.gif
			':x',':-x',':X',':-X',':#',':-#',':=x',':=X',':=#',
			':hi:',
			':call:',
			':devil:',
			':angel:',
			':envy:',
			':wait:',
			':bear:',':hug:',
			':makeup:',':kate:',
			':giggle:',':chuckle:',
			':clap:',
			':think:',':\\?',':-\\?',':=\\?',
			':bow:',
			':rofl:',
			':whew:',
			':happy:',
			':smirk:',
			':nod:',
			':shaking:',
			':punch:',  //image 60
			':emo:',
			':ok:','\\(y\\)','\\(Y\\)',
			':no:','\\(n\\)','\\(N\\)',
			':handshake',
			'<3',
			'\\<\\/3',
			':mail:',
			':flower:',
			':rain:',
			':sun:',
			':time:',
			':music:',
			':film:',':movie:',
			':coffee:',
			':pizza:',
			':cash:',':\\$:',
			':muscle:',':flex:',
			':cake:','\\(\\^\\)',
			':beer:',
			':drink:','\\(d\\)','\\(D\\)',
			':ninja:',
			';p',':=P',':-P',':p',':=p',':-p',
			':S',':-S',':\\=S',':s',':-s',':=s',
			':D',':=D',':-D',':d',':=d',':-d',
		];

var sreplace = [
			"82","82","82","82",
			"84","84",
			"74","74","74","74",
			"1", "2", "3", 
			"4", "4", "4",
			"5", "6", "7", "7",
			"8","9","10",
			"11","12","13",
			"14","15","15",
			"15","16","16",
			"16",
			"18","18","18","18","18","18","18",
			"19","19",
			"20","20","20","20","20","20",
			"21","21",
			"22","22","22",
			"23","23","23",
			"25","25","25","25",
			"26",
			"27","27","27","27","27",
			"28","28","28",
			"29",
			"30","30","30",
			"31",
			"32","32",
			"33","33","33","33",
			"34",
			"35","35","35","35","35","35","35","35","35",
			"36",
			"37",
			"39",
			"40","40","40","40","40","40","40",
			"41","41","41","41","41","41","41","41","41",
			"42",
			"43",
			"44",
			"45",
			"46",
			"47",
			"48","48",
			"49","49",
			"50","50",
			"51",
			"52","52","52","52",
			"53",
			"54",
			"55",
			"56",
			"57",
			"58",
			"59",
			"60",
			"61",
			"62","62","62",
			"63","63","63",
			"64",
			"65",
			"66",
			"67",
			"68",
			"69",
			"70",
			"71",
			"72",
			"73","73",
			"75",
			"76",
			"77","77",
			"78","78",
			"79","79",
			"80",
			"81","81","81",
			"83",
			"24","24","24","24","24","24",		
			"38","38","38","38","38","38",
			"17","17","17","17","17","17",				
			];

String.prototype.addSmileys = function() {
  var replaceString = this;
  var regex; 
  for (var i = 0; i < sfind.length; i++) {
    regex = new RegExp(sfind[i], "g");
    replaceString = replaceString.replace(regex, '<img class="img_smileys" src="'+ajaxpath+'images/smiley/'+sreplace[i]+'.gif" height="30"  width="30" />');
  }
  return replaceString;
};
</script>

<body>
<h1 style="text-align:center;color:#003;">Chat history - <?php echo $friend; ?></h1>
<hr/>
<ul class="historictspan">
	<a href="<?php echo $_SERVER['PHP_SELF']."?friend=".$_GET['friend']."&range=1W"; ?>" style="margin-right:5px;">1 Week</a>
	<a href="<?php echo $_SERVER['PHP_SELF']."?friend=".$_GET['friend']."&range=1M"; ?>" style="margin-right:5px;">1 Month</a>
	<a href="<?php echo $_SERVER['PHP_SELF']."?friend=".$_GET['friend']."&range=6M"; ?>" style="margin-right:5px;">6 Months</a>
	<a href="<?php echo $_SERVER['PHP_SELF']."?friend=".$_GET['friend']."&range=1Y"; ?>" style="margin-right:5px;">1 Year</a>
</ul>
<div class="chathistory">
<?php
while($row=mysql_fetch_assoc($result)){
	if($row['from_user']==$cuser)
		$from="<a style='font-weight:bold;color:#003;text-decoration:none;' href='javascript:void(0);'>".$cuser."</a>";
	else
		$from="<a style='font-weight:bold;color:#009;text-decoration:none;' href='javascript:void(0);'>".$row['from_user']."</a>";
?>
	<div class="messagebody">
		<div class="username"><?php echo $from; ?></div>
	    <div class="time"><?php  echo date("d-m-y h:i:s A",$row['datetime']); ?></div>
    	<div class="messages"><?php echo replace_d_smiley($row['messagebody']); ?></div>
	</div>
<?php
}
?>	
</div>
<ul class="pagination">
<?php
	$tbl_name="mychat_messages";		//your table name
	// How many adjacent pages should be shown on each side?
	$adjacents = 2;
	if($range!="")
		$addition_parameters="&range=$range";
	else
		$addition_parameters="";
	/* 
	   First get total number of rows in data table. 
	   If you have a WHERE clause in your query, make sure you mirror it here.
	*/
	$query=$sql_count;
	$total_pages = mysql_fetch_array(mysql_query($query));
	$total_pages = $total_pages['num'];
	/* Setup vars for query. */
	$targetpage = $_SERVER['PHP_SELF']; 	//your file name  (the name of this file)
	//$page = $_GET['page'];
	if($page) 
		$start = ($page - 1) * $limit; 			//first item to display on this page
	else
		$start = 0;								//if no page var is given, set start to 0
	
	/* Get data. */
//	$sql = "SELECT * FROM messages LIMIT $start, $limit";
//	$result = mysql_query($sql_count);
	
	/* Setup page vars for display. */
	if ($page == 0) $page = 1;					//if no page var is given, default to 1.
	$prev = $page - 1;							//previous page is page - 1
	$next = $page + 1;							//next page is page + 1
	$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
	$lpm1 = $lastpage - 1;						//last page minus 1
	/* 
		Now we apply our rules and draw the pagination object. 
		We're actually saving the code to a variable in case we want to draw it more than once.
	*/
	$pagination = "";
	if($lastpage > 1){	
		$pagination .= "<div class=\"pagination\">";
		//previous button
		if ($page > 1) 
			$pagination.= "<a href=\"$targetpage?page=$prev&friend=".$_GET['friend']."$addition_parameters\">&lt;&lt;</a>";
		else
			$pagination.= "<span class=\"disabled\">&lt;&lt;</span>";	
		
		//pages	
		if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<span class=\"current\">$counter</span>";
				else
					$pagination.= "<a href=\"$targetpage?page=$counter&friend=".$_GET['friend']."$addition_parameters\">$counter</a>";					
			}
		}elseif($lastpage > 5 + ($adjacents * 2)){//enough pages to hide some
			//close to beginning; only hide later pages
			if($page < 1 + ($adjacents * 2)){
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++){
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage?page=$counter&friend=".$_GET['friend']."$addition_parameters\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetpage?page=$lpm1&friend=".$_GET['friend']."$addition_parameters\">$lpm1</a>";
				$pagination.= "<a href=\"$targetpage?page=$lastpage&friend=".$_GET['friend']."$addition_parameters\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)){
				$pagination.= "<a href=\"$targetpage?page=1&friend=".$_GET['friend']."$addition_parameters\">1</a>";
				$pagination.= "<a href=\"$targetpage?page=2&friend=".$_GET['friend']."$addition_parameters\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage?page=$counter&friend=".$_GET['friend']."$addition_parameters\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetpage?page=$lpm1&friend=".$_GET['friend']."$addition_parameters\">$lpm1</a>";
				$pagination.= "<a href=\"$targetpage?page=$lastpage&friend=".$_GET['friend']."$addition_parameters\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else{
				$pagination.= "<a href=\"$targetpage?page=1&friend=".$_GET['friend']."$addition_parameters\">1</a>";
				$pagination.= "<a href=\"$targetpage?page=2&friend=".$_GET['friend']."$addition_parameters\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++){
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage?page=$counter&friend=".$_GET['friend']."$addition_parameters\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1) 
			$pagination.= "<a href=\"$targetpage?page=$next&friend=".$_GET['friend']."$addition_parameters\">&gt;&gt;</a>";
		else
			$pagination.= "<span class=\"disabled\">&gt;&gt;</span>";
		$pagination.= "</div>\n";		
	}
	
	function replace_d_smiley($str){
		$img_p1="<img class=\"img_smileys\" src=\"images/smiley/";
		$img_p2=".gif\" height=\"30\"  width=\"30\" />";
		
		$str=str_ireplace(array("<3","</3"),array($img_p1."65".$img_p2,$img_p1."66".$img_p2),$str);
		return $str;
	}
?>
<?php echo $pagination;?>
</ul>
</body>
</html>
<script>
$(document).ready(function(){
	$(".messages").each(function(index){
		var txt=$(this).html();
		var msg=txt.addSmileys();
		$(this).html(msg);
	});
});
</script>
