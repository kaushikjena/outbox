<?php
ob_start();
session_start();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();
//Important below 2 lines
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment; Filename=admin_tech_payment_report.doc");
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
		}
		   //echo $sch;exit;
		   if($sch!=''){
			 $cond="wt.wo_no=wo.wo_no AND wo.service_id=s.id AND wt.tech_id=t.id AND wt.payment_status='Completed' AND at.wo_no=wt.wo_no AND at.tech_id=t.id AND ".$sch;
		   }
		   elseif($sch==''){
			 $cond="wt.wo_no=wo.wo_no AND wo.service_id=s.id AND wt.tech_id=t.id AND wt.payment_status='Completed' AND at.wo_no=wt.wo_no AND at.tech_id=t.id";
		   }
		
           //print $cond;
           //count number of rows.		
        $num=$dbf->countRows("service s,technicians t,assign_tech at,work_order wo,work_order_tech_bill wt",$cond);   
        if($num >0){ ?>
       <table border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#808080" width="100%" style="border:solid 1px #999;"> 
        <thead>
          <tr bgcolor="#E6F9D5">
            <th width="15%" height="27" align="left" valign="middle" class="fetch_headers">Wo#</th>
            <th width="15%" height="27" align="left" valign="middle" class="fetch_headers">Tech Name</th>
            <th width="15%" height="27" align="left" valign="middle" class="fetch_headers">Email ID</th>
            <th width="15%" height="27" align="left" valign="middle" class="fetch_headers">Contact No</th>
            <th width="15%" height="27" align="center" valign="middle" class="fetch_headers">Service Name</th>
            <th width="15%" height="27" align="left" valign="middle" class="fetch_headers">Payment Date</th>
            <th width="10%" height="27" align="center" valign="middle" class="fetch_headers">Amount</th>
          </tr>
        </thead>
        <tbody>
        <?php 
			$grandtotal=0;
			$resGrArray=$dbf->fetchOrder("service s,technicians t,assign_tech at,work_order wo,work_order_tech_bill wt",$cond,"wt.tech_id ASC","","wt.tech_id");
			//group by service loop
			foreach($resGrArray as $k=>$sgRes){
				
		 ?>
      	<tr>
            <td height="25" colspan="7" align="left" valign="middle" bgcolor="#EEEEEE" class="fetch_headers"><?php echo $sgRes['first_name'].' '.$sgRes['middle_name'].' '.$sgRes['last_name'];?></td>
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
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_techPayment['wo_no'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_techPayment['first_name'].'&nbsp;'.$res_techPayment['middle_name'].'&nbsp;'.$res_techPayment['last_name'];?></td>
     		<td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_techPayment['email'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_techPayment['contact_phone'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_techPayment['service_name'];?></td>
           <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo date("d-m-Y",strtotime($res_techPayment['payment_date']));?></td>
           <td height="25" align="center" valign="middle" class="fetch_contents"><?php echo $res_techPayment['subtotal'];?></td>
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
