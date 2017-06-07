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
$res_equip=$dbf->fetchSingle("equipment","id='$_REQUEST[id]'");
if($_REQUEST['action']=="update"){
	$equipmentName=mysql_real_escape_string($_REQUEST['equipmentName']);
	$serviceName=mysql_real_escape_string($_REQUEST['serviceName']);
	$num=$dbf->countRows("equipment","service_id='$serviceName' AND equipment_name='$equipmentName' AND id<>'$_REQUEST[hidid]'");
	if($num>0){
		header("Location:edit-equipment?msg=002&id=$_REQUEST[hidid]");exit;
	}else{
		$string="equipment_name='$equipmentName',service_id='$serviceName',created_date=now()";
		//insert into equipment table
		$dbf->updateTable("equipment",$string,"id='$_REQUEST[hidid]'");
		header("Location:manage-equipment");exit;
	}
}
?>
<script type="text/javascript">
$(document).ready(function(){
	$("form :input").each(function(){
	 //if($(this).attr("id") !='SiteUrl'){
		  $(this).keyup(function(event){
			var xss =  $(this);
			var maintainplus = '';
			var numval = xss.val();
			curphonevar = numval.replace(/[\\!"£$%^&*+_={};:'#~()¦\/<>?|`¬\]\[]/g,'');
			xss.val(maintainplus + curphonevar) ;
			var maintainplus = '';
			xss.focus;
		  });
	// }
	});
});
</script>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />
<body>
    <div id="maindiv">
        <!-------------header--------------->
     	<?php include_once 'header.php';?>
   		<!-------------header--------------->
        
        <!-------------top menu--------------->
     	<?php include_once 'top-menu.php';?>
   		<!-------------top menu--------------->
         <div id="contentdiv">
                <!-------------Left menu--------------->
				<?php include_once 'left-menu.php';?>
                <!-------------left menu--------------->
                <!-------------Main Body--------------->
                <div class="rightcolum">
            		<div class="rightcoluminner">
                        <div class="headerbg">EDIT  EQUIPMENT</div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        	<!-----Table area start------->
                            <div  class="innertable">
                             <div align="center"><?php if($_REQUEST['msg']=="002"){?><span class="redText">This Equipment Name already exist!</span><?php }?></div>
                            <div class="spacer"></div>
                         	  <form action="" name="frmEquipment" id="frmEquipment" method="post" onSubmit="return validate_equipment();" enctype="multipart/form-data" autocomplete="off">
                              <input type="hidden" name="action" value="update">
                              <input type="hidden" name="hidid" id="hidid" value="<?php echo $res_equip['id'];?>">
                              <div  class="formtextadd">Service Name<span class="redText">*</span></div>
                                <div  class="textboxc">
                                  <select class="selectbox" name="serviceName" id="serviceName">
                                  	<option value="">--Select Service Name--</option>
                                    <?php foreach($dbf->fetch("service","") as $vservice){?>
                                    <option value="<?php echo $vservice['id'];?>" <?php if($vservice['id']==$res_equip['service_id']){echo 'selected';}?>><?php echo $vservice['service_name'];?></option>
                                    <?php }?>
                                  </select>
                                  <br/><label for="ServiceName" id="lblServiceName" class="redText"></label>
                               </div>
                               <div class="spacer"></div>
                             	<div  class="formtextadd">Equipment Name<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="equipmentName" id="equipmentName" value="<?php echo $res_equip['equipment_name'];?>"><br/><label for="equipmentName" id="lblequipmentName" class="redText"></label>
                               </div>
                              <div class="spacer"></div>
                                 <div align="center">
                                 	<input type="submit" class="buttonText" value="Submit Form"/>
                                    <a href="manage-equipment" style="text-decoration:none;"><input type="button" class="buttonText3" value="Back"/></a>
                                 </div>
                                </form>
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