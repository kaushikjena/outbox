<?php
ob_start();
session_start();
include '../includes/class.Main.php';
$dbf = new User();
?>
      <div>
          <div align="center" class="jobheader clService">Work Type</div>
          <div align="center" class="jobheader clEquipment">Equipment</div>
          <div align="center" class="jobheader clModel">Model</div>
          <div align="center" class="jobheader clQunt">Quantity</div>
          <div align="center" class="jobheader clPrice1">Price/Rate</div>
          <div align="center" class="jobheader clPrice1">Total</div>
          <div style="clear:both;"></div>
      </div>
      <?php 
		 $res_woservice = $dbf->fetch("workorder_service","workorder_id='$_REQUEST[id]' AND service_id='$_REQUEST[serviceid]'");
		 $arrWorkservice =array();$i=1;
		 $cntwotype =$dbf->countRows("work_type","");
		  for($j=0; $j<$cntwotype;$j++){
			  $arrWorkservice= $res_woservice[$j];
			  $total = ($arrWorkservice['quantity']*$arrWorkservice['outbox_price']);
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
      <div align="center" class="jobbody clPrice1"><input type="text" class="textboxjob" name="Price<?php echo $i;?>" id="Price<?php echo $i;?>" value="<?php echo $arrWorkservice['outbox_price'];?>" onKeyUp="return extractNumber(this,2);" tabindex="23" onBlur="ShowTotalPrice('<?php echo $i;?>');"></div>
      <div align="center" class="jobbody clPrice1"><input type="text" class="textboxjob" name="Total<?php echo $i;?>" id="Total<?php echo $i;?>" value="<?php if($total)echo number_format($total,2);?>" onKeyUp="return extractNumber(this,2);" tabindex="23" ></div>
      <div style="clear:both; height:5px;"></div>
    <?php $i++;}?>
                               
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
                           