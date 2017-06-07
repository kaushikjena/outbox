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
	//Delete record from cod_credit_card_info Table
	if($_REQUEST['action']=='delete'){	
		$dbf->deleteFromTable("cod_credit_card_info","id='$_REQUEST[id]'");
		header("Location:credit-card-details");exit;
	}
?>
<body>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />
<link rel="stylesheet" href="css/table.css" type="text/css" />
<script type="text/javascript">
function delete_credit_card_details(id){
	var r = confirm("Are you sure you want to delete this record ?");
	if(r){
		window.location.href="credit-card-details?action=delete&id="+id;
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
                        <div class="headerbg"><div style="float:left;">Manage Customer Credit Card Details</div></div>
                        <div id="contenttable">
                        	<div style="width:100%;float:left;">
                              <div class="table">
                                <div class="table-head">
                                    <div class="column" data-label="User Name" style="width:17%;">Customer Name</div>
                                    <div class="column" data-label="Wo No" style="width:8%;">Wo No</div>
                                    <div class="column" data-label="Card Type"  style="width:12%;">Card Type</div>
                                    <div class="column" data-label="Card No" style="width:12%;">Card No</div>  
                                    <div class="column" data-label="Exp Date"  style="width:5%;">Expiration Date</div>
                                    <div class="column" data-label="CCV No"  style="width:8%;">CCV No</div>
                                    <div class="column" data-label="Card Holder Name"  style="width:12%;">Card Holder Name</div>
                                    <div class="column" data-label="Email"  style="width:10%;">Email</div>
                                    <div class="column" data-label="Zip Code"  style="width:10%;">Zip Code</div>
                                    <div class="column" data-label="Action"  style="width:10%;">Action</div>
                                </div>
			                  <?php 
								//Pagination 
									$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
									$page = ($page == 0 ? 1 : $page);
									$perpage =50;//limit in each page
									$startpoint = ($page * $perpage) - $perpage;
									//-----------------------------------				
									$num=$dbf->countRows("cod_credit_card_info ccd,work_order wo,clients c","ccd.workorder_id=wo.id AND c.status=0 AND c.user_type='customer' AND c.id=ccd.client_id"); 
									foreach($dbf->fetchOrder("cod_credit_card_info ccd,work_order wo,clients c","ccd.workorder_id=wo.id AND c.status=0 AND c.user_type='customer' AND c.id=ccd.client_id","ccd.id DESC LIMIT $startpoint,$perpage","c.name,wo.wo_no,ccd.card_type,ccd.card_number,ccd.expiry_month,ccd.expiry_year,ccd.ccv_number,ccd.card_holder_name,ccd.zip_code,ccd.email,ccd.id")as $res_creditcard) {
										?>
                                <div class="row">
                                    <div class="column" data-label="User Name"><?php echo $res_creditcard['name'];?></div>
                                    <div class="column" data-label="Wo No"><?php echo $res_creditcard['wo_no'];?></div>
                                    <div class="column" data-label="Card Type"><?php echo $res_creditcard['card_type'];?></div>
                                    <div class="column" data-label="Card No"><?php echo $res_creditcard['card_number'];?></div>
                                    <div class="column" data-label="Exp Date"><?php echo $res_creditcard['expiry_month']."/".$res_creditcard['expiry_year'];?></div>
                                    <div class="column" data-label="CCV No"><?php echo $res_creditcard['ccv_number'];?></div>
                                    <div class="column" data-label="Card Holder Name"><?php echo $res_creditcard['card_holder_name'];?></div> 
                                    <div class="column" data-label="Zip Code"><?php echo $res_creditcard['email'];?></div>
                                    <div class="column" data-label="Zip Code"><?php echo $res_creditcard['zip_code'];?></div> 
                                    <div class="column" data-label="Action"><a href="view-credit-card-details?id=<?php echo $res_creditcard['id'];?>"><img src="images/view.png" title="View" alt="View"/></a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onClick="delete_credit_card_details('<?php echo $res_creditcard['id'];?>');"><img src="images/delete.png" title="Delete" alt="Delete"/></a></div>
                               </div>
                              <?php }?>
                        	</div>
                            <?php if($num == 0) {?><div class="noRecords" style="padding-left:40%;">No records founds!!</div>
                            <?php }?>
                            <div  align="center"><?php if($num>0) { echo $dbf->Pages($num,$perpage,"credit-card-details?");}?></div>
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