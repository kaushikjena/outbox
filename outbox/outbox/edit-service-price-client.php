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
//retrive data from service price client table
//$resService = $dbf->fetchSingle("service_price","equipment='$_REQUEST[eqid]' AND service_id='$_REQUEST[serviceid]'");

if(isset($_REQUEST['action']) && $_REQUEST['action']=='update'){
	$ServiceName=mysql_real_escape_string($_REQUEST['ServiceName']);
	$EquipmentName=mysql_real_escape_string($_REQUEST['EquipmentName']);
	$ClientName=mysql_real_escape_string($_REQUEST['ClientName']);
	$count = $_REQUEST['count'];
	//loop for dynamic getiing price and insert into service price table
	for($i=1;$i<=$count;$i++){
		$SoPrice = 'SoPrice'.$i;
		$SoPrice=$_REQUEST[$SoPrice];
		$SpPrice = 'SpPrice'.$i;
		$SpPrice=$_REQUEST[$SpPrice];
		$worktype='WorkType'.$i;
		$wotype=$_REQUEST[$worktype];
		$OutBoxPrice='OutBoxPrice'.$i;
	    $OutBoxPrice=$_REQUEST[$OutBoxPrice];
		$ClientPrice='ClientPrice'.$i;
		$ClientPrice=$_REQUEST[$ClientPrice];
		//check duplicate service price
		$num= $dbf->countRows("service_price_client","service_id='$ServiceName' AND equipment='$EquipmentName' AND work_type='$wotype' AND client_id='$ClientName' AND id<>'$SpPrice'");
		if($num>0){
			header("Location:add-service-price-client?msg=001");exit;
		}else{
			###############INSERT OR UPDATE CLIENT PRICE#####################
			if($SpPrice==''){
				//insert string
				$string="service_id='$ServiceName', equipment='$EquipmentName', work_type='$wotype', client_id='$ClientName', client_price='$ClientPrice', created_date=now()";
				$dbf->insertSet("service_price_client",$string);
			}else{
				//update string
				$string="service_id='$ServiceName', equipment='$EquipmentName', work_type='$wotype', client_id='$ClientName', client_price='$ClientPrice', updated_date=now()";
	   			$dbf->updateTable("service_price_client",$string,"id='$SpPrice'");
			}
			###############INSERT OR UPDATE CLIENT PRICE#####################
			###############INSERT OR UPDATE OUTBOX PRICE#####################
			if($SoPrice==''){
				//insert string
				$string="service_id='$ServiceName', equipment='$EquipmentName', work_type='$wotype',  outbox_price='$OutBoxPrice', created_date=now()";
				$dbf->insertSet("service_price_outbox",$string);
			}else{
				//update string
				$string="service_id='$ServiceName', equipment='$EquipmentName', work_type='$wotype', outbox_price='$OutBoxPrice', updated_date=now()";
	   			$dbf->updateTable("service_price_outbox",$string,"id='$SoPrice'");
			}
			###############INSERT OR UPDATE OUTBOX PRICE#####################
		}
	}
	header("Location:manage-service-price-client");exit;
}
?>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />
<script type="text/javascript">
function showEquipment(servid){
	var url="ajax_select_equipment.php";
	$.post(url,{"serviceid":servid},function(res){			
		$("#equipid").html(res);			
	});
}
function showServices(){
	var servid = $("#ServiceName").val();
	var eqid = $("#EquipmentName").val();
	var cid = $("#ClientName").val();
	var url="ajax_select_client_price.php";
	$.post(url,{"choice":"clientprice","serviceid":servid,"eqid":eqid,"cid":cid},function(res){			
		$("#DivService").html(res);			
	});
}
</script>
<body onLoad="document.frmPrice.ServiceName.focus();">
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
                        <div class="headerbg">
                        	<div style="float:left;">Edit Client Billing Price</div>
                        	<div style="float:right;"><input type="button" class="buttonText2" value="Return Back" onClick="javascript:window.location.href='manage-service-price-client'"/></div>
                        </div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        <!-----Table area start------->
                         <div  class="divBilling">
                            <div class="innerService">
                            <div align="center"><?php if($_REQUEST['msg']=="001"){?><span class="redText">Price for this service already exist!!!.</span><?php } ?></div>
                            <div class="spacer"></div>
                              <form action="" name="frmPrice" id="frmPrice" method="post" onSubmit="return validate_service_price_client();" enctype="multipart/form-data">
                              <input type="hidden" name="action" value="update">
                               <div style="float:left; width:40%;">
                                <div  class="formtextaddservice">Service Name:<span class="redText">*</span></div>
                                <div  class="textboxcservice">
                                 <select class="selectbox" name="ServiceName" id="ServiceName" onChange="showEquipment(this.value);">
                                    <option value="">--Select Service Name--</option>
                                    <?php foreach($dbf->fetch("service","id>0 ORDER BY service_name ASC") as $vservice){?>
                                    <option value="<?php echo $vservice['id'];?>" <?php if($_REQUEST['serviceid']==$vservice['id']){echo 'selected';}?>><?php echo $vservice['service_name'];?></option>
                                    <?php }?>
                                  </select>
                                    <br/><label for="ServiceName" id="lblServiceName" class="redText"></label>
                                </div>
                                <div class="spacer" style="height:30px;"></div>
                                <div  class="formtextaddservice" >Equipment Name:<span class="redText">*</span></div>
                                <div  class="textboxcservice" id="equipid">
                                <select class="selectbox" name="EquipmentName" id="EquipmentName" onChange="showServices();">
                                    <option value="">--Select Service Name--</option>
                                    <?php foreach($dbf->fetch("equipment","id>0 AND service_id='$_REQUEST[serviceid]' ORDER BY equipment_name ASC") as $valeq){?>
                                    <option value="<?php echo $valeq['id'];?>" <?php if($_REQUEST['eqid']==$valeq['id']){echo 'selected';}?>><?php echo $valeq['equipment_name'];?></option>
                                    <?php }?>
                                  </select><br/><label for="EquipmentName" id="lblEquipmentName" class="redText"></label>
                                </div>
                                <div class="spacer" style="height:30px;"></div>
                                <div  class="formtextaddservice" >Client:<span class="redText">*</span></div>
                                <div  class="textboxcservice">
                                <select class="selectbox" name="ClientName" id="ClientName" onChange="showServices();">
                                    <option value="">--Select Client--</option>
                                    <?php foreach($dbf->fetch("clients","user_type='client' AND status=1 ORDER BY name ASC") as $valclient){?>
                                    <option value="<?php echo $valclient['id'];?>" <?php if($_REQUEST['cid']==$valclient['id']){echo 'selected';}?>><?php echo $valclient['name'];?></option>
                                    <?php }?>
                                  </select><br/><label for="ClientName" id="lblClientName" class="redText"></label>
                                </div>
                                <div class="spacer" style="height:30px;"></div>
                                <div align="center">
                                <input type="submit" class="buttonText" value="Submit Form"/>
                                <input type="button" class="buttonText3" value="Back" onClick="javascript:window.location.href='manage-service-price-client'"/>
                                </div>
                                </div>
                                <div id="DivService" style="float:left; width:58%;">
                                <div>
                                  <div align="left" class="serprheader prService1">Work Type</div>
                                  <div align="center" class="serprheader prService">Client Price</div>
                                  <div align="center" class="serprheader prService">OutBox Price</div>
                                  <div style="clear:both;"></div>
                               </div>
                                <?php
                                  $i=1;
                                  $count=$dbf->countRows("work_type","");
								  $qry ="SELECT temp.*, so.id as soid, so.outbox_price FROM (SELECT wt.id AS wid, wt.worktype, sp.id,sp.client_price,sp.service_id,sp.equipment,sp.work_type FROM work_type wt LEFT JOIN service_price_client sp ON wt.id=sp.work_type AND sp.service_id='$_REQUEST[serviceid]' AND sp.equipment='$_REQUEST[eqid]' AND client_id='$_REQUEST[cid]' ORDER BY wt.id ASC) as temp LEFT JOIN service_price_outbox so ON temp.service_id=so.service_id AND temp.equipment=so.equipment AND temp.work_type=so.work_type";

								  $result = $dbf->simpleQuery($qry); 
                                  foreach($result as $vwtype){
                                 ?>
                                <div  class="textboxserview"><input type="hidden" name="WorkType<?php echo $i;?>" value="<?php echo $vwtype['wid'];?>"><?php echo $vwtype['worktype'];?></div>
                                <div  class="textboxserprc">
                                    <input type="text" class="textboxjob" name="ClientPrice<?php echo $i;?>" id="ClientPrice<?php echo $i;?>" onKeyUp="return extractNumber(this,2);" maxlength="8" value="<?php echo $vwtype['client_price'];?>"><br/><label for="ClientPrice" id="lblClientPrice<?php echo $i;?>" class="redText"></label>
                                </div>
                                 <div  class="textboxserprc">
                                    <input type="text" class="textboxjob" name="OutBoxPrice<?php echo $i;?>" id="OutBoxPrice<?php echo $i;?>" onKeyUp="return extractNumber(this,2);" maxlength="8" value="<?php echo $vwtype['outbox_price'];?>"><br/><label for="OutBoxPrice" id="lblOutBoxPrice<?php echo $i;?>" class="redText"></label>
                                </div>
                                <input type="hidden" name="SpPrice<?php echo $i;?>" value="<?php echo $vwtype['id'];?>"/>
                                <input type="hidden" name="SoPrice<?php echo $i;?>" value="<?php echo $vwtype['soid'];?>"/>
                                <div class="spacer"></div>
                                <?php $i++; }?>
                                <input type="hidden" name="count" id="count" value="<?php echo $count;?>"/>
                                </div>
                                <div class="spacer"></div>
                                </form>
                            </div>
                        </div>
                       <!-----Table area start-------> 
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