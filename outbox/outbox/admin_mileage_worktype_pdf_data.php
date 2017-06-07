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
					//$sch=$sch."w.created_by='$_REQUEST[srchClient]' AND ";
					$sch=$sch."w.created_by IN($_REQUEST[srchClient]) AND ";
				}
				if($_REQUEST['srchTechnician']!=''){
					$sch=$sch."t.id='$_REQUEST[srchTechnician]' AND ";
				}
				if($_REQUEST['srchService']!=''){
					$sch=$sch."s.id='$_REQUEST[srchService]' AND ";
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
			}
			//echo $sch;exit;
			if($sch!=''){
			 $cond="c.id=w.client_id AND w.service_id=s.id AND  w.approve_status='1' AND (w.work_status='Completed' OR w.work_status='Invoiced') AND w.wo_no=at.wo_no AND at.tech_id=t.id AND w.id=ws.workorder_id AND ws.work_type=wt.id AND wt.worktype='Mileage' AND ".$sch;
		  // echo $cond;exit;
			}
			elseif($sch==''){
			 $cond="c.id=w.client_id AND w.service_id=s.id AND  w.approve_status='1' AND (w.work_status='Completed' OR w.work_status='Invoiced') AND w.wo_no=at.wo_no AND at.tech_id=t.id AND w.id=ws.workorder_id AND ws.work_type=wt.id AND wt.worktype='Mileage'";
			}
		 //print $cond;
		//count number of rows.		
        $num=$dbf->countRows("clients c,service s,technicians t,assign_tech at,work_order w,workorder_service ws,work_type wt",$cond); 
		//$resArray=$dbf->simpleQuery($qry);			
		//$num=count($resArray); 
        if($num >0){ ?>
       <table border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#808080" width="100%" style="border:solid 1px #999;"> 
        <thead>
          <tr bgcolor="#E6F9D5">
            <th width="8%" height="27" align="left" valign="middle" class="fetch_headers">WO#</th>
            <th width="10%" height="27" align="left" valign="middle" class="fetch_headers">PO#</th>
            <th width="8%" height="27" align="left" valign="middle" class="fetch_headers">Order Status</th>
            <th width="12%" height="27" align="left" valign="middle" class="fetch_headers">Service Name</th>
            <th width="10%" height="27" align="left" valign="middle" class="fetch_headers">Work Type</th>
            <th width="12%" height="27" align="left" valign="middle" class="fetch_headers">Customer Name</th>
            <th width="12%" height="27" align="left" valign="middle" class="fetch_headers">Client</th>
            <th width="12%" height="27" align="left" valign="middle" class="fetch_headers">Tech</th>
            <th width="8%" height="27" align="center" valign="middle" class="fetch_headers">Client Price</th>
            <th width="8%" height="27" align="center" valign="middle" class="fetch_headers">Tech Price</th>
          </tr>
        </thead>
        <tbody>
       <?php 
		$grandtotal=0; $techgrandtotal =0;
		foreach($dbf->fetchOrder("clients c,service s,technicians t,assign_tech at,work_order w,workorder_service ws,work_type wt",$cond,"w.id DESC","c.name, s.service_name, w.id, w.wo_no, w.purchase_order_no, w.work_status, w.created_by, w.service_id, t.first_name,t.middle_name,t.last_name","")as  $res_JobBoard) {
		//get client name
		if($res_JobBoard['created_by']<>'0'){
			$clientname=$dbf->getDataFromTable("clients","name","id='$res_JobBoard[created_by]'");}else{
			$clientname="COD";
		}
		$subtotal=0; $techsubtotal=0;
		//fetch work type, model and total price of work order
		$workTypeArray =array(); //$modelArray =array();
		//echo $res_clientBill[id];
		$res_woservice = $dbf->fetch("equipment e,work_type wt,workorder_service ws","e.id=ws.equipment AND wt.id=ws.work_type AND ws.service_id='$res_JobBoard[service_id]' AND ws.workorder_id='$res_JobBoard[id]' AND wt.worktype='Mileage'");
		//print_r($res_woservice);
		foreach($res_woservice as $resServicePrice){
			$total = ($resServicePrice['quantity']*$resServicePrice['outbox_price']);
			$subtotal = $subtotal+$total; 
			$price=$resServicePrice['tech_price'];
			//$techtotal = ($resServicePrice['quantity']*$price);//commentd on nov24 2014
			$techsubtotal = $techsubtotal+$price;
			
			array_push($workTypeArray,$resServicePrice['worktype']);
			//array_push($modelArray,$resServicePrice['model']);
		}
		$grandtotal=$grandtotal+$subtotal;
		$techgrandtotal=$techgrandtotal+$techsubtotal;
		//print_r($workTypeArray);
		$workType= !empty($workTypeArray) ? implode(", ",$workTypeArray):'';
		?>
          <tr>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_JobBoard['wo_no'];?> </td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_JobBoard['purchase_order_no'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_JobBoard['work_status'];?></td>
       		<td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_JobBoard['service_name'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $workType;?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_JobBoard['name'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $clientname;?></td>
           	<td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_JobBoard['first_name'].' '.$res_JobBoard['middle_name'].' '.$res_JobBoard['last_name'];?></td>
           <td height="25" align="center" valign="middle" class="fetch_contents">$ <?php echo number_format($subtotal,2);?></td>
           <td height="25" align="center" valign="middle" class="fetch_contents">$  <?php echo number_format($techsubtotal,2);?></td>
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
           	<td height="25" align="center" valign="middle" class="fetch_contents"><b>$ <?php echo number_format($grandtotal,2);?></b></td>
            <td height="25" align="center" valign="middle" class="fetch_contents"><b>$ <?php echo number_format($techgrandtotal,2);?></b></td>
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
