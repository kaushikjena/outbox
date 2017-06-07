<?php
ob_start();
session_start();
include '../includes/class.Main.php';
$dbf = new User();
//Fetch details from work_order table 
$res_wono=$dbf->getDataFromTable("work_order","wo_no","id='$_REQUEST[id]'");
###########Insert Into work order notes table#############
if($_REQUEST['adminNotes']<>''){
	$adminNotes=mysql_real_escape_string($_REQUEST['adminNotes']);
	$string4="workorder_id='$_REQUEST[id]', user_type='$_SESSION[usertype]', user_id='$_SESSION[userid]', wo_notes='$adminNotes',created_date=now()";
	$dbf->insertSet("workorder_notes",$string4);
}
###########Insert Into work order notes table#############
//fetch notes from work order notes table
$resNotes=$dbf->fetchOrder("workorder_notes","workorder_id='$_REQUEST[id]' AND (user_type='admin' OR user_type='user' OR user_type='client')","created_date DESC");

foreach($resNotes as $resn){
 if($resn['user_type']=='admin'){
	 $uname = $dbf->getDataFromTable("admin","name","id='$resn[user_id]'");
 }elseif($resn['user_type']=='user'){
	  $uname = $dbf->getDataFromTable("users","name","id='$resn[user_id]'");
 }elseif($resn['user_type']=='client'){
	  $uname = $dbf->getDataFromTable("clients","name","id='$resn[user_id]'");
 }
?>
 <div class="textareaNoteView">
	 <div align="left"><?php echo $resn['wo_notes'];?></div>
	 <div class="spacer" style="border-bottom:dashed 1px #ccc;"></div>
	 <div align="right">By <?php echo $uname;?> on <?php echo date("d-M-Y g:i A",strtotime($resn['created_date']));?> for #<?php echo $res_wono;?></div>
 </div><div class="spacer"></div>
<?php }?>