<?php
ob_start();
session_start();
include 'includes/class.Main.php';
$dbf = new User();

?>
<select class="selectbox" name="EquipmentName" id="EquipmentName" onChange="showServices();">
    <option value="">--Select Service Name--</option>
    <?php foreach($dbf->fetch("equipment","service_id='$_REQUEST[serviceid]' AND id>0 ORDER BY equipment_name ASC") as $valeq){?>
    <option value="<?php echo $valeq['id'];?>"><?php echo $valeq['equipment_name'];?></option>
    <?php }?>
</select><br/><label for="EquipmentName" id="lblEquipmentName" class="redText"></label>