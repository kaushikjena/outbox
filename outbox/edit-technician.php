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
$res_editTech=$dbf->fetchSingle("technicians","id='$_REQUEST[id]'");
if($_REQUEST['src'] == 'active'){
	$redirect = "manage-technician-active";
}else{
	$redirect = "manage-technician";
}
if(isset($_REQUEST['action']) && $_REQUEST['action']=="update" && $_SERVER['REQUEST_METHOD']=='POST'){
	/*print "<pre>";
	print_r($_REQUEST);exit;*/
	$TechFirstName=mysql_real_escape_string(trim($_POST['TechFirstName']));
	$TechMiddleName=mysql_real_escape_string(trim($_POST['TechMiddleName']));
	$TechLastName=mysql_real_escape_string(trim($_POST['TechLastName']));
	$TechAddress=mysql_real_escape_string(trim($_POST['TechAddress']));
	$TechCity=mysql_real_escape_string(trim($_POST['TechCity']));
	$TechState=mysql_real_escape_string(trim($_POST['TechState']));
	//$TechZipcode=addslashes($_post['TechZipcode']);
	$TechDateBirth=mysql_real_escape_string(trim($_POST['TechDateBirth']));
	$TechCompanyName=mysql_real_escape_string(trim($_POST['TechCompanyName']));
	$TechSSN=mysql_real_escape_string(trim($_POST['TechSSN']));
	$TechFEIN=mysql_real_escape_string(trim($_POST['TechFEIN']));
	$TechDrLicenseNo=mysql_real_escape_string(trim($_POST['TechDrLicenseNo']));
	$TechPayGrade=mysql_real_escape_string(trim($_POST['TechPayGrade']));
	$TechEmailID =mysql_real_escape_string(trim($_REQUEST['TechEmailID']));
	if($TechEmailID ==''){
		header("Location:add-technician?id=$_REQUEST[hid]");exit;
	}elseif(filter_var($TechEmailID,FILTER_VALIDATE_EMAIL)== FALSE){
		header("Location:add-technician?id=$_REQUEST[hid]");exit;
	}
	//$TechPayble=addslashes($_POST['TechPayble']);
	//duplicate technician Email Id Check
	$numuser = $dbf->countRows("technicians","email='$TechEmailID' AND id<>'$_REQUEST[hid]'");
	if($numuser > 0){
		header("Location:add-technician?msg=002&id=$_REQUEST[hid]");exit;
	}else{
		//check for vehicle image
		if($_FILES['VehiclePhoto']['name']<>''){
			$file_name=strtotime("now").'_'.$_FILES['VehiclePhoto']['name'];
			$path="vehicle_image/";
			$img=$dbf->getDataFromTable("technicians","vehicle_image","id='$_REQUEST[hid]'");
			if($img<>''){
				unlink($path.$img);
			}
			move_uploaded_file($_FILES['VehiclePhoto']['tmp_name'],$path.$file_name);
		}else{
			$file_name="";
		}
		//check for technician image
		if($_FILES['TechPicture']['name'] <>''){
			$file_name1=strtotime("now").'_'.$_FILES['TechPicture']['name'];
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
		$dob= $TechDateBirth?date('Y-m-d',strtotime($TechDateBirth)):'';
		$SmsAlert=$_POST['SmsAlert'];
		//get latitude and longitude
		$val = $dbf->getLnt($_POST['TechAddress'].",".$_POST['TechCity'].",".$_POST['TechState'].",".$_POST['TechZipcode']);
		
		$string="first_name='".$TechFirstName."', middle_name='".$TechMiddleName."', last_name='".$TechLastName."', user_type='tech', email='".$TechEmailID."', contact_phone='".$_POST['TechContactNo']."', alt_phone='".$_POST['TechAltPhone']."', address='".$TechAddress."', city='".$TechCity."', state='".$TechState."',zip_code='".$_POST['TechZipcode']."',latitude='".$val['lat']."',longitude='".$val['lng']."', date_of_birth='".$dob."',company_name='".$TechCompanyName."', SSN='".$TechSSN."', FEIN='".$TechFEIN."', driver_license_no='".$TechDrLicenseNo."', pay_grade='".$TechPayGrade."', payble_to='".$payble."', sms_alert='".$SmsAlert."', created_date=now()";
		
		if($file_name<>''){
			$string.=", vehicle_image='".$file_name."'";
		}
		if($file_name1<>''){
			$string.=", tech_image='".$file_name1."'";
		}
		//update into technicians table
		$dbf->updateTable("technicians",$string,"id='".$_REQUEST['hid']."'");
		##########Insert Into Technician Skill Table###########
		if($_REQUEST['hid']){
			$countservice =$_REQUEST['countservice'];
			for($i=1;$i<=$countservice;$i++){
				$chkService='chkService'.$i;
				$chkService=$_REQUEST[$chkService];
				$hserviceid='hserviceid'.$i;
				$hserviceid=$_REQUEST[$hserviceid];
				if(!empty($chkService)){
					$deliver = in_array("D",$chkService)? 1 :0;
					$installation = in_array("I",$chkService)? 1 :0;
					$repair = in_array("R",$chkService)? 1 :0;
					//check if exist in table
					$numskill =$dbf->countRows("technicians_skill","tech_id='$_REQUEST[hid]' AND service_id='$hserviceid'");
					if($numskill >0){
						//update string
						$stringservice="deliver='$deliver',installation='$installation', repair='$repair', updated_date=now()";
						$dbf->updateTable("technicians_skill",$stringservice,"tech_id='$_REQUEST[hid]' AND service_id='$hserviceid'");
					}else{
						//insert string
						$stringservice="tech_id='$_REQUEST[hid]', service_id='$hserviceid', deliver='$deliver',installation='$installation', repair='$repair', created_date=now()";
						$dbf->insertSet("technicians_skill",$stringservice);
					}
				}
			}
		}
		##########Insert Into Technician Skill Table###########
		header("Location:$redirect");exit;
	}
}
?>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />
<link rel="stylesheet" href="css/table.css" type="text/css" />
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

$(document).ready(function(){
	$("form :input").each(function(){
	 if($(this).attr("id") !='SiteUrl'){
		  $(this).keyup(function(event){
			var xss =  $(this);
			var maintainplus = '';
			var numval = xss.val();
			curphonevar = numval.replace(/[\\!"£$%^&*+={};:'#~()¦\/<>?|`¬\]\[]/g,'');
			xss.val(maintainplus + curphonevar) ;
			var maintainplus = '';
			xss.focus;
		  });
	 }
	});
});
</script>
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
				<?php //include_once 'left-menu.php';?>
                <!-------------left menu--------------->
                <!-------------Main Body--------------->
                <div class="rightcolumjobboard">
            		<div class="rightcoluminner">
                     <form action="" name="frmTech" id="frmTech" method="post" onSubmit="return validate_techEdit();" enctype="multipart/form-data" autocomplete="off">
                        <div class="headerbg">EDIT TECHNICIAN
                        	<div  style="float:right;">
                              <input type="submit" class="buttonText2" value="Submit Form" tabindex="21"/>
                              <input type="button" class="buttonText2" value="Back" tabindex="22" onClick="window.location.href='<?php echo $redirect;?>'"/>
                             </div>
                        </div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        	<!-----Table area start------->
                            <input type="hidden" name="src" value="<?php echo $_REQUEST['src'];?>">
                             <input type="hidden" name="action" value="update">
                             <input type="hidden" name="hid" value="<?php echo $_REQUEST['id'];?>">
                             <div align="center"><?php if($_REQUEST['msg']=="002"){?><span class="redText">This Email ID already exist!</span><?php } ?></div>
                            <!---First Div Start--->
                        	<div  class="innerDivTech">
                                <div  class="formtextadd">First Name:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="TechFirstName" id="TechFirstName" value="<?php echo $res_editTech['first_name']?>" tabindex="1" onKeyPress="return onlyLetters(event)"><br/><label for="TechFirstName" id="lblTechFirstName" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Middle Name:</div>
                                <div  class="textboxc">
                                   <input type="text" class="textbox" name="TechMiddleName" id="TechMiddleName" value="<?php echo $res_editTech['middle_name'];?>" tabindex="2" onKeyPress="return onlyLetters(event)"><br/><label for="TechMiddleName" id="lblTechMiddleName" class="redText"></label>
                                </div>
                                 <div class="spacer"></div>
                                <div  class="formtextadd">Last Name:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="TechLastName" id="TechLastName" value="<?php echo $res_editTech['last_name'];?>" tabindex="3" onKeyPress="return onlyLetters(event)"><br/><label for="TechLastName" id="lblTechLastName" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Email ID:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                   <input type="text" class="textbox" name="TechEmailID" id="TechEmailID" value="<?php echo $res_editTech['email'];?>" tabindex="4"><br/><label for="TechEmailID" id="lblTechEmailID" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">ContactPhone:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                   <input type="text" class="textbox" name="TechContactNo" id="TechContactNo" onKeyUp="validatephone(this);" maxlength="12" value="<?php echo $res_editTech['contact_phone'];?>" tabindex="5"><br/><label for="TechContactNo" id="lblTechContactNo" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Alt Phone:</div>
                                <div  class="textboxc">
                                   <input type="text" class="textbox" name="TechAltPhone" id="TechAltPhone" onKeyUp="validatephone(this);" maxlength="12" value="<?php echo $res_editTech['alt_phone'];?>" tabindex="6"><br/><label for="TechAltPhone" id="lblTechAltPhone" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Address:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <textarea class="textarea" name="TechAddress" id="TechAddress" tabindex="7"><?php echo $res_editTech['address'];?></textarea><br/><label for="TechAddress" id="lblTechAddress" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">City:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="TechCity" id="TechCity" value="<?php echo $res_editTech['city'];?>" tabindex="8" onKeyPress="return onlyLetters(event)"><br/><label for="TechCity" id="lblTechCity" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">State:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                  <select class="selectbox" name="TechState" id="TechState" tabindex="9">
                                  	<option value="">--Select State--</option>
                                    <?php foreach($dbf->fetch("state","id>0 ORDER BY state_code ASC") as $vstate){?>
                                    <option value="<?php echo $vstate['state_code'];?>" <?php if($res_editTech['state']==$vstate['state_code']){echo 'selected';}?>><?php echo $vstate['state_name'];?></option>
                                    <?php }?>
                                 </select>
                                 <br/><label for="TechState" id="lblTechState" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Zip Code:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="TechZipcode" id="TechZipcode" value="<?php echo $res_editTech['zip_code'];?>" tabindex="10" maxlength="10"><br/><label for="TechZipcode" id="lblTechZipcode" class="redText"></label>
                                </div>
                        	</div><!---First Div End--->
                            <!---Second Div Start--->
                            <div class="innerDivTech">
                                <div  class="formtextadd">Date Of Birth:</div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox datepick" name="TechDateBirth" id="TechDateBirth" value="<?php echo ($res_editTech['date_of_birth']<>'0000-00-00')?date('d-m-Y',strtotime($res_editTech['date_of_birth'])):'';?>" tabindex="11" readonly><br/><label for="TechDateBirth" id="lblTechDateBirth" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Company Name:</div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="TechCompanyName" id="TechCompanyName" value="<?php echo $res_editTech['company_name'];?>" tabindex="12"><br/><label for="TechCompanyName" id="lblTechCompanyName" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">SSN#:</div>
                                <div  class="textboxc">
                                   <input type="text" class="textbox" name="TechSSN" id="TechSSN" value="<?php echo $res_editTech['SSN'];?>" tabindex="13"><br/><label for="TechSSN" id="lblTechSSN" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">FEIN#:</div>
                                <div  class="textboxc">
                                   <input type="text" class="textbox" name="TechFEIN" id="TechFEIN" value="<?php echo $res_editTech['FEIN'];?>" tabindex="14"><br/><label for="TechFEIN" id="lblTechFEIN" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Type Vehicle:</div>
                                <div  class="textboxc">
                                   <input type="file" class="textbox" name="VehiclePhoto" id="VehiclePhoto" tabindex="15"><br/><label for="VehiclePhoto" id="lblVehiclePhoto" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Driver License No:</div>
                                <div  class="textboxc">
                                   <input type="text" class="textbox" name="TechDrLicenseNo" id="TechDrLicenseNo" value="<?php echo $res_editTech['driver_license_no'];?>" tabindex="16"><br/><label for="TechDrLicenseNo" id="lblTechDrLicenseNo" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Tech Picture:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                	<input type="file" class="textbox" name="TechPicture" id="TechPicture" tabindex="17"><br/><label for="TechPicture" id="lblTechPicture" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Pay Grade:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                <select class="textbox" name="TechPayGrade" id="TechPayGrade" style="height:28px;width:100%;" tabindex="18">
                                    <option value="">--Select Pay Grade--</option>
                                    <option value="A" <?php if($res_editTech['pay_grade']=='A'){echo'selected';}?>> A </option>
                                    <option value="B" <?php if($res_editTech['pay_grade']=='B'){echo'selected';}?>> B </option>
                                    <option value="C" <?php if($res_editTech['pay_grade']=='C'){echo'selected';}?>> C </option>
                                    <option value="D" <?php if($res_editTech['pay_grade']=='D'){echo'selected';}?>> D </option>
                                    <option value="E" <?php if($res_editTech['pay_grade']=='E'){echo'selected';}?>> E </option>
                                    <option value="F" <?php if($res_editTech['pay_grade']=='F'){echo'selected';}?>> F </option>
                                    <option value="G" <?php if($res_editTech['pay_grade']=='G'){echo'selected';}?>> G </option>
                                    <option value="H" <?php if($res_editTech['pay_grade']=='H'){echo'selected';}?>> H </option>
                                    <option value="I" <?php if($res_editTech['pay_grade']=='I'){echo'selected';}?>> I </option>
                                    <option value="J" <?php if($res_editTech['pay_grade']=='J'){echo'selected';}?>> J </option>
                                </select><br/><label for="TechPayGrade" id="lblTechPayGrade" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <?php $respayble= $res_editTech['payble_to']?explode(",",$res_editTech['payble_to']):array();?>
                                <div  class="formtextadd">Payble To:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                	<input type="checkbox" name="TechPayble[]" id="TechPayble1" value="bank" <?php if(in_array("bank",$respayble,true)){echo 'checked';} ?> tabindex="19"/>&nbsp;Bank&nbsp;&nbsp;&nbsp;<input type="checkbox" name="TechPayble[]" id="TechPayble2" value="self" <?php if(in_array("self",$respayble,true)){echo 'checked';} ?> tabindex="20"/>&nbsp;Self&nbsp;<br/><label for="TechPayble" id="lblTechPayble" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">SMS Alert:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                	<input type="radio" name="SmsAlert" id="SmsAlert1" value="1" <?php if($res_editTech['sms_alert']=='1'){echo 'checked';} ?> tabindex="19"/>&nbsp;ON&nbsp;&nbsp;&nbsp;<input type="radio" name="SmsAlert" id="SmsAlert2" value="0" <?php if($res_editTech['sms_alert']=='0'){echo 'checked';} ?> tabindex="20"/>&nbsp;OFF&nbsp;<br/><label for="SmsAlert" id="lblSmsAlert" class="redText"></label>
                                </div>
                        	</div><!---Second Div End--->
                            <!---image Div Start--->
                            <div class="TechImgDiv">
                            <?php if($res_editTech['vehicle_image']!='') {?>
                                <div class="TechImgDivCon"><img src="vehicle_image/<?php echo $res_editTech['vehicle_image'];?>"><span class="formtxt">Vehicle Image</span></div>
                              <?php } if($res_editTech['tech_image']!=''){?>
                                <div class="TechImgDivCon"><img src="tech_image/<?php echo $res_editTech['tech_image'];?>"><span class="formtxt">Tech Image</span></div>
                                <?php }?>
                             </div>
                            <!---image Div End--->
                            <div class="spacer"></div>
                            <!---Third Div Start--->
                            <div class="DivSkill">
                            <?php
								$key = 1;
								$techid = $_REQUEST['id'];
								$serviceArray = $dbf->fetch("service","id>0 ORDER BY id ASC");
								$serviceArray = !empty($serviceArray)? $serviceArray :array();
							?> 
                            	<div><b>Please review the list of services below and click on the work type the tech can provide for each services</b></div>
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
                                <div align="left" class="teskillview techService"><input type="checkbox" name="chkService<?php echo $key;?>[]" id="chkService<?php echo $service['id'];?>" value="D" <?php if($resTechSkill['deliver']==1){echo "checked";}?>/></div>
                                <div align="left" class="teskillview techService"><input type="checkbox" name="chkService<?php echo $key;?>[]" id="chkService<?php echo $service['id'];?>" value="I" <?php if($resTechSkill['installation']==1){echo "checked";}?>/></div>
                                <div align="left" class="teskillview techService"><input type="checkbox" name="chkService<?php echo $key;?>[]" id="chkService<?php echo $service['id'];?>" value="R" <?php if($resTechSkill['repair']==1){echo "checked";}?>/></div>
                                <input type="hidden"  name="hserviceid<?php echo $key;?>" value="<?php echo $service['id'];?>"/>
                                <div class="spacer"></div>
                                <?php $key++;}?>
                            </div>
                            <!---Third Div End--->
                            <div class="spacer"></div>
                             <div align="center">
                                 <input type="submit" class="buttonText" value="Submit Form" tabindex="21"/>
                                 <input type="button" class="buttonText3" value="Back" tabindex="22" onClick="window.location.href='<?php echo $redirect;?>'"/>
                             </div>
                            <!-----Table area start-------> 
                        	<div class="spacer"></div>
                    	</div>
                     </form>
            	</div>
              <!-------------Main Body--------------->
             </div>
         </div>
        <div class="spacer"></div>
        <?php include_once 'footer.php'; ?>
    </div>
</body>
</html>