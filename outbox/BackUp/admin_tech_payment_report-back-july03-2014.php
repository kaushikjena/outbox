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
	document.SrchFrm.action="admin_tech_payment_report";
	document.SrchFrm.submit();
}
function ClearFields(){
	$('#srchTech').val("");
	$('#srchService').val("");
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
	 document.SrchFrm.action="admin_tech_payment_report_word";
	 document.SrchFrm.submit();
 }else if(val=='excell'){
	 document.SrchFrm.action="admin_tech_payment_report_excell";
	 document.SrchFrm.submit(); 
 }else if(val=='pdf'){
	 document.SrchFrm.action="admin_tech_payment_report_pdf";
	 document.SrchFrm.submit(); 
 }else if(val=='print'){
	document.SrchFrm.action="admin_tech_payment_report_print?page="+page;
	document.SrchFrm.target="_blank";
	document.SrchFrm.submit(); 
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
                                        <?php foreach($dbf->fetch("technicians","id>0 AND status=1 ORDER BY first_name ASC")as $tech){?>
                                        <option value="<?php echo $tech['id'];?>" <?php if($tech['id']==$_REQUEST['srchTech']){echo 'selected';}?>><?php echo $tech['first_name'].'&nbsp;'.$tech['middle_name'].'&nbsp;'.$tech['last_name'];?></option>
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
								 $cond="wt.wo_no=wo.wo_no AND wo.service_id=s.id AND wt.tech_id=t.id AND wt.payment_status='Completed' AND at.wo_no=wt.wo_no AND at.tech_id=t.id AND ".$sch;
								  // echo $cond;exit;
							   }
							   elseif($sch==''){
								 $cond="wt.wo_no=wo.wo_no AND wo.service_id=s.id AND wt.tech_id=t.id AND wt.payment_status='Completed' AND at.wo_no=wt.wo_no AND at.tech_id=t.id";
							   }
							   //print $cond;
							   //Pagination 
                                $page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
                                $page = ($page == 0 ? 1 : $page);
                                $perpage =100;//limit in each page
                                $startpoint = ($page * $perpage) - $perpage;
                                //-----------------------------------				
                                $num=$dbf->countRows("service s,technicians t,assign_tech at,work_order wo,work_order_tech_bill wt",$cond); 
								if($num>0){
							?>
                          		<!-----Table area start------->
                           		<table id="no-more-tables" class="draggable">
                                    <thead>
                                        <tr>
                                          <th width="15%">WO#</th>
                                          <th width="15%">Tech Name</th>
                                          <th width="15%">Email ID</th>
                                          <th width="15%">Contact No</th>
                                          <th width="15%">Service Name</th>
                                          <th width="15%">Payment Date</th>
                                          <th width="10%" style="text-align:center;">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
									 	$grandtotal=0;
										$resGrArray=$dbf->fetchOrder("service s,technicians t,assign_tech at,work_order wo,work_order_tech_bill wt",$cond,"wt.tech_id ASC","","wt.tech_id");
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
                                            <td class="hiderow">&nbsp;</td>
                                         </tr>
                                        <?php 
											$subtotal=0;
											$resArray=$dbf->fetchOrder("service s,technicians t,assign_tech at,work_order wo,work_order_tech_bill wt","wt.tech_id='$sgRes[tech_id]' AND " .$cond,"wt.id DESC","s.service_name,t.email,t.contact_phone,t.first_name,t.middle_name,t.last_name,wt.*","");
											//print'<pre>';
										    //print_r($resArray);
											foreach($resArray as $key=>$res_techPayment) { 
											  $total=$res_techPayment['subtotal'];
											  $subtotal=$subtotal+$total;
										?>
                                        <tr>
                                            <td data-title="WO#" class="coltext"><?php echo $res_techPayment['wo_no'];?></td>
                                            <td data-title="Tech Name"><b><?php echo $res_techPayment['first_name'].'&nbsp;'.$res_techPayment['middle_name'].'&nbsp;'.$res_techPayment['last_name'];?></b></td>
                                            <td data-title="Email ID"><?php echo $res_techPayment['email'];?></td>
                                            <td data-title="Contact No"><?php echo $res_techPayment['contact_phone'];?></td>
                                            <td data-title="Service Name"><?php echo $res_techPayment['service_name'];?></td>
                                            <td data-title="Payment Date"><?php echo date("d-m-Y",strtotime($res_techPayment['payment_date']));?></td>
                                            <td data-title="Amount" style="text-align:center;"><?php echo $res_techPayment['subtotal'];?></td>
                                            	
                                        </tr>
                                        <?php }?>
                                        <tr>
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
                           <!-----Table area end------->
                           <div id="subtotal">
                              <div class="reportSubtotal">Grand Total:</div><div class="reportPrice">$ <?php echo number_format($grandtotal,2);?></div>
                           </div>
                           <?php }else{?>
                              <div style="padding-left:40%;border:1px solid #000;color:#F00;">No records founds!!</div>
                            <?php }?>
                            <div  align="center"><?php if($num>0) { echo $dbf->Pages($num,$perpage,"admin_tech_payment_report?srchTech=$_REQUEST[srchTech]&FromDate=$_REQUEST[FromDate]&ToDate=$_REQUEST[ToDate]&");}?></div>
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