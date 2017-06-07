<?php 
ob_start();
session_start();
include_once '../includes/class.Main.php';
//Object initialization
$dbf = new User();
//Fetch details from work_order table 
$res_viewJobBoard=$dbf->fetchSingle("clients c,service s,work_order w","c.id=w.client_id AND w.service_id=s.id  AND w.id='$_REQUEST[id]'");
//technician details
$resTech = $dbf->fetchSingle("assign_tech at,technicians tc","at.tech_id=tc.id AND at.wo_no='$res_viewJobBoard[wo_no]' AND tc.id=$_SESSION[userid]");
$res_jobState=$dbf->getDataFromTable("state","state_name","state_code='$res_viewJobBoard[state]'");
$res_jobStatePick=$dbf->getDataFromTable("state","state_name","state_code='$res_viewJobBoard[pickup_state]'");
?>	
<style type="text/css">
.fetch_headers{
	font-family:Verdana, Geneva, sans-serif;
	font-size:12px;
	font-weight:bold;
	color:#000;
	line-height:14px;
}
.fetch_contents{
	font-family:Verdana, Geneva, sans-serif;
	font-size:12px;
	font-weight:normal;
	color:#666;
	line-height:14px;
}
.fetch_headers1{
	font-family:Verdana, Geneva, sans-serif;
	font-size:12px;
	font-weight:bold;
	color:#090;
	line-height:14px;
}
.divBorder{
	padding-left:5px;
	border:1px solid #808080;
	border-radius:5px; 
	-mox-border-radius:5px;
}
.heading{
	width:100%;
	color:#EF8510; 
	margin-bottom:10px;
	padding-top:10px; 
	padding-bottom:10px;
	border:1px solid #808080;
	border-radius:5px; 
	-mox-border-radius:5px;
	font-family:Verdana, Geneva, sans-serif;
	font-size:12px;
	font-weight:bold;
}
</style>
<body>
     <div  align="center" class="heading">Technician Workorder Details</div>
	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td width="49%" valign="top">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="divBorder">
              <tr>
                <td height="30" colspan="4"><div  class="fetch_headers" style="width:100%;color:#090;">Work Order Details:</div></td>
              </tr>
              <tr>
                <td width="20%" height="30" class="fetch_headers">WO#:</td>
                <td width="30%" class="fetch_contents"><?php echo $res_viewJobBoard['wo_no'];?></td>
                <td width="20%" class="fetch_headers">Purchase Order:</td>
                <td width="30%" class="fetch_contents"><?php echo $res_viewJobBoard['purchase_order_no'];?></td>
              </tr>
              <tr>
                <td height="30" class="fetch_headers">Job Status:</td>
                <td class="fetch_contents"><?php echo $res_viewJobBoard['job_status'];?></td>
                <td class="fetch_headers">Service Type:</td>
                <td class="fetch_contents"><?php echo $res_viewJobBoard['service_name'];?></td>
              </tr>
              <tr>
                <td height="30" class="fetch_headers">Technician:</td>
                <td class="fetch_contents"><?php echo $resTech['first_name'].'&nbsp;'.$resTech['middle_name'].'&nbsp;'.$resTech['last_name'];?></td>
                <td class="fetch_headers">Scheduled Date:</td>
                <td class="fetch_contents"><?php if(!empty($resTech)){echo date("d-M-Y",strtotime($resTech['start_date'])).'&nbsp;&nbsp;&nbsp;'.$resTech['start_time'];}?></td>
              </tr>
              <tr>
                <td height="30" class="fetch_headers">PayGrade:</td>
                <td class="fetch_contents"><?php echo $resTech['pay_grade'];?></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td height="20">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
            </table>
          <div style="clear:both; height:10px;"></div>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="divBorder">
              <tr>
                <td height="30" colspan="4"><div class="fetch_headers" style="width:100%;color:#090;">Pick Up Information:</div></td>
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
                <td>&nbsp;</td>
                <td>&nbsp;</td>
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
                <td height="30" colspan="4"><div  class="fetch_headers" style="width:100%;color:#090;">Customer Information:</div></td>
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
                <td height="30" class="fetch_headers">Fax No:</td>
                <td class="fetch_contents"><?php echo $res_viewJobBoard['fax_no'];?>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
            </table>
          <div style="clear:both; height:10px;"></div>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="divBorder">
              <tr>
                <td height="30"><div  class="fetch_headers" style="width:100%;color:#090;">Job Description:</div></td>
              </tr>
              <tr>
                <td height="80" valign="top" class="fetch_contents"><?php echo $res_viewJobBoard['notes'];?></td>
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
					  if($resTech['pay_grade']=='A'){
						$TechPrice=$arrWorkservice['gradeA_price'];
					  }elseif($resTech['pay_grade']=='B'){
						$TechPrice=$arrWorkservice['gradeB_price'];
					  }elseif($resTech['pay_grade']=='C'){
						$TechPrice=$arrWorkservice['gradeC_price'];
					  }elseif($resTech['pay_grade']=='D'){
						$TechPrice=$arrWorkservice['gradeD_price'];
					  }
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
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table>
   <script type="text/javascript">
      window.print();
   </script>
</body>