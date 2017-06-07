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
	  		if($_SERVER['HTTP_HOST'] == "box-ware.com"){
				$baseUrl="https://" . $_SERVER['HTTP_HOST']."/sys/outbox/";//Creating Base Url for SERVER
			}elseif($_SERVER['HTTP_HOST'] == "bletprojects.com"){
				$baseUrl="http://" . $_SERVER['HTTP_HOST']."/outbox/";//Creating Base Url for SERVER
			}else{
				$baseUrl="http://" . $_SERVER['HTTP_HOST'] ."/outbox/";//Creating Base Url for local
			} 
       		$sch="";
			$fromdt=date("Y-m-d",strtotime(($_REQUEST['FromDate'])));
			$todt=date("Y-m-d",strtotime(($_REQUEST['ToDate'])));
			
			if($_REQUEST['srchClient']!=''){
				$sch=$sch."w.created_by IN($_REQUEST[srchClient]) AND ";
			}
			if($_REQUEST['srchStatus']!=''){
				$sch=$sch."w.work_status='$_REQUEST[srchStatus]' AND ";
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
			 $cond="w.approve_status='1' AND at.wo_no=w.wo_no AND at.tech_id=t.id AND ".$sch;
			 if($_REQUEST['srchStatus']==''){$cond.=" AND (w.work_status='Invoiced' OR w.work_status='Completed')";}
			 if($_REQUEST['srchClient']==''){$cond.=" AND w.created_by<>0";}
			  //echo $cond;exit;
		   }
		   elseif($sch==''){
			 $cond="w.approve_status='1' AND (w.work_status='Invoiced' OR w.work_status='Completed') AND w.created_by<>0 AND at.wo_no=w.wo_no AND at.tech_id=t.id";
		   }
           //print $cond;
		   if($_REQUEST['srchClient']!=''){
				$resClient = $dbf->fetchSingle("clients","id='$_REQUEST[srchClient]'");
			}
	   	//count number of rows.		
        $num=$dbf->countRows("technicians t,assign_tech at,work_order w",$cond);    
        if($num >0){ ?>
       <table  border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#808080" width="100%" style="border:solid 1px #999;"> 
        <thead>
          <tr bgcolor="#E6F9D5">
            <th width="10%" height="27" align="left" valign="middle" class="fetch_headers">WO#</th>
            <th width="15%" height="27" align="left" valign="middle" class="fetch_headers">PO#</th>
            <th width="15%" height="27" align="left" valign="middle" class="fetch_headers">Invoice#</th>
            <th width="15%" height="27" align="left" valign="middle" class="fetch_headers">Date Scheduled</th>
            <th width="10%" height="27" align="center" valign="middle" class="fetch_headers">Price Amount</th>
            <th width="10%" height="27" align="center" valign="middle" class="fetch_headers">Invoice Received</th>
          </tr>
        </thead>
        <tbody>
		<?php 
		$grandtotal=0;
		$resArray=$dbf->fetchOrder("technicians t,assign_tech at,work_order w",$cond,"w.id DESC","at.start_date,w.purchase_order_no, w.service_id, w.wo_no, w.invoice_no,w.id","");
		//print "<pre>";
		//print_r($resArray);
		foreach($resArray as $key=>$res_clientBill) { 
			$subtotal=0;
			$res_woservice = $dbf->fetch("equipment e,work_type wt,workorder_service ws","e.id=ws.equipment AND wt.id=ws.work_type AND ws.service_id='$res_clientBill[service_id]' AND ws.workorder_id='$res_clientBill[id]'");
			//print_r($res_woservice);
			foreach($res_woservice as $resServicePrice){
				$total = ($resServicePrice['quantity']*$resServicePrice['outbox_price']);
				$subtotal = $subtotal+$total; 
			}
			$grandtotal=$grandtotal+$subtotal;
		?>
          <tr>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_clientBill['wo_no'];?> </td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_clientBill['purchase_order_no'];?></td>
       		<td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_clientBill['invoice_no'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo ($res_clientBill['start_date']<>'0000-00-00')? date("d-M-Y",strtotime($res_clientBill['start_date'])):"00-00-0000";?></td>
            <td height="25" align="center" valign="middle" class="fetch_contents">$ <?php echo number_format($subtotal,2);?></td>
           <td height="25" align="center" valign="middle" class="fetch_contents"><input type="checkbox" name="chkInvoice" id="chkInvoice"/></td>
          </tr>
        <?php } ?>
           <tr>
            <td height="25" align="left" valign="middle" class="fetch_contents">&nbsp;</td>
            <td height="25" align="left" valign="middle" class="fetch_contents">&nbsp;</td>
            <td height="25" align="left" valign="middle" class="fetch_contents">&nbsp;</td>
           	<td height="25" align="right" valign="middle" class="fetch_contents"><b>Grand Total:</b></td>
           	<td height="25" align="center" valign="middle" class="fetch_contents"><b>$<?php echo number_format($grandtotal,2);?></b></td>
            <td height="25" align="left" valign="middle" class="fetch_contents">&nbsp;</td>
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
