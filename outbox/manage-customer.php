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
	//Delete record from customer Table
	if($_REQUEST['action']=='delete'){	
	    $c_data=$dbf->countRows("work_order","client_id='$_REQUEST[id]'");
		if($c_data){
		   header("Location:manage-customer?msg=004");exit;
		}else{
		   $dbf->deleteFromTable("clients","id='$_REQUEST[id]'");
		   header("Location:manage-customer");exit;
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
function delete_client(id){
	var url = "ajax-client-delete.php"
	var r = confirm("Are you sure you want to delete this record ?");
	if(r){
		$.post(url,{"choice":"delete_customer","custid":id},function(res){
			if(res==0){
				window.location.href="manage-customer?action=delete&id="+id;
			}else{
				alert("Sorry ! You can't delete this customer. This customer has some work orders.");
			}
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
                <!-------------Main Body--------------->
                <div class="rightcolumjobboard">
            		<div class="rightcoluminner">
                        <div class="headerbg"><div style="float:left;">Manage Customer</div></div>
                        <?php
						 if($_REQUEST['msg']=='004'){ ?>
							 <div align="center" style="color:red">This customer has a Work Order,can't be deleted</div>
						<?php }else{?>
							 <div class="spacer"></div>
						<?php  }?>
                        <div id="contenttable">
                        	<div style="width:100%;float:left;">
                              <div class="table">
                                <div class="table-head">
                                    <div class="column" data-label="User Name" style="width:17%;">Customer Name</div>
                                    <div class="column" data-label="Email ID"  style="width:17%;">Email ID</div>
                                    <div class="column" data-label="Contact No" style="width:16%;">Contact No</div>  
                                    <div class="column" data-label="City"  style="width:15%;">City</div>
                                    <div class="column" data-label="State"  style="width:15%;">State</div>
                                    <div class="column" data-label="Action"  style="width:5%;">Action</div>
                                </div>
			                  <?php 
								//Pagination 
									$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
									$page = ($page == 0 ? 1 : $page);
									$perpage =50;//limit in each page
									$startpoint = ($page * $perpage) - $perpage;
									//-----------------------------------				
									$num=$dbf->countRows("state s,clients c","c.state=s.state_code AND c.status=0 AND c.user_type='customer'"); 
									foreach($dbf->fetchOrder("state s,clients c","c.state=s.state_code AND c.status=0 AND c.user_type='customer'","c.id DESC LIMIT $startpoint,$perpage","")as $res_client) {
										?>
                                <div class="row">
                                    <div class="column" data-label="User Name"><?php echo $res_client['name'];?></div>
                                    <div class="column" data-label="Email ID"><?php echo $res_client['email'];?></div>                                    <div class="column" data-label="Contact No"><?php echo $res_client['phone_no'];?></div>
                                    <div class="column" data-label="City"><?php echo $res_client['city'];?></div>
                                    <div class="column" data-label="State"><?php echo $res_client['state_name'];?></div>                                    <div class="column" data-label="Action"><a href="view-customer?id=<?php echo $res_client['id'];?>"><img src="images/view.png" title="View" alt="View"/></a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onClick="delete_client('<?php echo $res_client['id'];?>');"><img src="images/delete.png" title="Delete" alt="Delete"/></a></div>
                               </div>
                              <?php }?>
                        	</div>
                            <?php if($num == 0) {?><div class="noRecords" style="padding-left:40%;">No records founds!!</div>
                            <?php }?>
                            <div  align="center"><?php if($num>0) { echo $dbf->Pages($num,$perpage,"manage-customer?");}?></div>
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