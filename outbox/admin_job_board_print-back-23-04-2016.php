<?php 
ob_start();
session_start();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();
//Fetch details from work_order table 
$res_viewJobBoard=$dbf->fetchSingle("clients c,service s,work_order w","c.id=w.client_id AND w.service_id=s.id  AND w.id='$_REQUEST[id]'");
//technician details
$resTech = $dbf->fetchSingle("assign_tech at,technicians tc","at.tech_id=tc.id AND at.wo_no='$res_viewJobBoard[wo_no]'");
$res_jobState=$dbf->getDataFromTable("state","state_name","state_code='$res_viewJobBoard[state]'");
$res_jobStatePick=$dbf->getDataFromTable("state","state_name","state_code='$res_viewJobBoard[pickup_state]'");
//get client name
if($res_viewJobBoard['created_by']<>0){
	$clientname =$dbf->getDataFromTable("clients","name","id='$res_viewJobBoard[created_by]'");
}else{
	$clientname="COD";
}	
?>	
<style type="text/css">
.fetch_headers{
	font-family:Verdana, Geneva, sans-serif;
	font-size:13px;
	font-weight:bold;
	color:#000;
	line-height:14px;
}
.fetch_contents{
	font-family:Verdana, Geneva, sans-serif;
	font-size:13px;
	font-weight:normal;
	color:#000;
	line-height:14px;
}
.fetch_headers1{
	font-family:Verdana, Geneva, sans-serif;
	font-size:15px;
	font-weight:bold;
	color:#000;
	line-height:16px;
}
.divBorder{
	padding-left:5px;
	border:1px solid #808080;
	border-radius:5px; 
	-mox-border-radius:5px;
}
.heading{
	width:100%;
	color:#000; 
	margin-bottom:10px;
	padding-top:10px; 
	padding-bottom:10px;
	border:1px solid #808080;
	border-radius:5px; 
	-mox-border-radius:5px;
	font-family:Verdana, Geneva, sans-serif;
	font-size:15px;
	font-weight:bold;
}
.norecords{
	color:#000;
}
.dash{
	border-bottom:dashed 1px #000000;
}
</style>
<style type="text/css" media="print">
  @page { size: portrait;}
