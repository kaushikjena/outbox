<?php
ob_start();
session_start();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();
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
			
			if($_REQUEST['srchClient']!=''){
				//$sch=$sch."wb.created_by='$_REQUEST[srchClient]' AND ";
				$sch=$sch."wb.created_by IN($_REQUEST[srchClient]) AND ";
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
				$cond="wb.created_by=c.id AND wb.created_by<>'0' AND wb.payment_status='Completed' AND wb.tech_id=t.id AND ".$sch;
			}
			elseif($sch==''){
				$cond="wb.created_by=c.id AND wb.created_by<>'0' AND wb.payment_status='Completed' AND wb.tech_id=t.id";
			}	
           //print $cond;
           //count number of rows.		
        $num=$dbf->countRows("clients c,technicians t,work_order_bill wb",$cond);  
        if($num >0){ ?>
       <table  border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#808080" width="100%" style="border:solid 1px #999;"> 
        <thead>
          <tr bgcolor="#E6F9D5">
            <th width="15%" height="27" align="left" valign="middle" class="fetch_headers">Wo#</th>
            <th width="15%" height="27" align="left" valign="middle" class="fetch_headers">Client Name</th>
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
			$resGrArray=$dbf->fetchOrder("clients c,technicians t,work_order_bill wb",$cond,"wb.created_by ASC","wb.created_by,c.name","wb.created_by");
			//group by service loop
			foreach($resGrArray as $k=>$sgRes){
				
		 ?>
      	<tr>
            <td height="25" colspan="7" align="left" valign="middle" bgcolor="#EEEEEE" class="fetch_headers">Client &raquo; <?php echo $sgRes['name'];?></td>
       	</tr>
		<?php 
			$subtotal=0;
			$resArray=$dbf->fetchOrder("clients c,technicians t,work_order_bill wb","wb.created_by='$sgRes[created_by]' AND " .$cond,"wb.id DESC","c.name,c.email,c.phone_no,t.first_name,t.middle_name,t.last_name,wb.*","");
			foreach($resArray as $key=>$res_clientPayment) { 
			  $total=$res_clientPayment['subtotal'];
			  $subtotal=$subtotal+$total;
		?>
          <tr>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_clientPayment['wo_no'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_clientPayment['name'];?></td>
       		<td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_clientPayment['email'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_clientPayment['phone_no'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_clientPayment['first_name'].'&nbsp;'.$res_clientPayment['middle_name'].'&nbsp;'.$res_clientPayment['last_name'];?></td>
           <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo date("d-m-Y",strtotime($res_clientPayment['payment_date']));?></td>
           <td height="25" align="center" valign="middle" class="fetch_contents"><?php echo $res_clientPayment['subtotal'];?></td>
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
