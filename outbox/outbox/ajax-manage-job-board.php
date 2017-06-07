<?php 
	ob_start();
	session_start();
	include_once 'includes/class.Main.php';
	//Object initialization
	$dbf = new User();
	 ##########STORE AND RETRIVE THE SEARCH CONDITION FROM SESSION##########
		$FromDate=$_SESSION['requesto']['FromDate'];
		$ToDate=$_SESSION['requesto']['ToDate'];
		$srchCust=$_SESSION['requesto']['srchCust'];
		$srchClient=$_SESSION['requesto']['srchClient'];
		$srchService=$_SESSION['requesto']['srchService'];
	   ##########STORE AND RETRIVE THE SEARCH CONDITION FROM SESSION##########
	#############Search Conditions#####################
	$sch="";
	$fromdt=date("Y-m-d",strtotime(($FromDate)));
	$todt=date("Y-m-d",strtotime(($ToDate)));
	
	if($srchCust !=''){
		$sch=$sch."c.id='$srchCust' AND ";
	}
	if($srchClient !=''){
		$sch=$sch."w.created_by='$srchClient' AND ";
	}
	if($srchService !=''){
		$sch=$sch."s.id='$srchService' AND ";
	}
	if($FromDate !='' && $ToDate ==''){
		$sch=$sch."w.created_date >= '$fromdt' AND ";
	}
	if($FromDate =='' && $ToDate !=''){
		$sch=$sch."w.created_date <= '$todt' AND ";
	}
	if(($FromDate !='') && ($ToDate !='')){
		$sch=$sch."w.created_date BETWEEN '$fromdt' AND '$todt' AND ";
	}
   $sch=substr($sch,0,-5);
   if($sch!=''){
	 $cond="c.state=st.state_code AND c.id=w.client_id AND w.service_id=s.id AND w.work_status='Open' AND w.approve_status='1' AND ".$sch;
   }
   elseif($sch==''){
	 $cond="c.state=st.state_code AND c.id=w.client_id AND w.service_id=s.id AND w.work_status='Open' AND w.approve_status='1'";
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
                <th width="5%">Assign</th>
                <th width="7%">Action</th>
            </tr>
        </thead>
        <tbody>
        	<tr>
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
                <td class="hiderow">&nbsp;</td>
            </tr>
         <?php 
            $num=$dbf->countRows("state st,clients c,service s,work_order w",$cond); 
            $resGrArray=$dbf->fetchOrder("state st,clients c,service s,work_order w",$cond,"c.state ASC","","c.state");
            //group by state loop
            foreach($resGrArray as $k=>$sgRes){
            $Cls="g$k";	
            $numres = $dbf->countRows("state st,clients c,service s,work_order w","c.state='$sgRes[state]' AND " .$cond);
          ?>
            <tr style="background-color:#f9f9f9;" class="ho">
                <td valign="top" class="grheading">
                <div class="divgr">
                <a href="javascript:void(0);" onClick="funShow('<?php echo $Cls;?>','<?php echo $k;?>');" id="e<?php echo $k;?>" <?php if($k==0){?>style="display:none;" <?php }?> class="hoa"><img  src="images/plus.gif" height="13" width="13"/>&nbsp;<?php echo $numres;?> Open Jobs in <span style="color:#ff9812;"><?php echo $sgRes['state_name'];?></span> needs to be assigned</a> 
                <a href="javascript:void(0);" onClick="funHide('<?php echo $Cls;?>','<?php echo $k;?>');" id="c<?php echo $k;?>" <?php if($k!=0){?>style="display:none;" <?php }?> class="hob"><img  src="images/minus.gif" height="13" width="13"/>&nbsp;<?php echo $numres;?> Open Jobs in <span style="color:#ff9812;"><?php echo $sgRes['state_name'];?></span> needs to be assigned</a>
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
                <td class="hiderow">&nbsp;</td>
            </tr>
            <?php 
                $resArray=$dbf->fetchOrder("state st,clients c,service s,work_order w","c.state='$sgRes[state]' AND " .$cond,$orderby,"st.state_name,c.*,s.service_name,w.*","");
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
            <tr class="<?php echo $Cls;?> ro" <?php if($k!=0){?> style="display:none;" <?php } ?>>
                <input type="hidden" id="WorkOrder<?php echo $res_JobBoard['id'];?>" value="<?php echo $res_JobBoard['wo_no'];?>"/>
                <td data-title="WO#" class="coltext"><a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_JobBoard['id'];?>','view-job-board');" title="Click Here For Job Details" style="color:<?php echo $color;?>"><?php echo $res_JobBoard['wo_no'];?></a></td>
                <td data-title="CustomerName"><?php echo $res_JobBoard['name'];?></td>
                <td data-title="CreatedDate"><?php echo date("d-M-Y",strtotime($res_JobBoard['created_date']));?></td>
                <td data-title="JobStatus" class="coltext" style="color:<?php echo $color;?>"><?php echo $res_JobBoard['work_status'];?></td>
                <td data-title="ServiceType"><?php echo $res_JobBoard['service_name'];?></td>
                <td data-title="Pickupcity"><?php echo $res_JobBoard['pickup_city'];?></td>
                <td data-title="PickupState"><?php echo $pickupstate;?></td>
                <td data-title="PickupPhone" ><?php echo $res_JobBoard['pickup_phone_no'];?></td>
                <td data-title="DeliveryCity"><?php echo $res_JobBoard['city'];?></td>
                <td data-title="DeliveryState"><?php echo $res_JobBoard['state_name'];?></td>
                <td data-title="DeliveryPhone"><?php echo $res_JobBoard['phone_no'];?></td>
                <td data-title="Client" class="coltext"><?php echo $clientname;?></td>
                <td data-title="Assign" class="coltext"><a href="javascript:void(0);" onClick="ShowTechnicians('<?php echo $res_JobBoard['id'];?>');" title="Click Here To Assign Tech">Assign</a></td>
                <td data-title="Action"><a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_JobBoard['id'];?>','edit-job-board');"><img src="images/edit.png" title="Edit" alt="Edit"/></a>&nbsp;<a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_JobBoard['id'];?>','view-job-board');"><img src="images/view.png" title="View" alt="View"/></a>&nbsp;<a href="manage-job-board?action=delete&id=<?php echo $res_JobBoard['id'];?>" onClick="return confirm('Are you sure you want to delete this record ?')"><img src="images/delete.png" title="delete" alt="delete"></a>&nbsp;<a href="manage-job-board?action=email&id=<?php echo $res_JobBoard['id'];?>" onClick="return confirm('Are you sure you want to send email ?')"><img src="images/email_go.png" title="Send Email" alt="Email"></a></td>
            </tr>
             <?php } 
                }
            ?> 
        </tbody>
   </table>
  <!-----Table area start-------> 
<?php if($num == 0) {?><div class="noRecords" align="center">No records founds!!</div><?php }?>
