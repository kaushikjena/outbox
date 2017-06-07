<?php 
	ob_start();
	session_start();
	include_once '../includes/class.Main.php';
	//Object initialization
	$dbf = new User();
	##########STORE AND RETRIVE THE SEARCH CONDITION FROM SESSION##########
	//print_r($_SESSION['requesto']);
	$FromDate=$_SESSION['requesto']['FromDate'];
	$ToDate=$_SESSION['requesto']['ToDate'];
	$srchCust=$_SESSION['requesto']['srchCust'];
	$srchService=$_SESSION['requesto']['srchService'];
	##########STORE AND RETRIVE THE SEARCH CONDITION FROM SESSION##########
	##################Search Condition#####################
	$sch="";
	$fromdt=date("Y-m-d",strtotime(($FromDate)));
	$todt=date("Y-m-d",strtotime(($ToDate)));
	if($srchCust !=''){
		$sch=$sch."c.id='$srchCust' AND ";
	}
	if($srchService !=''){
		$sch=$sch."s.id='$srchService' AND ";
	}
	if($FromDate !='' && $ToDate ==''){
		$sch=$sch."w.created_date = '$fromdt' AND ";
	}
	if($FromDate =='' && $ToDate !=''){
		$sch=$sch."w.created_date = '$todt' AND ";
	}
	if(($FromDate !='') && ($ToDate !='')){
		$sch=$sch."w.created_date BETWEEN '$fromdt' AND '$todt' AND ";
	}
   $sch=substr($sch,0,-5);
   if($sch!=''){
	 $cond="c.state=st.state_code AND c.id=w.client_id AND w.service_id=s.id AND w.work_status='Open' AND  w.created_by='$_SESSION[userid]' AND ".$sch;
   }
   elseif($sch==''){
	 $cond="c.state=st.state_code AND c.id=w.client_id AND w.service_id=s.id AND w.work_status='Open' AND  w.created_by='$_SESSION[userid]'";
   }
   //condition for order by
   if($_REQUEST['cmbColumn']=='' && $_REQUEST['cmbType']==''){
   		$orderby = "w.id DESC";
   }else{
	  	$orderby = "$_REQUEST[cmbColumn] $_REQUEST[cmbType]"; 
   }
  ##################Search Condition#####################
?>
<script  type="text/javascript" src="../js/dragtable.js"></script>
    <!-----Table area start------->
    <table id="no-more-tables" class="draggable">
        <thead>
            <tr>
                <th width="6%">WO#</th>
                <th width="10%">CustomerName</th>
                <th width="8%">CreatedDate</th>
                <th width="6%">OrderStatus</th>
                <th width="10%">ServiceType</th>
                <th width="8%">Pickupcity</th>
                <th width="8%">PickupState</th>
                <th width="8%">PickupPhone</th>
                <th width="8%">DeliveryCity</th>
                <th width="8%">DeliveryState</th>
                <th width="8%">DeliveryPhone</th>
                <th width="6%">Status</th>
                <th width="6%">Action</th>
            </tr>
        </thead>
        <tbody>
         <?php 
            $num=$dbf->countRows("state st,clients c,service s,work_order w",$cond); 
            $resGrArray=$dbf->fetchOrder("state st,clients c,service s,work_order w",$cond,"c.state ASC","st.*,c.*","c.state");
            //group by state loop
            foreach($resGrArray as $k=>$sgRes){
            $Cls="g$k";	
            $numres = $dbf->countRows("state st,clients c,service s,work_order w","c.state='$sgRes[state]' AND " .$cond);
          ?>
            <tr style="background-color:#f9f9f9;">
                <td valign="top" class="grheading">
                <div class="divgr">
                <a href="javascript:void(0);" onClick="funShow('<?php echo $Cls;?>','<?php echo $k;?>');" id="e<?php echo $k;?>" <?php if($k==0){?>style="display:none;" <?php }?>><img  src="../images/plus.gif" height="13" width="13"/>&nbsp;<?php echo $numres;?> Open Jobs in <span style="color:#ff9812;"><?php echo $sgRes['state_name'];?></span> needs to be assigned</a> 
                <a href="javascript:void(0);" onClick="funHide('<?php echo $Cls;?>','<?php echo $k;?>');" id="c<?php echo $k;?>" <?php if($k!=0){?>style="display:none;" <?php }?>><img  src="../images/minus.gif" height="13" width="13"/>&nbsp;<?php echo $numres;?> Open Jobs in <span style="color:#ff9812;"><?php echo $sgRes['state_name'];?></span> needs to be assigned</a>
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
                $resArray=$dbf->fetchOrder("state st,clients c,service s,work_order w","c.state='$sgRes[state]' AND " .$cond,$orderby,"st.state_name,c.*,s.service_name,w.*","");
                foreach($resArray as $key=>$res_JobBoard) { 
                $pickupstate = $dbf->getDataFromTable("state","state_name","state_code='$res_JobBoard[pickup_state]'");
                if($res_JobBoard['work_status']=='Open'){$color='#333';}	
                                
            ?>   
            <tr class="<?php echo $Cls;?>" <?php if($k!=0){?> style="display:none;" <?php } ?>>
                <td data-title="WO#" class="coltext"><a href="client-view-job-board?id=<?php echo $res_JobBoard['id'];?>" title="Click Here For Job Details" style="color:<?php echo $color;?>"><?php echo $res_JobBoard['wo_no'];?></a></td>
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
                <td data-title="Status" class="coltext"><?php if($res_JobBoard['approve_status']=='1'){echo 'approved';}else{echo 'Unapproved';}?></td>
                <td data-title="Action"><a href="client-edit-job-board?id=<?php echo $res_JobBoard['id']?>"><img src="../images/edit.png" title="Edit" alt="Edit"/></a>&nbsp;<a href="client-view-job-board?id=<?php echo $res_JobBoard['id'];?>"><img src="../images/view.png" title="View" alt="View"/></a>&nbsp;<a href="client-manage-job-board?action=delete&id=<?php echo $res_JobBoard['id'];?>" onClick="return confirm('Are you sure you want to delete this record ?')"><img src="../images/delete.png" title="delete" alt="delete"></a></td>
            </tr>
             <?php } 
                }
            ?> 
        </tbody>
    </table>
    <!-----Table area start-------> 
    <?php if($num == 0) {?><div class="noRecords" align="center">No records founds!!</div><?php }?>
