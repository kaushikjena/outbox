<?php
ob_start();
session_start();
include 'includes/class.Main.php';
$dbf = new User();
//Fetch details from work_order table 
$res_wo=$dbf->fetchSingle("work_order","id='$_REQUEST[id]'");
###########Insert Into work order notes table#############
if($_REQUEST['id']<>''){
	$techNotes="This is the #".$_REQUEST['attempt']." attempt to customer";
	$string="workorder_id='$_REQUEST[id]', user_type='$_SESSION[usertype]', user_id='$_SESSION[userid]', wo_notes='$techNotes',customer_attempt='$_REQUEST[attempt]',created_date=now()";
	$dbf->insertSet("workorder_notes",$string);
}
###########Insert Into work order notes table#############
//fetch notes from work order notes table
$resNotes=$dbf->fetchOrder("workorder_notes","workorder_id='$_REQUEST[id]' AND (user_type='admin' OR user_type='user') AND customer_attempt <>0","created_date DESC");
$attempt = count($resNotes)+1;
?>
<input type="hidden" name="attempt" id="attempt" value="<?php echo $attempt;?>"/>
<?php 
foreach($resNotes as $resn){
  if($resn['user_type']=='tech'){
	 $unameTech = $dbf->fetchSingle("technicians","id='$resn[user_id]'");
	 $uname = $unameTech['first_name'].' '.$unameTech['middle_name'].' '.$unameTech['last_name'];
 }
?>
 <div class="textareaNoteView">
	 <div align="left"><?php echo $resn['wo_notes'];?></div>
	 <div class="spacer" style="border-bottom:dashed 1px #ccc;"></div>
	 <div align="right"># <?php echo $resn['customer_attempt'];?> attempt By <?php echo "Admin";?> on <?php echo date("d-M-Y g:i A",strtotime($resn['created_date']));?> for #<?php echo $res_wo['wo_no'];?></div>
 </div><div class="spacer"></div>
<?php }?>