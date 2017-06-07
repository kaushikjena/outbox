<?php
ob_start();
session_start();
include '../includes/class.Main.php';
$dbf = new User();
//Fetch details from work_order table 
$res_wo=$dbf->fetchSingle("work_order","id='$_REQUEST[id]'");
###########Insert Into work order notes table#############
if($_REQUEST['id']<>''){
	$techNotes="This is the #".$_REQUEST['attempt']." attempt to customer";
	$string="workorder_id='$_REQUEST[id]', user_type='$_SESSION[usertype]', user_id='$_SESSION[userid]', wo_notes='$techNotes',customer_attempt='$_REQUEST[attempt]',created_date=now()";
	$insid =$dbf->insertSet("workorder_notes",$string);
	if($insid){
		/********Admin Email************/
		$purchaseOrder=$dbf->getDataFromTable("work_order","purchase_order_no","id='$_REQUEST[id]'");
		//get client name
		$clientName =$dbf->getDataFromTable("clients","name","id='$res_wo[created_by]'");
		$clientName = $clientName ? $clientName :"COD";
		//get customer name
		$customerName = $dbf->getDataFromTable("clients","name","id='$res_wo[client_id]'");
		//get admin details
		$admin_email = $dbf->getDataFromTable("admin_email_notification","to_email","id=7");
		$res_admin=$dbf->fetchSingle("admin","id=1");
		$AdminName=$res_admin['name'];
		//$to=$res_admin['email'];
		$to=$admin_email;
		//get email template
		$admin_notification = $dbf->getDataFromTable("admin_email_notification","status","id=7");
		if($admin_notification==1){
			$res_template=$dbf->fetchSingle("email_template","id=19");
			$from=$res_template['from_email'];
			$fromname = $res_template['from_name'];
			$subject=$res_template['subject']." == ".$clientName." == ".$customerName;
			$input=$res_template['message'];
			//get technician details
			$tech=$dbf->fetchSingle("technicians","id='$_SESSION[userid]'");
			$TechName=$tech['first_name'].'&nbsp;'.$tech['middle_name'].'&nbsp;'.$tech['last_name'];
			$body =str_replace(array('%AdminName%','%TechName%','%CustomerName%','%WorkOrder%','%PurchaseOrder%','%ClientName%'),array($AdminName,$TechName,$customerName,$res_wo['wo_no'],$purchaseOrder,$clientName),$input);
			$headers = "MIME-Version: 1.0\n";
			$headers .= "Content-type: text/html; charset=UTF-8\n";
			$headers .= "From:".$fromname." <".$from.">\n";
			//echo $body;exit;
			@mail($to,$subject,$body,$headers);
		}
		/********Admin Email************/
	}
}
###########Insert Into work order notes table#############
//fetch notes from work order notes table
$resNotes=$dbf->fetchOrder("workorder_notes","workorder_id='$_REQUEST[id]' AND user_type='tech' AND customer_attempt <>0","created_date DESC");
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
	 <div align="right"># <?php echo $resn['customer_attempt'];?> attempt By <?php echo $uname;?> on <?php echo date("d-M-Y g:i A",strtotime($resn['created_date']));?> for #<?php echo $res_wo['wo_no'];?></div>
 </div><div class="spacer"></div>
<?php }?>