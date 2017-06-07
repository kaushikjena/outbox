<?php 
ob_start();
session_start();
include_once '../includes/class.Main.php';
//Object initialization
$dbf = new User();
//page titlevariable
$pageTitle="Welcome To Out Of The Box";
include 'applicationtop-tech.php';
if($_SESSION['usertype']!='tech'){
	header("location:../logout");exit;
}

$res_editTechProfile=$dbf->fetchSingle("technicians","id='$_SESSION[userid]'");

if($_REQUEST['action']=="update"){
	$TechFirstName=mysql_real_escape_string(trim($_POST['TechFirstName']));
	$TechMiddleName=mysql_real_escape_string(trim($_POST['TechMiddleName']));
	$TechLastName=mysql_real_escape_string(trim($_POST['TechLastName']));
	$TechAddress=mysql_real_escape_string(trim($_POST['TechAddress']));
	$TechCity=mysql_real_escape_string(trim($_POST['TechCity']));
	$TechState=mysql_real_escape_string(trim($_POST['TechState']));
	//$TechZipcode=addslashes($_post['TechZipcode']);
	$TechDateBirth=mysql_real_escape_string($_POST['TechDateBirth']);
	$TechCompanyName=mysql_real_escape_string($_POST['TechCompanyName']);
	$TechSSN=mysql_real_escape_string($_POST['TechSSN']);
	$TechFEIN=mysql_real_escape_string($_POST['TechFEIN']);
	$TechDrLicenseNo=mysql_real_escape_string($_POST['TechDrLicenseNo']);
	$TechPayGrade=mysql_real_escape_string($_POST['TechPayGrade']);
	//$TechPayble=addslashes($_POST['TechPayble']);
	//duplicate technician Email Id Check
	$numuser = $dbf->countRows("technicians","email='$_REQUEST[TechEmailID]' AND id<>'$_REQUEST[hid]'");
	if($numuser > 0){
		header("Location:tech-edit-profile?msg=002&id='$_REQUEST[hid]'");exit;
	}else{
		//check for vehicle image
		$file_name=$_FILES['VehiclePhoto']['name'];
		if($file_name <>''){
			$path="vehicle_image/";
			$img=$dbf->getDataFromTable("technicians","vehicle_image","id='$_REQUEST[hid]'");
			if($img<>''){
				unlink($path.$img);
			}
			move_uploaded_file($_FILES['VehiclePhoto']['tmp_name'],$path.$file_name);
		}else{
			$file_name="";
		}
		$file_name1=$_FILES['TechPicture']['name'];
		if($file_name1 <>''){
			$path1="tech_image/";
			$img1=$dbf->getDataFromTable("technicians","tech_image","id='$_REQUEST[hid]'");
			if($img1<>''){
				unlink($path1.$img1);
			}
			move_uploaded_file($_FILES['TechPicture']['tmp_name'],$path1.$file_name1);
			
		}else{
			$file_name1="";
		}
		//$ProfilePassword1 =rand();
		//$ProfilePassword=base64_encode(base64_encode($ProfilePassword1));
		$payble=implode(',',$_POST['TechPayble']);
		$dob=date('Y-m-d',strtotime($TechDateBirth));
		
		if($file_name<>'' && $file_name1==''){
		$string="first_name='$TechFirstName', middle_name='$TechMiddleName',last_name='$TechLastName', user_type='tech', email='$_POST[TechEmailID]',contact_phone='$_POST[TechContactNo]', alt_phone='$_POST[TechAltPhone]',address='$TechAddress', city='$TechCity', state='$TechState',zip_code='$_POST[TechZipcode]',date_of_birth='$dob',company_name='$TechCompanyName', SSN='$TechSSN',FEIN='$TechFEIN',vehicle_image='$file_name', driver_license_no='$TechDrLicenseNo', pay_grade='$TechPayGrade', payble_to='$payble', created_date=now()";
		}elseif($file_name=='' && $file_name1<>''){
		$string="first_name='$TechFirstName', middle_name='$TechMiddleName',last_name='$TechLastName', user_type='tech', email='$_POST[TechEmailID]',contact_phone='$_POST[TechContactNo]', alt_phone='$_POST[TechAltPhone]',address='$TechAddress', city='$TechCity', state='$TechState',zip_code='$_POST[TechZipcode]',date_of_birth='$dob',company_name='$TechCompanyName', SSN='$TechSSN',FEIN='$TechFEIN', driver_license_no='$TechDrLicenseNo', tech_image='$file_name1',pay_grade='$TechPayGrade', payble_to='$payble', created_date=now()";
		}elseif($file_name<>'' && $file_name1<>''){
		$string="first_name='$TechFirstName', middle_name='$TechMiddleName',last_name='$TechLastName', user_type='tech', email='$_POST[TechEmailID]',contact_phone='$_POST[TechContactNo]', alt_phone='$_POST[TechAltPhone]',address='$TechAddress', city='$TechCity', state='$TechState',zip_code='$_POST[TechZipcode]',date_of_birth='$dob',company_name='$TechCompanyName', SSN='$TechSSN',FEIN='$TechFEIN',vehicle_image='$file_name', driver_license_no='$TechDrLicenseNo', tech_image='$file_name1',pay_grade='$TechPayGrade', payble_to='$payble', created_date=now()";	
		}else{
		$string="first_name='$TechFirstName', middle_name='$TechMiddleName',last_name='$TechLastName', user_type='tech', email='$_POST[TechEmailID]',contact_phone='$_POST[TechContactNo]', alt_phone='$_POST[TechAltPhone]',address='$TechAddress', city='$TechCity', state='$TechState', zip_code='$_POST[TechZipcode]', date_of_birth='$dob',company_name='$TechCompanyName', SSN='$TechSSN',FEIN='$TechFEIN',driver_license_no='$TechDrLicenseNo', pay_grade='$TechPayGrade', payble_to='$payble', created_date=now()";	
		}
		//update into technicians table
		$dbf->updateTable("technicians",$string,"id='$_REQUEST[hid]'");
		header("Location:tech-edit-profile?msg=001");exit;
	}
}
?>
<link rel="stylesheet" href="../css/innermain.css" type="text/css" />
<link rel="stylesheet" href="../css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="../css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="../css/respmenu.css" type="text/css" />
<link rel="stylesheet" href="../css/table.css" type="text/css" />
<body>
<script type="text/javascript">
$(document).ready(function(){
	$('input[type="text"], textarea').attr('readonly', 'readonly');
	$('#frmTech select').attr('disabled', 'disabled');
	$('input[type="checkbox"]').attr('disabled', 'disabled');
	$('#frmTech input[type="file"]').attr('disabled', 'disabled');
});
</script>
    <div id="maindiv">
        <!-------------header--------------->
     	<?php include_once 'header-tech.php';?>
   		<!-------------header--------------->
        
        <!-------------top menu--------------->
     	<?php include_once 'tech-top-menu.php';?>
   		<!-------------top menu--------------->
         <div id="contentdiv">
                <!-------------Left menu--------------->
				<?php include_once 'tech-left-menu.php';?>
                <!-------------left menu--------------->
                <!-------------Main Body--------------->
                <div class="rightcolum">
            		<div class="rightcoluminner">
                        <div class="headerbg">TECHNICIAN VIEW PROFILE</div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        	<!-----Table area start------->
                            <form action="" name="frmTech" id="frmTech" method="post" onSubmit="return validate_techEdit();" enctype="multipart/form-data">
                             <input type="hidden" name="action" value="update">
                             <input type="hidden" name="hid" value="<?php echo $_SESSION['userid'];?>">
                             <div align="center"><?php if($_REQUEST['msg']=="002"){?><span class="redText">This Email ID already exist!</span><?php } ?></div>
                             <div align="center"><?php if($_REQUEST['msg']=="001"){?><span class="greenText">Profile has been updated successfully.</span><?php } ?></div>
                            <!---First Div Start--->
                        	<div  class="innerDivTech">
                                <div  class="formtextadd">First Name:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="TechFirstName" id="TechFirstName" value="<?php echo $res_editTechProfile['first_name']?>"><br/><label for="TechFirstName" id="lblTechFirstName" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Middle Name:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                   <input type="text" class="textbox" name="TechMiddleName" id="TechMiddleName" value="<?php echo $res_editTechProfile['middle_name'];?>"><br/><label for="TechMiddleName" id="lblTechMiddleName" class="redText"></label>
                                </div>
                                 <div class="spacer"></div>
                                <div  class="formtextadd">Last Name:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="TechLastName" id="TechLastName" value="<?php echo $res_editTechProfile['last_name'];?>"><br/><label for="TechLastName" id="lblTechLastName" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Email ID:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                   <input type="text" class="textbox" name="TechEmailID" id="TechEmailID" value="<?php echo $res_editTechProfile['email'];?>"><br/><label for="TechEmailID" id="lblTechEmailID" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">ContactPhone:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                   <input type="text" class="textbox" name="TechContactNo" id="TechContactNo" onKeyUp="return validatephone(this);" maxlength="12" value="<?php echo $res_editTechProfile['contact_phone'];?>"><br/><label for="TechContactNo" id="lblTechContactNo" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Alt Phone:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                   <input type="text" class="textbox" name="TechAltPhone" id="TechAltPhone" onKeyUp="return validatephone(this);" maxlength="12" value="<?php echo $res_editTechProfile['alt_phone'];?>"><br/><label for="TechAltPhone" id="lblTechAltPhone" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Address:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <textarea class="textarea" name="TechAddress" id="TechAddress"><?php echo $res_editTechProfile['address'];?></textarea><br/><label for="TechAddress" id="lblTechAddress" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">City:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="TechCity" id="TechCity" value="<?php echo $res_editTechProfile['city'];?>"><br/><label for="TechCity" id="lblTechCity" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">State:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <select class="selectbox" name="TechState" id="TechState">
                                    <option>--select state--</option>
                                    <?php foreach($dbf->fetch("state","")as $techState){?>
                                    <option value="<?php echo $techState['state_code']?>" <?php if($res_editTechProfile['state']==$techState['state_code']){echo 'selected';}?>><?php echo $techState['state_name'];?></option>
                                    <?php }?>
                                    </select>
                                    <br/><label for="TechState" id="lblTechState" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Zip Code:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="TechZipcode" id="TechZipcode" value="<?php echo $res_editTechProfile['zip_code'];?>"><br/><label for="TechZipcode" id="lblTechZipcode" class="redText"></label>
                                </div>
                        	</div><!---First Div End--->
                            <!---Second Div Start--->
                            <div class="innerDivTech">
                                <div  class="formtextadd">Date Of Birth:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox datepick1" name="TechDateBirth" id="TechDateBirth" value="<?php echo date('d-m-Y',strtotime($res_editTechProfile['date_of_birth']));?>"><br/><label for="TechDateBirth" id="lblTechDateBirth" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Company Name:</div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="TechCompanyName" id="TechCompanyName" value="<?php echo $res_editTechProfile['company_name'];?>"><br/><label for="TechCompanyName" id="lblTechCompanyName" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">SSN#:</div>
                                <div  class="textboxc">
                                   <input type="text" class="textbox" name="TechSSN" id="TechSSN" value="<?php echo $res_editTechProfile['SSN'];?>"><br/><label for="TechSSN" id="lblTechSSN" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">FEIN#:</div>
                                <div  class="textboxc">
                                   <input type="text" class="textbox" name="TechFEIN" id="TechFEIN" value="<?php echo $res_editTechProfile['FEIN'];?>"><br/><label for="TechFEIN" id="lblTechFEIN" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Type Vehicle:</div>
                                <div  class="textboxc">
                                   <input type="file" class="textbox" name="VehiclePhoto" id="VehiclePhoto"><br/><label for="VehiclePhoto" id="lblVehiclePhoto" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Driving License No:</div>
                                <div  class="textboxc">
                                   <input type="text" class="textbox" name="TechDrLicenseNo" id="TechDrLicenseNo" value="<?php echo $res_editTechProfile['driver_license_no'];?>"><br/><label for="TechDrLicenseNo" id="lblTechDrLicenseNo" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Tech Picture:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                	<input type="file" class="textbox" name="TechPicture" id="TechPicture"><br/><label for="TechPicture" id="lblTechPicture" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <?php /*?><div  class="formtextadd">Pay Grade:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                <select class="textbox" name="TechPayGrade" id="TechPayGrade" style="height:28px;width:100%;">
                                    <option value="">--Select Pay Grade--</option>
                                    <option value="A" <?php if($res_editTechProfile['pay_grade']=='A'){echo'selected';}?>> A </option>
                                    <option value="B" <?php if($res_editTechProfile['pay_grade']=='B'){echo'selected';}?>> B </option>
                                    <option value="C" <?php if($res_editTechProfile['pay_grade']=='C'){echo'selected';}?>> C </option>
                                    <option value="D" <?php if($res_editTechProfile['pay_grade']=='D'){echo'selected';}?>> D </option>
                                </select><br/><label for="TechPayGrade" id="lblTechPayGrade" class="redText"></label>
                                </div><?php */?>
                                <div class="spacer"></div>
                                <?php $respayble= $res_editTechProfile['payble_to']?explode(",",$res_editTechProfile['payble_to']):array();?>
                                <div  class="formtextadd">Payble To:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                	<input type="checkbox" name="TechPayble[]" id="TechPayble1" value="bank" <?php if(in_array("bank",$respayble,true)){echo 'checked';} ?>/>&nbsp;Bank&nbsp;&nbsp;&nbsp;<input type="checkbox" name="TechPayble[]" id="TechPayble2" value="self" <?php if(in_array("self",$respayble,true)){echo 'checked';} ?>/>&nbsp;Self&nbsp;<br/><label for="TechPayble" id="lblTechPayble" class="redText"></label>
                                </div>
                        	</div><!---Second Div End--->
                            <!---image Div Start--->
                            <div class="TechImgDiv">
                            <?php if($res_editTechProfile['vehicle_image']!='') {?>
                                <div class="TechImgDivCon"><img src="../vehicle_image/<?php echo $res_editTechProfile['vehicle_image'];?>"><span class="formtxt">Vehicle Image</span></div>
                              <?php } if($res_editTechProfile['tech_image']!=''){?>
                                <div class="TechImgDivCon"><img src="../tech_image/<?php echo $res_editTechProfile['tech_image'];?>"><span class="formtxt">Tech Image</span></div>
                                <?php }?>
                             </div>
                             <!---image Div End--->
                            <div class="spacer"></div>
                            <!---Third Div Start--->
                            <div class="DivSkill">
                            <?php
								$key = 1;
								$techid = $_SESSION['userid'];
								$serviceArray = $dbf->fetch("service","id>0 ORDER BY id ASC");
								$serviceArray = !empty($serviceArray)? $serviceArray :array();
							?> 
                            	<div><b>Please review the list of services below and the work type the tech can provide for each services</b></div>
                                <div class="spacer"></div>
                                <div align="left" class="techSkillheader techService1">Services</div>
                                <div align="left" class="techSkillheader techService">Deliver</div>
                                <div align="left" class="techSkillheader techService">Installation/Assembly</div>
                                <div align="left" class="techSkillheader techService">Repair</div>
                                <input type="hidden" name="countservice" value="<?php echo count($serviceArray);?>"/>
                                <div class="spacer"></div>
                                <?php foreach($serviceArray as $service){
									$resTechSkill = $dbf->fetchSingle("technicians_skill","tech_id='$techid' AND service_id='$service[id]'");
									//print_r($resTechSkill);
								?>
                                <div align="left" class="teskillview techService1"><?php echo $service['service_name'];?></div>
                                <div align="left" class="teskillview techService"><input type="checkbox" name="chkService<?php echo $key;?>[]" id="chkService<?php echo $service['id'];?>" value="D"  disabled <?php if($resTechSkill['deliver']==1){echo "checked";}?>/></div>
                                <div align="left" class="teskillview techService"><input type="checkbox" name="chkService<?php echo $key;?>[]" id="chkService<?php echo $service['id'];?>" value="I" disabled <?php if($resTechSkill['installation']==1){echo "checked";}?> /></div>
                                <div align="left" class="teskillview techService"><input type="checkbox" name="chkService<?php echo $key;?>[]" id="chkService<?php echo $service['id'];?>" value="R" disabled <?php if($resTechSkill['repair']==1){echo "checked";}?>/></div>
                                <input type="hidden"  name="hserviceid<?php echo $key;?>" value="<?php echo $service['id'];?>"/>
                                <div class="spacer"></div>
                                <?php $key++;}?>
                            </div>
                            <!---Third Div End--->
                            <div class="spacer"></div>
                            <div align="center"><!--<input type="submit" class="buttonText" value="Submit Form"/>--><input type="button" class="buttonText" value="Return Back" onClick="window.location.href='tech-dashboard'"/></div>
                            </form>
                            <!-----Table area start-------> 
                        	<div class="spacer"></div>
                    	</div>
            	</div>
              <!-------------Main Body--------------->
             </div>
         </div>
        <div class="spacer"></div>
        <?php include_once 'footer-tech.php'; ?>
    </div>
</body>
</html>