<?php
//include_once 'includes/class.Main.php';
//Object initialization
//$dbf = new Main();
extract($_POST);
$var = $message_id."====".$timestamp."====".$status."====".$credit_cost;
file_put_contents("textmagic.txt",$var);

$ExtractPost = $_POST;
$message_id = $ExtractPost['message_id'];
$timestamp = $ExtractPost['timestamp'];
$status = $ExtractPost['status'];
$credit_cost = $ExtractPost['credit_cost'];
###########INSERT INTO DELIVERY SMS HISTORY TABLE################
$string="message_id='$message_id',timestamp='$timestamp',status='$status',credit_cost='$credit_cost',created_date=NOW()"; 
//$ins_id=$dbf->insertSet("delivery_sms_history",$string);
###########INSERT INTO DELIVERY SMS HISTORY TABLE################
?>