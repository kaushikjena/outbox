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
	//Delete record from users Table
	if($_REQUEST['action']=='delete')
	{	
	    $dbf->deleteFromTable("client_offline_payment","id='$_REQUEST[id]'");
		header("Location:manage-receivable");exit;
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
	$('#srchBankName').val("");
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
                        <div class="headerbg"><div style="float:left;">Manage Receivables</div>
                        	<div style="float:right;"><input type="button" class="buttonText2" value="Add Receivable" onClick="javascript:window.location.href='add-receivable'"/></div>
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
                                        <?php foreach($dbf->fetch("clients","id")as $client){?>
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
                                    <div  class="formtextaddsrch"align="center">Bank Name</div>
                                    <div class="textboxcsrch">
                                    <input type="text" name="srchBankName" id="srchBankName" class="textboxsrch" value="<?php echo $_REQUEST['srchBankName'];?>"/>
                                    </div>
                                    <div  class="formtextaddsrchsmall"align="center">From:</div>
                                    <div class="textboxcsrchsmall">
                                    <input type="text" class="textboxsrch datepick" name="FromDate" id="FromDate" value="<?php echo $_REQUEST['FromDate'];?>" readonly></div>
                                    <div  class="formtextaddsrchsmall"align="center">To:</div>
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
								if($_REQUEST['srchBankName']!=''){
									$sch=$sch."cp.bank_name LIKE '%$_REQUEST[srchBankName]%' AND ";
								}
								if($_REQUEST['FromDate']!='' && $_REQUEST['ToDate']==''){
									$sch=$sch."cp.cheque_receive_date >= '$fromdt' AND ";
								}
								if($_REQUEST['FromDate']=='' && $_REQUEST['ToDate']!=''){
									$sch=$sch."cp.cheque_receive_date <= '$todt' AND ";
								}
							    if(($_REQUEST['FromDate']!='') && ($_REQUEST['ToDate']!='')){
									$sch=$sch."cp.cheque_receive_date BETWEEN '$fromdt' AND '$todt' AND ";
								}
							   $sch=substr($sch,0,-5);
							   //echo $sch;exit;
							   if($sch!=''){
								 $cond="c.state=st.state_code AND c.id=cp.client_id AND ".$sch;
								  // echo $cond;exit;
							   }
							   elseif($sch==''){
								 $cond="c.state=st.state_code AND c.id=cp.client_id";
							   }
							  ?>
                              <div class="table">
                                <div class="table-head">
                                    <div class="column" data-label="Customer Name" style="width:11%;">Customer Name</div>
                                    <div class="column" data-label="Customer Email" style="width:11%;">Customer Email</div>
                                    <div class="column" data-label="Phone No" style="width:10%;">Phone No</div>
                                    <div class="column" data-label="State" style="width:10%;">State</div>
                                    <div class="column" data-label="Cheque Number" style="width:11%;">Cheque Number</div>
                                    <div class="column" data-label="Receive Date" style="width:9%;">Receive Date</div>
                                    <div class="column" data-label="Cheque Amount" style="width:10%;">Cheque Amount</div>
                                    <div class="column" data-label="Bank Name"  style="width:10%;">Bank Name</div>
                                    <div class="column" data-label="Bank Address" style="width:12%;">Bank Address</div>
                                    <div class="column" data-label="Action"  style="width:6%;">Action</div>
                                </div>
                                <?php 
								$num=$dbf->countRows("state st,clients c,client_offline_payment cp",$cond); 
								foreach($dbf->fetchOrder("state st,clients c,client_offline_payment cp",$cond,"cp.id DESC","","")as $res_payment) {       
								?>
								<div class="row">
                                    <div class="column" data-label="Customer Name"><?php echo $res_payment['name'];?></div>
                                    <div class="column" data-label="Customer Email"><?php echo $res_payment['email'];?></div>
                                    <div class="column" data-label="Phone No"><?php echo $res_payment['phone_no'];?></div>
                                    <div class="column" data-label="State"><?php echo $res_payment['state_name'];?></div>
                                    <div class="column" data-label="Cheque Number"><?php echo $res_payment['cheque_no'];?></div>
                                    <div class="column" data-label="Receive Date"><?php echo date("d-M-Y",strtotime($res_payment['cheque_receive_date']));?></div>                           
                                    <div class="column" data-label="Cheque Amount">$ <?php echo $res_payment['cheque_amount'];?></div>
                                    <div class="column" data-label="Bank Name"><?php echo $res_payment['bank_name'];?></div>
                                    <div class="column" data-label="Bank Address"><?php echo $res_payment['bank_address'];?></div>
                                    <div class="column" data-label="Action"><a href="edit-receivable?id=<?php echo $res_payment['id']?>"><img src="images/edit.png" title="Edit" alt="Edit"/></a>&nbsp;&nbsp;<a href="manage-receivable?action=delete&id=<?php echo $res_payment['id'];?>" onClick="return confirm('Are you sure you want to delete this record ?')"><img src="images/delete.png" title="delete" alt="delete"></a></div>
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