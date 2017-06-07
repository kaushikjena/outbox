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
	    $c_data=$dbf->fetchSingle("assign_tech","id='$_REQUEST[id]'");
		if($c_data){
			header("Location:manage-technician?msg=004");exit;
		}else{
			$res_vehicle = $dbf->getDataFromTable("technicians","vehicle_image","id='$_REQUEST[id]'");
			$path="vehicle_image/".$res_vehicle;
			unlink($path);
			$res_tech= $dbf->getDataFromTable("technicians","tech_image","id='$_REQUEST[id]'");
			$path1="tech_image/".$res_tech;
			unlink($path1);
			$dbf->deleteFromTable("technicians","id='$_REQUEST[id]'");
			header("Location:manage-technician");exit;
		}
	}
?>
<body>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />
<link rel="stylesheet" href="css/table.css" type="text/css" />
<script type="text/javascript">
function SendSMS(techid){
	$.fancybox.showActivity();
	var url="ajax-send-sms.php";
	$.post(url,{"choice":"viewsms","techid":techid},function(res){			
		$.fancybox(res,{centerOnScroll:true,hideOnOverlayClick:false});				
	});
}
function validate_techsms(){
	if(document.frmTech.TechPhoneNo.value == ''){
		document.getElementById('lblTechPhoneNo').innerHTML = 'This field is required';
		document.frmTech.TechPhoneNo.focus();
		return false;
	}else{
		document.getElementById('lblTechPhoneNo').innerHTML = '';
	}
	if(document.frmTech.txaMessage.value == ''){
		document.getElementById('lbltxaMessage').innerHTML = 'This field is required';
		document.frmTech.txaMessage.focus();
		return false;
	}else{
		document.getElementById('lbltxaMessage').innerHTML = '';
	}
	if(document.frmTech.txaMessage.value.length >160){
		document.getElementById('lbltxaMessage').innerHTML = 'Message should be within 160 chars';
		document.frmTech.txaMessage.focus();
		return false;
	}else{
		document.getElementById('lbltxaMessage').innerHTML = '';
	}
	return true;	
}
function send_sms(){
	$.fancybox.showActivity();	
	var url="ajax-send-sms.php";	
	var x=validate_techsms();
	var TechPhoneNo=$('#TechPhoneNo').val();
	var txaMessage=$('#txaMessage').val();
	var techid=$('#techid').val();
	if(x){
	 	$.post(url,{"choice":"sendsms","TechPhoneNo":TechPhoneNo,"txaMessage":txaMessage,"techid":techid},function(res){
		 $.fancybox(res,{centerOnScroll:true,hideOnOverlayClick:false});
	 	});
	}else{
		return false; 
	}
}
function changeStatus(techid){
	var url="ajax-change-status.php";
	$.post(url,{"choice":"tech","techid":techid},function(res){	//alert(res);		
		$("#atech"+techid).html(res);		
	});
}
function ClearFields(){
	$('#srchTech').val("");
	$('#srchEmail').val("");
	$('#srchContactNo').val("");
	$('#srchState').val("");
	document.SrchFrm.submit();
}
/*********Function to redirect page************/
function redirectPage(id,page){
	$("#hid").val(id);
	document.frmRedirect.action=page;
	document.frmRedirect.submit();
}
/*********Function to redirect page************/
function delete_tech(id){
	var url = "ajax-client-delete.php"
	var r = confirm("Are you sure you want to delete this record ?");
	if(r){
		$.post(url,{"choice":"delete_tech","techid":id},function(res){
			if(res==0){
				window.location.href="manage-technician?action=delete&id="+id;
			}else{
				alert("Sorry ! You can't delete this technician. This technician has some work orders.");
			}
		});
	}
}
</script>
	<form name="frmRedirect" id="frmRedirect" action="" method="post"> 
    	<input type="hidden" name="id" id="hid" value=""/>
    </form>
    <div id="maindiv">
        <!-------------header--------------->
     	<?php include_once 'header.php';?>
   		<!-------------header--------------->
        <!-------------top menu--------------->
     	<?php include_once 'top-menu.php';?>
   		<!-------------top menu--------------->
         <div id="contentdiv">
                <!-------------Left menu--------------->
				<?php //include_once 'left-menu.php';?>
                <!-------------left menu--------------->
                <!-------------Main Body--------------->
                <div class="rightcolumjobboard">
            		<div class="rightcoluminner">
                        <div class="headerbg"><div style="float:left;">Manage Technicians</div>
                        	<div style="float:right;"><?php if($implode_techs==''){?><input type="button" class="buttonText2" value="Create Technician" onClick="javascript:window.location.href='add-technician'"/><?php }?></div>
                        </div> <?php
						 if($_REQUEST['msg']=='004'){ ?>
							 <div align="center" style="color:red">This technician has a Job,can't be deleted</div>
						<?php }else{?>
							 <div class="spacer"></div>
						<?php }?>
                        <div id="contenttable">
                        	<form name="SrchFrm" id="SrchFrm" action="" method="post">
                              <div style="margin-bottom:5px;" align="center">
                              	 <div  class="formtextaddsrch" align="center">Name:</div>
                                  <div class="textboxcsrch">
                                   	<input type="text" name="srchTech" id="srchTech" class="textboxsrch" value="<?php echo $_REQUEST['srchTech'];?>"/>
                                	</div>
                                   <div  class="formtextaddsrch" align="center">EmailID:</div>
                                   <div class="textboxcsrch">
                                   	<input type="text" name="srchEmail" id="srchEmail" class="textboxsrch" value="<?php echo $_REQUEST['srchEmail'];?>"/>
                                	</div>
                                 	<div  class="formtextaddsrch"align="center">ContactNo:</div>
                                   	<div class="textboxcsrch">
                                    <input type="text" name="srchContactNo" id="srchContactNo" class="textboxsrch" value="<?php echo $_REQUEST['srchContactNo'];?>"/>
                                    </div>
                                    <div  class="formtextaddsrch"align="center">State:</div>
                                   	<div class="textboxcsrch">
                                    <input type="text" name="srchState" id="srchState" class="textboxsrch" value="<?php echo $_REQUEST['srchState'];?>"/>
                                    </div>
                                    <div style="float:left; width:200px;">
                                    <input type="submit" class="buttonText2" name="SearchRecord" value="Filter Techs">
                                    <input type="button" class="buttonText2" name="Reset" value="Reset Filter" onClick="ClearFields();">
                                   </div>
                              </div>
                            </form>
                            <div class="spacer"></div>
                             <?php
							 	//condition
								$cond = "t.state=s.state_code"; 
								if($implode_techs <>''){
									$cond.=" AND FIND_IN_SET(t.id,'$implode_techs')";
								}
							   #############Search Conditions#####################
							   	$sch="";
								if($_REQUEST['srchTech']!=''){
									$scount = substr_count($_REQUEST['srchTech']," "); 
									$srchTechArry = $scount> 0 ? explode(" ",$_REQUEST['srchTech']):$_REQUEST['srchTech'];
									//print_r($srchTechArry);
									if($scount>0){
										$sch=$sch."((t.first_name ='$srchTechArry[0]') OR (t.middle_name = 'trim($srchTechArry[1])') OR(t.last_name ='$srchTechArry[2]')) AND ";
									}else{
										$sch=$sch."((t.first_name LIKE '%$srchTechArry%') OR (t.middle_name LIKE '%$srchTechArry%') OR(t.last_name LIKE '%$srchTechArry%')) AND ";
									}
								}
								if($_REQUEST['srchEmail']!=''){
									$sch=$sch."t.email LIKE '%$_REQUEST[srchEmail]%' AND ";
								}
								if($_REQUEST['srchContactNo']!=''){
									$sch=$sch."t.contact_phone LIKE '%$_REQUEST[srchContactNo]%' AND ";
								}
								if($_REQUEST['srchState']!=''){
									$sch=$sch."s.state_name LIKE '%$_REQUEST[srchState]%' AND ";
								}
							   $sch=substr($sch,0,-5);
							   //echo $sch;//exit;
							   if($sch!=''){
								 $cond.=" AND ".$sch;
							   }
							   //echo $cond;
							   #############Search Conditions#####################
							  ?>
                        	<div style="width:100%;float:left;">
                              <div class="table">
                                <div class="table-head">
                                    <div class="column" data-label="User Name" style="width:17%;">Tech Name</div>
                                    <div class="column" data-label="Email ID"  style="width:17%;">Email ID</div>
                                    <div class="column" data-label="Contact No" style="width:12%;">Contact No</div>  
                                    <div class="column" data-label="Address"  style="width:15%;">Address</div>
                                    <div class="column" data-label="City"  style="width:15%;">City</div>
                                    <div class="column" data-label="State"  style="width:12%;">State</div>
                                    <div class="column" data-label="Action"  style="width:12%;">Action</div>
                                </div>
								<?php 
                                //Pagination 
                                $page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
                                $page = ($page == 0 ? 1 : $page);
                                $perpage =15;//limit in each page
                                $startpoint = ($page * $perpage) - $perpage;
                                //-----------------------------------				
                                $num=$dbf->countRows("state s,technicians t",$cond); 
                                foreach($dbf->fetchOrder("state s,technicians t",$cond,"t.first_name ASC LIMIT $startpoint,$perpage","")as $res_tech) {
									if($res_tech['status']==1){
										$src="images/green-circle.png";$title="Active";
									}else{
										$src="images/red-circle.png";$title="Inactive";
									}
								?>
                                <div class="row">
                                    <div class="column" data-label="User Name"><?php echo $res_tech['first_name'].'&nbsp;'.$res_tech['middle_name'].'&nbsp;'.$res_tech['last_name'];?></div>
                                    <div class="column" data-label="Email ID"><?php echo $res_tech['email'];?></div>                                    <div class="column" data-label="Contact No"><?php echo $res_tech['contact_phone'];?></div>
                                    <div class="column" data-label="Address"><?php echo $res_tech['address'];?></div>
                                    <div class="column" data-label="City"><?php echo $res_tech['city'];?></div>
                                    <div class="column" data-label="State"><?php echo $res_tech['state_name'];?></div>                                    <div class="column" data-label="Action"><a href="javascript:void(0);" onClick="changeStatus('<?php echo $res_tech['id'];?>');" id="atech<?php echo $res_tech['id'];?>"><img src="<?php echo $src;?>" title="<?php echo $title;?>" alt="Status"/></a> &nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_tech['id'];?>','view-technician');"><img src="images/view.png" title="View" alt="View"/></a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_tech['id'];?>','edit-technician');"><img src="images/edit.png" title="Edit" alt="Edit"/></a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onClick="SendSMS('<?php echo $res_tech['id'];?>');"><img src="images/sms-img.png" title="Send SMS" alt="Send SMS"/></a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onClick="delete_tech('<?php echo $res_tech['id'];?>');"><img src="images/delete.png" title="Delete" alt="Delete"/></a></div>
                               </div>
                              <?php }?>
                        	</div>
                            <?php if($num == 0) {?><div class="noRecords" style="padding-left:40%;">No records founds!!</div>
                            <?php }?>
                            <div  align="center"><?php if($num>0) { echo $dbf->Pages($num,$perpage,"manage-technician?");}?></div>
                          </div>
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