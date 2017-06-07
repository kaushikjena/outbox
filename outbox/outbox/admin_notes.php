<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
    	<td align="center" style="border-bottom:1px dotted #ccc;"><b>Admin Latest Comments</b></td>
    </tr>
    <tr>
        <td width="100%" align="left" >
        <?php
         $LatestNotes=$dbf->strRecordID("workorder_notes","wo_notes,created_date","workorder_id='$res_JobBoard[id]' AND (user_type='admin' || user_type='user' || user_type='client') AND customer_attempt=0 AND waiting_parts NOT IN(1,2)  ORDER BY id DESC LIMIT 1");
		    echo ($LatestNotes['wo_notes']!='')?$LatestNotes['wo_notes']:'No comments given yet.'; ?>
        </td>
    </tr>
    <tr>
    	<td width="100%" style="text-align:right"><?php echo ($LatestNotes['created_date'])?date("d-M-Y g:i A",strtotime($LatestNotes['created_date'])):'';?></td>	
    </tr>
 </table>