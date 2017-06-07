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
            if($_REQUEST['srchTechnician']!=''){
		        $sch=$sch."t.id = '$_REQUEST[srchTechnician]' AND ";
			}
			if($_REQUEST['Delivrywo']!=''){
				$sch=$sch."wo.wo_no = '$_REQUEST[Delivrywo]' AND ";
			}
			if($_REQUEST['Customername']!=''){
				$sch=$sch."c.name like '$_REQUEST[Customername]%' AND ";
			}
			if($_REQUEST['Workstatus']!=''){
				$sch=$sch."wo.work_status like '$_REQUEST[Workstatus]%' AND ";
			}
           $sch=substr($sch,0,-5);
       }
           //echo $sch;exit;
           if($sch!=''){
			 $cond="c.id=wo.client_id AND wo.wo_no=at.wo_no AND at.tech_id=t.id AND wo.service_id=s.id AND wo.approve_status='1' AND ".$sch;
		   }elseif($sch==''){
			 $cond="c.id=wo.client_id AND wo.wo_no=at.wo_no AND at.tech_id=t.id AND wo.service_id=s.id AND wo.approve_status='1'";
		   }
           //print $cond;
           //count number of rows.		
        $num=$dbf->countRows("service s,clients c,technicians t,assign_tech at,work_order wo",$cond);   
        if($num >0){ ?>
       <table height="61" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#808080" width="100%" style="border:solid 1px #999;"> 
        <thead>
          <tr bgcolor="#E6F9D5">
           <th width="9%" height="27" align="left" valign="middle" class="fetch_headers">Work Order</th>
            <th width="9%" height="27" align="left" valign="middle" class="fetch_headers">Order Status</th>
            <th width="15%" height="27" align="left" valign="middle" class="fetch_headers">Technician Name </th>
            <th width="15%" height="27" align="left" valign="middle" class="fetch_headers">Customer Name</th>
            <th width="20%" height="27" align="left" valign="middle" class="fetch_headers">Service Type</th>
            <th width="12%" height="27" align="left" valign="middle" class="fetch_headers">Delivery City</th>
            </tr>
        </thead>
        <tbody>
        <?php
         foreach($dbf->fetchOrder("service s,clients c,technicians t,assign_tech at,work_order wo",$cond,"wo.id DESC","")as $res_tech) {   
        ?>
          <tr>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_tech['wo_no'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_tech['work_status'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_tech['first_name'].'&nbsp;'.$res_tech['middle_name'].'&nbsp;'.$res_tech['last_name'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_tech['name']?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_tech['service_name']?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_tech['city'];?></td>
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
