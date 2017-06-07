<?php 
ob_start();
session_start();
include_once '../includes/class.Main.php';
//Object initialization
$dbf = new User();
//page titlevariable
$pageTitle="Welcome To Out Of The Box";
include 'applicationtop-client.php';
if($_SESSION['usertype']!='client'){
	header("location:../logout");exit;
}
//Fetch details from work_order table 
$res_viewJobBoard=$dbf->fetchSingle("clients c,service s,work_order w","c.id=w.client_id AND w.service_id=s.id AND w.id='$_REQUEST[id]'");
if($res_viewJobBoard ==''){
	header("Location:client-manage-job-board");exit;
}
$resTech = $dbf->fetchSingle("assign_tech at,technicians tc","at.tech_id=tc.id AND at.wo_no='$res_viewJobBoard[wo_no]'");
//fetch from work order doc table
$workorderdoc = $dbf->getDataFromTable("workorder_doc","wo_document","workorder_id='$_REQUEST[id]'");

if($_REQUEST['src']=='disp'){
	$link="client-manage-job-board-dispatch";
}elseif($_REQUEST['src']=='bill'){
	$link="client-workorder-billings";
}else{
	$link="client-manage-job-board";
}
?>
<script type="text/javascript">
function gmap(zipcode){
	$.fancybox.showActivity();
	var url="../gmap.php?zipcode="+zipcode	
	$.post(url,function(res){
		    $.fancybox(res,{centerOnScroll:true,hideOnOverlayClick:false});
	    });
  }
