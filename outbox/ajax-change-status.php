<?php 
ob_clean();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();
if(isset($_REQUEST['choice']) &&$_REQUEST['choice']=='tech'){
	//select tech status from table
	$resStatus = $dbf->getDataFromTable("technicians","status","id='$_REQUEST[techid]'");
	if($resStatus ==1){
		$dbf->updateTable("technicians","status=0","id='$_REQUEST[techid]'");
	}elseif($resStatus ==0){
		$dbf->updateTable("technicians","status=1","id='$_REQUEST[techid]'");
	}
	$resStatus = $dbf->getDataFromTable("technicians","status","id='$_REQUEST[techid]'");
	if($resStatus ==1){
	?>
	<img src="images/green-circle.png" title="Active" alt="Status"/>
	<?php }elseif($resStatus ==0){?>
	<img src="images/red-circle.png" title="Inactive" alt="Status"/>
	<?php }?>
<?php
}elseif(isset($_REQUEST['choice']) &&$_REQUEST['choice']=='worktype'){
	//select tech status from table
	$resStatus = $dbf->getDataFromTable("work_type","status","id='$_REQUEST[wtypid]'");
	if($resStatus ==1){
		$dbf->updateTable("work_type","status=0","id='$_REQUEST[wtypid]'");
	}elseif($resStatus ==0){
		$dbf->updateTable("work_type","status=1","id='$_REQUEST[wtypid]'");
	}
	$resStatus = $dbf->getDataFromTable("work_type","status","id='$_REQUEST[wtypid]'");
	if($resStatus ==1){
	?>
	<img src="images/green-circle.png" title="Active" alt="Status"/>
	<?php }elseif($resStatus ==0){?>
	<img src="images/red-circle.png" title="Inactive" alt="Status"/>
<?php }
}elseif(isset($_REQUEST['choice']) &&$_REQUEST['choice']=='docsno'){
	$workorderdoc = $dbf->countRows("workorder_doc","workorder_id='$_REQUEST[woid]'");
	echo "(".$workorderdoc." "."Docs)";
}?>
	