<?php
ob_start();
session_start();
include 'includes/class.Main.php';
$dbf = new User();
//print_r($_REQUEST);exit;
if($_REQUEST['clientid']){
	$existClientid =$dbf->getDataFromTable("work_order","created_by","id='$_REQUEST[id]'");
	if($existClientid <> $_REQUEST['clientid']){
		$condition = "workorder_id='' AND service_id='$_REQUEST[serviceid]'";
	}else{
		$condition = "workorder_id='$_REQUEST[id]' AND service_id='$_REQUEST[serviceid]'";
	}
}else{
	$condition = "workorder_id='$_REQUEST[id]' AND service_id='$_REQUEST[serviceid]'";
}
?>
      <div>
          <div align="center" class="jobheader clService">Work Type</div>
          <div align="center" class="jobheader clEquipment">Equipment</div>
          <div align="center" class="jobheader clModel">Model</div>
          <div align="center" class="jobheader clQunt">Quantity</div>
          <div align="center" class="jobheader clPrice">Price/Rate</div>
          <div align="center" class="jobheader clPrice">Total</div>
          <div align="center" class="jobheader clPrice">Tech Price</div>
          <div style="clear:both;"></div>
      </div>
      <?php 
	  	$subtotal =0;  $subTechPrice =0;
		 $res_woservice = $dbf->fetch("workorder_service",$condition);
		 $arrWorkservice =array();$i=1;
		 $cntwotype =$dbf->countRows("work_type","status=1");
		 $cntwotype=floor($cntwotype/2);
		  for($j=0; $j<$cntwotype;$j++){
			$arrWorkservice= $res_woservice[$j];
			$total = ($arrWorkservice['quantity']*$arrWorkservice['outbox_price']);
			$TechPrice=$arrWorkservice['tech_price'];
			$subtotal = $subtotal+$total;
			$subTechPrice = $subTechPrice+$TechPrice;
		?>
      <div align="center" class="jobbody clService">
      <input type="hidden" name="hid<?php echo $i;?>" value="<?php echo $arrWorkservice['id'];?>">
      <select class="selectboxjob" name="Worktype<?php echo $i;?>" id="Worktype<?php echo $i;?>" tabindex="23" onchange="ShowPrice('<?php echo $i;?>');">
        <option value="">--Select Worktype--</option>
        <?php foreach($dbf->fetch("work_type","id>0 ORDER BY worktype ASC") as $vawt){?>
        <option value="<?php echo $vawt['id'];?>" <?php if($arrWorkservice['work_type']==$vawt['id']){echo 'selected';}?>><?php echo $vawt['worktype'];?></option>
        <?php }?>
      </select><br/><label for="worktype" id="lblWorktype<?php echo $i;?>"  class="redText"></label>
      </div>
      <div align="center" class="jobbody clEquipment">
      <select class="selectboxjob" name="Equipment<?php echo $i;?>" id="Equipment<?php echo $i;?>" tabindex="23" onchange="ShowPrice('<?php echo $i;?>');">
        <option value="">--Select Equipment--</option>
        <?php foreach($dbf->fetch("equipment","service_id='$_REQUEST[serviceid]' ORDER BY equipment_name ASC") as $valeq){?>
        <option value="<?php echo $valeq['id'];?>" <?php if($arrWorkservice['equipment']==$valeq['id']){echo 'selected';}?>><?php echo $valeq['equipment_name'];?></option>
        <?php }?>
      </select><br/><label for="Equipment1" id="lblEquipment<?php echo $i;?>" class="redText"></label>
      </div>
      <div align="center" class="jobbody clModel"><input type="text" class="textboxjob" name="Model<?php echo $i;?>" id="Model<?php echo $i;?>" value="<?php echo $arrWorkservice['model'];?>" tabindex="23"><br/><label for="Model" id="lblModel<?php echo $i;?>" class="redText"></label></div>
      <div align="center" class="jobbody clQunt"><input type="text" class="textboxjob" name="Quantity<?php echo $i;?>" id="Quantity<?php echo $i;?>" value="<?php echo $arrWorkservice['quantity'];?>" onKeyPress="return onlyNumbers(event);" maxlength="2" tabindex="23" onblur="ShowTotalPrice('<?php echo $i;?>');"><br/><label for="Quantity" id="lblQuantity<?php echo $i;?>" class="redText"></label></div>
      <div align="center" class="jobbody clPrice"><input type="text" class="textboxjob" name="Price<?php echo $i;?>" id="Price<?php echo $i;?>" value="<?php echo $arrWorkservice['outbox_price'];?>" onKeyUp="return extractNumber(this,2);" tabindex="23" onBlur="ShowTotalPrice('<?php echo $i;?>');" maxlength="10"></div>
      <div align="center" class="jobbody clPrice"><input type="text" class="textboxjob" name="Total<?php echo $i;?>" id="Total<?php echo $i;?>" value="<?php if($total)echo number_format($total,2);?>" onKeyUp="return extractNumber(this,2);" tabindex="23" readonly></div>
      <div align="center" class="jobbody clPrice"><input type="text" class="textboxjob" name="TechPrice<?php echo $i;?>" id="TechPrice<?php echo $i;?>" value="<?php echo $TechPrice;?>" onKeyUp="return extractNumber(this,2);" onBlur="ShowSubTotalPrice();" tabindex="23" maxlength="10"></div>
      <div style="clear:both; height:5px;"></div>
    <?php $i++;}?>
    <div>
    <input type="hidden" id="hidCount" value="<?php echo $i;?>"/>
    <div class="orderSubtotal">Sub Total:</div><div class="orderSubPrice" id="SubTotal">$ <?php echo number_format($subtotal,2);?></div><div class="orderSubPrice" id="SubTechPrice">$ <?php echo number_format($subTechPrice,2);?></div>
     <div style="clear:both;"></div>
  </div>
                               
<script type="text/javascript">
$(document).ready(function() {
    $('input:text,textarea,select,checkbox').focus(
    function(){
        $(this).css({'background-color' : '#EDE9E4'});
    });

    $('input:text,textarea,select,checkbox').blur(
    function(){
        $(this).css({'background-color' : '#FFFFFF'});
    });
});
</script>
                           