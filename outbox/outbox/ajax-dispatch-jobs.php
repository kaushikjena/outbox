<?php 
ob_start();
session_start();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();
//retrive state codes
$arrState=array();
if($_REQUEST['statecode']<>''){
	foreach($_REQUEST['statecode'] as $val){
		array_push($arrState,"'".$val."'");
	}
}
$statecode= !empty($arrState)?implode(",",$arrState):"''";
$cond = $_REQUEST['cond'];//exit;
?>
<script  type="text/javascript" src="js/dragtable.js"></script>
<script  type="text/javascript" src="js/sorttable.js"></script>
<table id="no-more-tables" class="draggable sortable">
    <thead>
        <tr>
            <th width="6%">WO#</th>
            <th width="9%">CustomerName</th>
            <th width="8%">CreatedDate</th>
            <!--<th width="6%">JobStatus</th>-->
            <th width="8%">OrderStatus</th>
            <th width="7%">ServiceType</th>
            <th width="6%">PickupState</th>
            <th width="6%">Pickupcity</th>
            <th width="8%">PickupPhone</th>
            <th width="6%">DeliveryCity</th>
            <th width="8%">DeliveryState</th>
            <th width="8%">Client</th>
            <th width="9%">TechName</th>
            <th width="5%">Action</th>
        </tr>
    </thead>
    <tbody>
    <?php
		$num=$dbf->countRows("state st,clients c,service s,technicians t,assign_tech at,work_order w","c.state IN($statecode) AND " .$cond);
        ##########Loop for fetching records of selected state###########
        $resArray=$dbf->fetchOrder("state st,clients c,service s,technicians t,assign_tech at,work_order w","c.state IN($statecode) AND " .$cond,"w.id DESC","*,t.state as tstate","");
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
            $link = 'javascript:void(0)';
        }else{
            //$rcolor='';
            $color='#F00';
            $link = 'edit-job-board?id='.$res_JobBoard['id'].'&src=disp';
        }
        
        if($res_JobBoard['created_by']<>'0'){
            $clientname=$dbf->getDataFromTable("clients","name","id='$res_JobBoard[created_by]'");}else{
            $clientname="COD";
        }
     ?>
    <tr class="<?php echo $Cls;?>">
    <td data-title="WO#" class="coltext">
    <a href="view-job-board?id=<?php echo $res_JobBoard['id'];?>&src=disp" title="Click Here For Job Details" style="color:<?php echo $color;?>"><?php echo $res_JobBoard['wo_no'];?></a></td>
    <td data-title="CustomerName"><?php echo $res_JobBoard['name'];?></td>
    <td data-title="CreatedDate"><?php echo date("d-M-Y",strtotime($res_JobBoard['created_date']));?></td>                          
    <!--<td data-title="JobStatus" class="coltext" style="font-weight:bold;"><?php //echo $res_JobBoard['work_status'];?></td>-->
    <td data-title="WorkStatus" style="font-weight:bold;" id="workstatus" class="coltext"><?php if($res_JobBoard['work_status']<>''){?><a href="javascript:void(0);" onClick="Show_Workstatus('<?php echo $res_JobBoard['wo_no'];?>')" title="Click Here To See WorkStatus"><?php echo $res_JobBoard['work_status'];?></a><?php } else{echo 'Not Started';}?></td>
    <td data-title="ServiceType"><?php echo $res_JobBoard['service_name'];?></td>
    <td data-title="PickupState"><?php echo $pickupstate ;?></td>                                    <td data-title="Pickupcity"><?php echo $res_JobBoard['pickup_city'];?></td>
    <td data-title="PickupPhone"><?php echo $res_JobBoard['pickup_phone_no'];?></td>                                    <td data-title="DeliveryCity"><?php echo $res_JobBoard['city'];?></td>
    <td data-title="DeliveryState"><?php echo $res_JobBoard['state_name'];?></td>                                    <td data-title="Client"><?php echo $clientname;?></td>
    <td data-title="TechName"><?php echo $res_JobBoard['first_name'].' '.$res_JobBoard['middle_name'].' '.$res_JobBoard['last_name'];?></td>          
    <td data-title="Action"><a href="<?php echo $link;?>"><img src="images/edit.png" title="Edit" alt="Edit"/></a>&nbsp;&nbsp;<a href="view-job-board?id=<?php echo $res_JobBoard['id'];?>&src=disp"><img src="images/view.png" title="View" alt="View"/></a></td>
</tr>
<?php }
//}
?>
</tbody>
</table>
<?php if($num == 0) {?><div class="noRecords" align="center">No records founds!!</div><?php }?>