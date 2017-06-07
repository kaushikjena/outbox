<?php 
ob_start();
session_start();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();
if(isset($_REQUEST['choice']) && $_REQUEST['choice']=="show_billing_notes_form"){ 
$wo_no=$dbf->getdataFromtable("work_order","wo_no","id=$_REQUEST[woid]");
?>
<style type="text/css">
textarea.textareaOrderNote{
	border-color:transparent;
}
textarea.textareaOrderNote:focus{
	border-color:#999;
}
</style>
<div id="maindiv">
     <div  style="margin:2px;">
          <!-------------Main Body--------------->
            <div class="technicianjobboard" style="width:800px;">
                <div class="rightcoluminner">
                    <div class="headerbg">Billing Notes Details for wo#:<?php echo $wo_no;?></div>
                    <div class="spacer"></div>
                    <div id="contenttable">
                        <!-----Table area start------->
                        <div  class="emailDiv">
                        <div class="spacer"></div>
                         <form action="" name="frmBillingInfo" id="frmBillingInfo" method="post" enctype="multipart/form-data">
                         	<div class="spacer"></div>
                            <div  class="divService">
                              <div id="equipid">	
                              <div>
                                  <div align="left" class="jobheader clQunt">SI No</div>
                                  <div align="left" class="jobheader clService">Work Type</div>
                                  <div align="left" class="jobheader clEquipment">Equipment</div>
                                  <div align="left" class="jobheader clModel">Model</div>
                                  <div align="left" class="jobheader clEquipment">Description</div>
                                  <div style="clear:both;"></div>
                              </div>
                             <?php
							 $res_woservice = $dbf->fetch("equipment e,work_type wt,workorder_service ws","e.id=ws.equipment AND wt.id=ws.work_type AND ws.workorder_id='$_REQUEST[woid]'");
							 $arrWorkservice =array();$i=1;
							 $cntwotype =$dbf->countRows("workorder_service","workorder_id='$_REQUEST[woid]'");
							 for($j=0; $j<$cntwotype;$j++){
								  $arrWorkservice= $res_woservice[$j];
							 ?>
                              <div align="left" class="jobbody clQunt" style="margin-right:0px;"><?php echo $i;?></div>
                              <input type="hidden" name="hid<?php echo $i;?>" value="<?php echo $arrWorkservice['id'];?>">
                              <div align="left" class="jobbody clService" style="margin-right:0px;"><?php echo $arrWorkservice['worktype'];?></div>
                              <div align="left" class="jobbody clEquipment" style="margin-right:0px;"><?php echo $arrWorkservice['equipment_name'];?>
                              </div>
                              <div align="left" class="jobbody clModel" style="margin-right:0px;"><?php echo $arrWorkservice['model'];?></div>
                              <div align="left" class="jobbody clEquipment" style="margin-right:0px;"><textarea name="Itemnotes<?php echo $i;?>" id="Itemnotes<?php echo $i;?>"  class="textareaOrderNote" tabindex="24" ><?php echo $arrWorkservice['item_notes'];?></textarea></div>
                              <div style="clear:both; height:5px;"></div>
                              <?php $i++;}?>
                              </div>
                              <div style="clear:both; height:5px;"></div>
                            </div>
                            <div class="spacer"></div>
                             <div align="center">
                             <input type="hidden" name="woid" id="woid" value="<?php echo $_REQUEST['woid']; ?>"/>
                             <input type="button" class="buttonText" value="Save" onClick="save_billing_info();"/></div>
                            </form>
                        </div>
                        <!-----Table area start-------> 
                        <div class="spacer"></div>
                    </div>
            </div>
           </div>
          <!-------------Main Body--------------->
     </div>
  </div>	
<?php }else if(isset($_REQUEST['choice'])&& $_REQUEST['choice']=="save_Info"){
	ob_clean();//print "<pre>";print_r($_REQUEST);exit;
	##########Update notes in workorder service table###########
	$cntwotype =$dbf->countRows("workorder_service","workorder_id='$_REQUEST[woid]'");
	for($i=1;$i<=$cntwotype;$i++){
		$hid='hid'.$i;
		$hid=$_REQUEST[$hid];
		$item='Itemnotes'.$i;
		$itemnotes=$_REQUEST[$item];
		if($itemnotes<>''){
			  //update into workorder service table
			   $string2="item_notes='$itemnotes'";
			   $dbf->updateTable("workorder_service",$string2,"id='$hid'");
		}
	}
	echo 1;exit;
}
?>   