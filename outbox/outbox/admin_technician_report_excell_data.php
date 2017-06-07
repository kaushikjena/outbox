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
            
            if($_REQUEST['Delivrycity']!=''){
                $sch=$sch."t.city like '$_REQUEST[Delivrycity]%' AND ";
            }
            if($_REQUEST['Delivrystate']!=''){
                $sch=$sch."t.state ='$_REQUEST[Delivrystate]' AND ";
            }
			if($_REQUEST['TechStatus']!=''){
				$sch=$sch."t.status='$_REQUEST[TechStatus]' AND ";
			}
            if($_REQUEST['FromDate']!='' && $_REQUEST['ToDate']==''){
                $sch=$sch."t.created_date >= '$fromdt' AND ";			
            }
            if($_REQUEST['FromDate']=='' && $_REQUEST['ToDate']!=''){
                $sch=$sch."t.created_date <= '$todt' AND ";
            }
            if(($_REQUEST['FromDate']!='') && ($_REQUEST['ToDate']!='')){
                $sch=$sch."t.created_date BETWEEN '$fromdt' AND '$todt' AND ";
            }
           $sch=substr($sch,0,-5);
       }
           //echo $sch;exit;
           if($sch!=''){
             $cond="t.state=s.state_code AND ".$sch;
           }
           elseif($sch==''){
             $cond="t.state=s.state_code";
           }	
           //print $cond;
           //count number of rows.		
        $num=$dbf->countRows("state s,technicians t",$cond);   
        if($num >0){ ?>
       <table height="61" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#808080" width="100%" style="border:solid 1px #999;"> 
        <thead>
          <tr bgcolor="#E6F9D5">
            <th width="9%" height="27" align="left" valign="middle" class="fetch_headers">Technician Name</th>
            <th width="7%" height="27" align="left" valign="middle" class="fetch_headers">Email ID</th>
            <th width="8%" height="27" align="left" valign="middle" class="fetch_headers">Contact No </th>
            <th width="5%" height="27" align="left" valign="middle" class="fetch_headers">Company Name</th>
            <th width="8%" height="27" align="left" valign="middle" class="fetch_headers">Address</th>
            <th width="7%" height="27" align="left" valign="middle" class="fetch_headers">City</th>
            <th width="9%" height="27" align="left" valign="middle" class="fetch_headers">State</th>
            <th width="7%" height="27" align="left" valign="middle" class="fetch_headers">Zip Code</th>
            <th width="7%" height="27" align="left" valign="middle" class="fetch_headers">Date</th>
            </tr>
        </thead>
        <?php
         foreach($dbf->fetchOrder("state s,technicians t",$cond,"t.id DESC","")as $res_tech) {   
        ?>
        <tbody>
          <tr>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_tech['first_name'].'&nbsp;'.$res_tech['middle_name'].'&nbsp;'.$res_tech['last_name']; ?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_tech['email'] ?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_tech['contact_phone'] ?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_tech['company_name'] ?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_tech['address'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_tech['city'] ?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_tech['state_name'] ?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_tech['zip_code'] ?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo date('d-m-Y',strtotime($res_tech['created_date']));?></td>
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
