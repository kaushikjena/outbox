<?php  
   $fetch_data=$dbf->fetchSingle("work_order wo,clients c,service s","wo.client_id=c.id AND wo.service_id=s.id AND wo.wo_no='$res_JobBoard[wo_no]'");
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
    <td width="40%">Customer:</td>
    <td width="70%" height="30" align="left">
	<?php echo $fetch_data['name']; ?>
    </td>
    </tr>
    <tr>
    <td width="40%" >Sevice Type:</td>
    <td width="70%" height="30" align="left" >
	<?php echo $fetch_data['service_name']; ?>
    </td>
    </tr>
    <tr>
    <td width="40%" >Created Date:</td>
    <td width="70%" height="30" align="left">
	<?php echo date("d-M-Y",strtotime($fetch_data['created_date'])); ?>
    </td>
    </tr>
 </table>