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
                        <div class="headerbg"><div style="float:left;">Payment History</div>
                        	<div style="float:right;padding-right:10px;"></div>
                        </div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        	<div style="width:100%;float:left;">
                            <form name="SrchFrm" id="SrchFrm" action="" method="post">
                              <div style="margin-bottom:5px;" align="center">
                                    <div  class="formtextaddsrch"align="center">From:</div>
                                    <div class="textboxcsrch">
                                    <input type="text" class="textboxsrch datepick" name="FromDate" id="FromDate" value="<?php echo $_REQUEST['FromDate'];?>" readonly></div>
                                    <div  class="formtextaddsrch"align="center">To:</div>
                                    <div class="textboxcsrch">
                                    <input type="text" class="textboxsrch datepick" name="ToDate" id="ToDate" value="<?php echo $_REQUEST['ToDate'];?>" readonly></div>
                                    <div>
                                    <input type="submit" class="buttonText2" name="SearchRecord" value="Filter Records">
                                    <input type="button" class="buttonText2" name="Reset" value="Reset Filter" onClick="ClearFields();">
                                   </div>
                                  </div>
                              </form>
                              <div class="spacer"></div>
                              <?php
							   	$sch="";
								$fromdt=date("Y-m-d",strtotime(($_REQUEST['FromDate'])));
								$todt=date("Y-m-d",strtotime(($_REQUEST['ToDate'])));
								
								if($_REQUEST['FromDate']!='' && $_REQUEST['ToDate']==''){
									$sch=$sch."payment_date >= '$fromdt' AND ";
								}
								if($_REQUEST['FromDate']=='' && $_REQUEST['ToDate']!=''){
									$sch=$sch."payment_date <= '$todt' AND ";
								}
							    if(($_REQUEST['FromDate']!='') && ($_REQUEST['ToDate']!='')){
									$sch=$sch."payment_date BETWEEN '$fromdt' AND '$todt' AND ";
								}
							   $sch=substr($sch,0,-5);
							   //echo $sch;exit;
							   if($sch!=''){
								 $cond="client_id='$_SESSION[userid]' AND ".$sch;
								  // echo $cond;exit;
							   }
							   elseif($sch==''){
								 $cond="client_id='$_SESSION[userid]'";
							   }
							  ?>
                              <div class="table">
                                <div class="table-head">
                                	<div class="column" data-label="Transaction ID" style="width:30%;">Transaction ID</div>
                                    <div class="column" data-label="Transaction Amt" style="width:20%;">Transaction Amt</div>
                                    <div class="column" data-label="Payment Status" style="width:20%;">Payment Status</div>
                                    <div class="column" data-label="Payment Date" style="width:20%;">Payment Date</div>
                                    <div class="column" data-label="Action"  style="width:10%;" align="center">Action</div>
                                </div>
                                <?php 
								$num=$dbf->countRows("client_payment_history",$cond); 
								foreach($dbf->fetchOrder("client_payment_history",$cond,"id DESC","","")as $resPayment) {
								?>
								<div class="row">
                                    <div class="column" data-label="Transaction ID"><?php echo $resPayment['transaction_id'];?></div>
                                    <div class="column" data-label="Transaction Amt">$ <?php echo $resPayment['transaction_amount'];?></div>
                                    <div class="column" data-label="Payment Status"><?php echo $resPayment['payment_status'];?></div>
                                    <div class="column" data-label="Payment Date"><?php echo date("d-M-Y",strtotime($resPayment['payment_date']));?></div>
                                    
                                    <div class="column" data-label="Action" align="center"><a href="view-client-payment-history?id=<?php echo $resPayment['id'];?>"><img src="../images/view.png" name="view" alt="View" title="View Payments"/></a></div>
                               </div>
                               <?php }?>
                        	</div>
                            <?php if($num == 0){?><div class="noRecords" align="center">No records founds!!</div><?php }?>
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