<?php 
	ob_start();
	session_start();
	include_once '../includes/class.Main.php';
	//Object initialization
	$dbf = new User();
	//page titlevariable
	$pageTitle="Welcome To Out Of The Box";
	include 'applicationtop-tech.php';
	//logout for users other than admin and user
	if($_SESSION['usertype']!='tech'){
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
	$('#srchCust').val("");
	$('#srchState').val("");
	$('#FromDate').val("");
	$('#ToDate').val("");
	document.SrchFrm.submit();
}
</script>
<body>
    <div id="maindiv">
        <!-------------header--------------->
     	<?php include_once 'header-tech.php';?>
   		<!-------------header--------------->
        
        <!-------------top menu--------------->
     	<?php include_once 'tech-top-menu.php';?>
   		<!-------------top menu--------------->
         <div id="contentdiv">
                <!-------------Main Body--------------->
                <div class="rightcolumjobboard">
            		<div class="rightcoluminner">
                        <div class="headerbg"><div style="float:left;">Technician Payment History</div>
                        	<div style="float:right;padding-right:10px;">
                            </div>
                        </div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        	<div style="width:100%;float:left;">
                            <form name="SrchFrm" id="SrchFrm" action="" method="post">
                              <div style="margin-bottom:5px;" align="center">
                              	 <div  class="formtextaddsrch" align="center">Customer</div>
                                    <div class="textboxcsrch">
                                    <select name="srchCust" id="srchCust" class="selectboxsrch" style="height:24px;">
                                        <option value="">--Select Customer--</option>
                                        <?php foreach($dbf->fetchOrder("work_order_tech_bill wb,clients cl"," wb.client_id=cl.id AND wb.payment_status='Completed'","cl.name ASC","","cl.name")as $customer){?>
                                        <option value="<?php echo $customer['id']?>" <?php if($customer['id']==$_REQUEST['srchCust']){echo 'selected';}?>><?php echo $customer['name'];?></option>
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
								
								if($_REQUEST['srchCust']!=''){
									$sch=$sch."c.id='$_REQUEST[srchCust]' AND ";
								}
								if($_REQUEST['srchState']!=''){
									$sch=$sch."c.state='$_REQUEST[srchState]' AND ";
								}
								if($_REQUEST['FromDate']!='' && $_REQUEST['ToDate']==''){
									$sch=$sch."wb.payment_date >= '$fromdt' AND ";
								}
								if($_REQUEST['FromDate']=='' && $_REQUEST['ToDate']!=''){
									$sch=$sch."wb.payment_date <= '$todt' AND ";
								}
							    if(($_REQUEST['FromDate']!='') && ($_REQUEST['ToDate']!='')){
									$sch=$sch."wb.payment_date BETWEEN '$fromdt' AND '$todt' AND ";
								}
							   $sch=substr($sch,0,-5);
							   //echo $sch;exit;
							   if($sch!=''){
								 $cond="wb.client_id=c.id AND wb.tech_id=t.id AND st.state_code=c.state AND wo.wo_no=wb.wo_no AND wo.service_id=s.id AND wb.tech_id='$_SESSION[userid]' AND ".$sch;
								  // echo $cond;exit;
							   }
							   elseif($sch==''){
								 $cond="wb.client_id=c.id AND wb.tech_id=t.id AND st.state_code=c.state AND wo.wo_no=wb.wo_no AND wo.service_id=s.id AND wb.tech_id='$_SESSION[userid]'";
							   }
							  ?>
                              <div class="table">
                                <div class="table-head">
                                	<div class="column" data-label="WoNo" style="width:10%;">WoNo</div>
                                    <div class="column" data-label="CustomerName" style="width:15%;">CustomerName</div>
                                    <div class="column" data-label="CustomerState" style="width:15%;">CustomerState</div>
                                    <div class="column" data-label="ServiceType" style="width:15%;">ServiceType</div>
                                    <div class="column" data-label="WorkStatus" style="width:10%;">OrderStatus</div>
                                    <div class="column" data-label="Subtotal" style="width:10%;">Subtotal</div>
                                    <div class="column" data-label="PaymentStatus" style="width:10%;">PaymentStatus</div>
                                    <div class="column" data-label="PaymentDate" style="width:10%;">PaymentDate</div>
                                    <div class="column" data-label="Action" style="width:5%;text-align:center;">Action</div>
                                </div>
                                <?php 
								$num=$dbf->countRows("state st,clients c,service s,technicians t,work_order wo,work_order_tech_bill wb",$cond); 
								foreach($dbf->fetchOrder("state st,clients c,service s,technicians t,work_order wo,work_order_tech_bill wb",$cond,"wb.id DESC","st.state_name,c.name,s.service_name,wo.work_status,wb.*","")as $techpayhistory){
								?>
								<div class="row">
                                	<div class="column" data-label="WoNo"><?php echo $techpayhistory['wo_no'];?></div>
                                    <div class="column" data-label="CustomerName"><?php echo $techpayhistory['name'];?></div>
                                    <div class="column" data-label="CustomerState"><?php echo $techpayhistory['state_name'];?></div>
                                    <div class="column" data-label="ServiceType"><?php echo $techpayhistory['service_name'];?></div>
                                    <div class="column" data-label="WorkStatus"><?php echo $techpayhistory['work_status'];?></div>
                                    <div class="column" data-label="Subtotal">$ <?php echo $techpayhistory['subtotal'];?></div>
                                    <div class="column" data-label="PaymentStatus"><?php echo $techpayhistory['payment_status'];?></div>
                                    <div class="column" data-label="PaymentDate"><?php if($techpayhistory['payment_date']<>'0000-00-00'){echo date("d-M-Y",strtotime($techpayhistory['payment_date']));}?></div>
                                    <div class="column" data-label="Action" style="text-align:center;"><a href="view-technician-payment-history?id=<?php echo $techpayhistory['id']?>"><img src="../images/view.png" title="View" alt="View"/></a></div>
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
        <?php include_once 'footer-tech.php'; ?>
    </div>
</body>
</html>