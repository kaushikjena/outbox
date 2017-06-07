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
				$sch=$sch."w.invoice_no_update_date >= '$fromdt' AND ";
			}
			if($_REQUEST['FromDate']=='' && $_REQUEST['ToDate']!=''){
				$sch=$sch."w.invoice_no_update_date <= '$todt' AND ";
			}
			if(($_REQUEST['FromDate']!='') && ($_REQUEST['ToDate']!='')){
				$sch=$sch."w.invoice_no_update_date BETWEEN '$fromdt' AND '$todt' AND ";
			}
		   $sch=substr($sch,0,-5);
       }
           //echo $sch;exit; 
           if($sch!=''){
			 $cond="c.state=st.state_code AND c.id=w.client_id AND w.service_id=s.id AND w.approve_status='1' AND (w.work_status='Completed' OR w.work_status='Invoiced') AND w.wo_no=at.wo_no AND at.tech_id=t.id AND ".$sch;
		  // echo $cond;exit;
		   }
		   elseif($sch==''){
			 $cond="c.state=st.state_code AND c.id=w.client_id AND w.service_id=s.id AND w.approve_status='1' AND (w.work_status='Completed' OR w.work_status='Invoiced') AND w.wo_no=at.wo_no AND at.tech_id=t.id";
		   }	
           //print $cond;exit;
           //count number of rows.		
        $num=$dbf->countRows("state st,clients c,service s,technicians t,assign_tech at,work_order w",$cond);   
        if($num >0){ ?>
       <table border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#808080" width="100%" style="border:solid 1px #999;"> 
        <thead>
          <tr bgcolor="#E6F9D5">
           	<th width="8%" height="27" align="left" valign="middle" class="fetch_headers">WO#</th>
            <th width="10%" height="27" align="left" valign="middle" class="fetch_headers">PO#</th>
            <th width="10%" height="27" align="left" valign="middle" class="fetch_headers">Invoice#</th>
            <th width="8%" height="27" align="left" valign="middle" class="fetch_headers">OrderStatus</th>
            <th width="10%" height="27" align="left" valign="middle" class="fetch_headers">ServiceName</th>
            <th width="12%" height="27" align="left" valign="middle" class="fetch_headers">Costomer Name</th>
            <th width="9%" height="27" align="left" valign="middle" class="fetch_headers">DeliveryCity</th>
            <th width="9%" height="27" align="left" valign="middle" class="fetch_headers">DeliveryState</th>
            <th width="12%" height="27" align="left" valign="middle" class="fetch_headers">Client</th>
            <th width="12%" height="27" align="left" valign="middle" class="fetch_headers">TechName</th>
          </tr>
        </thead>
        <tbody>
        <?php
         foreach($dbf->fetchOrder("state st,clients c,service s,technicians t,assign_tech at,work_order w",$cond,"w.id DESC","st.state_name, c.city, c.name, s.service_name, w.wo_no, w.purchase_order_no, w.work_status, w.created_by,w.invoice_no, t.first_name,t.middle_name,t.last_name","")as  $res_JobBoard) {   
		//get client name 
		 if($res_JobBoard['created_by']<>'0'){
			$clientname=$dbf->getDataFromTable("clients","name","id='$res_JobBoard[created_by]'");}else{
			$clientname="COD";
		}   
        ?>
          <tr>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_JobBoard['wo_no'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_JobBoard['purchase_order_no'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_JobBoard['invoice_no'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_JobBoard['work_status'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_JobBoard['service_name'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_JobBoard['name'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_JobBoard['city'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_JobBoard['state_name'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $clientname;?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_JobBoard['first_name'].' '.$res_JobBoard['middle_name'].' '.$res_JobBoard['last_name'];?></td>
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
