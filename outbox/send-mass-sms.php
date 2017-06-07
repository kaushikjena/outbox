<?php 
ob_start();
session_start();
include('textmagic-sms-api/TextMagicAPI.php');
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
//get sms template
$res_sms=$dbf->getDataFromTable("sms_template","message","id='3'");
//for submit here
if(isset($_REQUEST['action']) && $_REQUEST['action']=="sendsms" && $_SERVER['REQUEST_METHOD']=='POST'){
	$txaMessage=addslashes($_REQUEST['txaMessage']);
	$selectTech=$_POST['selectTech'];
	//print_r($selectTech);
	//exit;
	if(!empty($selectTech)){
		$phoneArr=array();
		//loop for sending email to technicians
		foreach($selectTech as $valt){
			$TechPhoneNo= $dbf->getDataFromTable("technicians","contact_phone","id='".$valt."'");
			//REMOVE . ,white spaces,hyphen from the string
			$TechPhoneNo = preg_replace('/[\. -]/', '', $TechPhoneNo);
			$tosend="1".$TechPhoneNo;
			array_push($phoneArr,$tosend);
		}
		//print_r($phoneArr);exit;
		###############FUNCTION FOR SEND SMS#######################
		//===================Call to Text magic SMS GATEWAY API===================//
		   try{
				$api = new TextMagicAPI(array(
					"username" => TEXT_MAGIC_API_USER,
					"password" => TEXT_MAGIC_API_PASSWORD
				));
				//$phones = array(16463050962);
				//$phones = array(99912345678);
				$phones = $phoneArr;
				$results = $api->send($txaMessage, $phones, true);
				//your data base code to insert; 
		   }catch(Exception $e){
		   }
		##########################################################################
	//Email Sending End
	header("Location:send-mass-sms?msg=01");exit;
  }
	
}
?>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />
<body>
<script type="text/javascript">
function check_all(){
  var chkval= $('input:checkbox[name=chkAll]:checked').val();
 //alert(chkval);
 if(chkval==1){
		$('input:checkbox[name="selectTech[]"]').each(function() { 
			 $(this).attr('checked', true);
		 });
	}else{
		$('input:checkbox[name="selectTech[]"]').each(function() { 
			 $(this).attr('checked', false);
		 });
	}
}
</script>
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
                        <div class="headerbg">SEND GROUP SMS</div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        	<!-----Table area start------->
                        	<div  class="innertable">
                            <div align="center"><?php if($_REQUEST['msg']=="01"){?><span class="greenText">SMS send successfully.</span><?php } ?></div>
                            <div class="spacer"></div>
                         	 <form action="" name="frmMassemail" id="frmMassemail" method="post" onSubmit="return validate_groupsms();" enctype="multipart/form-data">
                     		 <input type="hidden" name="action" value="sendsms">
                                <div  class="formtextadd">Message:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <textarea class="textareajob" name="txaMessage" id="txaMessage" style="height:80px;"><?php echo $res_sms;?></textarea><br/><label for="txaMessage" id="lbltxaMessage" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Select Technician:<span class="redText">*</span></div>
                                <div  class="textboxc" >
                                	<div style="padding:5px; color:#666;"><input type="checkbox" name="chkAll" id="chkAll" value="1" onClick="check_all();"/>&nbsp; Select All</div>
                                	<div class="selectboxjob" style=" min-height:200px;overflow:auto; width:97%;">
									<?php
										$cond1 = "t.id>0 AND t.status=1";
										//condition for users
										if($implode_techs <>''){
											$cond1.=" AND FIND_IN_SET(t.id,'$implode_techs')";
										} 
                                        $resTechArray=$dbf->fetchOrder("technicians t",$cond1,"t.first_name ASC","t.id,t.first_name,t.middle_name,t.last_name","t.id");
                                        foreach($resTechArray as $res){
                                     ?>
                                        <input type="checkbox" name="selectTech[]" id="selectTech" value="<?php echo $res['id'];?>"/> &nbsp; <?php echo $res['first_name'].' '.$res['middle_name'].' '.$res['last_name'];?><br/>
                                    <?php }?>
                                   </div><br/><label for="selectTech" id="lblselectTech" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div align="center">
                                 	<input type="submit" class="buttonText" value="Send SMS"/>&nbsp;&nbsp;
                                   	<input type="button" class="buttonText3" value="Back" onClick="window.location.href='dashboard'"/>
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
    </div>
</body>
</html>