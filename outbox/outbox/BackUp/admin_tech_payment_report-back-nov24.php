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
?>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />
<link rel="stylesheet" href="css/no_more_table.css" type="text/css"/>
<script type="text/javascript">
function Search_Records(){
	$("#SrchFrm").attr("action", "admin_tech_payment_report");
	$("#SrchFrm").submit();
}
function ClearFields(){
	$('#srchTech').val("");
	$('#srchClient').val("");
	$('#FromDate').val("");
	$('#ToDate').val("");
	$('#hidaction').val("");
	//document.SrchFrm.submit();
	/*below line added to refreash page as to prevent url 
	mismatch problem in search using pagination.*/
	window.location.href="admin_tech_payment_report";
}
//for exporting,print,pdf,word
function print_doc(val,page){
 if(val=='word'){
	 $("#SrchFrm").attr("action", "admin_tech_payment_report_word");
	 $("#SrchFrm").submit();
 }else if(val=='excell'){
	 $("#SrchFrm").attr("action", "admin_tech_payment_report_excell");
	 $("#SrchFrm").submit();
 }else if(val=='pdf'){
	 $("#SrchFrm").attr("action", "admin_tech_payment_report_pdf");
	 $("#SrchFrm").submit();
 }else if(val=='print'){
	 $("#SrchFrm").attr("action", "admin_tech_payment_report_print?page="+page);
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
                            <div style="float:left;;">Technician Payment Report</div>
                            <div style="float:right;">
                            <a href="javascript:void(0);" onClick="print_doc('word');"><img src="images/word2007.png" style="width:20px; height:20px;" title="Export to Word"/></a>
                            <a href="javascript:void(0);" onClick="print_doc('pdf');"><img src="images/pdf.png" style="width:20px; height:20px;" title="Export to PDF"/></a>
                            <a href="javascript:void(0);" onClick="print_doc('excell');"><img src="images/export_excel.png" style="width:20px; height:20px;" title="Export to Excel"></a>
                            <a href="javascript:void(0);"  onClick="print_doc('print','<?php echo (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);?>');" ><img src="images/print.png" alt="" style="width:20px; height:20px;" title="Print"></a>
                            </div>
                        </div>
                        <div id="contenttable">
                        	<div style="width:100%;float:left;">
                            <form name="SrchFrm" id="SrchFrm" action="" method="post">
                              <div style="margin-bottom:5px;" align="center">
                               <div  class="formtextaddsrch" align="center">Technician</div>
                                <div class="textboxcsrch">
                                <select name="srchTech" id="srchTech" class="selectboxsrch">
                                    	<option value="">--Select Tech--</option>
                                        <?php foreach($dbf->fetch("technicians","id>0 ORDER BY first_name ASC")as $tech){?>
                                        <option value="<?php echo $tech['id'];?>" <?php if($tech['id']==$_REQUEST['srchTech']){echo 'selected';}?>><?php echo $tech['first_name'].'&nbsp;'.$tech['middle_name'].'&nbsp;'.$tech['last_name'];?></option>
                                        <?php }?>
                                 </select>
                                </div>
                                <div  class="formtextaddsrch" align="center">Client</div>
                                <div class="textboxcsrch">
                                <select name="srchClient" id="srchClient" class="selectboxsrch">
                                  		<option value="">--Select Client--</option>
                                        <?php foreach($dbf->fetchOrder("work_order wo,clients cl","wo.created_by=cl.id AND wo.created_by<>'0'","cl.name ASC","","cl.name")as $client){?>
                                        <option value="<?php echo $client['id']?>" <?php if($client['id']==$_REQUEST['srchClient']){echo 'selected';}?>><?php echo $client['name'];?></option>
                                        <?php }?>
                                   </select>
                                </div>
                                <div  class="formtextaddsrch"align="center">From:</div>
                                <div class="textboxcsrch">
                                <input type="text" class="textboxsrch datepick" name="FromDate" id="FromDate" value="<?php echo $_REQUEST['FromDate'];?>" readonly></div>
                                <div  class="formtextaddsrch"align="center">To:</div>
                                <div class="textboxcsrch">
                                <input type="text" class="textboxsrch datepick" name="ToDate" id="ToDate" value="<?php echo $_REQUEST['ToDate'];?>" readonly></div>
                                <div style="float:left;padding-left:40px;">
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
								
								if($_REQUEST['srchTech']!=''){
									$sch=$sch."t.id='$_REQUEST[srchTech]' AND ";
								}
								if($_REQUEST['srchClient']!=''){
									$sch=$sch."wo.created_by='$_REQUEST[srchClient]' AND ";
								}
								if($_REQUEST['FromDate']!='' && $_REQUEST['ToDate']==''){
									$sch=$sch."at.start_date >= '$fromdt' AND ";
								}
								if($_REQUEST['FromDate']=='' && $_REQUEST['ToDate']!=''){
									$sch=$sch."at.start_date <= '$todt' AND ";
								}
								if(($_REQUEST['FromDate']!='') && ($_REQUEST['ToDate']!='')){
									$sch=$sch."at.start_date BETWEEN '$fromdt' AND '$todt' AND ";
								}
							   $sch=substr($sch,0,-5);
							   //echo $sch;exit;
							   if($sch!=''){
								 $cond="c.id=wo.client_id AND at.wo_no=wo.wo_no AND at.tech_id=t.id AND (wo.work_status='Completed' OR wo.work_status='Invoiced' OR wo.work_status='Ready to Invoice') AND ".$sch;
								  // echo $cond;exit;
							   }
							   elseif($sch==''){
								 $cond="c.id=wo.client_id AND at.wo_no=wo.wo_no AND at.tech_id=t.id AND (wo.work_status='Completed' OR wo.work_status='Invoiced' OR wo.work_status='Ready to Invoice')";
							   }
							   //print $cond;
							   //Pagination 
                                $page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
                                $page = ($page == 0 ? 1 : $page);
                                $perpage =100;//limit in each page
                                $startpoint = ($page * $perpage) - $perpage;
                                //-----------------------------------				
                                $num=$dbf->countRows("clients c,technicians t,assign_tech at,work_order wo",$cond); 
								if($num>0){
							?>
                          		<!-----Table area start------->
                           		<table id="no-more-tables" class="draggable">
                                    <thead>
                                        <tr>
                                          <th width="15%">WO#</th>
                                          <th width="20%">Client</th>
                                          <th width="20%">Customer Name</th>
                                          <th width="15%">Order Status</th>
                                          <th width="15%">Date Scheduled</th>
                                          <th width="15%" style="text-align:center;">Amount Paid For each Order</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
									 	$grandtotal=0;
										$resGrArray=$dbf->fetchOrder("clients c,technicians t,assign_tech at,work_order wo",$cond,"t.first_name ASC","t.first_name,t.middle_name,t.last_name,at.tech_id","at.tech_id");
										//group by service loop
										foreach($resGrArray as $k=>$sgRes){
									
									 ?>
                                    	<tr style="background-color:#f9f9f9;">
                                            <td valign="top" class="grheading">
                                            <div class="divgr">
                                            <span style="color:#ff9812;">Technician &raquo; <?php echo $sgRes['first_name'].' '.$sgRes['middle_name'].' '.$sgRes['last_name'];?></span></div>
                                            </td>
                                            <td class="hiderow">&nbsp;</td>
                                            <td class="hiderow">&nbsp;</td>
                                            <td class="hiderow">&nbsp;</td>
                                            <td class="hiderow">&nbsp;</td>
                                            <td class="hiderow">&nbsp;</td>
                                         </tr>
                                        <?php 
											$subsubtotal=0;
											$resArray=$dbf->fetchOrder("clients c,technicians t,assign_tech at,work_order wo","at.tech_id='$sgRes[tech_id]' AND " .$cond,"wo.id","c.name, at.start_date, t.pay_grade, wo.id, wo.wo_no, wo.service_id, wo.created_by, wo.work_status","");
											//print'<pre>';
										    //print_r($resArray);
											foreach($resArray as $key=>$res_techPayment) { 
												$subtotal=0; 
												//fetch total price of work order
												$res_woservice = $dbf->fetch("equipment e,work_type wt,workorder_service ws","e.id=ws.equipment AND wt.id=ws.work_type AND ws.service_id='$res_techPayment[service_id]' AND ws.workorder_id='$res_techPayment[id]'");
												//print_r($res_woservice);
												foreach($res_woservice as $resServicePrice){
													$price=$resServicePrice['tech_price'];
													//$total = ($resServicePrice['quantity']*$price);//comment on nov24 2014
													$subtotal = $subtotal+$price;
												}
												$subsubtotal=$subsubtotal+$subtotal;
											  //get client name
											  if($res_techPayment['created_by']<>'0'){
												$clientname=$dbf->getDataFromTable("clients","name","id='$res_techPayment[created_by]'");}else{$clientname="COD";}
										?>
                                        <tr>
                                            <td data-title="WO#" class="coltext"><?php echo $res_techPayment['wo_no'];?></td>
                                            <td data-title="Client"><b><?php echo $clientname;?></b></td>
                                            <td data-title="Customer Name"><?php echo $res_techPayment['name'];?></td>
                                            <td data-title="Order Status"><?php echo $res_techPayment['work_status'];?></td>
                                            <td data-title="Date Scheduled"><?php if($res_techPayment['start_date']<>'0000-00-00'){echo date("d-M-Y",strtotime($res_techPayment['start_date']));}else{echo "00-00-0000";}?></td>
                                            <td data-title="Amount Paid For each Order" style="text-align:center;"><?php echo  number_format($subtotal,2);?></td>     	
                                        </tr>
                                        <?php }?>
                                        <tr>
                                            <td class="hiderow">&nbsp;</td>
                                            <td class="hiderow">&nbsp;</td>
                                            <td class="hiderow">&nbsp;</td>
                                            <td class="hiderow">&nbsp;</td>
                                            <td class="hiderow">&nbsp;</td>
                                 			<td class="grheading" valign="top"><div class="divprice" style="right:100px;">SubTotal:&nbsp;&nbsp;$<?php echo number_format($subsubtotal,2);?></div></td>
                                        </tr>
                                        <?php
										//grand total
										$grandtotal=$grandtotal+$subsubtotal;
										}
										?>
                                   </tbody>
                               </table>
                           <!-----Table area end------->
                           <div id="subtotal">
                              <div class="reportSubtotal" style="width:84%;">Grand Total:</div><div class="reportPrice" style="width:16%;">$ <?php echo number_format($grandtotal,2);?></div>
                           </div>
                           <?php }else{?>
                              <div style="padding-left:40%;border:1px solid #000;color:#F00;">No records founds!!</div>
                            <?php }?>
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