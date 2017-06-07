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
if(isset($_REQUEST['action']) && $_REQUEST['action']=="insert" && $_SERVER['REQUEST_METHOD']=='POST'){
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
	$TechEmailID =mysql_real_escape_string(trim($_REQUEST['TechEmailID']));
	if($TechEmailID ==''){
		header("Location:add-technician");exit;
	}elseif(filter_var($TechEmailID,FILTER_VALIDATE_EMAIL)== FALSE){
		header("Location:add-technician");exit;
	}
	//duplicate technician Email Id Check
	$numuser = $dbf->countRows("technicians","email='$TechEmailID'");
	if($numuser > 0){
		header("Location:add-technician?msg=002");exit;
	}else{
		$ProfilePassword1=$TechFirstName.rand();
		$ProfilePassword=base64_encode(base64_encode($ProfilePassword1));
		//check for vehicle image
		if($_FILES['VehiclePhoto']['name']<>''){
			$file_name=strtotime("now").'_'.$_FILES['VehiclePhoto']['name'];
			$path="vehicle_image/";
			move_uploaded_file($_FILES['VehiclePhoto']['tmp_name'],$path.$file_name);
		}else{
			$file_name="";
		}
		//check for technician image
		if($_FILES['TechPicture']['name']<>''){
			$file_name1=strtotime("now").'_'.$_FILES['TechPicture']['name'];
			$path1="tech_image/";
			move_uploaded_file($_FILES['TechPicture']['tmp_name'],$path1.$file_name1);
		}else{
			$file_name1="";
		}
		$payble=implode(',',$_POST['TechPayble']);
		$dob= $TechDateBirth? date('Y-m-d',strtotime($TechDateBirth)):'';
		//get latitude and longitude
		$val = $dbf->getLnt($_POST['TechAddress'].",".$_POST['TechCity'].",".$_POST['TechState'].",".$_POST['TechZipcode']);
		//insert into technicians table
		$string="first_name='$TechFirstName', middle_name='$TechMiddleName',last_name='$TechLastName', user_type='tech', email='$TechEmailID',password='$ProfilePassword',contact_phone='$_POST[TechContactNo]', alt_phone='$_POST[TechAltPhone]', address='$TechAddress', city='$TechCity', state='$TechState', zip_code='$_POST[TechZipcode]', latitude='".$val['lat']."',longitude='".$val['lng']."', date_of_birth='$dob',company_name='$TechCompanyName', SSN='$TechSSN', FEIN='$TechFEIN', vehicle_image='$file_name', driver_license_no='$TechDrLicenseNo', tech_image='$file_name1',pay_grade='$TechPayGrade', payble_to='$payble', created_date=now()";
		$insid = $dbf->insertSet("technicians",$string);
		##########Insert Into Technician Skill Table###########
		if($insid){
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
					//insert string
					$stringservice="tech_id='$insid', service_id='$hserviceid', deliver='$deliver',installation='$installation', repair='$repair', created_date=now()";
					$dbf->insertSet("technicians_skill",$stringservice);
				}
			}
		}
		##########Insert Into Technician Skill Table###########
		//Email sending starts here
		$res_template=$dbf->fetchSingle("email_template","id=4");
		$from=$res_template['from_email'];
		$fromName=$res_template['from_name'];
		$subject=$res_template['subject'];
		$input=$res_template['message'];
		$toName=ucfirst($_REQUEST['TechFirstName']);
		$to=$TechEmailID;
		$body = str_replace(array('%Name%','%PayGrade%','%Email%','%Password%'),array($toName,$TechPayGrade,$to,$ProfilePassword1),$input);
		$headers = "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=UTF-8\n";
		$headers .= "From:".$fromName." <".$from.">\r\n";
		$headers .= "Bcc:" .$from. "\r\n";
	    //echo $body;exit;
		@mail($to,$subject,$body,$headers);
		/*Email sending end*/
		header("Location:manage-technician");exit;
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
<body onLoad="document.frmTech.TechFirstName.focus();">
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
                        <div class="headerbg">ADD TECHNICIAN</div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        	<!-----Table area start------->
                            <form action="" name="frmTech" id="frmTech" method="post" onSubmit="return validate_techAdd();" enctype="multipart/form-data" autocomplete="off">
                            <input type="hidden" name="action" value="insert">
                            <div align="center"><?php if($_REQUEST['msg']=="002"){?><span class="redText">This Email ID already exist!</span><?php } ?></div>
                            <!---First Div Start--->
                        	<div class="innerDivTech">
                                <div  class="formtextadd">First Name:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="TechFirstName" id="TechFirstName" tabindex="1" onKeyPress="return onlyLetters(event)"><br/><label for="TechFirstName" id="lblTechFirstName" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Middle Name:</div>
                                <div  class="textboxc">
                                   <input type="text" class="textbox" name="TechMiddleName" id="TechMiddleName" tabindex="2" onKeyPress="return onlyLetters(event)"><br/><label for="TechMiddleName" id="lblTechMiddleName" class="redText"></label>
                                </div>
                                 <div class="spacer"></div>
                                <div  class="formtextadd">Last Name:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="TechLastName" id="TechLastName" tabindex="3" onKeyPress="return onlyLetters(event)"><br/><label for="TechLastName" id="lblTechLastName" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Email ID:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                   <input type="text" class="textbox" name="TechEmailID" id="TechEmailID" tabindex="4"><br/><label for="TechEmailID" id="lblTechEmailID" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">ContactPhone:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                   <input type="text" class="textbox" name="TechContactNo" id="TechContactNo" onKeyUp="validatephone(this);" maxlength="12" tabindex="5"><br/><label for="TechContactNo" id="lblTechContactNo" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Alt Phone:</div>
                                <div  class="textboxc">
                                   <input type="text" class="textbox" name="TechAltPhone" id="TechAltPhone" onKeyUp="validatephone(this);" maxlength="12" tabindex="6"><br/><label for="TechAltPhone" id="lblTechAltPhone" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Address:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <textarea class="textarea" name="TechAddress" id="TechAddress" tabindex="7"></textarea><br/><label for="TechAddress" id="lblTechAddress" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">City:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="TechCity" id="TechCity" tabindex="8" onKeyPress="return onlyLetters(event)"><br/><label for="TechCity" id="lblTechCity" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">State:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                  <select class="selectbox" name="TechState" id="TechState" tabindex="9">
                                  	<option value="">--Select State--</option>
                                    <?php foreach($dbf->fetch("state","id>0 ORDER BY state_code ASC") as $vstate){?>
                                    <option value="<?php echo $vstate['state_code'];?>"><?php echo $vstate['state_name'];?></option>
                                    <?php }?>
                                  </select>
                                    <br/><label for="TechState" id="lblTechState" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Zip Code:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="TechZipcode" id="TechZipcode" tabindex="10" maxlength="10"><br/><label for="TechZipcode" id="lblTechZipcode" class="redText"></label>
                                </div>
                        	</div><!---First Div End--->
                            <!---Second Div Start--->
                            <div class="innerDivTech">
                                <div  class="formtextadd">Date Of Birth:</div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox datepick" name="TechDateBirth" id="TechDateBirth" tabindex="11"  readonly><br/><label for="TechDateBirth" id="lblTechDateBirth" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Company Name:</div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="TechCompanyName" id="TechCompanyName" tabindex="12"><br/><label for="TechCompanyName" id="lblTechCompanyName" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">SSN#:</div>
                                <div  class="textboxc">
                                   <input type="text" class="textbox" name="TechSSN" id="TechSSN" tabindex="13"><br/><label for="TechSSN" id="lblTechSSN" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">FEIN#:</div>
                                <div  class="textboxc">
                                   <input type="text" class="textbox" name="TechFEIN" id="TechFEIN" tabindex="14"><br/><label for="TechFEIN" id="lblTechFEIN" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Type Vehicle:</div>
                                <div  class="textboxc">
                                   <input type="file" class="textbox" name="VehiclePhoto" id="VehiclePhoto" tabindex="15"><br/><label for="VehiclePhoto" id="lblVehiclePhoto" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Driver License No:</div>
                                <div  class="textboxc">
                                   <input type="text" class="textbox" name="TechDrLicenseNo" id="TechDrLicenseNo" tabindex="16"><br/><label for="TechDrLicenseNo" id="lblTechDrLicenseNo" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Tech Picture:</div>
                                <div  class="textboxc">
                                	<input type="file" class="textbox" name="TechPicture" id="TechPicture" tabindex="17"><br/>
                                    <label for="TechPicture" id="lblTechPicture" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Pay Grade:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <select class="selectbox" name="TechPayGrade" id="TechPayGrade" tabindex="18">
                                        <option value="">--Select Pay Grade--</option>
                                        <option value="A"> A </option>
                                        <option value="B"> B </option>
                                        <option value="C"> C </option>
                                        <option value="D"> D </option>
                                        <option value="E"> E </option>
                                        <option value="F"> F </option>
                                        <option value="G"> G </option>
                                        <option value="H"> H </option>
                                        <option value="I"> I </option>
                                        <option value="J"> J </option>
                                    </select><br/><label for="TechPayGrade" id="lblTechPayGrade" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Payble To:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                	<input type="checkbox" name="TechPayble[]" id="TechPayble1" value="bank" tabindex="19"/>&nbsp;Bank&nbsp;&nbsp;&nbsp;<input type="checkbox" name="TechPayble[]" id="TechPayble2" value="self" tabindex="20"/>&nbsp;Self&nbsp;<br/><label for="TechPayble" id="lblTechPayble" class="redText"></label>
                                </div>
                        	</div><!---Second Div End--->
                            <div class="spacer"></div>
                            <!---Third Div Start--->
                            <div class="DivSkill">
                            <?php
								$key = 1;
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
                                <?php foreach($serviceArray as $service){?>
                                <div align="left" class="teskillview techService1"><?php echo $service['service_name'];?></div>
                                <div align="left" class="teskillview techService"><input type="checkbox" name="chkService<?php echo $key;?>[]" id="chkService<?php echo $service['id'];?>" value="D"/></div>
                                <div align="left" class="teskillview techService"><input type="checkbox" name="chkService<?php echo $key;?>[]" id="chkService<?php echo $service['id'];?>" value="I"/></div>
                                <div align="left" class="teskillview techService"><input type="checkbox" name="chkService<?php echo $key;?>[]" id="chkService<?php echo $service['id'];?>" value="R"/></div>
                                <input type="hidden"  name="hserviceid<?php echo $key;?>" value="<?php echo $service['id'];?>"/>
                                <div class="spacer"></div>
                                <?php $key++;}?>
                            </div>
                            <!---Third Div End--->
                            <div class="spacer"></div>
                             <div align="center">
                                 	<input type="submit" class="buttonText" value="Submit Form" tabindex="21"/>
                                    <a href="manage-technician" style="text-decoration:none;"><input type="button" class="buttonText3" value="Back" tabindex="22"/></a>
                             </div>
                             
                             </form>
                            <!-----Table area start-------> 
                        	<div class="spacer"></div>
                    	</div>
            	</div>
              <!-------------Main Body--------------->
         </div>
         </div>
        <div class="spacer"></div>
        <?php include_once 'footer.php'; ?>
    </div>
</body>
</html>