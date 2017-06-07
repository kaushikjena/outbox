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
##############RETRIVE DATA FROM USER PERMISSION TABLE##############
$resUser=$dbf->fetchSingle("users","id='$_REQUEST[id]'");
$resFilterSearch = $dbf->fetchSingle("user_permission","user_type='user' AND user_id='$_REQUEST[id]'");
##############RETRIVE DATA FROM USER PERMISSION TABLE##############
##############CONDITION FOR SET PERMISSION#########################
if(isset($_REQUEST['schaction']) && $_REQUEST['schaction']=="permission"){
	//print "<pre>";
	//print_r($_REQUEST);//exit;
	$srchModule=$_REQUEST['search']['srchModule'];
	$srchClient=$_REQUEST['search']['srchClient'];
	$srchTechnician=$_REQUEST['search']['srchTechnician'];
	$srchReport=$_REQUEST['search']['srchReport'];
	$insert= "user_type='user',user_id='$_REQUEST[user_id]'";
	if($srchModule !=''){
		$implode_srchModule =implode(",",$srchModule);
		$insert.=",modules='$implode_srchModule'";
	}else{
		$insert.=",modules='$implode_srchModule'";
	}
	if($srchClient !=''){
		$implode_srchClient =implode(",",$srchClient);
		$insert.=",clients='$implode_srchClient'";
	}else{
		$insert.=",clients='$implode_srchClient'";
	}
	if($srchTechnician !=''){
		$implode_srchTechnician =implode(",",$srchTechnician);
		$insert.=",techs='$implode_srchTechnician'";
	}else{
		$insert.=",techs='$implode_srchTechnician'";
	}
	if($srchReport !=''){
		$implode_srchReport =implode(",",$srchReport);
		$insert.=",reports='$implode_srchReport'";
	}else{
		$insert.=",reports='$implode_srchReport'";
	}
	//echo $insert;
	################INSERT OR UPDATE USER PERMISSION TABLE####################
	$num=$dbf->countRows("user_permission","user_id='$_REQUEST[user_id]'");
	if($num>0){
		$dbf->updateTable("user_permission",$insert.",updated_date=now()","user_id='$_REQUEST[user_id]'");
	}else{
		$dbf->insertSet("user_permission",$insert.",created_date=now()");
	}
	################INSERT OR UPDATE USER PERMISSION TABLE####################
	header("Location:manage-user");exit;
}
##############CONDITION FOR SET PERMISSION#########################
?>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />
<script type="text/javascript">
/*function check_all(){
 var chkval= $('input:checkbox[name=chkAll]:checked').val();
 //alert(chkval);
 if(chkval==1){
		$('input:checkbox[name=chkSetUser[]]').each(function() { 
        	 $(this).attr('checked', true);
   		 });
	}else{
		$('input:checkbox[name=chkSetUser[]]').each(function() { 
        	 $(this).attr('checked', false);
   		 });
	}
}*/
function SubmitFields(){
	$("#schaction").val("permission");
	$("#frmPermission").submit();
}
function ClearFields(){
	$('input:checkbox[name="search[srchModule][]"]').each(function(){
		if ($(this).is(':checked')){
			$(this).attr("checked",false);
		}
	});
	$('input:checkbox[name="search[srchClient][]"]').each(function(){
		if ($(this).is(':checked')){
			$(this).attr("checked",false);
		}
	});
	$('input:checkbox[name="search[srchTechnician][]"]').each(function(){
		if ($(this).is(':checked')){
			$(this).attr("checked",false);
		}
	});
	$("#schaction").val("permission");
	$("#frmPermission").submit();
}
/*********Function to redirect page************/
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
                    	<form name="frmPermission" id="frmPermission" action="set-user-permission" method="post">
                        <input type="hidden" name="schaction"  id="schaction" value=""/>
                         <input type="hidden" name="user_id" value="<?php echo $_REQUEST['id'];?>">
                        <div class="headerbg">
                        	<div style="float:left;">Set User Permission</div>
                            <div  style="float:left; text-transform:none; padding-left:100px;">( This filter applies to: Manage User )</div>
                            <div style="float:right;"><input type="button" class="buttonText2" value="Set Permission" onClick="SubmitFields();"/>
                            <input type="button" class="buttonText2" value="Reset Permission" onClick="ClearFields();"/>
                            <input type="button" class="buttonText2" value="Return Back" onClick="document.location.href='manage-user'"/></div>
                        </div>
                        <div class="spacer"></div>
                        <div class="divFilterleft">
                        	<div align="center" class="filterdHead">User Info</div>
                            <div class="divFilterconPermission">
                              <div style="width:420px;">
                            	<div class="spacer"></div>
                                <div  class="formtextadd">User Name:</div>
                                <div  class="textboxview"><?php echo $resUser['name'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Email ID:</div>
                                <div  class="textboxview"><?php echo $resUser['email'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Mobile No:</div>
                                <div  class="textboxview"><?php echo $resUser['mobile'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">City:</div>
                                <div  class="textboxview"><?php echo $resUser['city'];?></div>
                                <div class="spacer"></div>
                                <?php $setState=$dbf->getDataFromTable("state","state_name","state_code='$resUser[state]'");?>
                                <div  class="formtextadd">State:</div>
                                <div  class="textboxview"><?php echo $setState;?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Active Status:</div>
                                <div  class="textboxview"><?php if($resUser['status']=='1'){echo 'Active';}else{echo 'Inactive';}?></div>
                              </div>
                              <div style="float:left; width:120px; height:120px; margin-top:20px;"><img src="user_photo/<?php echo $resUser['user_photo'];?>" alt="User Photo"/></div>
                           	</div>
                        </div>
                        <div class="divFiltercent2">
                        	<div align="center" class="filterdHead">Modules</div>
                            <div class="divFilterconPermission">
                                <?php 
								$modulesArray = ($resFilterSearch['modules'] !='')?explode(",",$resFilterSearch['modules']):array();
								foreach($dbf->fetchOrder("module","","","","")as $Modules) { ?>
                                <div><input type="checkbox" name="search[srchModule][]" value="<?php echo $Modules['id']?>" <?php if(in_array($Modules['id'],$modulesArray,true)){echo "checked";}?>/><?php echo $Modules['module_name'];?> </div> 
                               <?php }?>
                           	</div>
                        </div>
                        <div class="divFiltercent">
                        	<div align="center" class="filterdHead">Clients</div>
                            <div class="divFilterconPermission">
                                <?php 
								$clientsArray = ($resFilterSearch['clients'] !='')?explode(",",$resFilterSearch['clients']):array();
								foreach($dbf->fetchOrder("clients cl","cl.status='1'","cl.name ASC","","cl.name")as $client){ ?>
                                <div><input type="checkbox" name="search[srchClient][]" value="<?php echo $client['id']?>" <?php if(in_array($client['id'],$clientsArray,true)){echo "checked";}?>/><?php echo $client['name'];?> </div> 
                               <?php }?>
                           	</div>
                        </div>
                       	<div class="divFiltercent">
                         	<div align="center" class="filterdHead">Techs</div>
                         	<div class="divFilterconPermission">
                            <?php 
								$techsArray = ($resFilterSearch['techs'] !='')?explode(",",$resFilterSearch['techs']):array();
								foreach($dbf->fetch("technicians","id>0 AND status=1 ORDER BY first_name ASC")as $tech){?>
                            	<div><input type="checkbox" name="search[srchTechnician][]" value="<?php echo $tech['id']?>" <?php if(in_array($tech['id'],$techsArray,true)){echo "checked";}?>/><?php echo $tech['first_name'].'&nbsp;'.$tech['middle_name'].'&nbsp;'.$tech['last_name'];?> </div>
                              <?php }?>
                           	</div>
                        </div>
                        <div class="divFilterleft">
                         	<div align="center" class="filterdHead">Reports</div>
                         	<div class="divFilterconPermission">
                            <?php 
								$reportsArray = ($resFilterSearch['reports'] !='')?explode(",",$resFilterSearch['reports']):array();?>
                              	<div><input type="checkbox" name="search[srchReport][]" value="1" <?php if(in_array("1",$reportsArray,true)){echo "checked";}?>/>Client Report</div>
                                <div><input type="checkbox" name="search[srchReport][]" value="2" <?php if(in_array("2",$reportsArray,true)){echo "checked";}?>/>Technician Report</div>
                                <div><input type="checkbox" name="search[srchReport][]" value="3" <?php if(in_array("3",$reportsArray,true)){echo "checked";}?>/>Technician Work Status</div>
                                <div><input type="checkbox" name="search[srchReport][]" value="4" <?php if(in_array("4",$reportsArray,true)){echo "checked";}?>/>Open Jobs Report</div>
                                <div><input type="checkbox" name="search[srchReport][]" value="5" <?php if(in_array("5",$reportsArray,true)){echo "checked";}?>/>Schedule Jobs Report</div>
                                <div><input type="checkbox" name="search[srchReport][]" value="6" <?php if(in_array("6",$reportsArray,true)){echo "checked";}?>/>Client's Payment Report</div>
                                <div><input type="checkbox" name="search[srchReport][]" value="7" <?php if(in_array("7",$reportsArray,true)){echo "checked";}?>/>COD's Payment Report</div>
                                <div><input type="checkbox" name="search[srchReport][]" value="8" <?php if(in_array("8",$reportsArray,true)){echo "checked";}?>/>Tech Payments Report</div>
                                <div><input type="checkbox" name="search[srchReport][]" value="9" <?php if(in_array("9",$reportsArray,true)){echo "checked";}?>/>Total Job Payment Report</div>
                                <div><input type="checkbox" name="search[srchReport][]" value="10" <?php if(in_array("10",$reportsArray,true)){echo "checked";}?>/>Total Service Payment Report</div>
                                <div><input type="checkbox" name="search[srchReport][]" value="11" <?php if(in_array("11",$reportsArray,true)){echo "checked";}?>/>Client Billing Report</div>
                                <div><input type="checkbox" name="search[srchReport][]" value="12" <?php if(in_array("12",$reportsArray,true)){echo "checked";}?>/>Invoiced Order Report</div>
                                <div><input type="checkbox" name="search[srchReport][]" value="13" <?php if(in_array("13",$reportsArray,true)){echo "checked";}?>/>Completed Jobs Report(For reconcile Invoice#)</div>
                                <div><input type="checkbox" name="search[srchReport][]" value="14" <?php if(in_array("14",$reportsArray,true)){echo "checked";}?>/>Client Reconcile Report</div>
                                <div><input type="checkbox" name="search[srchReport][]" value="15" <?php if(in_array("15",$reportsArray,true)){echo "checked";}?>/>Total Job Report</div>
                           	</div>
                        </div>
                       <div class="spacer"></div>
                       </form>
            		</div>
                </div>
              <!-------------Main Body--------------->
         </div>
        <div class="spacer"></div>
        <?php include_once 'footer.php'; ?>
    </div>
</body>
</html>