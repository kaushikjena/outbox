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
	if($_REQUEST['action']=='delete')
	{	
		$res_photo = $dbf->getDataFromTable("users","user_photo","id='$_REQUEST[id]'");
		$path="user_photo/".$res_photo;
		unlink($path);
		$dbf->deleteFromTable("users","id='$_REQUEST[id]'");
		header("Location:manage-user");exit;
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
                        <div class="headerbg"><div style="float:left;">Manage Users</div>
                        	<div style="float:right;"><input type="button" class="buttonText2" value="Create User" onClick="javascript:window.location.href='add-user'"/></div>
                        </div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        	<div style="width:100%;float:left;">
                              <div class="table">
                                <div class="table-head">
                                    <div class="column" data-label="User Name" style="width:23%;">User Name</div>
                                    <div class="column" data-label="Email ID"  style="width:24%;">Email ID</div>
                                    <div class="column" data-label="Mobile No" style="width:16%;">Mobile No</div>  
                                    <div class="column" data-label="City"  style="width:12%;">City</div>
                                    <div class="column" data-label="State"  style="width:15%;">State</div>
                                    <div class="column" data-label="Action"  style="width:10%;">Action</div>
                                </div>
                        		<?php 
								//Pagination 
								$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
								$page = ($page == 0 ? 1 : $page);
								$perpage =10;//limit in each page
								$startpoint = ($page * $perpage) - $perpage;
								//-----------------------------------				
								$num=$dbf->countRows("state s,users u","s.state_code=u.state AND user_type='user'"); 
								foreach($dbf->fetchOrder("state s,users u","s.state_code=u.state AND user_type='user'","u.id DESC LIMIT $startpoint,$perpage","")as $res_user) {?>
                                <div class="row">
                                    <div class="column" data-label="User Name"><?php echo $res_user['name'];?></div>
                                    <div class="column" data-label="Email ID"><?php echo $res_user['email'];?></div>                                    <div class="column" data-label="Mobile No"><?php echo $res_user['mobile'];?></div>
                                    <div class="column" data-label="City"><?php echo $res_user['city'];?></div>
                                    <div class="column" data-label="State"><?php echo $res_user['state_name'];?></div>                                    <div class="column" data-label="Action"><a href="javascript:void();" onClick="redirectPage('<?php echo $res_user['id'];?>','set-user-permission');"><img src="images/setpermission.png" title="Set Permission" alt="Set Permission" width="16" height="16"></a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_user['id'];?>','view-user');"><img src="images/view.png" title="View" alt="View"/></a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onClick="redirectPage('<?php echo $res_user['id'];?>','edit-user');"><img src="images/edit.png" title="Edit" alt="Edit"/></a>&nbsp;&nbsp;&nbsp;<a href="manage-user?action=delete&id=<?php echo $res_user['id'];?>" onClick="return confirm('Are you sure you want to delete this record ?')"><img src="images/delete.png" title="Delete" alt="Delete"/></a></div>
                               </div>
                              <?php }?>
                        	</div>
                            <?php if($num == 0) {?><div class="noRecords" style="padding-left:40%;">No records founds!!</div>
                            <?php }?>
                            <div  align="center"><?php if($num>0) { echo $dbf->Pages($num,$perpage,"manage-user?");}?></div>
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