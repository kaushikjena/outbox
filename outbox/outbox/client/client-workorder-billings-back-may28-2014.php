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
								$cond="c.state=st.state_code AND c.id=w.client_id AND w.service_id=s.id  AND w.wo_no=wb.wo_no AND wb.payment_status='Pending' AND w.job_status='Assigned' AND w.approve_status='1' AND w.work_status='Completed' AND w.created_by='$_SESSION[userid]'";
							  ?>
                              <div class="table">
                                <div class="table-head">
                                	<div class="column" data-label="WoNo" style="width:8%;">WoNo</div>
                                    <div class="column" data-label="WorkStatus" style="width:8%;">WorkStatus</div>
                                    <div class="column" data-label="ServiceName" style="width:13%;">ServiceName</div>
                                    <div class="column" data-label="CustomerName" style="width:15%;">CustomerName</div>
                                    <div class="column" data-label="CustomerEmail" style="width:15%;">CustomerEmail</div>
                                    <div class="column" data-label="PhoneNo" style="width:11%;">PhoneNo</div>
                                    <div class="column" data-label="State" style="width:11%;">State</div>
                                    <div class="column" data-label="TechName" style="width:12%;">Amount</div>
                                    <div class="column" data-label="Action"  style="width:7%;">ViewBills</div>
                                </div>
                                <?php
								$grandtotal=0; $_SESSION['WorkOrder']=array();
								$num=$dbf->countRows("state st,clients c,service s,work_order_bill wb,work_order w",$cond); 
								foreach($dbf->fetchOrder("state st,clients c,service s,work_order_bill wb,work_order w",$cond,"w.id DESC","","")as $res_JobBoard) { 
								array_push($_SESSION['WorkOrder'],$res_JobBoard['wo_no']);      
								//get work order Amount
								$grandtotal=$grandtotal+$res_JobBoard['subtotal'];
								?>
								<div class="row">
                                    <div class="column" data-label="WoNo">
                                    <a href="client-view-job-board?id=<?php echo $res_JobBoard['id'];?>&src=bill" title="Click Here For Job Details"><?php echo $res_JobBoard['wo_no'];?></a></div>
                                    <div class="column" data-label="WorkStatus" style="color:#090;font-weight:bold;"><?php echo $res_JobBoard['work_status'];?></div>
                                    <div class="column" data-label="ServiceName"><?php echo $res_JobBoard['service_name'];?></div>
                                    <div class="column" data-label="CustomerName"><?php echo $res_JobBoard['name'];?></div>
                                    <div class="column" data-label="CustomerEmail"><?php echo $res_JobBoard['email'];?></div>
                                    <div class="column" data-label="PhoneNo"><?php echo $res_JobBoard['phone_no'];?></div>
                                    <div class="column" data-label="State"><?php echo $res_JobBoard['state_name'];?></div>
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