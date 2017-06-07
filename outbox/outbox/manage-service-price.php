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
		$dbf->deleteFromTable("service_price","id='$_REQUEST[id]'");
		header("Location:manage-service-price");exit;
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
                        <div class="headerbg"><div style="float:left;">Manage Tech Payment Price</div>
                        	<div style="float:right;"><input type="button" class="buttonText2" value="Add Tech Payment Price" onClick="javascript:window.location.href='add-service-price'"/></div>
                        </div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                          <!-----Table area start------->
                            <table id="no-more-tables" class="draggable">
                                <thead>
                                    <tr>
                                        <th width="10%">Service Name</th>
                                        <th width="10%">Equipment Name</th>
                                        <th width="9%">Work Type</th>
                                        <th width="6.5%">PayGrade A</th>
                                        <th width="6.5%">PayGrade B</th>
                                        <th width="6.5%">PayGrade C</th>
                                        <th width="6.5%">PayGrade D</th>
                                        <th width="6.5%">PayGrade E</th>
                                        <th width="6.5%">PayGrade F</th>
                                        <th width="6.5%">PayGrade G</th>
                                        <th width="6.5%">PayGrade H</th>
                                        <th width="6.5%">PayGrade I</th>
                                        <th width="6.5%">PayGrade J</th>
                                        <th width="6%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                 <?php 
                                   $num=$dbf->countRows("equipment e,work_type wt,service s,service_price sp","e.id=sp.equipment AND wt.id=sp.work_type AND s.id=sp.service_id");  
                                    $resGrArray=$dbf->fetchOrder("equipment e,work_type wt,service s,service_price sp","e.id=sp.equipment AND wt.id=sp.work_type AND s.id=sp.service_id","sp.service_id,sp.equipment ASC","e.equipment_name,s.service_name,sp.equipment","sp.equipment");
                                    //group by state loop
                                    foreach($resGrArray as $k=>$sgRes){
                                    $Cls="g$k";	
                                  ?>
                                    <tr style="background-color:#f9f9f9;">
                                        <td valign="top" class="grheading">
                                        <div class="divgr">
                                        <a href="javascript:void(0);" onClick="funShow('<?php echo $Cls;?>','<?php echo $k;?>');" id="e<?php echo $k;?>" <?php if($k==0){?>style="display:none;" <?php }?>><img  src="images/plus.gif" height="13" width="13"/>&nbsp;<?php echo $sgRes['service_name'];?> &raquo; <span style="color:#ff9812;"><?php echo $sgRes['equipment_name'];?></span></a> 
                                        <a href="javascript:void(0);" onClick="funHide('<?php echo $Cls;?>','<?php echo $k;?>');" id="c<?php echo $k;?>" <?php if($k!=0){?>style="display:none;" <?php }?>><img  src="images/minus.gif" height="13" width="13"/>&nbsp;<?php echo $sgRes['service_name'];?> &raquo; <span style="color:#ff9812;"><?php echo $sgRes['equipment_name'];?></span></a>
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
                                        $resArray=$dbf->fetchOrder("equipment e,work_type wt,service s,service_price sp","e.id=sp.equipment AND wt.id=sp.work_type AND s.id=sp.service_id AND sp.equipment='$sgRes[equipment]'","sp.id ASC","s.service_name, e.equipment_name, wt.worktype,sp.*","");
                                        foreach($resArray as $key=>$res_service) { 
                                    ?>   
                                    <tr class="<?php echo $Cls;?>" <?php if($k!=0){?> style="display:none;" <?php } ?>>
                                        <td data-title="Service Name"><?php echo $res_service['service_name'];?></td>
                                        <td data-title="Equipment Name"><?php echo $res_service['equipment_name'];?></td>
                                        <td data-title="Work Type"><?php echo $res_service['worktype'];?></td>
                                        <td data-title="PayGrade A">$ <?php echo $res_service['gradeA_price'];?></td>
                                        <td data-title="PayGrade B">$ <?php echo $res_service['gradeB_price'];?></td>
                                        <td data-title="PayGrade C">$ <?php echo $res_service['gradeC_price'];?></td>
                                        <td data-title="PayGrade D">$ <?php echo $res_service['gradeD_price'];?></td>
                                        <td data-title="PayGrade E">$ <?php echo $res_service['gradeE_price'];?></td>
                                        <td data-title="PayGrade F">$ <?php echo $res_service['gradeF_price'];?></td>
                                        <td data-title="PayGrade G">$ <?php echo $res_service['gradeG_price'];?></td>
                                        <td data-title="PayGrade H">$ <?php echo $res_service['gradeH_price'];?></td>
                                        <td data-title="PayGrade I">$ <?php echo $res_service['gradeI_price'];?></td>
                                        <td data-title="PayGrade J">$ <?php echo $res_service['gradeJ_price'];?></td>
                                        <td data-title="Action"><a href="edit-service-price?serviceid=<?php echo $res_service['service_id'];?>&eqid=<?php echo $res_service['equipment'];?>"><img src="images/edit.png" title="Edit" alt="Edit"/></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="manage-service-price?action=delete&id=<?php echo $res_service['id'];?>" onClick="return confirm('Are you sure you want to delete this record ?')"><img src="images/delete.png" title="Delete" alt="Delete"/></a></td>
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