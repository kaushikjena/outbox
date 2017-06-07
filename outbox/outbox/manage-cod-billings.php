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
	$('#srchCust').val("");
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
                <!-------------Main Body--------------->
                <div class="rightcolumjobboard">
            		<div class="rightcoluminner">
                        <div class="headerbg"><div style="float:left;">Manage COD Billings</div>
                        	<div style="float:right;padding-right:10px;"></div>
                        </div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        	<div style="width:100%;float:left;">
                            <form name="SrchFrm" id="SrchFrm" action="" method="post">
                              <div style="margin-bottom:5px;" align="center">
                                  <div  class="formtextaddsrch" align="center">Customer</div>
                                  <div class="textboxcsrch">
                                    <select name="srchCust" id="srchCust" class="selectboxsrch">
                                        <option value="">--Select Customer--</option>
                                        <?php foreach($dbf->fetchOrder("work_order wo,clients cl","wo.client_id=cl.id AND  wo.approve_status='1' AND wo.work_status='Completed'","cl.name ASC","","cl.name")as $customer){?>
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
									$sch=$sch."w.created_date >= '$fromdt' AND ";
								}
								if($_REQUEST['FromDate']=='' && $_REQUEST['ToDate']!=''){
									$sch=$sch."w.created_date <= '$todt' AND ";
								}
							    if(($_REQUEST['FromDate']!='') && ($_REQUEST['ToDate']!='')){
									$sch=$sch."w.created_date BETWEEN '$fromdt' AND '$todt' AND ";
								}
							   $sch=substr($sch,0,-5);
							   //echo $sch;exit;
							   if($sch!=''){
								 $cond="c.state=st.state_code AND c.id=w.client_id AND w.service_id=s.id AND  w.approve_status='1' AND w.work_status='Completed' AND w.created_by=0 AND ".$sch;
								  // echo $cond;exit;
							   }
							   elseif($sch==''){
								 $cond="c.state=st.state_code AND c.id=w.client_id AND w.service_id=s.id AND  w.approve_status='1' AND w.work_status='Completed' AND w.created_by=0";
							   }
							  ?>
                              <div class="table">
                                <div class="table-head">
                                	<div class="column" data-label="WoNo" style="width:8%;">WoNo</div>
                                    <div class="column" data-label="WorkStatus" style="width:8%;">OrderStatus</div>
                                    <div class="column" data-label="ServiceName" style="width:12%;">ServiceName</div>
                                    <div class="column" data-label="CustomerName" style="width:12%;">CustomerName</div>
                                    <div class="column" data-label="CustomerEmail" style="width:12%;">CustomerEmail</div>
                                    <div class="column" data-label="CreatedDate" style="width:10%;">Created Date</div>
                                    <div class="column" data-label="State" style="width:10%;">State</div>
                                    <div class="column" data-label="Clients" style="width:8%;">Clients</div>
                                    <div class="column" data-label="TechName" style="width:13%;">TechName</div>
                                    <div class="column" data-label="Action"  style="width:7%;">GenerateBills</div>
                                </div>
                                <?php
								$c=0;
								//count total completed records 
								$num=$dbf->countRows("state st,clients c,service s,work_order w",$cond); 
								foreach($dbf->fetchOrder("state st,clients c,service s,work_order w",$cond,"w.id DESC","","")as $res_JobBoard) {       
								//get technician name
								$techname =$dbf->fetchSingle("assign_tech at,technicians t","t.id=at.tech_id AND at.wo_no='$res_JobBoard[wo_no]'");
								//get client name
								if($res_JobBoard['created_by']<>0){
									$clientname =$dbf->getDataFromTable("clients","name","id='$res_JobBoard[created_by]'");
								}else{
									$clientname="COD";
								}
								//check for payment completed work orders
								$paymentstatus = $dbf->getDataFromTable("work_order_bill","payment_status","wo_no='$res_JobBoard[wo_no]'");
								if($paymentstatus<>'Completed'){
									$color="#090";
								}else{
									$color="#0FCBFF";
								}
								if($paymentstatus<>'Completed'){
								?>
								<div class="row">
                                    <div class="column" data-label="WoNo">
                                    <a href="view-job-board?id=<?php echo $res_JobBoard['id'];?>&src=cod" title="Click Here For Job Details" style="color:<?php echo $color;?>"><?php echo $res_JobBoard['wo_no'];?></a></div>
                                    <div class="column" data-label="WorkStatus" style="font-weight:bold;"><?php echo $res_JobBoard['work_status'];?></div>
                                    <div class="column" data-label="ServiceName"><?php echo $res_JobBoard['service_name'];?></div>
                                    <div class="column" data-label="CustomerName"><b><?php echo $res_JobBoard['name'];?></b></div>
                                    <div class="column" data-label="CustomerEmail"><?php echo $res_JobBoard['email'];?></div>
                                    <div class="column" data-label="CreatedDate"><?php echo date("d-M-Y",strtotime($res_JobBoard['created_date']));?></div>
                                    <div class="column" data-label="State"><?php echo $res_JobBoard['state_name'];?></div>
                                    <div class="column" data-label="Clients"><b><?php echo $clientname;?></b></div>
                                    <div class="column" data-label="TechName"><?php echo $techname['first_name'].' '.$techname['middle_name'].' '.$techname['last_name'];?></div>
                                    <div class="column" data-label="Action" align="center"><a href="admin-cod-billings?id=<?php echo $res_JobBoard['id']?>">Generate</a>
                                    </div>
                               </div>
                               <?php $c++;}
								  }
							   ?>
                        	</div>
                            <?php if($num == 0 || $c==0){?><div class="noRecords" align="center">No records founds!!</div><?php }?>
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