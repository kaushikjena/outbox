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
	if($_REQUEST['action']=='status')
	{	
	    $string="payment_status='Completed',payment_date=now()";
	    $dbf->updateTable("work_order_tech_bill",$string,"id='$_REQUEST[id]'");
		header("Location:manage-technician-payments-history");exit;
	}
?>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />
<link rel="stylesheet" href="css/no_more_table.css" type="text/css" />
<script type="text/javascript">
function ClearFields(){
	$('#srchTech').val("");
	$('#srchState').val("");
	$('#FromDate').val("");
	$('#ToDate').val("");
	document.SrchFrm.submit();
}
/*********Function to expand and collapse group************/
function funHide(clss,id){
	//alert(id);
	$('.'+clss).hide();
	$('#e'+id).show();
	$('#c'+id).hide();
}
function funShow(clss,id){
	$('.'+clss).show();
	$('#c'+id).show();
	$('#e'+id).hide();
}
/*********Function to expand and collapse group************/
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
                        <div class="headerbg"><div style="float:left;">Manage Technician Payment History</div>
                        	<div style="float:right;padding-right:10px;">
                            </div>
                        </div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        	<div style="width:100%;float:left;">
                            <form name="SrchFrm" id="SrchFrm" action="" method="post">
                              <div style="margin-bottom:5px;" align="center">
                              	  <div  class="formtextaddsrch" align="center">Technician</div>
                                  <div class="textboxcsrch">
                                  <select name="srchTech" id="srchTech" class="selectboxsrch">
                                  		<option value="">--Select Tech--</option>
                                        <?php foreach($dbf->fetch("technicians","id>0 ORDER BY first_name ASC")as $tech){?>
                                        <option value="<?php echo $tech['id']?>" <?php if($tech['id']==$_REQUEST['srchTech']){echo 'selected';}?>><?php echo $tech['first_name'].' '.$tech['middle_name'].' '.$tech['last_name'];?></option>
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
								
								if($_REQUEST['srchTech']!=''){
									$sch=$sch."t.id='$_REQUEST[srchTech]' AND ";
								}
								if($_REQUEST['srchState']!=''){
									$sch=$sch."t.state='$_REQUEST[srchState]' AND ";
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
								 $cond="wb.tech_id=t.id AND st.state_code=t.state AND ".$sch;
								  // echo $cond;exit;
							   }
							   elseif($sch==''){
								 $cond="wb.tech_id=t.id AND st.state_code=t.state";
							   }
							  ?>
                             <!-----Table area start------->
                                <table id="no-more-tables">
                                    <thead>
                                        <tr>
                                            <th width="13%">TechnicianName</th>
                                            <th width="13%">TechnicianEmail</th>
                                            <th width="13%">TechnicianPhone</th>
                                            <th width="10%">TechnicianState</th>
                                            <th width="10%">WoNo</th>
                                            <th width="10%">Subtotal</th>
                                            <th width="10%">PaymentStatus</th>
                                            <th width="11%">PaymentDate</th>
                                            <th width="10%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                     <?php 
										$num=$dbf->countRows("state st,technicians t,work_order_tech_bill wb",$cond); 
										$resGrArray=$dbf->fetchOrder("state st,technicians t,work_order_tech_bill wb",$cond,"wb.tech_id ASC","","wb.tech_id");
										//group by state loop
										foreach($resGrArray as $k=>$sgRes){
										$Cls="g$k";	
									  ?>
										<tr style="background-color:#f9f9f9;">
                                            <td valign="top" class="grheading">
                                            <div class="divgr">
                                            <a href="javascript:void(0);" onClick="funShow('<?php echo $Cls;?>','<?php echo $k;?>');" id="e<?php echo $k;?>" <?php if($k==0){?>style="display:none;" <?php }?>><img  src="images/plus.gif" height="13" width="13"/>&nbsp;<span style="color:#ff9812;"><?php echo $sgRes['first_name'].' '.$sgRes['middle_name'].' '.$sgRes['last_name'];?></span> </a> 
                                			<a href="javascript:void(0);" onClick="funHide('<?php echo $Cls;?>','<?php echo $k;?>');" id="c<?php echo $k;?>" <?php if($k!=0){?>style="display:none;" <?php }?>><img  src="images/minus.gif" height="13" width="13"/>&nbsp;<span style="color:#ff9812;"><?php echo $sgRes['first_name'].' '.$sgRes['middle_name'].' '.$sgRes['last_name'];?></span></a>
                                            </div>
                                            </td>
                                            <td class="hiderow">&nbsp;</td>
                                            <td class="hiderow">&nbsp;</td>
                                            <td class="hiderow">&nbsp;</td>
                                            <td class="hiderow">&nbsp;</td>
                                            <td class="hiderow">&nbsp;</td>
                                            <td class="hiderow">&nbsp;</td>
                                            <td class="hiderow">&nbsp;</td>
                                            <td class="hiderow">&nbsp;</td>
                                        </tr>
                                        <?php 
											$resArray=$dbf->fetchOrder("state st,technicians t,work_order_tech_bill wb","wb.tech_id='$sgRes[tech_id]' AND " .$cond,"wb.id DESC","","");
											foreach($resArray as $key=>$techpayhistory) { 
															
										?>   
                                    	<tr class="<?php echo $Cls;?>" <?php if($k!=0){?> style="display:none;" <?php } ?>>
                                        	
                                            <td data-title="TechnicianName" class="coltext"><?php echo $techpayhistory['first_name'].' '.$techpayhistory['middle_name'].' '.$techpayhistory['last_name'];?></td>
                                            <td data-title="TechnicianEmail"><?php echo $techpayhistory['email'];?></td>
                                            <td data-title="TechnicianPhone"><?php echo $techpayhistory['contact_phone'];?></td>
                                            <td data-title="TechnicianState"><?php echo $techpayhistory['state_name'];?></td>
                                            <td data-title="WoNo"><?php echo $techpayhistory['wo_no'];?></td>
                                            <td data-title="Subtotal"><?php echo $techpayhistory['subtotal'];?></td>
                                            <td data-title="PaymentStatus"><b><?php echo $techpayhistory['payment_status'];?></b></td>
                                            <td data-title="PaymentDate" ><?php if($techpayhistory['payment_date']<>'0000-00-00'){echo date("d-M-Y",strtotime($techpayhistory['payment_date']));}?></td>
                                            <td data-title="Action" class="coltext"><a href="manage-technician-payments-history?action=status&id=<?php echo $techpayhistory['id']?>" onClick="return confirm('Are you sure the payment status is completed?')">Change Status</a></td>
                                            
                                        </tr>
                                         <?php } 
											}
										?> 
                                    </tbody>
                               </table>
                              <!-----Table area start-------> 
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