function downLoadDocument(fname){
	window.location.href='../docdnd.php?file=workorder_doc/'+fname;
}
function add_notes(){
	var url="ajax_addnote.php";
	var worid = $("#worid").val();
	var adminNotes = $("#adminNotes").val();
	if(adminNotes !=''){
		$.post(url,{"id":worid,"adminNotes":adminNotes},function(res){			
			$("#resnotes").html(res);
			$("#adminNotes").val('');			
		});
	}
}
</script>
<link rel="stylesheet" href="../css/innermain.css" type="text/css" />
<link rel="stylesheet" href="../css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="../css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="../css/respmenu.css" type="text/css" />
<body>
    <div id="maindiv">
        <!-------------header--------------->
     	<?php include_once 'header-client.php';?>
   		<!-------------header--------------->
        
        <!-------------top menu--------------->
     	<?php include_once 'client-top-menu.php';?>
   		<!-------------top menu--------------->
         <div id="contentdiv">
                <!-------------Main Body--------------->
                <div class="rightcolumjobboard">
            		<div class="rightcoluminner">
                        <div class="headerbg">
                        	<div style="float:left;">CLIENT VIEW JOB BOARD</div>
                            <div style="float:right;"><input type="button" class="buttonText2" value="Return Back" onClick="window.location='<?php echo $link;?>'"/></div>
                         </div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        <!-----Table area start------->
                          <form name="createJob" id="createJob" action="" method="post" onSubmit="return validate_createjob();" autocomplete="off">
                        	<input type="hidden" name="action" value="update"/>
                            <input type="hidden" name="clientid" value="<?php echo $res_viewJobBoard['client_id'];?>">
                            <input type="hidden" name="worid" id="worid" value="<?php echo $res_viewJobBoard['id'];?>">
                            <div align="center"><?php if($_REQUEST['msg']=='002'){?><span class="redText">This Email ID already exist!</span><?php }?></div>
                            <!-----address div start--------->
                            <div  class="divAddress">
                            <div class="greenText" align="left">Work Order Details</div>
                            <div  class="formtextaddjob">WO#:</div>
                            <div  class="textboxjobview">&nbsp;<?php echo $res_viewJobBoard['wo_no'];?></div>
                            <div  class="formtextaddjoblong">Purchase Order:</div>
                            <div  class="textboxjobview">&nbsp;<?php echo $res_viewJobBoard['purchase_order_no'];?></div>
                            <div class="spacer"></div>
                            <div  class="formtextaddjob">Job Status:</div>
                            <div  class="textboxjobview">&nbsp;<?php echo $res_viewJobBoard['job_status'];?></div>
                            <div  class="formtextaddjoblong">Service Type:</div>
                            <div  class="textboxjobview">&nbsp;<?php echo $res_viewJobBoard['service_name'];?></div>
                            <div class="spacer"></div>
                            <div  class="formtextaddjob">Technician:</div>
                            <div  class="textboxjobview">&nbsp;<?php if($resTech<>''){?><?php echo $resTech['first_name'].'&nbsp;'.$resTech['middle_name'].'&nbsp;'.$resTech['last_name'];?><?php }else{?> Not Assigned<?php }?></div>
                            <div  class="formtextaddjoblong">Assigned Date:</div>
                            <div  class="textboxjobview">&nbsp;<?php if($resTech<>'' && $resTech['assign_date']<>'0000-00-00'){echo date("d-M-Y",strtotime($resTech['assign_date']));}?></div>
                            <div class="spacer"></div>
                            <div  class="formtextaddjob">Scheduled Date:</div>
                            <div  class="textboxjobview">&nbsp;<?php if($resTech<>'' && $resTech['start_date']<>'0000-00-00'){echo date("d-M-Y",strtotime($resTech['start_date']));}?></div>
                           <div class="spacer" style="height:12px;"></div>
                         </div>
                         	<!-----address div end--------->
                            <!-----purchase div start--------->
                           	<div  class="divPurchase">
                               <div class="greenText" align="left">Customer Information</div>
                                <div  class="formtextaddjob"> Name:</div>
                                <div  class="textboxjobview"><?php echo $res_viewJobBoard['name'];?></div>
                                <div  class="formtextaddjoblong">Email Address:</div>
                                <div  class="textboxjobview"><?php echo $res_viewJobBoard['email'];?></div>
                                <div class="spacer"></div>
                                <div id="rescust">
                                <div  class="formtextaddjob">Address:</div>
                                <div  class="textboxjobview"><?php echo $res_viewJobBoard['address'];?></div>
                                <div  class="formtextaddjoblong"> Contact Name:</div>
                                <div  class="textboxjobview"><?php echo $res_viewJobBoard['contact_name'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextaddjob">City:</div>
                                <div  class="textboxjobview"><?php echo $res_viewJobBoard['city'];?></div>
                                <?php $res_jobState=$dbf->getDataFromTable("state","state_name","state_code='$res_viewJobBoard[state]'");?>
                                <div  class="formtextaddjoblong">State:</div>
                                <div  class="textboxjobview"><?php echo $res_jobState;?></div>
                                <div class="spacer"></div>
                                <div  class="formtextaddjob">Zip Code:</div>
                                <div  class="textboxjobview"><?php echo $res_viewJobBoard['zip_code'];?></div>
                                <div  class="formtextaddjoblong">Phone No:</div>
                                <div  class="textboxjobview"><?php echo $res_viewJobBoard['phone_no'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextaddjob">Cell No:</div>
                                <div  class="textboxjobview"><?php echo $res_viewJobBoard['fax_no'];?>&nbsp;</div>
                                <div  class="formtextaddjoblong"><a href="javascript:void(0);" onClick="return gmap('<?php echo $res_viewJobBoard['zip_code'];?>')" style="text-decoration:none;color:#F90;"><img src="../images/locater.jpg" alt="" width="21" height="24" align="absmiddle">Click For Map</a></div>
                                <div class="spacer"></div>
                            </div>
                        	</div>
                            <!-----purchase div end--------->
                            <!-----pickup div start--------->
                            <div  class="divPickup">
                            	<div class="greenText" align="left">Pick Up Information</div>
                            	<div class="spacer"></div>
                                <div  class="formtextaddjob">Location:</div>
                                <div  class="textboxjobview">&nbsp;<?php echo $res_viewJobBoard['pickup_location'];?></div>
                                <div  class="formtextaddjoblong">City:</div>
                                <div  class="textboxjobview">&nbsp;<?php echo $res_viewJobBoard['pickup_city'];?></div>
                                <div class="spacer"></div>
                                <?php $res_jobStatePick=$dbf->getDataFromTable("state","state_name","state_code='$res_viewJobBoard[pickup_state]'");?>
                                <div  class="formtextaddjob">State:</div>
                                <div  class="textboxjobview">&nbsp;<?php echo $res_jobStatePick;?></div>
                                <div  class="formtextaddjoblong">Address:</div>
                                <div  class="textboxjobview">&nbsp;<?php echo $res_viewJobBoard['pickup_address'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextaddjob">Zip Code:</div>
                                <div  class="textboxjobview">&nbsp;<?php echo $res_viewJobBoard['pickup_zip_code'];?></div>
                                <div  class="formtextaddjoblong">Phone Number:</div>
                                <div  class="textboxjobview">&nbsp;<?php echo $res_viewJobBoard['pickup_phone_no'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextaddjob">Alt Phone:</div>
                                <div  class="textboxjobview">&nbsp;<?php echo $res_viewJobBoard['pickup_alt_phone'];?></div>
                                <div class="spacer"></div>
                            </div>
                            <!-----pickup div end--------->
                            <!-----note div start--------->
                            <div  class="divNotes">
                             <div class="spacer"></div>
                             <div class="greenText" align="left">Job Description:</div>
                             <div  class="textboxjobviewlarge">&nbsp;<?php echo $res_viewJobBoard['notes'];?></div>
                             <?php if($workorderdoc){ ?>
                             <div class="spacer"></div>
                             <div style="float:right; padding-right:5px;"><span class="formtext"><a href="javascript:void(0);" onClick="downLoadDocument('<?php echo $workorderdoc;?>');"><?php echo $workorderdoc;?></a></span></div>
                             <?php }else{?>
                             <div class="spacer" style="height:15px;"></div>
                             <?php }?>
                            </div>
                            <!-----note div end--------->
                            <div class="spacer"></div>
                            <!-----service div start--------->
                            <div  class="divService">
                             <div>
                                  <div align="left" class="jobheader clService">Work Type</div>
                                  <div align="left" class="jobheader clEquipment">Equipment</div>
                                  <div align="left" class="jobheader clModel">Model</div>
                                  <div align="left" class="jobheader clQunt">Quantity</div>
                                  <div align="left" class="jobheader clPrice">Price/Rate</div>
                                  <div align="left" class="jobheader clPrice">Total Price</div>
                                  <div align="left" class="jobheader clPrice">Tech Price</div>
                                  <div style="clear:both;"></div>
                             </div>
                              <?php 
								 $res_woservice = $dbf->fetch("equipment e,work_type wt,workorder_service ws","e.id=ws.equipment AND wt.id=ws.work_type AND ws.workorder_id='$_REQUEST[id]'");
								  foreach($res_woservice as $arrWorkservice){
									  $total = ($arrWorkservice['quantity']*$arrWorkservice['outbox_price']);
									  if($resTech['pay_grade']=='A'){
										$TechPrice=$arrWorkservice['gradeA_price'];
									  }elseif($resTech['pay_grade']=='B'){
										$TechPrice=$arrWorkservice['gradeB_price'];
									  }elseif($resTech['pay_grade']=='C'){
										$TechPrice=$arrWorkservice['gradeC_price'];
									  }elseif($resTech['pay_grade']=='D'){
										$TechPrice=$arrWorkservice['gradeD_price'];
									  }
								?>
                           	  <div align="left" class="jobbody clService"><?php echo $arrWorkservice['worktype'];?></div>
                              <div align="left" class="jobbody clEquipment"><?php echo $arrWorkservice['equipment_name'];?></div>
                              <div align="left" class="jobbody clModel"><?php echo $arrWorkservice['model'];?></div>
                              <div align="left" class="jobbody clQunt"><?php echo $arrWorkservice['quantity'];?></div>
                              <div align="left" class="jobbody clPrice"><?php echo $arrWorkservice['outbox_price'];?></div>
                              <div align="left" class="jobbody clPrice"><?php echo number_format($total,2);?></div>
                              <div align="left" class="jobbody clPrice"><?php echo number_format($TechPrice,2);?></div>
                              <div style="clear:both; height:5px;"></div>
                               <?php }?>
                              <div style="clear:both; height:5px;"></div>
                            </div>
                            <!-----service div end--------->
                        	<div class="spacer"></div>
                             <!-----admin notes div start--------->
                            <div  class="divPickup">
                            <?php //fetch notes from work order notes table
								$resNotes=$dbf->fetchOrder("workorder_notes","workorder_id='$_REQUEST[id]' AND (user_type='admin' OR user_type='user' OR user_type='client')","created_date DESC");
							?>
                             <div class="greenText" align="left">Admin Notes:</div>
                             <div><textarea name="adminNotes" id="adminNotes"  class="textareaOrderNote" tabindex="24"></textarea><br/><label for="adminNotes" id="lbladminNotes" class="redText"></label></div>
                             <div align="right" style="margin:5px;"><img src="../images/add_note.png" alt="Add Note" title="Add Note" style="cursor:pointer;" onClick="add_notes();"/></div>
                             <div id="resnotes">
                             <?php foreach($resNotes as $resn){
								 if($resn['user_type']=='admin'){
									 $uname = $dbf->getDataFromTable("admin","name","id='$resn[user_id]'");
								 }elseif($resn['user_type']=='user'){
									  $uname = $dbf->getDataFromTable("users","name","id='$resn[user_id]'");
								 }elseif($resn['user_type']=='client'){
									  $uname = $dbf->getDataFromTable("clients","name","id='$resn[user_id]'");
								 }
							  ?>
                             <div class="textareaNoteView">
                                 <div align="left"><?php echo $resn['wo_notes'];?></div>
                                 <div class="spacer" style="border-bottom:dashed 1px #ccc;"></div>
                                 <div align="right">By <?php echo $uname;?> on <?php echo date("d-M-Y g:i A",strtotime($resn['created_date']));?> for #<?php echo $res_viewJobBoard['wo_no'];?></div>
                             </div> <div class="spacer"></div>
                             <?php }?>
                             </div>
                             <div class="spacer" style="height:10px;"></div>
                            </div>
                            <!-----admin note div end--------->
                            <!-----tech note div start--------->
                           	<div  class="divNotes">
                            <div class="greenText" align="left">Tech Notes:</div>
                            <?php
                             //fetch notes from work order notes table
                            $resNotes2=$dbf->fetchOrder("workorder_notes","workorder_id='$_REQUEST[id]' AND user_type='tech'","created_date DESC");
                            if(!empty($resNotes2)){
                            foreach($resNotes2 as $resn){
                              if($resn['user_type']=='tech'){
                                 $unameTech = $dbf->fetchSingle("technicians","id='$resn[user_id]'");
                                 $uname = $unameTech['first_name'].' '.$unameTech['middle_name'].' '.$unameTech['last_name'];
                             }
                            ?>
                             <div class="textareaNoteView">
                                 <div align="left"><?php echo $resn['wo_notes'];?></div>
                                 <div class="spacer" style="border-bottom:dashed 1px #ccc;"></div>
                                 <div align="right">By <?php echo $uname;?> on <?php echo date("d-M-Y g:i A",strtotime($resn['created_date']));?> for #<?php echo $res_viewJobBoard['wo_no'];?></div>
                             </div><div class="spacer"></div>
                            <?php }
							}else{
							?>
                            <span class="redText">Tech notes are not available.</span>
                            <div class="spacer"></div>
                            <?php }?>
                            </div>
                            <!-----tech note div end--------->
                            <div class="spacer"></div>
                            <div align="center">
                         	 <input type="button" class="buttonText" value="Return Back" onClick="window.location='<?php echo $link;?>'"/>
                             </div>
                          	<div class="spacer"></div>
                           </form>
                           <!-----Table area end------->
                    	</div>
            		</div>
               </div>
              <!-------------Main Body--------------->
         </div>
        <div class="spacer"></div>
        <?php include_once 'footer-client.php'; ?>
  </div>
</body>
</html>