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
	font-size:14px;
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
       		
		   if(isset($_REQUEST['action']) && $_REQUEST['action']=='payment'){
			   	$implode_workorders = $_REQUEST['wonos'];
				$explode_workorders = $_REQUEST['wonos']?explode(",",$_REQUEST['wonos']):array();
				#############update work_order_bill table###################
				foreach($explode_workorders as $wno){
					$dbf->updateTable("work_order_bill","payment_status='Completed',payment_date=now()","wo_no='$wno' AND created_by='$_REQUEST[cid]'");
				}
				#############update work_order_bill table###################
				$resClient = $dbf->fetchSingle("clients","id='$_REQUEST[cid]'");
			}
		?>
        <table  border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#808080" width="100%" style="border:solid 1px #999;">
          	 <tr>
                <td width="25%" height="25" align="left" valign="top" class="fetch_contents"><img src="<?php echo $baseUrl;?>images/logo.png" height="100"  width="112" alt="Out Box Logo"/></td>
                <td width="53%" align="center" valign="middle" class="fetch_contents"><b>Bill For Period <?php echo $_REQUEST['billperiod'];?></b></td>
                <td width="6%" height="25" align="left" valign="middle" class="fetch_contents">&nbsp;</td>
                <td width="16%" height="25" align="left" valign="middle" class="fetch_contents">&nbsp;</td>
              </tr>
              <tr>
                <td height="25" align="left" valign="top" class="fetch_contents"><b>Out Of The Box Installation  and Repair</b><br>8448 Loxton Cellars St<br/>Las Vegas NV<br/>89139</td>
                <td align="right" valign="top" class="fetch_contents">&nbsp;</td>
                <td height="25" align="left" valign="top" class="fetch_contents">Phone: <br/>Fax: <br/>E-mail:</td>
                <td height="25" align="left" valign="top" class="fetch_contents"> 702-375-9907 <br/>
                866-977-5589 <br/> carlos@oo-thebox.com</td>
              </tr>
              <tr>
                <td height="25" align="left" valign="middle" class="fetch_contents"><em style="font-size:16px; font-weight:bold;">Invoice</em></td>
                <td align="left" valign="middle" class="fetch_contents">&nbsp;</td>
                <td height="25" align="left" valign="middle" class="fetch_contents">&nbsp;</td>
                <td height="25" align="left" valign="middle" class="fetch_contents">&nbsp;</td>
              </tr>
              <tr>
                <td height="25" align="left" valign="top" class="fetch_contents">Invoice # : <?php echo date("ndy");?> <br/> Date : <?php echo date("M d, Y");?></td>
                <td align="right" valign="top" class="fetch_contents">&nbsp;</td>
                <td height="25" align="left" valign="top" class="fetch_contents">Bill To:</td>
                <td height="25" align="left" valign="top" class="fetch_contents"> <?php echo $resClient['name']?> <br/>
                <?php echo $resClient['address']?><br/><?php echo $resClient['city']." ".$resClient['state']." ".$resClient['zip_code']?></td>
              </tr>
          </table><br/>
        <?php
		 $cond="c.id=w.client_id AND w.service_id=s.id AND w.approve_status='1' AND w.work_status='Invoiced' AND w.created_by<>0 AND at.wo_no=w.wo_no AND at.tech_id=t.id AND FIND_IN_SET(w.wo_no,'$implode_workorders')";
        //count number of rows.		
        $num=$dbf->countRows("clients c,service s,technicians t,assign_tech at,work_order w",$cond);    
        if($num >0){ ?>
       <table border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#808080" width="100%" style="border:solid 1px #999;"> 
        <thead>
          <tr bgcolor="#E6F9D5">
            <th width="10%" height="27" align="left" valign="middle" class="fetch_headers">Date Scheduled</th>
            <th width="15%" height="27" align="left" valign="middle" class="fetch_headers">Service</th>
            <th width="15%" height="27" align="left" valign="middle" class="fetch_headers">Work Type</th>
            <th width="15%" height="27" align="left" valign="middle" class="fetch_headers">Model</th>
            <th width="15%" height="27" align="center" valign="middle" class="fetch_headers">Customer Name</th>
            <th width="10%" height="27" align="left" valign="middle" class="fetch_headers">WO#</th>
            <th width="10%" height="27" align="left" valign="middle" class="fetch_headers">Purchase Order#</th>
            <th width="10%" height="27" align="center" valign="middle" class="fetch_headers">Price Amount</th>
          </tr>
        </thead>
        <tbody>
		<?php 
		$grandtotal=0;
		$resArray=$dbf->fetchOrder("clients c,service s,technicians t,assign_tech at,work_order w",$cond,"w.id DESC","c.name,s.service_name,at.start_date,t.id as techid,w.purchase_order_no, w.service_id, w.wo_no, w.client_id, w.created_by, w.id","");
		//print "<pre>";
		//print_r($resArray);
		foreach($resArray as $key=>$res_clientBill) { 
			$subtotal=0;
			//fetch work type, model and total price of work order
			$workTypeArray =array(); $modelArray =array();
			//echo $res_clientBill[id];
			$res_woservice = $dbf->fetch("equipment e,work_type wt,workorder_service ws","e.id=ws.equipment AND wt.id=ws.work_type AND ws.service_id='$res_clientBill[service_id]' AND ws.workorder_id='$res_clientBill[id]'");
			//print_r($res_woservice);
			foreach($res_woservice as $resServicePrice){
				$total = ($resServicePrice['quantity']*$resServicePrice['outbox_price']);
				$subtotal = $subtotal+$total; 
				array_push($workTypeArray,$resServicePrice['worktype']);
				array_push($modelArray,$resServicePrice['model']);
			}
			$grandtotal=$grandtotal+$subtotal;
			//print_r($workTypeArray);
			$workType= !empty($workTypeArray) ? implode(", ",$workTypeArray):'';
			$model = !empty($modelArray) ? implode(", ",$modelArray):'';
		?>
          <tr>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo date("d-M-Y",strtotime($res_clientBill['start_date']));?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_clientBill['service_name'];?></td>
       		<td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $workType;?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $model;?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_clientBill['name'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_clientBill['wo_no'];?></td>
           	<td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_clientBill['purchase_order_no'];?></td>
           	<td height="25" align="center" valign="middle" class="fetch_contents">$ <?php echo number_format($subtotal,2);?></td>
          </tr>
        <?php } ?>
          <tr>
            <td height="25" align="left" valign="middle" class="fetch_contents">&nbsp;</td>
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
   <script type="text/javascript">
      window.print();
   </script>
</body>
