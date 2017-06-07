<?php 
	ob_start();
	session_start();
	include_once 'includes/class.Main.php';
	//Object initialization
	$dbf = new User();
	#############Search Conditions#####################
	##########STORE AND RETRIVE THE SEARCH CONDITION FROM SESSION##########
	$FromDate=$_SESSION['request']['FromDate'];
	$ToDate=$_SESSION['request']['ToDate'];
	$srchCust=$_SESSION['request']['srchCust'];
	$srchClient=$_SESSION['request']['srchClient'];
	$srchTechnician=$_SESSION['request']['srchTechnician'];
	$srchService=$_SESSION['request']['srchService'];
	$srchStatus=$_SESSION['request']['srchStatus'];
	##########STORE AND RETRIVE THE SEARCH CONDITION FROM SESSION##########
	$sch=""; 
	$fromdt=date("Y-m-d",strtotime(($FromDate)));
	$todt=date("Y-m-d",strtotime(($ToDate)));
	
	if($srchCust !=''){
		$sch=$sch."c.id='$srchCust' AND ";
	}
	if($srchClient !=''){
		$sch=$sch."w.created_by='$srchClient' AND ";
	}
	if($srchTechnician !=''){
		$sch=$sch."t.id='$srchTechnician' AND ";
	}
	if($srchService !=''){
		$sch=$sch."s.id='$srchService' AND ";
	}
	if($srchStatus !=''){
		$sch=$sch."w.work_status='$srchStatus' AND ";
	}
	if($FromDate !='' && $ToDate ==''){
		$sch=$sch."at.start_date >= '$fromdt' AND ";
	}
	if($FromDate =='' && $ToDate !=''){
		$sch=$sch."at.start_date <= '$todt' AND ";
	}
	if(($FromDate !='') && ($ToDate !='')){
		$sch=$sch."at.start_date BETWEEN '$fromdt' AND '$todt' AND ";
	}
   $sch=substr($sch,0,-5);
   if($sch!=''){
	 $cond="c.state=st.state_code AND c.id=w.client_id AND w.service_id=s.id AND w.work_status='Assigned' AND w.approve_status='1' AND w.wo_no=at.wo_no AND at.tech_id=t.id AND ".$sch;
   }
   elseif($sch==''){
	 $cond="c.state=st.state_code AND c.id=w.client_id AND w.service_id=s.id AND w.work_status='Assigned' AND w.approve_status='1' AND w.wo_no=at.wo_no AND at.tech_id=t.id AND (at.start_date=CURDATE() OR at.start_date='0000-00-00')";
   }
   //condition for order by
   if($_REQUEST['cmbColumn']=='' && $_REQUEST['cmbType']==''){
   		$orderby = "w.id DESC";
   }else{
	  	$orderby = "$_REQUEST[cmbColumn] $_REQUEST[cmbType]"; 
   }
  #############Search Conditions##################### 
