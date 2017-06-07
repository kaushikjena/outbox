<?php
ob_start();
session_start();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();
$res_viewJobBoard=$dbf->fetchSingle("state st,clients c,service s,work_order w","c.state=st.state_code AND c.id=w.client_id AND w.service_id=s.id AND w.id='$_REQUEST[hid]'");
//technician details
$resTech = $dbf->fetchSingle("assign_tech at,technicians tc","at.tech_id=tc.id AND at.wo_no='$res_viewJobBoard[wo_no]'");
//technician workorder details
$resTechDetails = $dbf->fetchSingle("work_order_tech","tech_id='$resTech[id]' AND wo_no='$res_viewJobBoard[wo_no]' ORDER BY id DESC");
	if($resTech['pay_grade']=='A'){
	 	$techgrade= 'Grade A';
	}elseif($resTech['pay_grade']=='B'){
		 $techgrade= 'Grade B';
	}elseif($resTech['pay_grade']=='C'){
	 	$techgrade= 'Grade C';
	}elseif($resTech['pay_grade']=='D'){
	 	$techgrade= 'Grade D';
	}				
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
    <div  align="center" class="fetch_headers" style="width:100%;color:#EF8510; margin-bottom:20px;padding-top:10px; padding-bottom:10px;">Technician Workorder Invoice</div>
	<div  class="fetch_headers" style="width:100%;color:#090; margin-bottom:10px;">Work Order Details:</div>
	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td width="15%" align="left" class="fetch_headers">WO#:</td>
          <td width="35%" align="left" class="fetch_contents"><?php echo $res_viewJobBoard['wo_no'];?></td>
          <td width="15%" align="left" class="fetch_headers">Work Status:</td>
          <td width="35%" align="left" class="fetch_contents"><?php echo $res_viewJobBoard['work_status'];?></td>
        </tr>
        <tr>
          <td width="15%" align="left" class="fetch_headers">Job Status:</td>
          <td width="35%" align="left" class="fetch_contents"><?php echo $res_viewJobBoard['job_status'];?></td>
          <td width="15%" align="left" class="fetch_headers">Service Name:</td>
          <td width="35%" align="left" class="fetch_contents"><?php echo $res_viewJobBoard['service_name'];?></td>
        </tr>
        <tr>
          <td width="15%" align="left" class="fetch_headers">Customer Name</td>
          <td width="35%" align="left" class="fetch_contents"><?php echo $res_viewJobBoard['name'];?></td>
          <td width="15%" align="left" class="fetch_headers">Email ID:</td>
          <td width="35%" align="left" class="fetch_contents"><?php echo $res_viewJobBoard['email'];?></td>
        </tr>
        <tr>
          <td width="15%" align="left" class="fetch_headers">Phone Number</td>
          <td width="35%" align="left" class="fetch_contents"><?php echo $res_viewJobBoard['phone_no'];?></td>
          <td width="15%" align="left" class="fetch_headers">State:</td>
          <td width="35%" align="left" class="fetch_contents"><?php echo $res_viewJobBoard['state_name'];?></td>
        </tr>
        <tr>
          <td width="15%" align="left" class="fetch_headers">Technician</td>
          <td width="35%" align="left" class="fetch_contents"><?php echo $resTech['first_name'].'&nbsp;'.$resTech['middle_name'].'&nbsp;'.$resTech['last_name'];?></td>
          <td width="15%" align="left" class="fetch_headers">Completed Date:</td>
          <td width="35%" align="left" class="fetch_contents"><?php if(!empty($resTech)){echo date("d-M-Y",strtotime($resTechDetails['arrival_date'])).' '.$resTechDetails['depart_time'];}?></td>
        </tr>
        <tr>
          <td width="15%" align="left" class="fetch_headers">PayGrade</td>
          <td width="35%" align="left" class="fetch_contents"><?php echo $techgrade;?></td>
          <td width="15%" align="left" class="fetch_headers">Payble To:</td>
          <td width="35%" align="left" class="fetch_contents"><?php echo $resTech['payble_to'];?></td>
        </tr>
    </table>
    <div  class="fetch_headers" style="width:100%;color:#090; margin-bottom:10px; margin-top:10px">Work Order Bill:</div>
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td width="25%" height="30" align="left" class="fetch_headers">Work Type:</td>
          <td width="25%" height="30" align="left" class="fetch_headers">Equipment</td>
          <td width="20%" height="30" align="left" class="fetch_headers">Model:</td>
          <td width="10%" height="30" align="center" class="fetch_headers">Qnty</td>
          <td width="10%" height="30" align="center" class="fetch_headers">Price </td>
          <td width="10%" height="30" align="center" class="fetch_headers">Total</td>
        </tr>
         <?php 
           $subtotal=0;
           $res_woservice = $dbf->fetch("equipment e,work_type wt,workorder_service ws","e.id=ws.equipment AND wt.id=ws.work_type AND ws.service_id='$res_viewJobBoard[service_id]' AND workorder_id='$_REQUEST[hid]'");
          		
				foreach($res_woservice as $resServicePrice){
                    if($resTech['pay_grade']=='A'){
                        $price=$resServicePrice['gradeA_price'];
                    }elseif($resTech['pay_grade']=='B'){
                        $price=$resServicePrice['gradeB_price'];
                    }elseif($resTech['pay_grade']=='C'){
                        $price=$resServicePrice['gradeC_price'];
                    }elseif($resTech['pay_grade']=='D'){
                        $price=$resServicePrice['gradeD_price'];
                    }
                    
                    $total = ($resServicePrice['quantity']*$price);
                    $subtotal = $subtotal+$total; 
          ?>
            <tr>
              <td width="25%" align="left" class="fetch_contents"><?php echo $resServicePrice['worktype'];?></td>
              <td width="25%" align="left" class="fetch_contents"><?php echo $resServicePrice['equipment_name'];?></td>
              <td width="20%" align="left" class="fetch_contents"><?php echo $resServicePrice['model'];?></td>
              <td width="10%" align="center" class="fetch_contents"><?php echo $resServicePrice['quantity'];?></td>
              <td width="10%" align="center" class="fetch_contents">$ <?php echo $price;?></td>
              <td width="10%" align="center" class="fetch_contents">$ <?php echo $total;?></td>
            </tr>
        <?php }?>
        <tr>
          <td width="25%" align="left">&nbsp;</td>
          <td width="25%" align="left">&nbsp;</td>
          <td width="20%" align="left">&nbsp;</td>
          <td width="10%" align="center">&nbsp;</td>
          <td width="10%" align="center" class="fetch_headers">Subtotal</td>
          <td width="10%" align="center" class="fetch_headers">$ <?php echo $subtotal;?></td>
        </tr>
      </table>
      <div  class="fetch_headers" style="width:100%;color:#090; margin-bottom:10px; margin-top:10px">Signature Block:</div>
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
    
</body>