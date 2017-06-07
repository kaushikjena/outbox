<?php
ob_start();
session_start();
include '../includes/class.Main.php';
$dbf = new User();
//Fetch details from work_order table 
$res_wono=$dbf->getDataFromTable("work_order","wo_no","id='$_REQUEST[id]'");
###########Insert Into work order notes table#############
if($_REQUEST['techNotes']<>''){
	$techNotes=mysql_real_escape_string($_REQUEST['techNotes']);
	$string4="workorder_id='$_REQUEST[id]', user_type='$_SESSION[usertype]', user_id='$_SESSION[userid]', wo_notes='$techNotes',read_status='1',created_date=now()";
	$dbf->insertSet("workorder_notes",$string4);
}
###########Insert Into work order notes table#############
//fetch notes from work order notes table
$resNotes=$dbf->fetchOrder("workorder_notes","workorder_id='$_REQUEST[id]' AND user_type='tech' AND customer_attempt=0 AND waiting_parts NOT IN(1,2)","created_date DESC");

foreach($resNotes as $resn){
  if($resn['user_type']=='tech'){
	 $unameTech = $dbf->fetchSingle("technicians","id='$resn[user_id]'");
	 $uname = $unameTech['first_name'].' '.$unameTech['middle_name'].' '.$unameTech['last_name'];
 }
?>
 <div class="textareaNoteView">
	 <div align="left"><?php echo $resn['wo_notes'];?></div>
	 <div class="spacer" style="border-bottom:dashed 1px #ccc;"></div>
	 <div align="right">By <?php echo $uname;?> on <?php echo date("d-M-Y g:i A",strtotime($resn['created_date']));?> for #<?php echo $res_wono;?></div>
 </div><div class="spacer"></div>
<?php }?>