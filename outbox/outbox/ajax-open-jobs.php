<?php 
ob_start();
session_start();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();
ob_clean();
//retrive state codes
$arrState=array();
if($_REQUEST['statecode']<>''){
	foreach($_REQUEST['statecode'] as $val){
		array_push($arrState,"'".$val."'");
	}
}
$statecode= !empty($arrState)?implode(",",$arrState):"''";
$cond = $_REQUEST['cond'];//exit;

//ob_clean();
?>

<table id="no-more-tables" class="draggable sortable">
    <thead>
        <tr>
            <th width="6%">WO#</th>
            <th width="9%">CustomerName</th>
            <th width="8%">CreatedDate</th>
            <th width="6%">OrderStatus</th>
            <th width="8%">ServiceType</th>
            <th width="7%">Pickupcity</th>
            <th width="6%">PickupState</th>
            <th width="8%">PickupPhone</th>
            <th width="8%">DeliveryCity</th>
            <th width="6%">DeliveryState</th>
            <th width="8%">DeliveryPhone</th>
            <th width="8%">Client</th>
            <th width="6%">Assign</th>
            <th width="6%">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
			$num=$dbf->countRows("state st,clients c,service s,work_order w","c.state IN($statecode) AND " .$cond);
            ##########Loop for fetching records of selected state###########
            $resArray=$dbf->fetchOrder("state st,clients c,service s,work_order w","c.state IN($statecode) AND " .$cond,"w.id DESC","","");
            foreach($resArray as $key=>$res_JobBoard) { 
            $pickupstate = $dbf->getDataFromTable("state","state_name","state_code='$res_JobBoard[pickup_state]'");
            if($res_JobBoard['work_status']=='Open'){$color='#333';}	
            //get client name
            if($res_JobBoard['created_by']<>0){
                $clientname =$dbf->getDataFromTable("clients","name","id='$res_JobBoard[created_by]'");
            }else{
                $clientname="COD";
            }							
        ?>   
        <tr>
            <input type="hidden" id="WorkOrder<?php echo $res_JobBoard['id'];?>" value="<?php echo $res_JobBoard['wo_no'];?>"/>
            <td data-title="WO#" class="coltext"><a href="view-job-board?id=<?php echo $res_JobBoard['id'];?>" title="Click Here For Job Details" style="color:<?php echo $color;?>"><?php echo $res_JobBoard['wo_no'];?></a></td>
            <td data-title="CustomerName"><?php echo $res_JobBoard['name'];?></td>
            <td data-title="CreatedDate"><?php echo date("d-M-Y",strtotime($res_JobBoard['created_date']));?></td>
            <td data-title="OrderStatus" class="coltext" style="color:<?php echo $color;?>"><?php echo $res_JobBoard['work_status'];?></td>
            <td data-title="ServiceType"><?php echo $res_JobBoard['service_name'];?></td>
            <td data-title="Pickupcity"><?php echo $res_JobBoard['pickup_city'];?></td>
            <td data-title="PickupState"><?php echo $pickupstate;?></td>
            <td data-title="PickupPhone" ><?php echo $res_JobBoard['pickup_phone_no'];?></td>
            <td data-title="DeliveryCity"><?php echo $res_JobBoard['city'];?></td>
            <td data-title="DeliveryState"><?php echo $res_JobBoard['state_name'];?></td>
            <td data-title="DeliveryPhone"><?php echo $res_JobBoard['phone_no'];?></td>
            <td data-title="Client" class="coltext"><?php echo $clientname;?></td>
            <td data-title="Assign" class="coltext"><a href="javascript:void(0);" onClick="ShowTechnicians('<?php echo $res_JobBoard['id'];?>');" title="Click Here To Assign Tech">Assign</a></td>
            <td data-title="Action"><a href="edit-job-board?id=<?php echo $res_JobBoard['id']?>"><img src="images/edit.png" title="Edit" alt="Edit"/></a>&nbsp;<a href="view-job-board?id=<?php echo $res_JobBoard['id'];?>"><img src="images/view.png" title="View" alt="View"/></a>&nbsp;<a href="manage-job-board?action=delete&id=<?php echo $res_JobBoard['id'];?>" onClick="return confirm('Are you sure you want to delete this record ?')"><img src="images/delete.png" title="delete" alt="delete"/></a></td>
        </tr>
       <?php } ?> 
    </tbody>
</table>
<?php if($num == 0) {?><div class="noRecords" align="center">No records founds!!</div><?php }?>
<script  type="text/javascript" src="js/dragtable.js"></script>
<script  type="text/javascript" src="js/sorttable.js"></script>
