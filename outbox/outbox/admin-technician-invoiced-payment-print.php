<?php
ob_start();
session_start();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();
?>
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
					$dbf->updateTable("work_order_tech_bill","payment_status='Completed',payment_date=now()","wo_no='$wno' AND tech_id='$_REQUEST[tid]'");
				}
				#############update work_order_bill table###################
				$resTech=$dbf->fetchSingle("state st,technicians t","t.state=st.state_code AND t.id='$_REQUEST[tid]'");
			}
		?>
        <table  border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#808080" width="100%" style="border:solid 1px #999;">
          	 <tr>
                <td width="19%" height="25" align="left" valign="top" class="fetch_contents"><img src="<?php echo $baseUrl;?>images/logo.png" height="100"  width="112" alt="Out Box Logo"/></td>
                <td width="50%" align="center" valign="middle" class="fetch_contents"><b>Payment For Invoiced</b></td>
                <td width="10%" height="25" align="left" valign="middle" class="fetch_contents">&nbsp;</td>
                <td width="21%" height="25" align="left" valign="middle" class="fetch_contents">&nbsp;</td>
              </tr>
              <tr>
                <td align="left" valign="top" class="fetch_contents"></td>
                <td align="right" valign="top" class="fetch_contents"></td>
                <td align="left" valign="top" class="fetch_contents"></td>
                <td height="5" align="left" valign="top" class="fetch_contents"> </td>
              </tr>
              <tr>
                <td height="25" align="left" valign="middle" class="fetch_contents">Tech Name:</td>
                <td align="left" valign="middle" class="fetch_contents"><?php echo $resTech['first_name'].'&nbsp;'.$resTech['middle_name'].'&nbsp;'.$resTech['last_name'];?></td>
                <td height="25" align="left" valign="middle" class="fetch_contents">Email ID:</td>
                <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $resTech['email'];?></td>
              </tr>
              <tr>
                <td height="25" align="left" valign="top" class="fetch_contents">Phone Number:</td>
                <td align="left" valign="top" class="fetch_contents"><?php echo $resTech['contact_phone'];?></td>
                <td height="25" align="left" valign="top" class="fetch_contents">State:</td>
                <td height="25" align="left" valign="top" class="fetch_contents"><?php echo $resTech['state_name'];?></td>
              </tr>
               <tr>
                <td height="25" align="left" valign="top" class="fetch_contents">Order Status:</td>
                <td align="left" valign="top" class="fetch_contents">Invoiced</td>
                <td height="25" align="left" valign="top" class="fetch_contents">Bill Period:</td>
                <td height="25" align="left" valign="top" class="fetch_contents"><?php echo $_REQUEST['billperiod'];?></td>
              </tr>
          </table><br/>
        <?php
		 $cond="c.id=w.client_id AND w.service_id=s.id AND w.approve_status='1' AND w.work_status='Invoiced' AND at.wo_no=w.wo_no AND at.tech_id=t.id AND FIND_IN_SET(w.wo_no,'$implode_workorders')";
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
		
		foreach($resArray as $key=>$res_clientBill) { 
			$subtotal=0;
			//fetch work type, model and total price of work order
			$workTypeArray =array(); $modelArray =array();
			$res_woservice = $dbf->fetch("equipment e,work_type wt,workorder_service ws","e.id=ws.equipment AND wt.id=ws.work_type AND ws.service_id='$res_clientBill[service_id]' AND ws.workorder_id='$res_clientBill[id]'");
			//print_r($res_woservice);
			foreach($res_woservice as $resServicePrice){
				$price=$resServicePrice['tech_price'];
				//$total = ($resServicePrice['quantity']*$price);
				$subtotal = $subtotal+$price; 
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
       <table width="100%" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td width="10%" height="30" align="center" class="fetch_headers">Date:</td>
          <td width="55%" align="left" class="fetch_contents">&nbsp;</td>
          <td width="10%" align="right" class="fetch_headers">Signature:</td>
          <td width="25%" align="left" class="fetch_contents">&nbsp;</td>
        </tr>
        <tr>
          <td width="10%" height="30" align="center" class="fetch_headers">Place</td>
          <td width="55%" align="left" class="fetch_contents">&nbsp;</td>
          <td width="10%" align="right" class="fetch_headers">&nbsp;</td>
          <td width="25%" align="left" class="fetch_contents">&nbsp;</td>
        </tr>
      </table>
 <script type="text/javascript">
      window.print();
   </script>
</body>
