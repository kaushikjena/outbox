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
//retrive data from service price table
//$resService = $dbf->fetchSingle("service_price","equipment='$_REQUEST[eqid]' AND service_id='$_REQUEST[serviceid]'");

if(isset($_REQUEST['action']) && $_REQUEST['action']=='update'){
	$EquipmentName=mysql_real_escape_string($_REQUEST['EquipmentName']);
	$count = $_REQUEST['count'];
	//loop for dynamic getiing price and insert into service price table
	for($i=1;$i<=$count;$i++){
		$SpPrice = 'SpPrice'.$i;
		$SpPrice=$_REQUEST[$SpPrice];
		$worktype='WorkType'.$i;
		$wotype=$_REQUEST[$worktype];
		$PayGradeA='PayGradeA'.$i;
	    $PayGradeA=$_REQUEST[$PayGradeA];
		$PayGradeB='PayGradeB'.$i;
		$PayGradeB=$_REQUEST[$PayGradeB];
		$PayGradeC='PayGradeC'.$i;
		$PayGradeC=$_REQUEST[$PayGradeC];
		$PayGradeD='PayGradeD'.$i;
	    $PayGradeD=$_REQUEST[$PayGradeD];
		$PayGradeE='PayGradeE'.$i;
	    $PayGradeE=$_REQUEST[$PayGradeE];
		$PayGradeF='PayGradeF'.$i;
	    $PayGradeF=$_REQUEST[$PayGradeF];
		$PayGradeG='PayGradeG'.$i;
	    $PayGradeG=$_REQUEST[$PayGradeG];
		$PayGradeH='PayGradeH'.$i;
	    $PayGradeH=$_REQUEST[$PayGradeH];
		$PayGradeI='PayGradeI'.$i;
	    $PayGradeI=$_REQUEST[$PayGradeI];
		$PayGradeJ='PayGradeJ'.$i;
	    $PayGradeJ=$_REQUEST[$PayGradeJ];
		//check duplicate service price
		$num= $dbf->countRows("service_price","service_id='$_REQUEST[ServiceName]' AND equipment='$EquipmentName' AND work_type='$wotype' AND id<>'$SpPrice'");
		if($num>0){
			header("Location:edit-service-price?msg=001");exit;
		}else{
			if($SpPrice==''){
				//insert string
				$string="service_id='$_REQUEST[ServiceName]',equipment='$EquipmentName',work_type='$wotype', gradeA_price='$PayGradeA',gradeB_price='$PayGradeB',gradeC_price='$PayGradeC',gradeD_price='$PayGradeD', gradeE_price='$PayGradeE', gradeF_price='$PayGradeF', gradeG_price='$PayGradeG', gradeH_price='$PayGradeH', gradeI_price='$PayGradeI', gradeJ_price='$PayGradeJ', created_date=now()";
				$dbf->insertSet("service_price",$string);
			}else{
				//update string
				$string="service_id='$_REQUEST[ServiceName]',equipment='$EquipmentName',work_type='$wotype', gradeA_price='$PayGradeA',gradeB_price='$PayGradeB',gradeC_price='$PayGradeC',gradeD_price='$PayGradeD', gradeE_price='$PayGradeE', gradeF_price='$PayGradeF', gradeG_price='$PayGradeG', gradeH_price='$PayGradeH', gradeI_price='$PayGradeI', gradeJ_price='$PayGradeJ', updated_date=now()";
	   			$dbf->updateTable("service_price",$string,"id='$SpPrice'");
			}
		}	
	}
	header("Location:manage-service-price");exit;
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
	var url="ajax_select_services.php";
	$.post(url,{"serviceid":servid,"eqid":eqid},function(res){			
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
                        	<div style="float:left;">Edit Tech Payment Price</div>
                        	<div style="float:right;"><input type="button" class="buttonText2" value="Return Back" onClick="javascript:window.location.href='manage-service-price'"/></div>
                        </div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        	<!-----Table area start------->
                          <div  class="divBilling">
                          <div class="innerService">
                            <div align="center"><?php if($_REQUEST['msg']=="001"){?><span class="redText">Price for this service already exist!!!.</span><?php } ?></div>
                            <div class="spacer"></div>
                         	  <form action="" name="frmPrice" id="frmPrice" method="post" onSubmit="return validate_service_price();" enctype="multipart/form-data">
                              <input type="hidden" name="action" value="update">
                                <div  class="formtextaddservice">Service Name:<span class="redText">*</span></div>
                                <div  class="textboxcservice">
                                 <select class="selectbox" name="ServiceName" id="ServiceName" onChange="showEquipment(this.value);">
                                  	<option value="">--Select Service Name--</option>
                                    <?php foreach($dbf->fetch("service","id>0 ORDER BY service_name ASC") as $vservice){?>
                                    <option value="<?php echo $vservice['id'];?>"<?php if($_REQUEST['serviceid']==$vservice['id']){echo 'selected';}?>><?php echo $vservice['service_name'];?></option>
                                    <?php }?>
                                  </select>
                    				<br/><label for="ServiceName" id="lblServiceName" class="redText"></label>
                                </div>
                                <div  class="formtextaddservice" >Equipment Name:<span class="redText">*</span></div>
                                <div  class="textboxcservice" id="equipid">
                                <select class="selectbox" name="EquipmentName" id="EquipmentName" onChange="showServices();">
                                  	<option value="">--Select Service Name--</option>
                                    <?php foreach($dbf->fetch("equipment","id>0 AND service_id='$_REQUEST[serviceid]' ORDER BY equipment_name ASC") as $valeq){?>
                                    <option value="<?php echo $valeq['id'];?>" <?php if($_REQUEST['eqid']==$valeq['id']){echo 'selected';}?>><?php echo $valeq['equipment_name'];?></option>
                                    <?php }?>
                                  </select><br/><label for="EquipmentName" id="lblEquipmentName" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div id="DivService">
                                <div>
                                  <div align="left" class="serprheader prService1">Work Type</div>
                                  <div align="center" class="serprheader prService">Pay Grade A</div>
                                  <div align="center" class="serprheader prService">Pay Grade B</div>
                                  <div align="center" class="serprheader prService">Pay Grade C</div>
                                  <div align="center" class="serprheader prService">Pay Grade D</div>
                                  <div align="center" class="serprheader prService">Pay Grade E</div>
                                  <div align="center" class="serprheader prService">Pay Grade F</div>
                                  <div align="center" class="serprheader prService">Pay Grade G</div>
                                  <div align="center" class="serprheader prService">Pay Grade H</div>
                                  <div align="center" class="serprheader prService">Pay Grade I</div>
                                  <div align="center" class="serprheader prService">Pay Grade J</div>
                                  <div style="clear:both;"></div>
                               </div>
                                <?php
								  $i=1;
								  $count=$dbf->countRows("work_type","");
								  $qry ="SELECT wt.id AS wid, wt.worktype, sp.* FROM work_type wt LEFT JOIN service_price sp ON wt.id=sp.work_type AND sp.equipment='$_REQUEST[eqid]' AND sp.service_id='$_REQUEST[serviceid]' ORDER BY wt.id ASC";
								  $result = $dbf->simpleQuery($qry); 
								  foreach($result as $vwtype){
							     ?>
                                <div  class="textboxserview"><input type="hidden" name="WorkType<?php echo $i;?>" value="<?php echo $vwtype['wid'];?>"><?php echo $vwtype['worktype'];?></div>
                                <div  class="textboxserprc">
                                    <input type="text" class="textboxjob" name="PayGradeA<?php echo $i;?>" id="PayGradeA<?php echo $i;?>" onKeyUp="return extractNumber(this,2);" maxlength="8" value="<?php echo $vwtype['gradeA_price'];?>"><br/><label for="PayGradeA" id="lblPayGradeA<?php echo $i;?>" class="redText"></label>
                                </div>
                                <div  class="textboxserprc">
                                    <input type="text" class="textboxjob" name="PayGradeB<?php echo $i;?>" id="PayGradeB<?php echo $i;?>" onKeyUp="return extractNumber(this,2);" maxlength="8" value="<?php echo $vwtype['gradeB_price'];?>"><br/><label for="PayGradeB" id="lblPayGradeB<?php echo $i;?>" class="redText"></label>
                                </div>
                                <div  class="textboxserprc">
                                    <input type="text" class="textboxjob" name="PayGradeC<?php echo $i;?>" id="PayGradeC<?php echo $i;?>" onKeyUp="return extractNumber(this,2);" maxlength="8" value="<?php echo $vwtype['gradeC_price'];?>"><br/><label for="PayGradeC" id="lblPayGradeC<?php echo $i;?>" class="redText"></label>
                               </div>
                               <div  class="textboxserprc">
                                    <input type="text" class="textboxjob" name="PayGradeD<?php echo $i;?>" id="PayGradeD<?php echo $i;?>" onKeyUp="return extractNumber(this,2);" maxlength="8" value="<?php echo $vwtype['gradeD_price'];?>"><br/><label for="PayGradeD" id="lblPayGradeD<?php echo $i;?>" class="redText"></label>
                                </div>
                                <div  class="textboxserprc">
                                    <input type="text" class="textboxjob" name="PayGradeE<?php echo $i;?>" id="PayGradeE<?php echo $i;?>" onKeyUp="return extractNumber(this,2);" maxlength="8" value="<?php echo $vwtype['gradeE_price'];?>"><br/><label for="PayGradeE" id="lblPayGradeE<?php echo $i;?>" class="redText"></label>
                                </div>
                                <div  class="textboxserprc">
                                    <input type="text" class="textboxjob" name="PayGradeF<?php echo $i;?>" id="PayGradeF<?php echo $i;?>" onKeyUp="return extractNumber(this,2);" maxlength="8" value="<?php echo $vwtype['gradeF_price'];?>"><br/><label for="PayGradeF" id="lblPayGradeF<?php echo $i;?>" class="redText"></label>
                                </div>
                                <div  class="textboxserprc">
                                    <input type="text" class="textboxjob" name="PayGradeG<?php echo $i;?>" id="PayGradeG<?php echo $i;?>" onKeyUp="return extractNumber(this,2);" maxlength="8" value="<?php echo $vwtype['gradeG_price'];?>"><br/><label for="PayGradeG" id="lblPayGradeG<?php echo $i;?>" class="redText"></label>
                                </div>
                                <div  class="textboxserprc">
                                    <input type="text" class="textboxjob" name="PayGradeH<?php echo $i;?>" id="PayGradeH<?php echo $i;?>" onKeyUp="return extractNumber(this,2);" maxlength="8" value="<?php echo $vwtype['gradeH_price'];?>"><br/><label for="PayGradeH" id="lblPayGradeH<?php echo $i;?>" class="redText"></label>
                                </div>
                                <div  class="textboxserprc">
                                    <input type="text" class="textboxjob" name="PayGradeI<?php echo $i;?>" id="PayGradeI<?php echo $i;?>" onKeyUp="return extractNumber(this,2);" maxlength="8" value="<?php echo $vwtype['gradeI_price'];?>"><br/><label for="PayGradeI" id="lblPayGradeI<?php echo $i;?>" class="redText"></label>
                                </div>
                                <div  class="textboxserprc">
                                    <input type="text" class="textboxjob" name="PayGradeJ<?php echo $i;?>" id="PayGradeJ<?php echo $i;?>" onKeyUp="return extractNumber(this,2);" maxlength="8" value="<?php echo $vwtype['gradeJ_price'];?>"><br/><label for="PayGradeJ" id="lblPayGradeJ<?php echo $i;?>" class="redText"></label>
                                </div>
                                <input type="hidden" name="SpPrice<?php echo $i;?>" value="<?php echo $vwtype['id'];?>"/>
                                <div class="spacer"></div>
                                <?php $i++; }?>
                                <div class="spacer" style="height:20px;"></div>
                                <div align="center">
                                <input type="hidden" name="count" id="count" value="<?php echo $count;?>"/>
                                <input type="submit" class="buttonText" value="Submit Form"/>
                                <input type="button" class="buttonText3" value="Back" onClick="javascript:window.location.href='manage-service-price'"/>
                                </div>
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