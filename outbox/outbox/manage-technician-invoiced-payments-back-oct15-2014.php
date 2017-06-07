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
?>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />
<link rel="stylesheet" href="css/no_more_table.css" type="text/css" />
<script type="text/javascript">
function ClearFields(){
	$('#srchTech').val("");
	$('#SrchFrm').submit();
}
function generateBill(c,tid,page){
	var wonos = $("#wonoArr_"+c+"_"+tid).val();
	//alert(billperiod);
	$("#action").val("generatebill");
	$("#tid").val(tid);
	$("#wonos").val(wonos);
	$("#BillFrm").attr("action",page);
	$("#BillFrm").submit();
}
function displayRecords(month){
	$("#SrchFrm").submit();
}
</script>
<body>
	<form name="BillFrm" id="BillFrm" action="" method="post">
    	<input type="hidden" name="action" id="action" value=""/>
    	<input type="hidden" name="tid" id="tid" value=""/>
        <input type="hidden" name="wonos" id="wonos" value="" />
        <input type="hidden" name="billperiod" id="billperiod" value="" />
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
                        <div class="headerbg"><div style="float:left;">Technician Invoiced Payments</div>
                        	<div style="float:right;padding-right:10px;">
                            </div>
                        </div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        	<div style="width:100%;float:left;">
                            <form name="SrchFrm" id="SrchFrm" action="" method="post">
                              <div style="margin-bottom:5px;" align="center">
                              	  <div  class="formtextaddsrch" align="center">Technician</div>
                                  <div class="textboxcsrch">
                                  <select name="srchTech" id="srchTech" class="selectboxsrch">
                                  		<option value="">--Select Tech--</option>
                                        <?php foreach($dbf->fetch("technicians","id>0 AND status=1 ORDER BY first_name ASC")as $tech){?>
                                        <option value="<?php echo $tech['id']?>" <?php if($tech['id']==$_REQUEST['srchTech']){echo 'selected';}?>><?php echo $tech['first_name'].''.$tech['middle_name'].''.$tech['last_name'];?></option>
                                        <?php }?>
                                   </select>
                                    </div>
                                    <div  style="float:left; padding-left:20px;">
                                    <input type="submit" class="buttonText2" name="SearchRecord" value="Filter Records">
                                    <input type="button" class="buttonText2" name="Reset" value="Reset Filter" onClick="ClearFields();">
                                   </div>
                                  </div>
                              </form>
                              <div class="spacer"></div>
                              <?php
							   	$sch="";
								$fromdt=date("Y-m-d",strtotime(($_REQUEST['FromDate'])));
								$todt=date("Y-m-d",strtotime(($_REQUEST['ToDate'])));
								
								if($_REQUEST['srchTech']!=''){
									$sch=$sch."t.id='$_REQUEST[srchTech]' AND ";
								}
								
							   $sch=substr($sch,0,-5);
							   //echo $sch;exit;
							   if($sch!=''){
								 $cond="w.approve_status='1' AND w.work_status='Invoiced' AND t.id=at.tech_id AND at.wo_no=w.wo_no AND (wt.payment_status <>'Completed' OR wt.payment_status is NULL) AND ".$sch;
								  // echo $cond;exit;
							   }
							   elseif($sch==''){
								 $cond="w.approve_status='1' AND w.work_status='Invoiced' AND t.id=at.tech_id AND at.wo_no=w.wo_no AND (wt.payment_status <>'Completed' OR wt.payment_status is NULL)";
							   }
							  ?>
                            <!-----Table area start------->
                            <table id="no-more-tables" class="draggable">
                                <thead>
                                    <tr>
                                        <th width="25%">Tech Name</th>
                                        <th width="25%">Email</th>
                                        <th width="15%">Phone No</th>
                                        <th width="15%">OrderStatus</th>
                                        <th width="10%">No Of Jobs</th>
                                        <th width="10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
								<?php
									$c=1;
                                    $resGrArray=array();
									$qryg = "SELECT t.first_name, t.middle_name, t.last_name, t.email, t.contact_phone, w.work_status, t.id FROM assign_tech at,technicians t,work_order w LEFT JOIN work_order_tech_bill wt ON w.wo_no=wt.wo_no WHERE ".$cond." GROUP BY t.id";
									$resGrArray=$dbf->simpleQuery($qryg);
									
                                    //group by tech loop
                                    foreach($resGrArray as $k=>$sgRes){
									//count no of work orders
									$resSubArray = array();
                                    //collect work orders of the client
									$qry = "SELECT w.wo_no FROM assign_tech at,technicians t,work_order w LEFT JOIN work_order_tech_bill wt ON w.wo_no=wt.wo_no WHERE t.id='$sgRes[id]' AND ".$cond;
									$simpleResult = $dbf->simpleQuery($qry);
									foreach($simpleResult as $resw){
										array_push($resSubArray,$resw['wo_no']);
									}
									$numres = count($resSubArray);
									//print_r($resSubArray); 
								?>
                                  <tr>
                                    <td data-title="Tech"><?php echo $sgRes['first_name'].' '.$sgRes['middle_name'].' '.$sgRes['last_name'];?></td>
                                    <td data-title="Email"><?php echo $sgRes['email'];?></td>
                                    <td data-title="PhoneNo"><?php echo $sgRes['contact_phone'];?></td>
                                    <td data-title="WorkStatus"><?php echo $sgRes['work_status'];?></td>
                                    <td data-title="NoOfJobs"><?php echo $numres;?></td>
                                    <td data-title="Action" class="coltext"><a href="javascript:void(0);" onClick="generateBill('<?php echo $c;?>','<?php echo $sgRes['id'];?>','admin-technician-invoiced-payments');">Generate</a> <input type="hidden" id="wonoArr_<?php echo $c;?>_<?php echo $sgRes['id'];?>" value="<?php echo implode(",",$resSubArray);?>"/>
                                    </td>
                               	 </tr>
								   <?php 
                                	}$c++;//end of group array
							   ?>
                        	  </tbody>
                            </table>
                            <!-----Table area end------->
                            <?php if($c == 1){?><div class="noRecords" align="center">No records founds!!</div><?php }?>
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