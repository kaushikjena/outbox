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
	//Delete record from clients Table
	if($_REQUEST['action']=='delete'){	
	    $c_data=$dbf->countRows("work_order","created_by='$_REQUEST[id]'");
		if($c_data){
		   header("Location:manage-client?msg=004");exit;
		}else{
		   $dbf->deleteFromTable("clients","id='$_REQUEST[id]'");
		   header("Location:manage-client");exit;
		}
	}
	
	if(isset($_REQUEST['action']) && $_REQUEST['action']=='send'){
		
		$clientData = $dbf->fetchSingle("clients","id='$_REQUEST[cid]'");
		//Email Sending Starts here
		  $res_template=$dbf->fetchSingle("email_template","id=7");
		  $from=$res_template['from_email'];
		  $from_name=$res_template['from_name'];  
		  $subject=$res_template['subject'];
		  $input=$res_template['message'];
		  
		  $to=$clientData['email'];
		  $toName=ucfirst($clientData['name']);
		  //$ProfilePassword=time();
		  $ProfilePassword=base64_decode(base64_decode($clientData['password']));
		  
		  $body=str_replace(array('%Name%','%EmailID%','%Password%'),array($toName,$to,$ProfilePassword),$input);
		  $headers = "MIME-Version: 1.0\n";
		  $headers .= "Content-type: text/html; charset=UTF-8\n";
		  $headers .= "From:".$from_name." <".$from.">\n";
		  //echo $body;exit;
		  @mail($to,$subject,$body,$headers);
		//Email Sending End
		//update client new password
		//$dbf->updateTable("clients","password='$ProfilePassword1'","id='$_REQUEST[cid]'");
		header("Location:manage-client");exit;
	}
?>
<body>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />
<link rel="stylesheet" href="css/table.css" type="text/css" />
<script type="text/javascript">
/*********Function to redirect page************/
function redirectPage(id,page){
	$("#hid").val(id);
	document.frmRedirect.action=page;
	document.frmRedirect.submit();
}
/*********Function to redirect page************/
function send_email_client(id){
	var r = confirm("Are you sure to send email to this client?");
	if(r){
		window.location.href="manage-client?action=send&cid="+id;
	}
}
function delete_client(id){
	var url = "ajax-client-delete.php"
	var r = confirm("Are you sure you want to delete this record ?");
	if(r){
		$.post(url,{"choice":"delete_client","clientid":id},function(res){
			if(res==0){
				window.location.href="manage-client?action=delete&id="+id;
			}else{
				alert("Sorry ! You can't delete this client. This client has some work orders.");
			}
		});
	}
}
/**********Send Notification to assigned Tech*********/
function save_tech_instruction(clientid){
	$.fancybox.showActivity();	
	var url="ajax-client-delete.php";
	$.post(url,{"choice":"show_text","clientid":clientid},function(res){			
		$.fancybox(res,{centerOnScroll:true,hideOnOverlayClick:false});				
	});
}
function saveInstruction(){
	$.fancybox.showActivity();	
	var url="ajax-client-delete.php";
	//var message = CKEDITOR.instances['message'].getData();
	var message = $("#message").val();
	//alert(message);
	var clientid=$('#clientid').val();
	if(message==''){
		alert("Please write some text.");
		return false;
	}else{
	 	$.post(url,{"choice":"save_text","clientid":clientid,"message":message},function(res){ //alert(res);
		 if(res=='1'){
			$.fancybox("Instruction saved Successfully",{centerOnScroll:true,hideOnOverlayClick:false});
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
              <!-------------Main Body--------------->
                <div class="rightcolumjobboard">
            		<div class="rightcoluminner">
                        <div class="headerbg"><div style="float:left;">Manage Clients</div>
                        	<div style="float:right;"><?php if($implode_clients==''){?><input type="button" class="buttonText2" value="Create Client" onClick="javascript:window.location.href='add-client'"/><?php }?></div>
                        </div>
                        <?php
						 if($_REQUEST['msg']=='004'){ ?>
							 <div align="center" style="color:red">This client has a Work Order,can't be deleted</div>
						<?php }else{?>
							 <div class="spacer"></div>
						<?php }?>
                        <div id="contenttable">
                        	<div style="width:100%;float:left;">
                              <div class="table">
                                <div class="table-head">
                                    <div class="column" data-label="User Name" style="width:20%;">Client Name</div>
                                    <div class="column" data-label="Email ID"  style="width:20%;">Email ID</div>
                                    <div class="column" data-label="Contact No" style="width:15%;">Contact No</div>  
                                    <div class="column" data-label="City"  style="width:15%;">City</div>
                                    <div class="column" data-label="State"  style="width:15%;">State</div>
                                    <div class="column" data-label="Action"  style="width:15%;">Action</div>
                                </div>
			                  	<?php
									//condition
									$cond = "c.state=s.state_code AND c.status=1 AND user_type='client'"; 
									//condition for users
									if($implode_clients <>''){
										$cond.=" AND FIND_IN_SET(c.id,'$implode_clients')";
									}
									//echo $cond;
									//Pagination 
									$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
									$page = ($page == 0 ? 1 : $page);
									$perpage =50;//limit in each page
									$startpoint = ($page * $perpage) - $perpage;
									//-----------------------------------				
									$num=$dbf->countRows("state s,clients c",$cond); 
									foreach($dbf->fetchOrder("state s,clients c",$cond,"c.id DESC LIMIT $startpoint,$perpage","")as $res_client) {
								?>
                                <div class="row">
                                    <div class="column" data-label="User Name"><?php echo $res_client['name'];?></div>
                                    <div class="column" data-label="Email ID"><?php echo $res_client['email'];?></div>                                    <div class="column" data-label="Contact No"><?php echo $res_client['phone_no'];?></div>
                                    <div class="column" data-label="City"><?php echo $res_client['city'];?></div>
                                    <div class="column" data-label="State"><?php echo $res_client['state_name'];?></div>                                    <div class="column" data-label="Action"><a href="javascript:void(0);" onClick="save_tech_instruction('<?php echo $res_client['id'];?>');"><img src="images/tech_instruction.png" title="Tech Instruction" alt="Tech Instruction"/></a>&nbsp;&nbsp;<a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_client['id'];?>','set-client-permission');"><img src="images/setpermission.png" title="Set Permission" alt="Set Permission" width="16" height="16"/></a>&nbsp;&nbsp;<a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_client['id'];?>','change-client-login');"><img src="images/changelogin.png" title="Change Login" alt="Change Login" width="16" height="16"/></a>&nbsp;&nbsp;<a href="javascript:void(0);" onClick="send_email_client('<?php echo $res_client['id'];?>');"><img src="images/email_go.png" title="Email To Client" alt="Email"></a>&nbsp;&nbsp;<a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_client['id'];?>','view-client');"><img src="images/view.png" title="View" alt="View"/></a>&nbsp;&nbsp;<a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_client['id'];?>','edit-client');"><img src="images/edit.png" title="Edit" alt="Edit"/></a>&nbsp;&nbsp;<a href="javascript:void(0);" onClick="delete_client('<?php echo $res_client['id'];?>');"><img src="images/delete.png" title="Delete" alt="Delete"/></a></div>
                               </div>
                              <?php }?>
                        	</div>
                            <?php if($num == 0) {?><div class="noRecords" style="padding-left:40%;">No records founds!!</div>
                            <?php }?>
                            <div  align="center"><?php if($num>0) { echo $dbf->Pages($num,$perpage,"manage-client?");}?></div>
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