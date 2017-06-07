<?php 
ob_start();
session_start();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();
//page titlevariable
$pageTitle="Welcome To Out Of The Box";
include 'applicationtop.php';
if($_SESSION['userid']==''){
	header("location:logout");exit;
}
$res_viewClient=$dbf->fetchSingle("clients","id='$_REQUEST[id]'");
if(isset($_REQUEST['action']) && $_REQUEST['action']=="permission"){
	 $num=$dbf->countRows("client_permission","user_id='$_REQUEST[user_id]'");
	 if($num>0){
		$dbf->deleteFromTable("client_permission","user_id='$_REQUEST[user_id]'");
	 }
	 foreach($_REQUEST['chkSetUser'] as $val){
		 $string="user_id='$_REQUEST[user_id]',module_id='$val',created_date=now()";
		 $insid =$dbf->insertSet("client_permission",$string);
	 }
	 header("Location:set-client-permission?id=$_REQUEST[user_id]");exit;
}
?>
<body>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />

<script type="text/javascript">
function check_all(){
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
}
</script>
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
                        <div class="headerbg">Set Client Permission</div>
                        <div class="spacer"></div>
                        <div id="contenttable"  style="border: 1px solid #666;">
                        	<div  class="innerDivTech">
                                <div class="spacer"></div>
                                <div  class="formtextadd">Client Name:</div>
                                <div  class="textboxview"><?php echo $res_viewClient['name'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Email ID:</div>
                                <div  class="textboxview"><?php echo $res_viewClient['email'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Contact No:</div>
                                <div  class="textboxview"><?php echo $res_viewClient['phone_no'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Cell No:</div>
                                <div  class="textboxview"><?php echo $res_viewClient['fax_no'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Address:</div>
                                <div  class="textboxview"><?php echo $res_viewClient['address'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">City:</div>
                                <div  class="textboxview"><?php echo $res_viewClient['city'];?></div>
                                <div class="spacer"></div>
                                <?php $res_StateClient=$dbf->getDataFromTable("state","state_name","state_code='$res_viewClient[state]'"); ?>
                                <div  class="formtextadd">State:</div>
                                <div  class="textboxview"><?php echo $res_StateClient;?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Zipcode:</div>
                                <div  class="textboxview"><?php echo $res_viewClient['zip_code'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Active Status:</div>
                                <div  class="textboxview"><?php if($res_viewClient['status']=='1'){echo 'Active';}else{echo 'Inactive';}?></div>
                        	</div>
                            <div  class="innerDivTech">
                           	 <form name="frmPermission" id="frmPermission" method="post">
                             	<input type="hidden" name="action" value="permission">
                                <input type="hidden" name="user_id" value="<?php echo $_REQUEST['id'];?>">
                                <div class="spacer"></div>
                                <div class="greenText" align="left">Select Modules To Set Permission </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd"><input type="checkbox" name="chkAll" id="checkAll" onClick="check_all();" value="1">&nbsp;Check All</div>
                                <div  class="textboxview" style="font-weight:bold;">Module Name</div>
                                 <div style="clear:both; border:solid 1px #ccc;"></div>
                                 <?php 
								  foreach($dbf->fetchOrder("client_module","","","","")as $res_clientPermission) { 
								  $resModuleId=$dbf->getDataFromTable("client_permission","module_id","user_id='$_REQUEST[id]' AND module_id='$res_clientPermission[id]'");
								?>
                                <div  class="formtextadd"><input type="checkbox" name="chkSetUser[]" id="chkSetUser" value="<?php echo $res_clientPermission['id'];?>" <?php if($res_clientPermission['id']==$resModuleId){echo 'checked';}?>/></div>
                                <div  class="textboxview"><?php echo $res_clientPermission['module_name'];?></div>
                                <div class="spacer"></div>
                                <?php }?>
                                <div class="spacer"></div>
                                <div align="center"  style="padding-right:60px;"><input type="submit" class="buttonText" value="Set Client Permission"/>&nbsp;<input type="button" class="buttonText" value="Return Back" onClick="window.location='manage-client'"/></div>
                               </form> 
                        	</div>
                            <!-----Table area start-------> 
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