?>
<script  type="text/javascript" src="js/dragtable.js"></script>
<!-----Table area start------->
<table id="no-more-tables" class="draggable">
    <thead>
        <tr>
            <th width="6%">WO#</th>
            <th width="9%">CustomerName</th>
            <th width="8%">OrderStatus</th>
            <th width="10%">ServiceType</th>
            <th width="6%">PickupState</th>
            <th width="6%">Pickupcity</th>
            <th width="8%">DeliveryState</th>
            <th width="6%">DeliveryCity</th>
            <th width="9%">ClientName</th>
            <th width="10%">TechName</th>
            <th width="7%">StartDate</th>
            <th width="6%">StartTime</th>
            <th width="11%">Action</th>
        </tr>
    </thead>
    <tbody>
    <tr style="background-color:#f9f9f9;">
        <td valign="top" class="grheading">
        <div class="divgr">
            <a href="javascript:void(0);" onClick="funShow1('ho','ro');" id="expand" style="display:none;"><img  src="images/expand.png" height="21" width="73" alt="Expand All" /></a> 
            <a href="javascript:void(0);" onClick="funHide1('ho','ro');" id="colapse" ><img  src="images/collapse.png"  height="21" width="73" alt="Collapse All"/></a>
        </div>
        </td>
        <td class="hiderow">&nbsp;</td>
        <td class="hiderow">&nbsp;</td>
        <td class="hiderow">&nbsp;</td>
        <td class="hiderow">&nbsp;</td>
        <td class="hiderow">&nbsp;</td>
        <td class="hiderow">&nbsp;</td>
        <td class="hiderow">&nbsp;</td>
        <td class="hiderow">&nbsp;</td>
        <td class="hiderow">&nbsp;</td>
        <td class="hiderow">&nbsp;</td>
        <td class="hiderow">&nbsp;</td>
        <td class="hiderow">&nbsp;</td>
    </tr>
    <?php 
        $num=$dbf->countRows("state st,clients c,service s,technicians t,assign_tech at,work_order w",$cond); 
        $resGrArray=$dbf->fetchOrder("state st,clients c,service s,technicians t,assign_tech at,work_order w",$cond,"c.state ASC","st.*,c.*","c.state");
        //group by state loop
        foreach($resGrArray as $k=>$sgRes){
        $Cls="g$k";	
        $numres = $dbf->countRows("state st,clients c,service s,technicians t,assign_tech at,work_order w","c.state='$sgRes[state]' AND " .$cond);
    ?>
    <tr style="background-color:#f9f9f9;" class="ho">
        <td valign="top" class="grheading">
        <div class="divgr">
        <a href="javascript:void(0);" onClick="funShow('<?php echo $Cls;?>','<?php echo $k;?>');" id="e<?php echo $k;?>" <?php if($k==0){?>style="display:none;" <?php }?> class="hoa"><img  src="images/plus.gif" height="13" width="13"/>&nbsp;<?php echo $numres;?> Jobs in &nbsp;<span style="color:#ff9812;"><?php echo $sgRes['state_name'];?></span> assigned to Tech</a> 
        <a href="javascript:void(0);" onClick="funHide('<?php echo $Cls;?>','<?php echo $k;?>');" id="c<?php echo $k;?>" <?php if($k!=0){?>style="display:none;" <?php }?> class="hob"><img  src="images/minus.gif" height="13" width="13"/>&nbsp;<?php echo $numres;?> Jobs in &nbsp;<span style="color:#ff9812;"><?php echo $sgRes['state_name'];?></span> assigned to Tech</a>
        </div>
        </td>
        <td class="hiderow">&nbsp;</td>
        <td class="hiderow">&nbsp;</td>
        <td class="hiderow">&nbsp;</td>
        <td class="hiderow">&nbsp;</td>
        <td class="hiderow">&nbsp;</td>
        <td class="hiderow">&nbsp;</td>
        <td class="hiderow">&nbsp;</td>
        <td class="hiderow">&nbsp;</td>
        <td class="hiderow">&nbsp;</td>
        <td class="hiderow">&nbsp;</td>
        <td class="hiderow">&nbsp;</td>
        <td class="hiderow">&nbsp;</td>
    </tr>				
    <?php
        $resArray=$dbf->fetchOrder("state st,clients c,service s,technicians t,assign_tech at,work_order w","c.state='$sgRes[state]' AND " .$cond,$orderby,"st.state_name,c.*,w.*,s.service_name,t.first_name,t.middle_name,t.last_name,at.start_date,at.start_time","");
        foreach($resArray as $key=>$res_JobBoard) { 
        $pickupstate = $dbf->getDataFromTable("state","state_name","state_code='$res_JobBoard[pickup_state]'");
        if($res_JobBoard['work_status']=='Completed'){
            //check for payment completed work orders
            $paymentstatus = $dbf->getDataFromTable("work_order_bill","payment_status","wo_no='$res_JobBoard[wo_no]'");
            if($paymentstatus<>'Completed'){
                $color="#090";	
            }else{
                $color="#0FCBFF";
            }
            //$rcolor='background-color:#F3FCEB';
            //$link = 'javascript:void(0)';
			//$link = 'edit-job-board?id='.$res_JobBoard['id'].'&src=disp';
        }else{
            //$rcolor='';
            $color='#F00';
            //$link = 'edit-job-board?id='.$res_JobBoard['id'].'&src=disp';
        }
        
        if($res_JobBoard['created_by']<>'0'){
            $clientname=$dbf->getDataFromTable("clients","name","id='$res_JobBoard[created_by]'");}else{
            $clientname="COD";
        }
     ?>
    <tr class="<?php echo $Cls;?> ro" <?php if($k!=0){?> style="display:none;"<?php }?>>
        <td data-title="WO#" class="coltext">
        <a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_JobBoard['id'];?>','view-job-board');" title="Click Here For Job Details" style="color:<?php echo $color;?>"><?php echo $res_JobBoard['wo_no'];?></a></td>
        <td data-title="CustomerName"><?php echo $dbf->cut($res_JobBoard['name'],15);?></td>
        <td data-title="WorkStatus" style="font-weight:bold;" id="workstatus" class="coltext"><?php if($res_JobBoard['work_status']<>''){?><a href="javascript:void(0);" onClick="Show_Workstatus('<?php echo $res_JobBoard['wo_no'];?>')" title="Click Here To See WorkStatus"><?php echo $res_JobBoard['work_status'];?></a><?php } else{echo 'Not Started';}?></td>
        <td data-title="ServiceType"><?php echo $res_JobBoard['service_name'];?></td>
        <td data-title="PickupState"><?php echo $pickupstate ;?></td>
        <td data-title="Pickupcity"><?php echo $res_JobBoard['pickup_city'];?></td>
        <td data-title="DeliveryState"><?php echo $res_JobBoard['state_name'];?></td>
        <td data-title="DeliveryCity"><?php echo $res_JobBoard['city'];?></td>
        <td data-title="Client"><?php echo $clientname;?></td>
        <td data-title="TechName"><?php echo $res_JobBoard['first_name'].' '.$res_JobBoard['middle_name'].' '.$res_JobBoard['last_name'];?></td>
        <td data-title="StartDate"><?php if($res_JobBoard['start_date']<>'0000-00-00'){echo date("d-M-Y",strtotime($res_JobBoard['start_date']));}else{echo 'None';}?></td>
       <td data-title="StartTime"><?php if($res_JobBoard['start_time']){echo $res_JobBoard['start_time'];}else{echo 'None';}?></td>                              
        <td data-title="Action"><a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_JobBoard['id'];?>','edit-job-board');"><img src="images/edit.png" title="Edit" alt="Edit"/></a>&nbsp;<a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_JobBoard['id'];?>','view-job-board');"><img src="images/view.png" title="View" alt="View"/></a>&nbsp;<a href="manage-job-board-dispatch?action=cancel&id=<?php echo $res_JobBoard['id'];?>"><img src="images/cancel_round.png" title="Cancel" alt="Cancel"/></a>&nbsp;<a href="javascript:void(0);"  onClick="print_doc('print','<?php echo $res_JobBoard['id'];?>');" ><img src="images/print.png" alt="Print"  title="Print Workorder"></a>&nbsp;<a href="javascript:void(0);" onClick="print_doc('pdf','<?php echo $res_JobBoard['id'];?>');"><img src="images/pdf.png" style="width:16px; height:16px;" title="Export to PDF"/></a></td>
   </tr>
	<?php }
    }
    ?>
	</tbody>
</table>
<!-----Table area end------->
<?php if($num == 0) {?><div class="noRecords" align="center">No records founds!!</div><?php }?>
