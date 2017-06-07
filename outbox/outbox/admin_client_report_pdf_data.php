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
	  <?php 
       $sch="";
       if($_REQUEST['hidaction'] == 1){
            $fromdt=date("Y-m-d",strtotime(($_REQUEST['FromDate'])));
            $todt=date("Y-m-d",strtotime(($_REQUEST['ToDate'])));
            if($_REQUEST['Delivrycity']!=''){
                $sch=$sch."c.city like '$_REQUEST[Delivrycity]%' AND ";
            }
            if($_REQUEST['Delivrystate']!=''){
                $sch=$sch."c.state ='$_REQUEST[Delivrystate]' AND ";
            }
            if($_REQUEST['FromDate']!='' && $_REQUEST['ToDate']==''){
                $sch=$sch."c.created_date >= '$fromdt' AND ";			
            }
            if($_REQUEST['FromDate']=='' && $_REQUEST['ToDate']!=''){
                $sch=$sch."c.created_date <= '$todt' AND ";
            }
            if(($_REQUEST['FromDate']!='') && ($_REQUEST['ToDate']!='')){
                $sch=$sch."c.created_date BETWEEN '$fromdt' AND '$todt' AND ";
            }
           $sch=substr($sch,0,-5);
       }
           //echo $sch;exit;
           if($sch!=''){
             $cond="c.state=s.state_code AND c.status=1 AND c.user_type='client' AND ".$sch;
           }
           elseif($sch==''){
             $cond="c.state=s.state_code AND c.status=1 AND c.user_type='client'";
           }	
           //print $cond;
           //count number of rows.		
        $num=$dbf->countRows("state s,clients c",$cond);   
        if($num >0){ ?>
       <table border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#808080" width="100%" style="border:solid 1px #999;"> 
        <thead>
          <tr bgcolor="#E6F9D5">
            <td width="15%" height="27" align="left" valign="middle" class="fetch_headers">Client Name</td>
            <td width="15%" height="27" align="left" valign="middle" class="fetch_headers" >Email ID</td>
            <td width="10%" height="27" align="left" valign="middle" class="fetch_headers">Contact No </td>
            <td width="10%" height="27" align="left" valign="middle" class="fetch_headers">Fax No</td>
            <td width="10%" height="27" align="left" valign="middle" class="fetch_headers">Address</td>
            <td width="10%" height="27" align="left" valign="middle" class="fetch_headers">City</td>
            <td width="10%" height="27" align="left" valign="middle" class="fetch_headers">State</td>
            <td width="10%" height="27" align="left" valign="middle" class="fetch_headers" >Zip Code</td>
            <td width="10%" height="27" align="left" valign="middle" class="fetch_headers">Date</td>
         </tr>
        </thead>
        <?php
         foreach($dbf->fetchOrder("state s,clients c",$cond,"c.id DESC","")as $res_client) { 
        ?>
          <tr>
            <td width="15%" height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_client['name'] ?></td>
            <td width="15%" height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_client['email'] ?></td>
            <td width="10%" height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_client['phone_no'] ?></td>
            <td width="10%" height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_client['fax_no'] ?></td>
            <td width="10%" height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_client['address'];?></td>
            <td width="10%" height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_client['city'] ?></td>
            <td width="10%" height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_client['state_name'] ?></td>
            <td width="10%" height="25" align="left" valign="middle" class="fetch_contents"><?php echo $res_client['zip_code'] ?></td>
            <td width="10%" height="25" align="left" valign="middle" class="fetch_contents"><?php echo date('d-m-Y',strtotime($res_client['created_date']));?></td>
          </tr>
          <?php } ?>
          
       </table>
       <?php }else{?>
		<table border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#808080" width="100%" style="border:solid 1px #999;"> 
            <tr>
                <td  style="color:#F00; font-family:Verdana, Geneva, sans-serif; font-weight:bold;font-size:12px;" align="center"> Sorry No Records Found </td>
            </tr>
        </table>
       <?php }?>

