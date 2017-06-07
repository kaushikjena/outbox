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
<body>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />
<link rel="stylesheet" href="css/table.css" type="text/css" />
<script type="text/javascript">
function changeStatus(wtypid){//alert(wtypid)
	var url="ajax-change-status.php";
	$.post(url,{"choice":"worktype","wtypid":wtypid},function(res){	alert(res);		
		$("#awtyp"+wtypid).html(res);		
	});
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
				<?php include_once 'left-menu.php';?>
                <!-------------left menu--------------->
                <!-------------Main Body--------------->
                <div class="rightcolum">
            		<div class="rightcoluminner">
                        <div class="headerbg">
                        	<div style="float:left;">Manage Work Type</div>
                        	<div style="float:right;"><input type="button" class="buttonText2" value="Create Worktype" onClick="javascript:window.location.href='add-worktype'"/></div>
                        </div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        	<div style="width:100%;float:left;">
                              <div class="table">
                                <div class="table-head">
                                    <div class="column" data-label="Serial No" style="width:20%;">Serial No</div>
                                    <div class="column" data-label="WorkType Name"  style="width:50%;">WorkType Name</div>
                                    <div class="column" data-label="Created Date" style="width:20%;">Created Date</div>  					
                                    <div class="column" data-label="Action"  style="width:10%;">Action</div>		  						       </div>
                        		<?php 
								//Pagination 
								$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
								$page = ($page == 0 ? 1 : $page);
								$perpage =10;//limit in each page
								$startpoint = ($page * $perpage) - $perpage;
								//-----------------------------------				
								$num=$dbf->countRows("work_type",""); 
								foreach($dbf->fetchOrder("work_type","","id ASC LIMIT $startpoint,$perpage","")as $res_worktype) {
								if($res_worktype['status']==1){
										$src="images/green-circle.png";$title="Active";
								}else{
									$src="images/red-circle.png";$title="Inactive";
								}
								?>
                                <div class="row">
                                    <div class="column" data-label="Serial No"><?php echo $res_worktype['id'];?></div>
                                    <div class="column" data-label="WorkType Name"><?php echo $res_worktype['worktype'];?></div>                                    
                                    <div class="column" data-label="Created Date"><?php echo $res_worktype['created_date'];?></div>                                   
                                    <div class="column" data-label="Action"><a href="javascript:void(0);" onClick="changeStatus('<?php echo $res_worktype['id'];?>');" id="awtyp<?php echo $res_worktype['id'];?>"><img src="<?php echo $src;?>" title="<?php echo $title;?>" alt="Status"/></a>&nbsp;&nbsp;<a href="edit-worktype?id=<?php echo $res_worktype['id'];?>"><img src="images/edit.png" title="Edit" alt="Edit"/></a>&nbsp;&nbsp;<a href="manage-worktype?action=delete&id=<?php echo $res_worktype['id'];?>" onClick="return confirm('Are you sure you want to delete this record ?')"><img src="images/delete.png" title="Delete" alt="Delete"/></a></div>
                               </div>
                              <?php }?>
                        	</div>
                            <?php if($num == 0){?><div class="noRecords" style="padding-left:40%;">No records founds!!</div>
                            <?php }?>
                            <div  align="center"><?php if($num>0){ echo $dbf->Pages($num,$perpage,"manage-worktype?");}?></div>
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