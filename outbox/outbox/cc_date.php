
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
    	<td align="center" style="border-bottom:1px dotted #ccc;"><b>Last CC Date</b></td>
    </tr>
    <tr>
        <td width="100%" align="left" >
        <?php
		 $lastDate=$dbf->getDataFromTable("workorder_notes","created_date","workorder_id='$res_JobBoard[id]' AND (user_type='admin' OR user_type='user' OR user_type='tech') AND customer_attempt <>0 ORDER BY id DESC LIMIT 1");
        echo ($lastDate !='')?date("d-M-Y",strtotime($lastDate)):'No contacts make yet.'; ?>
        </td>
    </tr>
 </table>