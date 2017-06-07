<?php 
	ob_start();
	session_start();
	include_once '../includes/class.Main.php';
	//Object initialization
	$dbf = new User();
	//page titlevariable
	$pageTitle="Welcome To Out Of The Box";
	include 'applicationtop-client.php';
	//logout if user type is not client
	if($_SESSION['usertype']!='client'){
		header("location:../logout");exit;
	}
?>
<link rel="stylesheet" href="../css/innermain.css" type="text/css" />
<link rel="stylesheet" href="../css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="../css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="../css/respmenu.css" type="text/css" />
<link rel="stylesheet" href="../css/tablejob.css" type="text/css" />
<script type="text/javascript">
function ClearFields(){
	$('#srchClient').val("");
	$('#srchState').val("");
	$('#srchBankName').val("");
	$('#FromDate').val("");
	$('#ToDate').val("");
	document.SrchFrm.submit();
}
</script>
<body>
    <div id="maindiv">
        <!-------------header--------------->
     	<?php include_once 'header-client.php';?>
   		<!-------------header--------------->
        
        <!-------------top menu--------------->
     	<?php include_once 'client-top-menu.php';?>
   		<!-------------top menu--------------->
         <div id="contentdiv">
                <!-------------Main Body--------------->
                <div class="rightcolumjobboard">
            		<div class="rightcoluminner">
                        <div class="headerbg"><div style="float:left;">Work Order Bills</div>
                        	<div style="float:right;padding-right:10px;"></div>
                        </div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        	<div style="width:100%;float:left;">
                              <div class="spacer"></div>
                              <?php
							  //condition for fetching work order bill to pay
								$cond="c.id=w.client_id AND w.service_id=s.id  AND w.wo_no=wb.wo_no AND wb.payment_status='Pending' AND w.approve_status='1' AND w.work_status='Completed' AND w.created_by='$_SESSION[userid]' AND at.wo_no=w.wo_no AND at.tech_id=t.id";
							  ?>
                              <div class="table">
                                <div class="table-head">
                                    <div class="column" data-label="DateScheduled" style="width:8%;">DateScheduled</div>
                                    <div class="column" data-label="ServiceName" style="width:13%;">ServiceName</div>
                                    <div class="column" data-label="WorkType" style="width:15%;">WorkType</div>
                                    <div class="column" data-label="Model" style="width:13%;">Model</div>
                                    <div class="column" data-label="CustomerName" style="width:15%;">CustomerName</div>
                                    <div class="column" data-label="Wo#" style="width:8%;">Wo#</div>
                                    <div class="column" data-label="PurchaseOrder#" style="width:11%;">PurchaseOrder#</div>
                                    <div class="column" data-label="Amount" style="width:10%;">Amount</div>
                                    <div class="column" data-label="Action"  style="width:7%;">ViewBills</div>
                                </div>
                                <?php
								$grandtotal=0; $_SESSION['WorkOrder']=array();
								$num=$dbf->countRows("clients c,service s,technicians t,assign_tech at,work_order_bill wb,work_order w",$cond); 
								foreach($dbf->fetchOrder("clients c,service s,technicians t,assign_tech at,work_order_bill wb,work_order w",$cond,"w.id DESC","s.service_name, at.start_date, c.name, wb.subtotal, w.wo_no, w.purchase_order_no, w.service_id, w.id","")as $res_JobBoard){ 
								array_push($_SESSION['WorkOrder'],$res_JobBoard['wo_no']);      
								//get work order Amount
								$grandtotal=$grandtotal+$res_JobBoard['subtotal'];
								//fetch work type, model and total price of work order
								$workTypeArray =array(); $modelArray =array();
								$res_woservice = $dbf->fetch("equipment e,work_type wt,workorder_service ws","e.id=ws.equipment AND wt.id=ws.work_type AND ws.service_id='$res_JobBoard[service_id]' AND ws.workorder_id='$res_JobBoard[id]'");
								foreach($res_woservice as $resServicePrice){
									array_push($workTypeArray,$resServicePrice['worktype']);
									array_push($modelArray,$resServicePrice['model']);
								}
								$workType= !empty($workTypeArray) ? implode(", ",$workTypeArray):'';
								$model = !empty($modelArray) ? implode(", ",$modelArray):'';
								?>
								<div class="row">
                                    <div class="column" data-label="DateScheduled"><?php echo date("d-M-Y",strtotime($res_JobBoard['start_date']));?></div>
                                    <div class="column" data-label="ServiceName"><?php echo $res_JobBoard['service_name'];?></div>
                                    <div class="column" data-label="WorkType"><?php echo $workType;?></div>
                                    <div class="column" data-label="Model"><?php echo $model;?></div>
                                    <div class="column" data-label="CustomerName"><?php echo $res_JobBoard['name'];?></div>
                                    <div class="column" data-label="Wo#">
                                    <a href="client-view-job-board?id=<?php echo $res_JobBoard['id'];?>&src=bill" title="Click Here For Job Details"><?php echo $res_JobBoard['wo_no'];?></a></div>
                                    <div class="column" data-label="PurchaseOrder#"><?php echo $res_JobBoard['purchase_order_no'];?></div>
                                    <div class="column" data-label="TechName">$ <?php echo $res_JobBoard['subtotal'];?></div>
                                    <div class="column" data-label="Action"><a href="client-view-billings?id=<?php echo $res_JobBoard['id']?>">View Bill</a></div>
                               </div>
                              <?php }?>
                        	</div>
                            <?php if($num <> 0){?>
                            <form name="frmBill" action="client-checkout" method="post">
                            <div align="right" class="redText" style="margin-top:10px;">
                            <input type="hidden" name="amount" value="<?php echo $grandtotal?>"/>
                            <div class="ClSubtotal">Subtotal&nbsp;&nbsp;:&nbsp;&nbsp;$ <?php echo number_format($grandtotal,2);?></div>
                            <div class="blSubtotalPrice"><input type="submit"class="buttonText"value="Checkout Now"/></div>
                            </div>
                            </form>
                            <?php }if($num == 0){?><div class="noRecords" align="center">No records founds!!</div><?php }?>
                          </div>
                        </div>
                        <div class="spacer"></div>
                    </div>
            	</div>
              <!-------------Main Body--------------->
         </div>
        <div class="spacer"></div>
        <?php include_once 'footer-client.php'; ?>
    </div>
</body>
</html>