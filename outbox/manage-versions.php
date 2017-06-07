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
		$dbf->deleteFromTable("versions","id='$_REQUEST[id]'");
		header("Location:manage-versions");exit;
	}
?>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />
<link rel="stylesheet" href="css/table.css" type="text/css" />
<body>
    <div id="maindiv">
        <!-------------header--------------->
     	<?php include_once 'header.php';?>
   		<!-------------header--------------->
        
        <!-------------top menu--------------->
     	<?php include_once 'top-menu.php';?>
   		<!-------------top menu--------------->
         <div id="contentdiv">
                <!-------------Left menu--------------->
				<?php include_once 'left-menu.php';?>
                <!-------------left menu--------------->
                <!-------------Main Body--------------->
                <div class="rightcolum">
            		<div class="rightcoluminner">
                        <div class="headerbg"><div style="float:left;">Manage Versions</div>
                        	<div style="float:right;"><input type="button" class="buttonText2" value="Create Version" onClick="javascript:window.location.href='add-version'"/></div>
                        </div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        	<div style="width:100%;float:left;">
                              <div class="table">
                                <div class="table-head">
                                    <div class="column" data-label="Serial No" style="width:15%;">Serial No</div>
                                    <div class="column" data-label="Email"  style="width:30%;">Name</div>
                                    <div class="column" data-label="Email"  style="width:45%;">Title</div>
                                    <div class="column" data-label="Action"  style="width:10%;">Action</div>		  						 </div>
                        		<?php 
								//Pagination 
								$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
								$page = ($page == 0 ? 1 : $page);
								$perpage =10;//limit in each page
								$startpoint = ($page * $perpage) - $perpage;
								//-----------------------------------				
								$num=$dbf->countRows("versions",""); 
								foreach($dbf->fetchOrder("versions","","id ASC LIMIT $startpoint,$perpage","")as $res_version) {?>
                                <div class="row">
                                    <div class="column" data-label="Serial No"><?php echo $res_version['id'];?></div>
                                    <div class="column" data-label="email"><?php echo $res_version['name'];?></div>
                                    <div class="column" data-label="email"><?php echo $res_version['title'];?></div>
                                    <div class="column" data-label="Action"><a href="add-version?id=<?php echo $res_version['id'];?>"><img src="images/edit.png" title="Edit" alt="Edit"/></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="manage-versions?action=delete&id=<?php echo $res_version['id'];?>" onClick="return confirm('Are you sure you want to delete this record ?')"><img src="images/delete.png" title="Delete" alt="Delete"/></a></div>
                               </div>
                              <?php }?>
                        	</div>
                            <?php if($num == 0) {?><div class="noRecords" style="padding-left:40%;">No records founds!!</div>
                            <?php }?>
                            <div  align="center"><?php if($num>0) { echo $dbf->Pages($num,$perpage,"manage-versions?");}?></div>
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