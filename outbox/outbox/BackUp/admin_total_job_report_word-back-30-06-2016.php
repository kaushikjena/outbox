<?php
ob_start();
session_start();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();

//Important below 2 lines
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment; Filename=admin_total_job_report.doc");
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
				//$sch=$sch."temp.created_by='$_REQUEST[srchClient]' AND ";
				$creatorlist=implode(",",$_REQUEST['srchClient']);
				$sch=$sch."w.created_by IN($creatorlist) AND ";
			}
			if($_REQUEST['srchStatus']!=''){
				//$sch=$sch."temp.work_status='$_REQUEST[srchStatus]' AND ";
				$tmpwstatusarray=array();
				foreach($_REQUEST['srchStatus'] as $val){
					array_push($tmpwstatusarray,"'".$val."'");
				}
				$workstatuslist=implode(",",$tmpwstatusarray);
				$sch=$sch."w.work_status IN($workstatuslist) AND ";
			}
			if($_REQUEST['srchTechnician']!=''){
				$sch=$sch."t.id='$_REQUEST[srchTechnician]' AND ";
			}
			if($_REQUEST['srchService']!=''){
				$sch=$sch."s.id='$_REQUEST[srchService]' AND ";
			}
			if($_REQUEST['FromDate']!='' && $_REQUEST['ToDate']==''){
				$sch=$sch."w.created_date >= '$fromdt' AND ";
			}
			if($_REQUEST['FromDate']=='' && $_REQUEST['ToDate']!=''){
				$sch=$sch."w.created_date <= '$todt' AND ";
			}
			if(($_REQUEST['FromDate']!='') && ($_REQUEST['ToDate']!='')){
				$sch=$sch."w.created_date BETWEEN '$fromdt' AND '$todt' AND ";
			}
		   $sch=substr($sch,0,-5);
       }
           //echo $sch;exit; 
          if($sch!=''){
			  $cond=" AND ".$sch;
		   }
		   elseif($sch==''){
			 $cond=" AND w.id>0";  
		   }
           //print $cond;
           //count number of rows.
		$qry = "SELECT w.id, w.wo_no, w.purchase_order_no, w.work_status, w.created_date, w.created_by,w.service_id,w.tracking_number,w.parts_arrive,c.name, c.phone_no, c.city, st.state_name,s.id as sid, s.service_name ,t.id as tid,t.first_name, t.middle_name, t.last_name,at.assign_date, at.start_date FROM work_order w LEFT JOIN clients c ON w.client_id=c.id LEFT JOIN state st ON c.state=st.state_code LEFT JOIN service s ON w.service_id=s.id LEFT JOIN assign_tech at  ON w.wo_no=at.wo_no LEFT JOIN technicians t ON t.id=at.tech_id WHERE w.approve_status='1' ".$cond." ORDER BY w.id DESC";
		$resArray = $dbf->simpleQuery($qry); 
		$num = count($resArray);  
        if($num >0){ ?>
       <table height="61" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#808080" width="100%" style="border:solid 1px #999;"> 
        <thead>
          <tr bgcolor="#E6F9D5">
            <th width="6%" height="27" align="left" valign="middle" class="fetch_headers">WO#</th>
            <th width="5%" height="27" align="left" valign="middle" class="fetch_headers">PO#</th>
            <th width="8%" height="27" align="left" valign="middle" class="fetch_headers">CreatedDate</th>
            <th width="8%" height="27" align="left" valign="middle" class="fetch_headers">CustomerName</th>
            <th width="7%" height="27" align="left" valign="middle" class="fetch_headers">DeliveryState</th>
            <th width="10%" height="27" align="left" valign="middle" class="fetch_headers">DeliveryPhone</th>
            <th width="7%" height="27" align="left" valign="middle" class="fetch_headers">TrackingNumber</th>
            <th width="9%" height="27" align="left" valign="middle" class="fetch_headers">PartsArrival</th>
            <th width="8%" height="27" align="left" valign="middle" class="fetch_headers">ServiceType</th>
            <th width="6%" height="27" align="left" valign="middle" class="fetch_headers">OrderStatus</th>
            <th width="10%" height="27" align="left" valign="middle" class="fetch_headers">Client</th>
            <th width="10%" height="27" align="left" valign="middle" class="fetch_headers">TechName</th>
            <th width="6%" height="27" align="left" valign="middle" class="fetch_headers">ScheduledDate</th>
            <th width="8%" height="27" align="left" valign="middle" class="fetch_headers">OrderTotal</th>
            <th width="6%" height="27" align="left" valign="middle" class="fetch_headers">TechPay</th>
          </tr>
        </thead>
        <tbody>
        <?php
		foreach($resArray as $res_JobBoard){
			$clientname= ($res_JobBoard['created_by']<>'0') ? $dbf->getDataFromTable("clients","name","id='$res_JobBoard[created_by]'"):"COD";
			//calculating order billing total and tech pay
			$subtotal=0; $techsubtotal=0;
			$res_woservice = $dbf->simpleQuery("SELECT ws.quantity, ws.outbox_price, ws.tech_price FROM workorder_service ws LEFT JOIN equipment e ON ws.equipment=e.id LEFT JOIN work_type wt ON ws.work_type=wt.id WHERE ws.service_id='".$res_JobBoard['service_id']."' AND ws.workorder_id='".$res_JobBoard['id']."'");
			//print_r($res_woservice);
			foreach($res_woservice as $resServicePrice){
				$total = ($resServicePrice['quantity']*$resServicePrice['outbox_price']);
				$subtotal = $subtotal+$total; 
				$price=$resServicePrice['tech_price'];
				//$techtotal = ($resServicePrice['quantity']*$price);
				$techsubtotal = $techsubtotal+$price;
			}
			$techname = $res_JobBoard['first_name'].' '.$res_JobBoard['middle_name'].' '.$res_JobBoard['last_name'];
			$TrackingNumber=($res_JobBoard['tracking_number']!='')?$res_JobBoard['tracking_number']:'NIL';
			$PartsArrival=($res_JobBoard['parts_arrive']!='0000-00-00')?date('d-M-Y',strtotime($res_JobBoard['parts_arrive'])):'NIL';
		?>
         <tr>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_JobBoard['wo_no'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_JobBoard['purchase_order_no'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo date("d-M-Y",strtotime($res_JobBoard['created_date']));?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $dbf->cut($res_JobBoard['name'],15);?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_JobBoard['state_name'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_JobBoard['phone_no'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $TrackingNumber;?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $PartsArrival;?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_JobBoard['service_name'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_JobBoard['work_status'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $clientname;?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo ($techname!='  ')?$techname:'NIL';?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo ($res_JobBoard['start_date']<>'0000-00-00' && $res_JobBoard['start_date']<>NULL)? date("d-M-Y",strtotime($res_JobBoard['start_date'])):'NIL';?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents">$ <?php echo number_format($subtotal,2);?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents">$ <?php echo number_format($techsubtotal,2);?></td>
          </tr>
          <?php } ?>
          </tbody>
       </table>
       <?php }else{?>
        <table height="61" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#808080" width="100%" style="border:solid 1px #999;"> 
        <tr>
            <td  style="color:#F00; font-family:Verdana, Geneva, sans-serif; font-weight:bold;font-size:12px;" align="center"> Sorry No Records Found </td>
        </tr>
     </table>
    <?php }?>
</body>
