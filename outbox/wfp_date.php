
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
    	<td align="center" style="border-bottom:1px dotted #ccc;"><b>Last WFP Date</b></td>
    </tr>
    <tr>
        <td width="100%" align="left" >
        <?php
		 $lastwfpDate=$dbf->getDataFromTable("workorder_notes","created_date","workorder_id='$res_JobBoard[id]' AND (user_type='admin' OR user_type='user' OR user_type='tech') AND waiting_parts!=0 ORDER BY id DESC LIMIT 1");
        echo ($lastwfpDate !='')?date("d-M-Y",strtotime($lastwfpDate)):'No waiting for parts yet.'; ?>
        </td>
    </tr>
 </table>