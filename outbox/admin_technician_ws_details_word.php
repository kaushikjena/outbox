<?php
ob_start();
session_start();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();
//Important below 2 lines
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment; Filename=admin_technician_workstatus_report.doc");
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
        //count number of rows.		
        $num=$dbf->countRows("work_order_tech"," wo_no='$_REQUEST[wo_no]'");   
        if($num >0){ ?>
       <table height="61" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#808080" width="100%" style="border:solid 1px #999;"> 
        <thead>
          <tr bgcolor="#E6F9D5">
            <th width="15%" height="27" align="left" valign="middle" class="fetch_headers">Order Status</th>
            <th width="15%" height="27" align="left" valign="middle" class="fetch_headers">Arrival Date</th>
            <th width="15%" height="27" align="left" valign="middle" class="fetch_headers">Arrival Time </th>
            <th width="15%" height="27" align="left" valign="middle" class="fetch_headers">Departure Time</th>
            <th width="15%" height="27" align="left" valign="middle" class="fetch_headers">Duration</th>
            <th width="15%" height="27" align="left" valign="middle" class="fetch_headers">Price</th>
            </tr>
        </thead>
        <tbody>
        <?php
         foreach($dbf->fetchOrder("work_order_tech"," wo_no='$_REQUEST[wo_no]'","id ASC","")as $res_tech) {   
        ?>
          <tr>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_tech['work_status'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_tech['arrival_date'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_tech['arrival_time'];?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_tech['depart_time'];?></td>  
            <?php $tm_diff=gmdate('H:i:s',strtotime($res_tech['depart_time'])-strtotime($res_tech['arrival_time'])); ?>  
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $tm_diff;?></td>
            <td height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_tech['price'];?></td>
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
