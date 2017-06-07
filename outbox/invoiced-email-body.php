<?php
	  	$body='<style type="text/css">
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
		</style>'; 
	  		if($_SERVER['HTTP_HOST'] == "box-ware.com"){
				$baseUrl="https://" . $_SERVER['HTTP_HOST']."/sys/outbox/";//Creating Base Url for SERVER
			}elseif($_SERVER['HTTP_HOST'] == "bletprojects.com"){
				$baseUrl="http://" . $_SERVER['HTTP_HOST']."/outbox/";//Creating Base Url for SERVER
			}else{
				$baseUrl="http://" . $_SERVER['HTTP_HOST'] ."/outbox/";//Creating Base Url for local
			}
			$implode_workorders = $_REQUEST['wonos'];
			$explode_workorders = $_REQUEST['wonos']?explode(",",$_REQUEST['wonos']):array();
			$BillTitle = ($_REQUEST['status']=='invoiced')? "Tech Invoiced Bill Payment For Period ".$_REQUEST['billperiod']:"Payment For Period ".$_REQUEST['billperiod'];
			$OrderStatus = ($_REQUEST['status']=='invoiced')? "Invoiced" :"Completed"; 
     	$body.='<table  border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#808080" width="100%" style="border:solid 1px #999;">
          	 <tr>
                <td width="19%" height="25" align="left" valign="top" class="fetch_contents"><img src="'.$baseUrl.'images/logo.png" height="100"  width="112" alt="Out Box Logo"/></td>
                <td width="50%" align="center" valign="middle" class="fetch_contents"><b>'.$BillTitle.'</b></td>
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
                <td align="left" valign="middle" class="fetch_contents">'.$resTech['first_name'].'&nbsp;'.$resTech['middle_name'].'&nbsp;'.$resTech['last_name'].'</td>
                <td height="25" align="left" valign="middle" class="fetch_contents">Email ID:</td>
                <td height="25" align="left" valign="middle" class="fetch_contents">'.$resTech['email'].'</td>
              </tr>
              <tr>
                <td height="25" align="left" valign="top" class="fetch_contents">Phone Number:</td>
                <td align="left" valign="top" class="fetch_contents">'.$resTech['contact_phone'].'</td>
                <td height="25" align="left" valign="top" class="fetch_contents">State:</td>
                <td height="25" align="left" valign="top" class="fetch_contents">'.$resTech['state_name'].'</td>
              </tr>
               <tr>
                <td height="25" align="left" valign="top" class="fetch_contents">Order Status:</td>
                <td align="left" valign="top" class="fetch_contents">'.$OrderStatus.'</td>
                <td height="25" align="left" valign="top" class="fetch_contents">Bill Period:</td>
                <td height="25" align="left" valign="top" class="fetch_contents"><b>'.$_REQUEST['billperiod'].'</b></td>
              </tr>
          </table><br/>';
		  $orderstatus =($_REQUEST['status']=='invoiced')? "Invoiced":"Completed";
		 $cond="c.id=w.client_id AND w.service_id=s.id AND w.approve_status='1' AND w.work_status='$orderstatus' AND at.wo_no=w.wo_no AND at.tech_id=t.id AND FIND_IN_SET(w.wo_no,'$implode_workorders')";
        //count number of rows.		
        $num=$dbf->countRows("clients c,service s,technicians t,assign_tech at,work_order w",$cond);   
        if($num >0){ 
       $body.='<table border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#808080" width="100%" style="border:solid 1px #999;"> 
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
        <tbody>';
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
		
         $body.='<tr>
            <td height="25" align="left" valign="middle" class="fetch_contents">'.date("d-M-Y",strtotime($res_clientBill['start_date'])).'</td>
            <td height="25" align="left" valign="middle" class="fetch_contents">'.$res_clientBill['service_name'].'</td>
       		<td height="25" align="left" valign="middle" class="fetch_contents">'.$workType.'</td>
            <td height="25" align="left" valign="middle" class="fetch_contents">'.$model.'</td>
            <td height="25" align="left" valign="middle" class="fetch_contents">'.$res_clientBill['name'].'</td>
            <td height="25" align="left" valign="middle" class="fetch_contents">'.$res_clientBill['wo_no'].'</td>
           	<td height="25" align="left" valign="middle" class="fetch_contents">'.$res_clientBill['purchase_order_no'].'</td>
           <td height="25" align="center" valign="middle" class="fetch_contents">$ '.number_format($subtotal,2).'</td>
          </tr>';
        	} 
         $body.='<tr>
            <td height="25" align="left" valign="middle" class="fetch_contents">&nbsp;</td>
            <td height="25" align="left" valign="middle" class="fetch_contents">&nbsp;</td>
            <td height="25" align="left" valign="middle" class="fetch_contents">&nbsp;</td>
            <td height="25" align="left" valign="middle" class="fetch_contents">&nbsp;</td>
            <td height="25" align="left" valign="middle" class="fetch_contents">&nbsp;</td>
            <td height="25" align="left" valign="middle" class="fetch_contents">&nbsp;</td>
           	<td height="25" align="right" valign="middle" class="fetch_contents"><b>Grand Total:</b></td>
           	<td height="25" align="center" valign="middle" class="fetch_contents"><b>$ '.number_format($grandtotal,2).'</b></td>
          </tr>
         </tbody>
       </table>';
       }
?>