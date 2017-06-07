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
	color:#000;
	line-height:14px;
}
</style>
<body>
	 <?php
        	$sch="";
			$fromdt=date("Y-m-d",strtotime(($_REQUEST['FromDate'])));
			$todt=date("Y-m-d",strtotime(($_REQUEST['ToDate'])));
			
			if($_REQUEST['srchClient']!=''){
				//$sch=$sch."w.created_by='$_REQUEST[srchClient]' AND ";
				$creatorlist=implode(",",$_REQUEST['srchClient']);
				$sch=$sch."w.created_by IN($creatorlist) AND ";
			}
			if($_REQUEST['FromDate']!='' && $_REQUEST['ToDate']==''){
				$sch=$sch."w.invoiced_date >= '$fromdt' AND ";
			}
			if($_REQUEST['FromDate']=='' && $_REQUEST['ToDate']!=''){
				$sch=$sch."w.invoiced_date <= '$todt' AND ";
			}
			if(($_REQUEST['FromDate']!='') && ($_REQUEST['ToDate']!='')){
				$sch=$sch."w.invoiced_date BETWEEN '$fromdt' AND '$todt' AND ";
			}
		   $sch=substr($sch,0,-5);
		  if($sch!=''){
			 $cond="c.id=w.client_id AND w.service_id=s.id AND w.approve_status='1' AND w.work_status='Invoiced' AND at.wo_no=w.wo_no AND at.tech_id=t.id AND ".$sch;
		   }elseif($sch==''){
			 $cond="c.id=w.client_id AND w.service_id=s.id AND  w.approve_status='1' AND w.work_status='Invoiced' AND at.wo_no=w.wo_no AND at.tech_id=t.id";
		   }
		 //print $cond;
		//count number of rows.		
       $qry = "SELECT c.name,s.service_name,at.start_date,t.pay_grade,w.invoiced_date,w.purchase_order_no, w.service_id, w.wo_no, w.client_id, w.created_by, w.id ,wb.payment_status,wt.payment_status as tpayment_status FROM clients c,service s,technicians t,assign_tech at,work_order w LEFT JOIN work_order_bill wb ON w.wo_no=wb.wo_no LEFT JOIN work_order_tech_bill wt ON w.wo_no=wt.wo_no WHERE ".$cond." AND ((wb.payment_status <>'Completed' OR wb.payment_status is NULL) OR (wt.payment_status <>'Completed' OR wt.payment_status is NULL)) order by w.id DESC";
		$resArray=$dbf->simpleQuery($qry);			
		$num=count($resArray); 
        if($num >0){ ?>
       <table border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#808080" width="100%" style="border:solid 1px #999;"> 
        <thead>
          <tr bgcolor="#E6F9D5">
            <th width="10%" height="27" align="left" valign="middle" class="fetch_headers">Date Scheduled</th>
            <th width="10%" height="27" align="left" valign="middle" class="fetch_headers">Date Invoiced</th>
            <th width="10%" height="27" align="left" valign="middle" class="fetch_headers">Service</th>
            <th width="10%" height="27" align="left" valign="middle" class="fetch_headers">Work Type</th>
            <th width="12%" height="27" align="left" valign="middle" class="fetch_headers">Model</th>
            <th width="12%" height="27" align="left" valign="middle" class="fetch_headers">Customer Name</th>
            <th width="10%" height="27" align="left" valign="middle" class="fetch_headers">WO#</th>
            <th width="10%" height="27" align="left" valign="middle" class="fetch_headers">PO#</th>
            <th width="8%" height="27" align="center" valign="middle" class="fetch_headers">Client Price</th>
            <th width="8%" height="27" align="center" valign="middle" class="fetch_headers">Tech Price</th>
          </tr>
        </thead>
        <tbody>
       <?php 
		$grandtotal=0; $techgrandtotal =0;
		foreach($resArray as $key=>$res_clientBill) { 
		//check for payment completed work orders
			$subtotal=0; $techsubtotal=0;
			//fetch work type, model and total price of work order
			$workTypeArray =array(); $modelArray =array();
			//echo $res_clientBill[id];
			$res_woservice = $dbf->fetch("equipment e,work_type wt,workorder_service ws","e.id=ws.equipment AND wt.id=ws.work_type AND ws.service_id='$res_clientBill[service_id]' AND ws.workorder_id='$res_clientBill[id]'");
			//print_r($res_woservice);
			foreach($res_woservice as $resServicePrice){
				$total = ($resServicePrice['quantity']*$resServicePrice['outbox_price']);
				$subtotal = $subtotal+$total; 
				$price=$resServicePrice['tech_price'];
				//$techtotal = ($resServicePrice['quantity']*$price);
				$techsubtotal = $techsubtotal+$price;
				
				array_push($workTypeArray,$resServicePrice['worktype']);
				array_push($modelArray,$resServicePrice['model']);
			}
			$grandtotal=$grandtotal+$subtotal;
			$techgrandtotal=$techgrandtotal+$techsubtotal;
			//print_r($workTypeArray);
			$workType= !empty($workTypeArray) ? implode(", ",$workTypeArray):'';
			$model = !empty($modelArray) ? implode(", ",$modelArray):'';
		?>
          <tr>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo ($res_clientBill['start_date']<>'0000-00-00')? date("d-M-Y",strtotime($res_clientBill['start_date'])):"00-00-0000";?> </td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo ($res_clientBill['invoiced_date']<>'0000-00-00')? date("d-M-Y",strtotime($res_clientBill['invoiced_date'])):"00-00-0000";?> </td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_clientBill['service_name'];?></td>
       		<td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $workType;?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $model;?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_clientBill['name'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_clientBill['wo_no'];?></td>
           	<td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_clientBill['purchase_order_no'];?></td>
           <td height="25" align="center" valign="middle" class="fetch_contents">$ <?php echo number_format($subtotal,2);?></td>
           <td height="25" align="center" valign="middle" class="fetch_contents">$ <?php echo number_format($techsubtotal,2);?></td>
          </tr>
        <?php } ?>
           <tr>
            <td height="25" align="left" valign="middle" class="fetch_contents">&nbsp;</td>
            <td height="25" align="left" valign="middle" class="fetch_contents">&nbsp;</td>
            <td height="25" align="left" valign="middle" class="fetch_contents">&nbsp;</td>
            <td height="25" align="left" valign="middle" class="fetch_contents">&nbsp;</td>
            <td height="25" align="left" valign="middle" class="fetch_contents">&nbsp;</td>
            <td height="25" align="left" valign="middle" class="fetch_contents">&nbsp;</td>
            <td height="25" align="left" valign="middle" class="fetch_contents">&nbsp;</td>
           	<td height="25" align="right" valign="middle" class="fetch_contents"><b>Grand Total:</b></td>
           	<td height="25" align="center" valign="middle" class="fetch_contents"><b>$<?php echo number_format($grandtotal,2);?></b></td>
            <td height="25" align="center" valign="middle" class="fetch_contents"><b>$<?php echo number_format($techgrandtotal,2);?></b></td>
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
   <script type="text/javascript">
      window.print();
   </script>
</body>
