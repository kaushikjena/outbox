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
			
			if($_REQUEST['srchTech']!=''){
				$sch=$sch."t.id='$_REQUEST[srchTech]' AND ";
			}
			if($_REQUEST['srchClient']!=''){
				//$sch=$sch."wo.created_by='$_REQUEST[srchClient]' AND ";
				$sch=$sch."wo.created_by IN($_REQUEST[srchClient]) AND ";
			}
			if($_REQUEST['srchStatus']!=''){
				$sch=$sch."wo.work_status='$_REQUEST[srchStatus]' AND ";
			}
			if($_REQUEST['FromDate']!='' && $_REQUEST['ToDate']==''){
				$sch=$sch."wo.invoiced_date >= '$fromdt' AND ";
			}
			if($_REQUEST['FromDate']=='' && $_REQUEST['ToDate']!=''){
				$sch=$sch."wo.invoiced_date <= '$todt' AND ";
			}
			if(($_REQUEST['FromDate']!='') && ($_REQUEST['ToDate']!='')){
				$sch=$sch."wo.invoiced_date BETWEEN '$fromdt' AND '$todt' AND ";
			}
		   $sch=substr($sch,0,-5);
		}
		   //echo $sch;exit;
		   if($sch!=''){
			 $cond="c.id=wo.client_id AND at.wo_no=wo.wo_no AND at.tech_id=t.id AND (wo.work_status='Completed' OR wo.work_status='Invoiced' OR wo.work_status='Ready to Invoice') AND ".$sch;
		   }
		   elseif($sch==''){
			  $cond="c.id=wo.client_id AND at.wo_no=wo.wo_no AND at.tech_id=t.id AND (wo.work_status='Completed' OR wo.work_status='Invoiced' OR wo.work_status='Ready to Invoice')";
		   }
		
           //print $cond;
           //count number of rows.		
        $num=$dbf->countRows("clients c,technicians t,assign_tech at,work_order wo",$cond);   
        if($num >0){ ?>
       <table border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#808080" width="100%" style="border:solid 1px #999;"> 
        <thead>
          <tr bgcolor="#E6F9D5">
            <th width="15%" height="27" align="left" valign="middle" class="fetch_headers">Wo#</th>
            <th width="20%" height="27" align="left" valign="middle" class="fetch_headers">Client</th>
            <th width="20%" height="27" align="left" valign="middle" class="fetch_headers">Customer Name</th>
            <th width="15%" height="27" align="left" valign="middle" class="fetch_headers">Order Status</th>
            <th width="15%" height="27" align="left" valign="middle" class="fetch_headers">Date Scheduled</th>
            <th width="15%" height="27" align="center" valign="middle" class="fetch_headers">Amount Paid For each Order</th>
          </tr>
        </thead>
        <tbody>
        <?php 
			$grandtotal=0;
			$resGrArray=$dbf->fetchOrder("clients c,technicians t,assign_tech at,work_order wo",$cond,"t.last_name ASC","t.first_name,t.middle_name,t.last_name,at.tech_id","at.tech_id");
			//group by service loop
			foreach($resGrArray as $k=>$sgRes){
				
		 ?>
      	<tr>
            <td height="25" colspan="6" align="left" valign="middle" bgcolor="#EEEEEE" class="fetch_headers"><?php echo $sgRes['first_name'].' '.$sgRes['middle_name'].' '.$sgRes['last_name'];?></td>
       	</tr>
		<?php 
			$subsubtotal=0;
			$resArray=$dbf->fetchOrder("clients c,technicians t,assign_tech at,work_order wo","at.tech_id='$sgRes[tech_id]' AND " .$cond,"wo.id","c.name,at.start_date,t.pay_grade,wo.*","");
			//print'<pre>';
			//print_r($resArray);
			foreach($resArray as $key=>$res_techPayment) { 
				$subtotal=0; 
				//fetch total price of work order
				$res_woservice = $dbf->fetch("equipment e,work_type wt,workorder_service ws","e.id=ws.equipment AND wt.id=ws.work_type AND ws.service_id='$res_techPayment[service_id]' AND ws.workorder_id='$res_techPayment[id]'");
				//print_r($res_woservice);
				foreach($res_woservice as $resServicePrice){
					$price=$resServicePrice['tech_price'];
					//$total = ($resServicePrice['quantity']*$price);
					$subtotal = $subtotal+$price;
				}
				$subsubtotal=$subsubtotal+$subtotal;
			  //get client name
			  if($res_techPayment['created_by']<>'0'){
				$clientname=$dbf->getDataFromTable("clients","name","id='$res_techPayment[created_by]'");}else{$clientname="COD";}
		?>
          <tr>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_techPayment['wo_no'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $clientname;?></td>
     		<td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_techPayment['name'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_techPayment['work_status'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php if($res_techPayment['start_date']<>'0000-00-00'){echo date("d-M-Y",strtotime($res_techPayment['start_date']));}else{echo "00-00-0000";}?></td>
           <td height="25" align="center" valign="middle" class="fetch_contents"><?php echo  number_format($subtotal,2);?></td>
          </tr>
          <?php } ?>
          <tr>
            <td height="25" align="left" valign="middle" class="fetch_contents">&nbsp;</td>
            <td height="25" align="left" valign="middle" class="fetch_contents">&nbsp;</td>
            <td height="25" align="left" valign="middle" class="fetch_contents">&nbsp;</td>
            <td height="25" align="left" valign="middle" class="fetch_contents">&nbsp;</td>
           	<td height="25" align="right" valign="middle" class="fetch_contents"><b>SubTotal:</b></td>
           	<td height="25" align="center" valign="middle" class="fetch_contents"><b>$<?php echo number_format($subsubtotal,2);?></b></td>
          </tr>
         <?php $grandtotal=$grandtotal+$subsubtotal;
		  	} 
		  ?>
          <tr>
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
