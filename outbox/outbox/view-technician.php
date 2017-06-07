<?php 
ob_start();
session_start();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();
//page titlevariable
$pageTitle="Welcome To Out Of The Box";
include 'applicationtop.php';
if($_SESSION['userid']==''){
	header("location:logout");exit;
}
$res_viewTech=$dbf->fetchSingle("technicians","id='$_REQUEST[id]'");
if($_REQUEST['src'] == 'active'){
	$redirect = "manage-technician-active";
}else{
	$redirect = "manage-technician";
}
?>
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
				<?php //include_once 'left-menu.php';?>
                <!-------------left menu--------------->
                <!-------------Main Body--------------->
                <div class="rightcolumjobboard">
            		<div class="rightcoluminner">
                        <div class="headerbg">VIEW TECHNICIAN
                        	<div style="float:right;"><input type="button" class="buttonText2" value="Return Back" onClick="javascript:window.location.href='<?php echo $redirect;?>'"/></div>
                        </div>
                        <div class="spacer"></div>
                        <div id="contenttable" style="border: 1px solid #666;">
                        	<!-----Table area start------->
                        <div align="center"></div>
                        
                            <!---First Div Start--->
                        	<div  class="innerDivTech">
                                <div  class="formtextadd">First Name:</div>
                                <div  class="textboxview"><?php echo $res_viewTech['first_name'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Middle Name:</div>
                                <div  class="textboxview"><?php echo $res_viewTech['middle_name'];?></div>
                                 <div class="spacer"></div>
                                <div  class="formtextadd">Last Name:</div>
                                <div  class="textboxview"><?php echo $res_viewTech['last_name'];?> </div>
                                <div class="spacer"></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">User Type:</div>
                                <div  class="textboxview"><?php echo $res_viewTech['user_type'];?> </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Email ID:</div>
                                <div  class="textboxview"><?php echo $res_viewTech['email'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Contact No:</div>
                                <div  class="textboxview"><?php echo $res_viewTech['contact_phone'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">AltContact No:</div>
                                <div  class="textboxview"><?php echo $res_viewTech['alt_phone'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Address:</div>
                                <div  class="textboxview"><?php echo $res_viewTech['address'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">City:</div>
                                <div  class="textboxview"><?php echo $res_viewTech['city'];?></div>
                                <div class="spacer"></div>
                                <?php $res_techState=$dbf->getDataFromTable("state","state_name","state_code='$res_viewTech[state]'");?>
                                <div  class="formtextadd">State:</div>
                                <div  class="textboxview"><?php echo $res_techState;?></div>
                        	</div><!---First Div End--->
                            <!---Second Div Start--->
                            <div class="innerDivTech">
                                <div  class="formtextadd">Zipcode:</div>
                                <div  class="textboxview"><?php echo $res_viewTech['zip_code'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Date of Birth:</div>
                                <div  class="textboxview"><?php echo ($res_viewTech['date_of_birth']<>'0000-00-00')?date('d-m-Y',strtotime($res_viewTech['date_of_birth'])):'';?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Company Name:</div>
                                <div  class="textboxview"><?php echo $res_viewTech['company_name'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">SSN:</div>
                                <div  class="textboxview"><?php echo $res_viewTech['SSN'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">FEIN:</div>
                                <div  class="textboxview"><?php echo $res_viewTech['FEIN'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Driver License No:</div>
                                <div  class="textboxview"><?php echo $res_viewTech['driver_license_no'];?></div>
                                <div class="spacer"></div>
                  			    <div  class="formtextadd">Pay Grade:</div>
                                <div  class="textboxview"><?php echo $res_viewTech['pay_grade'];?></div>
                                <div class="spacer"></div>
                  			    <div  class="formtextadd">Payble To:</div>
                                <div  class="textboxview"><?php echo $res_viewTech['payble_to'];?></div>
                                <div class="spacer"></div>
                  			    <div  class="formtextadd">Active Status:</div>
                                <div  class="textboxview"><?php if($res_viewTech['status']=='1'){echo 'Active';}else{echo 'Inactive';}?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Password:</div>
                                <div  class="textboxview"><?php echo base64_decode(base64_decode($res_viewTech['password']));?></div>
                        	</div><!---Second Div Start--->
                             <!---image Div Start--->
                            <div class="TechImgDiv">
                            <?php if($res_viewTech['vehicle_image']!='') {?>
                                <div class="TechImgDivCon"><img src="vehicle_image/<?php echo $res_viewTech['vehicle_image'];?>"><span class="formtxt">Vehicle Image</span></div>
                              <?php } if($res_viewTech['tech_image']!=''){?>
                                <div class="TechImgDivCon"><img src="tech_image/<?php echo $res_viewTech['tech_image'];?>"><span class="formtxt">Tech Image</span></div>
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
                             <div align="center">
                                 <input type="button" class="buttonText" value="Return Back" onClick="window.location='<?php echo $redirect;?>'"/>
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