</style>
<body>
	<table width="1000" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
      	<td colspan="3"><div  align="center" class="heading"> Workorder Details</div></td>
      </tr>
      <tr>
        <td width="49%" valign="top">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="divBorder">
              <tr>
                <td height="30" colspan="4"><div  class="fetch_headers1" style="width:100%;color:#000;">Work Order Details:</div></td>
              </tr>
               <tr>
                <td width="20%" height="30" class="fetch_headers">Client:</td>
                <td width="30%" class="fetch_contents"><?php echo $clientname;?></td>
                <td width="20%" class="fetch_headers">Order Status:</td>
                <td width="30%" class="fetch_contents"><?php echo $res_viewJobBoard['work_status'];?></td>
              </tr>
              <tr>
                <td width="20%" height="30" class="fetch_headers">WO#:</td>
                <td width="30%" class="fetch_contents"><?php echo $res_viewJobBoard['wo_no'];?></td>
                <td width="20%" class="fetch_headers">Purchase Order:</td>
                <td width="30%" class="fetch_contents"><?php echo $res_viewJobBoard['purchase_order_no'];?></td>
              </tr>
              <tr>
                <td class="fetch_headers">Service Type:</td>
                <td class="fetch_contents"><?php echo $res_viewJobBoard['service_name'];?></td>
                <td height="30" class="fetch_headers">Technician:</td>
                <td class="fetch_contents"><?php echo $resTech['first_name'].'&nbsp;'.$resTech['middle_name'].'&nbsp;'.$resTech['last_name'];?></td>
              </tr>
              <tr>
                <td class="fetch_headers">Assigned Date:</td>
                <td class="fetch_contents"><?php if($resTech<>'' && $resTech['assign_date']<>'0000-00-00'){echo date("d-M-Y",strtotime($resTech['assign_date']));}?></td>
                <td height="30" class="fetch_headers">Scheduled Date:</td>
                <td class="fetch_contents"><?php if($resTech<>'' && $resTech['start_date']<>'0000-00-00'){echo date("d-M-Y",strtotime($resTech['start_date']));}?></td>
              </tr>
              <tr>                
                <td height="30" class="fetch_headers">PayGrade:</td>
                <td class="fetch_contents"><?php echo $resTech['pay_grade'];?></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
            </table>
   	    <div style="clear:both; height:10px;"></div>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="divBorder">
              <tr>
                <td height="30" colspan="4"><div class="fetch_headers1" style="width:100%;color:#000;">Pick Up Information:</div></td>
              </tr>
              <tr>
                <td width="20%" height="30" class="fetch_headers">Location:</td>
                <td width="30%" class="fetch_contents"><?php echo $res_viewJobBoard['pickup_location'];?></td>
                <td width="20%" class="fetch_headers">City:</td>
                <td width="30%" class="fetch_contents"><?php echo $res_viewJobBoard['pickup_city'];?></td>
              </tr>
              <tr>
                <td height="30" class="fetch_headers">State:</td>
                <td class="fetch_contents"><?php echo $res_jobStatePick;?></td>
                <td class="fetch_headers">Address:</td>
                <td class="fetch_contents"><?php echo $res_viewJobBoard['pickup_address'];?></td>
              </tr>
              <tr>
                <td height="30" class="fetch_headers">Zip Code:</td>
                <td class="fetch_contents"><?php echo $res_viewJobBoard['pickup_zip_code'];?></td>
                <td class="fetch_headers">Phone Number:</td>
                <td class="fetch_contents"><?php echo $res_viewJobBoard['pickup_phone_no'];?></td>
              </tr>
              <tr>
                <td height="30" class="fetch_headers">Alt Phone:</td>
                <td class="fetch_contents"><?php echo $res_viewJobBoard['pickup_alt_phone'];?></td>
                <td height="30" class="fetch_headers">Parts Arrive:</td>
                <td class="fetch_contents"><?php if($res_viewJobBoard['parts_arrive']<>'0000-00-00'){echo date('d-M-Y',strtotime($res_viewJobBoard['parts_arrive']));}?></td>
              </tr>
              <tr>
                <td height="30" class="fetch_headers">Tracking No:</td>
                <td class="fetch_contents"><?php echo $res_viewJobBoard['tracking_number'];?></td>
                <td class="fetch_headers">Carrier Company:</td>
                <td class="fetch_contents"><?php echo $res_viewJobBoard['carrier_company'];?></td>
              </tr>
              <tr>
                <td height="30" class="fetch_headers">Serial Number:</td>
                <td class="fetch_contents"><?php echo $res_viewJobBoard['serial_number'];?></td>
                <td class="fetch_headers">Model Number:</td>
                <td class="fetch_contents"><?php echo $res_viewJobBoard['model_number'];?></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
            </table>
        </td>
        <td width="2%">&nbsp;</td>
        <td width="49%" valign="top">
        	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="divBorder">
              <tr>
                <td height="30" colspan="4"><div  class="fetch_headers1" style="width:100%;color:#000;">Customer Information:</div></td>
              </tr>
              <tr>
                <td width="20%" height="30" class="fetch_headers">Name:</td>
                <td width="30%" class="fetch_contents"><?php echo $res_viewJobBoard['name'];?></td>
                <td width="20%" class="fetch_headers">Email Address:</td>
                <td width="30%" class="fetch_contents"><?php echo $res_viewJobBoard['email'];?></td>
              </tr>
              <tr>
                <td height="30" class="fetch_headers">Address:</td>
                <td class="fetch_contents"><?php echo $res_viewJobBoard['address'];?></td>
                <td class="fetch_headers">Contact Name:</td>
                <td class="fetch_contents"><?php echo $res_viewJobBoard['contact_name'];?></td>
              </tr>
              <tr>
                <td height="30" class="fetch_headers">City:</td>
                <td class="fetch_contents"><?php echo $res_viewJobBoard['city'];?></td>
                <td class="fetch_headers">State:</td>
                <td class="fetch_contents"><?php echo $res_jobState;?></td>
              </tr>
              <tr>
                <td height="30" class="fetch_headers">Zip Code:</td>
                <td class="fetch_contents"><?php echo $res_viewJobBoard['zip_code'];?></td>
                <td class="fetch_headers">Phone No:</td>
                <td class="fetch_contents"><?php echo $res_viewJobBoard['phone_no'];?></td>
              </tr>
              <tr>
                <td height="30" class="fetch_headers">Cell No:</td>
                <td class="fetch_contents"><?php echo $res_viewJobBoard['fax_no'];?>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
            </table>
   	    <div style="clear:both; height:10px;"></div>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="divBorder">
              <tr>
                <td height="30"><div  class="fetch_headers" style="width:100%;color:#000;">Job Description:</div></td>
              </tr>
              <tr>
                <td height="120" valign="top" class="fetch_contents"><?php echo $res_viewJobBoard['notes'];?></td>
              </tr>
            </table>
        </td>
      </tr>
      <tr>
        <td height="10"></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td colspan="3">
        	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="divBorder">
              <tr class="fetch_headers1">
                <td width="25%" height="30">Work Type</td>
                <td width="23%">Equipment</td>
                <td width="22%">Model</td>
                <td width="10%">Quantity</td>
                <td width="10%">Tech Price</td>
                <td width="10%">Total Price</td>
              </tr>
               <?php 
			   	$grandtotal=0;
				 $res_woservice = $dbf->fetch("equipment e,work_type wt,workorder_service ws","e.id=ws.equipment AND wt.id=ws.work_type AND ws.workorder_id='$_REQUEST[id]'");
				  foreach($res_woservice as $arrWorkservice){
					  $TechPrice=$arrWorkservice['tech_price'];
					  $total = ($arrWorkservice['quantity']*$TechPrice);
					  $grandtotal= $grandtotal+$total;
				?>
              <tr>
                <td height="30" class="fetch_contents"><?php echo $arrWorkservice['worktype'];?></td>
                <td class="fetch_contents"><?php echo $arrWorkservice['equipment_name'];?></td>
                <td class="fetch_contents"><?php echo $arrWorkservice['model'];?></td>
                <td class="fetch_contents"><?php echo $arrWorkservice['quantity'];?></td>
                <td class="fetch_contents"><?php echo number_format($TechPrice,2);?></td>
                <td class="fetch_contents">$ <?php echo number_format($total,2);?></td>
              </tr>
               <?php } ?>
               <tr>
                <td height="30"></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="fetch_headers1">Grand Total</td>
                <td class="fetch_headers1">$ <?php echo number_format($grandtotal,2);?></td>
              </tr>
            </table>
        </td>
      </tr>
      <tr>
        <td height="10"></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td valign="top">
        	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="divBorder">
              <tr>
                <td height="30" colspan="4"><div class="fetch_headers" style="width:100%;color:#000;">Admin Notes:</div></td>
              </tr>
              <?php //fetch notes from work order notes table
				$resNotes=$dbf->fetchOrder("workorder_notes","workorder_id='$_REQUEST[id]' AND (user_type='admin' OR user_type='user' OR user_type='client')","created_date DESC");
				if(!empty($resNotes)){
				foreach($resNotes as $resn){
				 if($resn['user_type']=='admin'){
					 $uname = $dbf->getDataFromTable("admin","name","id='$resn[user_id]'");
				 }elseif($resn['user_type']=='user'){
					  $uname = $dbf->getDataFromTable("users","name","id='$resn[user_id]'");
				 }elseif($resn['user_type']=='client'){
					  $uname = $dbf->getDataFromTable("clients","name","id='$resn[user_id]'");
				 }
			  ?>
              <tr>
                <td height="30" align="left" class="fetch_contents"><?php echo $resn['wo_notes'];?></td>
              </tr>
              <tr>
                <td height="30" align="right"class="fetch_contents">By <?php echo $uname;?> on <?php echo date("d-M-Y g:i A",strtotime($resn['created_date']));?> for #<?php echo $res_viewJobBoard['wo_no'];?></td>
              </tr>
              <?php } }else{?>
              <tr>
                <td height="30" align="left" class="fetch_contents norecords">Admin notes not available!</td>
              </tr>
              <?php }?>
              <tr>
                <td width="20%">&nbsp;</td>
              </tr>
            </table>
        </td>
        <td>&nbsp;</td>
        <td valign="top">
        	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="divBorder">
              <tr>
                <td height="30"><div class="fetch_headers" style="width:100%;color:#000;">Tech Notes:</div></td>
              </tr>
               <?php
				 //fetch notes from work order notes table
				$resNotes2=$dbf->fetchOrder("workorder_notes","workorder_id='$_REQUEST[id]' AND user_type='tech'","created_date DESC");
				if(!empty($resNotes2)){
				foreach($resNotes2 as $resn){
				  if($resn['user_type']=='tech'){
					 $unameTech = $dbf->fetchSingle("technicians","id='$resn[user_id]'");
					 $uname = $unameTech['first_name'].' '.$unameTech['middle_name'].' '.$unameTech['last_name'];
				 }
				?>
              <tr>
                <td height="30" class="fetch_contents"><?php echo $resn['wo_notes'];?></td>
              </tr>
              <tr>
                <td height="30" align="right" class="fetch_contents">By <?php echo $uname;?> on <?php echo date("d-M-Y g:i A",strtotime($resn['created_date']));?> for #<?php echo $res_viewJobBoard['wo_no'];?></td>
              </tr>
              <?php }}else{?>
              <tr>
                <td height="30" class=" fetch_contents norecords">Tech notes are not available.</td>
              </tr>
              <?php }?>
              <tr>
                <td>&nbsp;</td>
              </tr>
            </table>
        </td>
      </tr>
      <tr>
        <td height="10"></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td height="20" colspan="3" align="left" class="fetch_contents">By signing here we acknowledge that we are satisfied with the work performed by the tech and no damage was done to the unit or my property.</td>
      </tr>
      <tr>
        <td height="10"></td>
        <td></td>
        <td height="40" align="right" valign="bottom" class="fetch_contents">Customer Signature</td>
      </tr>
      <tr>
        <td height="10" align="right" valign="bottom" class="fetch_contents">Serial No.1</td>
        <td></td>
        <td height="20" align="right" valign="bottom" class="fetch_contents dash">&nbsp;</td>
      </tr>
       <tr>
        <td height="19" align="right" valign="bottom" class="fetch_contents">Serial No.2</td>
        <td></td>
        <td height="19" align="right" valign="bottom" class="fetch_contents dash">&nbsp;</td>
      </tr>
       <tr>
        <td height="10" align="right" valign="bottom" class="fetch_contents">Serial No.3</td>
        <td></td>
        <td height="20" align="right" valign="bottom" class="fetch_contents dash">&nbsp;</td>
      </tr>
       <tr>
        <td height="10" align="right" valign="bottom" class="fetch_contents">Serial No.4</td>
        <td></td>
        <td height="20" align="right" valign="bottom" class="fetch_contents dash">&nbsp;</td>
      </tr>
      <tr>
        <td height="10" align="right" valign="bottom" class="fetch_contents">Serial No.5</td>
        <td></td>
        <td height="20" align="right" valign="bottom" class="fetch_contents dash">&nbsp;</td>
      </tr>
      <tr>
        <td height="10"></td>
        <td></td>
        <td></td>
      </tr>
    </table>
   <script type="text/javascript">
      window.print();
   </script>
</body>