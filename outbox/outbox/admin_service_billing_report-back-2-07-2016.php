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
	$x = 0;
	if($_REQUEST['action']=='search' || $_GET["page"]){
	   $x=1; 
	}
	$srchClientArr = $_REQUEST['srchClient']? $_REQUEST['srchClient']:array();
?>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />
<link rel="stylesheet" href="css/no_more_table.css" type="text/css" />
<script type="text/javascript">
function Search_Records(){
	$("#SrchFrm").attr("action","admin_service_billing_report");
	$("#SrchFrm").submit();
}
function ClearFields(){
	$('#srchCust').val("");
	$('#srchClient').val("");
	$('#srchTechnician').val("");
	$('#srchService').val("");
	$('#FromDate').val("");
	$('#ToDate').val("");
	$('#hidaction').val("");
	//document.SrchFrm.submit();
	/*below line added to refreash page as to prevent url 
	mismatch problem in search using pagination.*/
	window.location.href="admin_service_billing_report";
}
//for exporting,print,pdf,word
function print_doc(val,page){
 if(val=='word'){
	$("#SrchFrm").attr("action","admin_service_billing_report_word");
	$("#SrchFrm").submit();
 }else if(val=='excell'){
	$("#SrchFrm").attr("action","admin_service_billing_report_excell");
	$("#SrchFrm").submit();
 }else if(val=='pdf'){
	$("#SrchFrm").attr("action","admin_service_billing_report_pdf");
	$("#SrchFrm").submit(); 
 }else if(val=='print'){
	$("#SrchFrm").attr("action","admin_service_billing_report_print?page="+page);
	$("#SrchFrm").attr("target","_blank");
	$("#SrchFrm").submit(); 
 }
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
                        <div class="headerbg">
                            <div style="float:left;;">Total Service Payment Report</div>
                            <div style="float:right;">
                            <a href="javascript:void(0);" onClick="print_doc('word');"><img src="images/word2007.png" style="width:20px; height:20px;" title="Export to Word"/></a>
                            <a href="javascript:void(0);" onClick="print_doc('pdf');"><img src="images/pdf.png" style="width:20px; height:20px;" title="Export to PDF"/></a>
                            <a href="javascript:void(0);" onClick="print_doc('excell');"><img src="images/export_excel.png" style="width:20px; height:20px;" title="Export to Excel"></a>
                            <a href="javascript:void(0);"  onClick="print_doc('print','<?php echo (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);?>');" ><img src="images/print.png" alt="" style="width:20px; height:20px;" title="Print"></a>
                            </div>
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
                                    <?php foreach($dbf->fetchOrder("work_order_bill wb,clients cl"," wb.client_id=cl.id AND wb.payment_status='Completed'","cl.name ASC","","cl.name")as $customer){?>
                                    <option value="<?php echo $customer['id']?>" <?php if($customer['id']==$_REQUEST['srchCust']){echo 'selected';}?>><?php echo $customer['name'];?></option>
                                    <?php }?>
                                  </select>
                               </div>
                               <div  class="formtextaddsrchsmall" align="center">Client</div>
                                <div class="textboxcsrch">
                                <select name="srchClient[]" id="srchClient" class="selectboxsrch" style="height:auto;" multiple="multiple">
                                  	<option value="0" <?php if(in_array(0,$srchClientArr)){echo 'selected';}?>> COD </option>
                                    <?php foreach($dbf->fetchOrder("work_order_bill wb,clients cl","wb.created_by=cl.id AND wb.created_by<>'0' AND wb.payment_status='Completed'","cl.name ASC","","cl.name")as $client){ ?>
                                    <option value="<?php echo $client['id']?>" <?php if(in_array($client['id'],$srchClientArr)){echo 'selected';}?>><?php echo $client['name'];?></option>
                                    <?php }?>
                                   </select>
                                </div>
                                <div  class="formtextaddsrchsmall" align="center">Tech</div>
                                <div class="textboxcsrch">
                                <select name="srchTechnician" id="srchTechnician" class="selectboxsrch">
                                    <option value="">--Select Tech--</option>
                                    <?php foreach($dbf->fetch("technicians","id>0 ORDER BY first_name ASC")as $tech){?>
                                    <option value="<?php echo $tech['id']?>" <?php if($tech['id']==$_REQUEST['srchTechnician']){echo 'selected';}?>><?php echo $tech['first_name'].'&nbsp;'.$tech['middle_name'].'&nbsp;'.$tech['last_name'];?></option>
                                    <?php }?>
                                </select>
                                </div>
                                <div  class="formtextaddsrchsmall" align="center">Service</div>
                                <div class="textboxcsrch">
                                <select name="srchService" id="srchService" class="selectboxsrch">
                                    <option value="">--Service Name--</option>
                                    <?php foreach($dbf->fetch("service","id>0 ORDER BY service_name ASC")as $service){?>
                                    <option value="<?php echo $service['id'];?>" <?php if($service['id']==$_REQUEST['srchService']){echo 'selected';}?>><?php echo $service['service_name'];?></option>
                                    <?php }?>
                                </select>
                                </div>
                                <div  class="formtextaddsrchsmall"align="center">From:</div>
                                <div class="textboxcsrchsmall">
                                <input type="text" class="textboxsrch datepick" name="FromDate" id="FromDate" value="<?php echo $_REQUEST['FromDate'];?>" readonly></div>
                                <div  class="formtextaddsrchsmall"align="center">To:</div>
                                <div class="textboxcsrchsmall">
                                <input type="text" class="textboxsrch datepick" name="ToDate" id="ToDate" value="<?php echo $_REQUEST['ToDate'];?>" readonly></div>
                                <div>
                                <input type="hidden" name="action"  value="search">
                                <input type="hidden" name="hidaction"  value="<?php echo $x;?>">
                                <input type="button" class="buttonText2" name="SearchRecord" id="SearchRecord" value="Filter Report" onClick="Search_Records();">
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
								if($_REQUEST['srchClient']!=''){
									//$sch=$sch."wb.created_by='$_REQUEST[srchClient]' AND ";
									$creatorlist=implode(",",$_REQUEST['srchClient']);
									$sch=$sch."wb.created_by IN($creatorlist) AND ";
								}
								if($_REQUEST['srchTechnician']!=''){
									$sch=$sch."t.id='$_REQUEST[srchTechnician]' AND ";
								}
								if($_REQUEST['srchService']!=''){
									$sch=$sch."s.id='$_REQUEST[srchService]' AND ";
								}
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
								 $cond="wb.client_id=c.id AND wb.payment_status='Completed' AND wb.tech_id=t.id AND wo.wo_no=wb.wo_no AND wo.service_id=s.id AND ".$sch;
								  // echo $cond;exit;
							   }
							   elseif($sch==''){
								 $cond="wb.client_id=c.id AND wb.payment_status='Completed' AND wb.tech_id=t.id AND wo.wo_no=wb.wo_no AND wo.service_id=s.id";
							   }
							   //print $cond;
							   //Pagination 
                                $page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
                                $page = ($page == 0 ? 1 : $page);
                                $perpage =100;//limit in each page
                                $startpoint = ($page * $perpage) - $perpage;
                                //-----------------------------------				
                                $num=$dbf->countRows("clients c,service s,technicians t,work_order wo,work_order_bill wb",$cond); 
								if($num>0){
							   ?>
                           	  <!-----Table area start------->
                                <table id="no-more-tables" class="draggable">
                                    <thead>
                                        <tr>
                                          <th width="15%">WO#</th>
                                          <th width="15%">Customer Name</th>
                                          <th width="15%">Service Type</th>
                                          <th width="15%">Client</th>
                                          <th width="15%">Tech Name</th>
                                          <th width="15%">Payment Date</th>
                                          <th width="10%" style="text-align:center;">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                     <?php 
									 	$grandtotal=0;
										$resGrArray=$dbf->fetchOrder("clients c,service s,technicians t,work_order wo,work_order_bill wb",$cond,"wo.service_id ASC","","s.id");
										//group by service loop
										foreach($resGrArray as $k=>$sgRes){
									 ?>
										<tr style="background-color:#f9f9f9;">
                                            <td valign="top" class="grheading">
                                            <div class="divgr">
                                            <span style="color:#ff9812;"><?php echo $sgRes['service_name'];?></span></div>
                                            </td>
                                            <td class="hiderow">&nbsp;</td>
                                            <td class="hiderow">&nbsp;</td>
                                            <td class="hiderow">&nbsp;</td>
                                            <td class="hiderow">&nbsp;</td>
                                          	<td class="hiderow">&nbsp;</td>
                                            <td class="hiderow">&nbsp;</td>
                                        </tr>
                                        <?php 
											$subtotal=0;
											$resArray=$dbf->fetchOrder("clients c,service s,technicians t,work_order wo,work_order_bill wb","wo.service_id='$sgRes[service_id]' AND " .$cond,"wb.id DESC","","");
											foreach($resArray as $key=>$res_jobPayment) { 
											  $total=$res_jobPayment['subtotal'];
											  $subtotal=$subtotal+$total;
											  
											  if($res_jobPayment['created_by']<>'0'){
												  $clientname=$dbf->getDataFromTable("clients","name","id='$res_jobPayment[created_by]'");
											  }else{
												  $clientname="COD"; 
											  }
										?>   
                               	  		<tr>
                                            <td data-title="WO#" class="coltext"><?php echo $res_jobPayment['wo_no'];?></td>
                                            <td data-title="Customer Name"><?php echo $res_jobPayment['name'];?></td>
                                            <td data-title="Service Type"><?php echo $res_jobPayment['service_name'];?></td>
                                            <td data-title="Client"><?php echo $clientname;?></td>
                                            <td data-title="Tech Name"><?php echo $res_jobPayment['first_name'].$res_jobPayment['middle_name'].$res_jobPayment['last_name'];?></td>
                                            <td data-title="Payment Date"><?php echo date('d-m-Y',strtotime($res_jobPayment['payment_date']));?></td>
                                  			<td data-title="Amount" style="text-align:center;"><?php echo $res_jobPayment['subtotal'];?></td>
                                        </tr>
                                         <?php } ?>
                                  		<tr >
                                            <td class="hiderow">&nbsp;</td>
                                            <td class="hiderow">&nbsp;</td>
                                            <td class="hiderow">&nbsp;</td>
                                            <td class="hiderow">&nbsp;</td>
                                            <td class="hiderow">&nbsp;</td>
                                            <td class="hiderow"></td>
                                 			<td class="grheading" valign="top"><div class="divprice">SubTotal:&nbsp;&nbsp;$<?php echo number_format($subtotal,2);?></div></td>
                                        </tr>
										<?php 
										$grandtotal=$grandtotal+$subtotal;
										}?> 
                                    </tbody>
                               </table>
                              <!-----Table area start-------> 
                          	<div id="subtotal">
                              <div  class="reportSubtotal">Grand Total:</div><div class="reportPrice">$ <?php echo number_format($grandtotal,2);?></div>
                           </div>
                           <div class="spacer"></div>
                           <?php }else{?>
                              <div style="padding-left:40%;border:1px solid #000;color:#F00;">No records founds!!</div>
                            <?php }?>
                            <div  align="center"><?php if($num>0) { echo $dbf->Pages($num,$perpage,"admin_service_billing_report?srchCust=$_REQUEST[srchCust]&srchClient=$_REQUEST[srchClient]&srchTechnician=$_REQUEST[srchTechnician]&srchService=$_REQUEST[srchService]&FromDate=$_REQUEST[FromDate]&ToDate=$_REQUEST[ToDate]&");}?></div>
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