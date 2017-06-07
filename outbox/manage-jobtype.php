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
		$dbf->deleteFromTable("job_type","id='$_REQUEST[id]'");
		header("Location:manage-jobtype");exit;
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
                        <div class="headerbg">
                        	<div style="float:left;">Manage Status type</div>
                        	<div style="float:right;"><!--<input type="button" class="buttonText2" value="Create Jobtype" onClick="javascript:window.location.href='add-jobtype'"/>--></div>
                        </div>
                        <?php if($_REQUEST['msg']=="002"){?>
                          <div align="center" style="color:red;">This Job type already exist!</div>
						<?php }else{?>
                          <div class="spacer"></div>
                          <?php } ?>
                        <div id="contenttable">
                        	<div style="width:100%;float:left;">
                              <div class="table">
                                <div class="table-head">
                                    <div class="column" data-label="Serial No" style="width:25%;">Serial No</div>
                                    <div class="column" data-label="Jobtype Name"  style="width:40%;">Status Name</div>
                                    <div class="column" data-label="Created Date" style="width:25%;">Created Date</div>  					
                                    <div class="column" data-label="Action"  style="width:10%;">Action</div>		  						       </div>
                        		<?php 
								//Pagination 
								$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
								$page = ($page == 0 ? 1 : $page);
								$perpage =20;//limit in each page
								$startpoint = ($page * $perpage) - $perpage;
								//-----------------------------------				
								$num=$dbf->countRows("status_type",""); 
								foreach($dbf->fetchOrder("status_type","","id ASC LIMIT $startpoint,$perpage","")as $res_jobtype) {?>
                                <div class="row">
                                    <div class="column" data-label="Serial No"><?php echo $res_jobtype['id'];?></div>
                                    <div class="column" data-label="Jobtype Name"><?php echo $res_jobtype['status_type'];?></div>                                    
                                    <div class="column" data-label="Created Date"><?php echo $res_jobtype['created_date'];?></div>                                   
                                    <div class="column" data-label="Action"><a href="edit-jobtype?id=<?php echo $res_jobtype['id'];?>"><img src="images/edit.png" title="Edit" alt="Edit"/></a><!--<a href="manage-jobtype?action=delete&id=<?php //echo $res_jobtype['id'];?>" onClick="return confirm('Are you sure you want to delete this record ?')"><img src="images/delete.png" title="Delete" alt="Delete"/></a>--></div>
                               </div>
                              <?php }?>
                        	</div>
                            <?php if($num == 0) {?><div class="noRecords" style="padding-left:40%;">No records founds!!</div>
                            <?php }?>
                            <div  align="center"><?php if($num>0) { echo $dbf->Pages($num,$perpage,"manage-jobtype?");}?></div>
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