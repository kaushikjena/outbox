<?php 
	ob_start();
	session_start();
	include_once 'includes/class.Main.php';
	//Object initialization
	$dbf = new User();
	//page titlevariable
	$pageTitle="Welcome To Out Of The Box";
	include 'applicationtop.php';
	//logout for users other than admin and user
	if($_SESSION['usertype']!='admin' && $_SESSION['usertype']!='user'){
		header("location:logout");exit;
	}
	//Delete record from users Table
	if($_REQUEST['action']=='delete'){	
		$dbf->deleteFromTable("service_price_client","id='$_REQUEST[id]'");
		header("Location:manage-service-price-client");exit;
	}
?>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />
<link rel="stylesheet" href="css/no_more_table.css" type="text/css" />
<script  type="text/javascript" src="js/dragtable.js"></script>
<script type="text/javascript">
/*********Function to expand and collapse group************/
function funHide(clss,id){
	//alert(id);
	$('.'+clss).hide();
	$('#e'+id).show();
	$('#c'+id).hide();
}
function funShow(clss,id){
	$('.'+clss).show();
	$('#c'+id).show();
	$('#e'+id).hide();
}
/*********Function to expand and collapse group************/
</script>
<body>
    <div id="maindiv">
        <!-------------header--------------->
     	<?php include_once 'header.php';?>
   		<!-------------header--------------->
        
        <!-------------top menu--------------->
     	<?php include_once 'top-menu.php';?>
   		<!-------------top menu--------------->
         <div id="contentdiv">
                <!-------------Main Body--------------->
                <div class="rightcolumjobboard">
            		<div class="rightcoluminner">
                        <div class="headerbg"><div style="float:left;">Manage Client Billing Price</div>
                        	<div style="float:right;"><input type="button" class="buttonText2" value="Add Client Billing Price" onClick="javascript:window.location.href='add-service-price-client'"/></div>
                        </div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                          <!-----Table area start------->
                            <table id="no-more-tables" class="draggable">
                                <thead>
                                    <tr>
                                        <th width="17%">Service Name</th>
                                        <th width="17%">Equipment Name</th>
                                        <th width="20%">Work Type</th>
                                        <th width="20%">Client Name</th>
                                        <th width="10%">Client Price</th>
                                        <th width="10%">OutBox Price</th>
                                        <th width="6%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                 <?php 
                                   $num=$dbf->countRows("clients c,equipment e,work_type wt,service s,service_price_client sp","c.id=sp.client_id AND e.id=sp.equipment AND wt.id=sp.work_type AND s.id=sp.service_id");  
                                    $resGrArray=$dbf->fetchOrder("clients c,equipment e,work_type wt,service s,service_price_client sp","c.id=sp.client_id AND e.id=sp.equipment AND wt.id=sp.work_type AND s.id=sp.service_id","sp.service_id,sp.equipment ASC","c.name,e.equipment_name,s.service_name,sp.equipment,sp.client_id","sp.equipment,sp.client_id");
                                    //group by state loop
                                    foreach($resGrArray as $k=>$sgRes){
                                    $Cls="g$k";	
                                  ?>
                                    <tr style="background-color:#f9f9f9;">
                                        <td valign="top" class="grheading">
                                        <div class="divgr">
                                        <a href="javascript:void(0);" onClick="funShow('<?php echo $Cls;?>','<?php echo $k;?>');" id="e<?php echo $k;?>" <?php if($k==0){?>style="display:none;" <?php }?>><img  src="images/plus.gif" height="13" width="13"/>&nbsp;<?php echo $sgRes['service_name'];?> &raquo; <span style="color:#ff9812;"><?php echo $sgRes['equipment_name'];?></span> &raquo; <span style="color:#090;"><?php echo $sgRes['name'];?></span></a> 
                                        <a href="javascript:void(0);" onClick="funHide('<?php echo $Cls;?>','<?php echo $k;?>');" id="c<?php echo $k;?>" <?php if($k!=0){?>style="display:none;" <?php }?>><img  src="images/minus.gif" height="13" width="13"/>&nbsp;<?php echo $sgRes['service_name'];?> &raquo; <span style="color:#ff9812;"><?php echo $sgRes['equipment_name'];?></span> &raquo; <span style="color:#090;"><?php echo $sgRes['name'];?></span></a>
                                        </div>
                                        </td>
                                        <td class="hiderow">&nbsp;</td>
                                        <td class="hiderow">&nbsp;</td>
                                        <td class="hiderow">&nbsp;</td>
                                        <td class="hiderow">&nbsp;</td>
                                        <td class="hiderow">&nbsp;</td>
                                        <td class="hiderow">&nbsp;</td>
                                    </tr>
                                    <?php
										$qry ="SELECT temp.*,so.outbox_price FROM (SELECT s.service_name, e.equipment_name, wt.worktype, c.name, sp.client_price, sp.id, sp.service_id, sp.equipment,sp.work_type, sp.client_id FROM clients c,equipment e,work_type wt,service s,service_price_client sp WHERE c.id=sp.client_id AND e.id=sp.equipment AND wt.id=sp.work_type AND s.id=sp.service_id AND sp.client_id='$sgRes[client_id]' AND sp.equipment='$sgRes[equipment]' order by sp.id ASC)as temp LEFT JOIN service_price_outbox so ON temp.service_id=so.service_id AND temp.equipment=so.equipment AND temp.work_type=so.work_type"; 
										$resArray =$dbf->simpleQuery($qry);
                                        foreach($resArray as $key=>$res_service) { 
                                    ?>   
                                    <tr class="<?php echo $Cls;?>" <?php if($k!=0){?> style="display:none;" <?php } ?>>
                                        <td data-title="Service Name"><?php echo $res_service['service_name'];?></td>
                                        <td data-title="Equipment Name"><?php echo $res_service['equipment_name'];?></td>
                                        <td data-title="Work Type"><?php echo $res_service['worktype'];?></td>
                                        <td data-title="Client"><?php echo $res_service['name'];?></td>
                                        <td data-title="Client Price">$ <?php echo $res_service['client_price'];?></td>
                                        <td data-title="OutBox Price">$ <?php echo $res_service['outbox_price'];?></td>
                                        <td data-title="Action"><a href="edit-service-price-client?serviceid=<?php echo $res_service['service_id'];?>&eqid=<?php echo $res_service['equipment'];?>&cid=<?php echo $res_service['client_id'];?>"><img src="images/edit.png" title="Edit" alt="Edit"/></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="manage-service-price-client?action=delete&id=<?php echo $res_service['id'];?>" onClick="return confirm('Are you sure you want to delete this record ?')"><img src="images/delete.png" title="Delete" alt="Delete"/></a></td>
                                    </tr>
                                     <?php } 
                                        }
                                    ?> 
                                </tbody>
                           </table>
                          <!-----Table area start-------> 
                          <?php if($num == 0){?><div class="noRecords" align="center">No records founds!!</div><?php }?>
                        </div>
                        <div class="spacer"></div>
                    </div>
            	</div>
              <!-------------Main Body--------------->
         </div>
        <div class="spacer"></div>
        <?php include_once 'footer.php'; ?>
    </div>
</body>
</html>