<?php
ob_start();
session_start();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();
//Important below 2 lines
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment; Filename=admin_cod_payment_report.doc");
?>	
<!--Important-->
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">
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
</style>
<body>
	  <?php 
        $sch="";
		if($_REQUEST['hidaction'] == 1){
			$fromdt=date("Y-m-d",strtotime(($_REQUEST['FromDate'])));
			$todt=date("Y-m-d",strtotime(($_REQUEST['ToDate'])));
			
			if($_REQUEST['srchCust']!=''){
				$sch=$sch."wb.client_id='$_REQUEST[srchCust]' AND ";
			}
			if($_REQUEST['srchTechnician']!=''){
				$sch=$sch."t.id='$_REQUEST[srchTechnician]' AND ";
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
		}
		   //echo $sch;exit;
		   if($sch!=''){
			 $cond="c.state=st.state_code AND wb.client_id=c.id AND wb.created_by='0' AND wb.payment_status='Completed' AND wb.tech_id=t.id AND ".$sch;
		   }
		   elseif($sch==''){
			 $cond="c.state=st.state_code AND wb.client_id=c.id AND wb.created_by='0' AND wb.payment_status='Completed' AND wb.tech_id=t.id";
		   }
		
           //print $cond;
           //count number of rows.		
        $num=$dbf->countRows("state st,clients c,technicians t,work_order_bill wb",$cond);   
        if($num >0){ ?>
       <table border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#808080" width="100%" style="border:solid 1px #999;"> 
        <thead>
          <tr bgcolor="#E6F9D5">
            <th width="15%" height="27" align="left" valign="middle" class="fetch_headers">Wo#</th>
            <th width="15%" height="27" align="left" valign="middle" class="fetch_headers">Customer Name</th>
            <th width="15%" height="27" align="left" valign="middle" class="fetch_headers">Email ID</th>
            <th width="15%" height="27" align="left" valign="middle" class="fetch_headers">Contact No</th>
            <th width="15%" height="27" align="center" valign="middle" class="fetch_headers">Tech Name</th>
            <th width="15%" height="27" align="left" valign="middle" class="fetch_headers">Payment Date</th>
            <th width="10%" height="27" align="center" valign="middle" class="fetch_headers">Amount</th>
          </tr>
        </thead>
        <tbody>
        <?php 
			$grandtotal=0;
			$resGrArray=$dbf->fetchOrder("state st,clients c,technicians t,work_order_bill wb",$cond,"c.state ASC","","c.state");
			//group by service loop
			foreach($resGrArray as $k=>$sgRes){
				
		 ?>
      	<tr>
            <td height="25" colspan="7" align="left" valign="middle" bgcolor="#EEEEEE" class="fetch_headers"><?php echo $sgRes['state_name'];?></td>
       	</tr>
		<?php 
			$subtotal=0;
			$resArray=$dbf->fetchOrder("state st,clients c,technicians t,work_order_bill wb","c.state='$sgRes[state_code]' AND " .$cond,"wb.id DESC","c.name,c.email,c.phone_no,t.first_name,t.middle_name,t.last_name,wb.*","");
			foreach($resArray as $key=>$res_codPayment) { 
			  $total=$res_codPayment['subtotal'];
			  $subtotal=$subtotal+$total;
		?>
          <tr>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_codPayment['wo_no'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_codPayment['name'];?></td>
       		<td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_codPayment['email'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_codPayment['phone_no'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_codPayment['first_name'].'&nbsp;'.$res_codPayment['middle_name'].'&nbsp;'.$res_codPayment['last_name'];?></td>
           <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo date("d-m-Y",strtotime($res_codPayment['payment_date']));?></td>
           <td height="25" align="center" valign="middle" class="fetch_contents"><?php echo $res_codPayment['subtotal'];?></td>
          </tr>
          <?php } ?>
          <tr>
            <td height="25" align="left" valign="middle" class="fetch_contents">&nbsp;</td>
            <td height="25" align="left" valign="middle" class="fetch_contents">&nbsp;</td>
            <td height="25" align="left" valign="middle" class="fetch_contents">&nbsp;</td>
            <td height="25" align="left" valign="middle" class="fetch_contents">&nbsp;</td>
            <td height="25" align="left" valign="middle" class="fetch_contents">&nbsp;</td>
           	<td height="25" align="right" valign="middle" class="fetch_contents"><b>SubTotal:</b></td>
           	<td height="25" align="center" valign="middle" class="fetch_contents"><b>$<?php echo number_format($subtotal,2);?></b></td>
          </tr>
          
        <?php $grandtotal=$grandtotal+$subtotal;
		  	} 
		  ?>
           <tr>
            <td height="25" align="left" valign="middle" class="fetch_contents">&nbsp;</td>
            <td height="25" align="left" valign="middle" class="fetch_contents">&nbsp;</td>
            <td height="25" align="left" valign="middle" class="fetch_contents">&nbsp;</td>
            <td height="25" align="left" valign="middle" class="fetch_contents">&nbsp;</td>
            <td height="25" align="left" valign="middle" class="fetch_contents">&nbsp;</td>
           <td height="25" align="right" valign="middle" class="fetch_contents"><b>Grand Total:</b></td>
           <td height="25" align="center" valign="middle" class="fetch_contents"><b>$<?php echo number_format($grandtotal,2);?></b></td>
          </tr>
          </tbody>
       </table>
       <?php }else{?>
		<table height="61" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#808080" width="100%" style="border:solid 1px #999;"> 
            <tr>
                <td  style="color:#F00; font-family:Verdana, Geneva, sans-serif; font-weight:bold;font-size:12px;" align="center"> Sorry No Records Found </td>
            </tr>
        </table>
       <?php }?>
</body>
