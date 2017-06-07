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
	 <?php $sch="";
       if($_REQUEST['hidaction'] == 1){
            $fromdt=date("Y-m-d",strtotime(($_REQUEST['FromDate'])));
			$todt=date("Y-m-d",strtotime(($_REQUEST['ToDate'])));
			
			if($_REQUEST['srchClient']!=''){
				$sch=$sch."w.created_by='$_REQUEST[srchClient]' AND ";
			}
			if($_REQUEST['srchTechnician']!=''){
				$sch=$sch."t.id='$_REQUEST[srchTechnician]' AND ";
			}
			if($_REQUEST['srchService']!=''){
				$sch=$sch."s.id='$_REQUEST[srchService]' AND ";
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
			 $cond="c.state=st.state_code AND c.id=w.client_id AND w.service_id=s.id AND w.work_status='Scheduled' AND w.approve_status='1' AND w.wo_no=at.wo_no AND at.tech_id=t.id AND ".$sch;
		  // echo $cond;exit;
		   }
		   elseif($sch==''){
			 $cond="c.state=st.state_code AND c.id=w.client_id AND w.service_id=s.id AND w.work_status='Scheduled' AND w.approve_status='1' AND w.wo_no=at.wo_no AND at.tech_id=t.id";
		   }	
           //print $cond;exit;
           //count number of rows.		
        $num=$dbf->countRows("state st,clients c,service s,technicians t,assign_tech at,work_order w",$cond);   
        if($num >0){  ?>
       <table height="61" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#808080" width="100%" style="border:solid 1px #999;"> 
        <thead>
          <tr bgcolor="#E6F9D5">
           <th width="6%" height="27" align="left" valign="middle" class="fetch_headers">WO No</th>
            <th width="8%" height="27" align="left" valign="middle" class="fetch_headers">Customer Name</th>
            <th width="8%" height="27" align="left" valign="middle" class="fetch_headers">CreatedDate</th>
            <th width="6%" height="27" align="left" valign="middle" class="fetch_headers">OrderStatus</th>
            <th width="8%" height="27" align="left" valign="middle" class="fetch_headers">ServiceType</th>
            <th width="8%" height="27" align="left" valign="middle" class="fetch_headers">PickupAddress</th>
            <th width="6%" height="27" align="left" valign="middle" class="fetch_headers">Pickupcity</th>
            <th width="10%" height="27" align="left" valign="middle" class="fetch_headers">PickupPhone</th>
            <th width="7%" height="27" align="left" valign="middle" class="fetch_headers">DeliveryCity</th>
            <th width="7%" height="27" align="left" valign="middle" class="fetch_headers">DeliveryState</th>
            <th width="10%" height="27" align="left" valign="middle" class="fetch_headers">Client</th>
            <th width="10%" height="27" align="left" valign="middle" class="fetch_headers">TechName</th>
            </tr>
        </thead>
        <tbody>
        <?php
          foreach($dbf->fetchOrder("state st,clients c,service s,technicians t,assign_tech at,work_order w",$cond,"w.id DESC","","")as  $res_JobBoard) {   
		  $techname =$dbf->fetchSingle("assign_tech at,technicians t","t.id=at.tech_id AND at.wo_no='$res_JobBoard[wo_no]'");
		  if($res_JobBoard['created_by']<>'0'){
			$clientname=$dbf->getDataFromTable("clients","name","id='$res_JobBoard[created_by]'");}else{
			$clientname="COD";
		}   
        ?>
          <tr>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_JobBoard['wo_no'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_JobBoard['name'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo date("d-M-Y",strtotime($res_JobBoard['created_date']));?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php if($res_JobBoard['work_status']<>''){?><?php echo $res_JobBoard['work_status'];?><?php } else{echo 'Not Started';}?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_JobBoard['service_name'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_JobBoard['pickup_address'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_JobBoard['pickup_city'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_JobBoard['pickup_phone_no'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_JobBoard['city'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_JobBoard['state_name'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $clientname;?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $techname['first_name'].' '.$techname['middle_name'].' '.$techname['last_name'];?></td>
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
