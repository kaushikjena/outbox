<?php 
	ob_start();
	session_start();
	include_once 'includes/class.Main.php';
	//Object initialization
	$dbf = new User();
	//page titlevariable
	$pageTitle="Welcome To Out Of The Box";
	include 'applicationtop.php';
	//logout for users other than admin and user
	if($_SESSION['usertype']!='admin' && $_SESSION['usertype']!='user'){
		header("location:logout");exit;
	}
?>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />
<link rel="stylesheet" href="css/tablejob.css" type="text/css" />
<script type="text/javascript">
function ClearFields(){
	$('#srchClient').val("");
	$('#srchState').val("");
	$('#FromDate').val("");
	$('#ToDate').val("");
	document.SrchFrm.submit();
}
</script>
<body>
    <div id="maindiv">
        <!-------------header--------------->
     	<?php include_once 'header.php';?>
   		<!-------------header--------------->
        
        <!-------------top menu--------------->
     	<?php include_once 'top-menu.php';?>
   		<!-------------top menu--------------->
         <div id="contentdiv">
                <!-------------Left menu--------------->
				<?php //include_once 'left-menu.php';?>
                <!-------------left menu--------------->
                <!-------------Main Body--------------->
                <div class="rightcolumjobboard">
            		<div class="rightcoluminner">
                        <div class="headerbg"><div style="float:left;">Manage Client Payments History</div>
                        	<div style="float:right;padding-right:10px;">
                            </div>
                        </div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        	<div style="width:100%;float:left;">
                            <form name="SrchFrm" id="SrchFrm" action="" method="post">
                              <div style="margin-bottom:5px;" align="center">
                              	  <div  class="formtextaddsrch" align="center">Client</div>
                                  <div class="textboxcsrch">
                                   <select name="srchClient" id="srchClient" class="selectboxsrch">
                                  		<option value="">--Select Client--</option>
                                        <?php foreach($dbf->fetch("client_payment_history cp,clients c","cp.client_id=c.id GROUP BY c.id ORDER BY c.name ASC")as $client){?>
                                        <option value="<?php echo $client['id']?>" <?php if($client['id']==$_REQUEST['srchClient']){echo 'selected';}?>><?php echo $client['name'];?></option>
                                        <?php }?>
                                   </select>
                                    </div>
                                    <div  class="formtextaddsrch" align="center">State</div>
                                    <div class="textboxcsrch">
                                    <select name="srchState" id="srchState" class="selectboxsrch">
                                    	<option value="">--Select State--</option>
                                        <?php foreach($dbf->fetch("state","id>0 ORDER BY state_code ASC")as $srcState){?>
                                        <option value="<?php echo $srcState['state_code'];?>" <?php if($srcState['state_code']==$_REQUEST['srchState']){echo 'selected';}?>><?php echo $srcState['state_name']?></option>
                                        <?php }?>
                                    </select>
                                    </div>
                                    <div  class="formtextaddsrch"align="center">From:</div>
                                    <div class="textboxcsrchsmall">
                                    <input type="text" class="textboxsrch datepick" name="FromDate" id="FromDate" value="<?php echo $_REQUEST['FromDate'];?>" readonly></div>
                                    <div  class="formtextaddsrch"align="center">To:</div>
                                    <div class="textboxcsrchsmall">
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
								
								if($_REQUEST['srchClient']!=''){
									$sch=$sch."c.id='$_REQUEST[srchClient]' AND ";
								}
								if($_REQUEST['srchState']!=''){
									$sch=$sch."c.state='$_REQUEST[srchState]' AND ";
								}
								if($_REQUEST['FromDate']!='' && $_REQUEST['ToDate']==''){
									$sch=$sch."cp.payment_date >= '$fromdt' AND ";
								}
								if($_REQUEST['FromDate']=='' && $_REQUEST['ToDate']!=''){
									$sch=$sch."cp.payment_date <= '$todt' AND ";
								}
							    if(($_REQUEST['FromDate']!='') && ($_REQUEST['ToDate']!='')){
									$sch=$sch."cp.payment_date BETWEEN '$fromdt' AND '$todt' AND ";
								}
							   $sch=substr($sch,0,-5);
							   //echo $sch;exit;
							   if($sch!=''){
								 $cond="c.id=cp.client_id AND st.state_code=c.state AND ".$sch;
								  // echo $cond;exit;
							   }
							   elseif($sch==''){
								 $cond="c.id=cp.client_id AND st.state_code=c.state";
							   }
							  ?>
                              <div class="table">
                                <div class="table-head">
                                    <div class="column" data-label="ClientName" style="width:14%;">Client Name</div>
                                    <div class="column" data-label="ClientEmail" style="width:15%;">Client Email</div>
                                    <div class="column" data-label="ClientState" style="width:11%;">Client State</div>
                                    <div class="column" data-label="TransactionId" style="width:15%;">Transaction Id</div>
                                    <div class="column" data-label="TransactionAmount"style="width:15%;">Transaction Amt</div>
                                    <div class="column" data-label="PaymentStatus" style="width:10%;">Payment Status</div>
                                    <div class="column" data-label="PaymentDate" style="width:10%;">Payment Date</div>
                                    <div class="column" data-label="Action"  style="width:10%;text-align:center;">Action</div>
                                </div>
                                <?php 
								$num=$dbf->countRows("state st,clients c,client_payment_history cp",$cond); 
								foreach($dbf->fetchOrder("state st,clients c,client_payment_history cp",$cond,"cp.id DESC","","")as $clntpayhistory){
								?>
								<div class="row">
                                    <div class="column" data-label="ClientName"><?php echo $clntpayhistory['name'];?></div>
                                    <div class="column" data-label="ClientEmail"><?php echo $clntpayhistory['email'];?></div>
                                    <div class="column" data-label="ClientState"><?php echo $clntpayhistory['state_name'];?></div>
                                    <div class="column" data-label="TransactionId"><?php echo $clntpayhistory['transaction_id'];?></div>
                                    <div class="column" data-label="TransactionAmount">$ <?php echo $clntpayhistory['transaction_amount'];?></div>
                                    <div class="column" data-label="PaymentStatus"><?php echo $clntpayhistory['payment_status'];?></div>
                                    <div class="column" data-label="PaymentDate"><?php echo date("d-M-Y",strtotime($clntpayhistory['payment_date']));?></div>
                                    <div class="column" data-label="Action" style="text-align:center;"><a href="view-client-payments-history?id=<?php echo $clntpayhistory['id'];?>"><img src="images/view.png" title="View" alt="View"/></a></div>
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
        <?php include_once 'footer.php'; ?>
    </div>
</body>
</html>