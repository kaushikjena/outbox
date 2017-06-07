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
	$('#srchClient').val("");
	$("#SrchFrm").submit();
}
function generateBill(c,cid,page){
	var wonos = $("#wonoArr_"+c+"_"+cid).val();
	//alert(wonos);
	$("#action").val("generatebill");
	$("#cid").val(cid);
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
    	<input type="hidden" name="cid" id="cid" value=""/>
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
                <!-------------Main Body--------------->
                <div class="rightcolumjobboard">
            		<div class="rightcoluminner">
                        <div class="headerbg"><div style="float:left;">Client Invoived Billings</div>
                        	<div style="float:right;padding-right:10px;"></div>
                        </div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        	<div style="width:100%;float:left;">
                            <form name="SrchFrm" id="SrchFrm" action="" method="post">
                              <div style="margin-bottom:5px;" align="center">
                              	  <div  class="formtextaddsrch" align="center">Client</div>
                                  <div class="textboxcsrch">
                                  <select name="srchClient" id="srchClient" class="selectboxsrch">
                                  		<option value="">--Select Client--</option>
                                        <?php foreach($dbf->fetchOrder("work_order wo,clients cl","wo.created_by=cl.id AND wo.created_by<>'0' AND wo.work_status='Invoiced'","cl.name ASC","","cl.name")as $client){?>
                                        <option value="<?php echo $client['id']?>" <?php if($client['id']==$_REQUEST['srchClient']){echo 'selected';}?>><?php echo $client['name'];?></option>
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
								if($_REQUEST['srchClient']!=''){
									$sch=$sch."w.created_by='$_REQUEST[srchClient]' AND ";
								}
							   $sch=substr($sch,0,-5);
							   //echo $sch;exit;
							   if($sch!=''){
								 $cond="c.id=w.created_by AND w.approve_status='1' AND w.work_status='Invoiced' AND w.created_by<>0 AND (wb.payment_status <>'Completed' OR wb.payment_status is NULL) AND ".$sch;
								  // echo $cond;exit;
							   }
							   elseif($sch==''){
								 $cond="c.id=w.created_by AND w.approve_status='1' AND w.work_status='Invoiced' AND w.created_by<>0 AND (wb.payment_status <>'Completed' OR wb.payment_status is NULL)";
							   }
							   //echo $cond;
							  ?>
                            <!-----Table area start------->
                            <table id="no-more-tables" class="draggable">
                                <thead>
                                    <tr>
                                        <th width="25%">Client</th>
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
									$qryg = "SELECT c.name,c.email,c.phone_no,w.work_status,w.created_by FROM clients c,work_order w LEFT JOIN work_order_bill wb ON w.wo_no=wb.wo_no WHERE ".$cond." GROUP BY w.created_by";
									$resGrArray=$dbf->simpleQuery($qryg);
                                    //group by client loop
                                    foreach($resGrArray as $k=>$sgRes){
									//count no of work orders
									$resSubArray = array();
                                    //collect work orders of the client
									$qry = "SELECT w.wo_no FROM clients c,work_order w LEFT JOIN work_order_bill wb ON w.wo_no=wb.wo_no WHERE w.created_by='$sgRes[created_by]' AND ".$cond;
									$simpleResult = $dbf->simpleQuery($qry);
									foreach($simpleResult as $resw){	
										array_push($resSubArray,$resw['wo_no']);
									}
									$numres = count($resSubArray);
									//print "<pre>";
									//print_r($resSubArray); 
								?>
                                  <tr>
                                    <td data-title="Client"><?php echo $sgRes['name'];?></td>
                                    <td data-title="Email"><?php echo $sgRes['email'];?></td>
                                    <td data-title="PhoneNo"><?php echo $sgRes['phone_no'];?></td>
                                    <td data-title="WorkStatus"><?php echo $sgRes['work_status'];?></td>
                                    <td data-title="NoOfJobs"><?php echo $numres;?></td>
                                    <td data-title="Action" class="coltext"><a href="javascript:void(0);" onClick="generateBill('<?php echo $c;?>','<?php echo $sgRes['created_by'];?>','admin-client-invoiced-billings');">Generate</a> <input type="hidden" id="wonoArr_<?php echo $c;?>_<?php echo $sgRes['created_by'];?>" value="<?php echo implode(",",$resSubArray);?>"/>
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