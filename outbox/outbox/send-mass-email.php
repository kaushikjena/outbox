<?php 
ob_start();
session_start();
ini_set('memory_limit','-1'); // set memory limit upto 2 GB 
ini_set('max_execution_time','3600'); // set memory limit upto 1 hour
ini_set('max_input_time', '3600');
ini_set("post_max_size", "256M");
ini_set("upload_max_filesize", "256M");

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
if(isset($_REQUEST['action']) && $_REQUEST['action']=="sendemail" && $_SERVER['REQUEST_METHOD']=='POST'){
	
	$EmailSubject=mysql_real_escape_string($_POST['EmailSubject']);
	$chkAdmin=$_POST['chkAdmin'];
	$selectTech=$_POST['selectTech'];
	//print_r($selectTech);
	//exit;
	if(!empty($selectTech)){
	//Email Sending Starts here
	  $adminemail=$dbf->getDataFromTable("admin","email","id=1");
	  $res_template=$dbf->fetchSingle("email_template","id=17");
	  $from=$res_template['from_email'];
	  $from_name=$res_template['from_name'];  
	  $subject= $EmailSubject;
	  $inputmessage=$res_template['message'];
	  if(isset($_FILES['attachment1']) && $_FILES['attachment1']['name'] !=''){ 
		  $mime_boundary="==Multipart_Boundary_x".md5(mt_rand())."x";
		  $headers = "From:".$from_name." <".$from.">\r\n";
		  if($chkAdmin == 1){
			$headers .= "Cc:".$adminemail."\r\n";
		  }
		  $headers.= "MIME-Version: 1.0\r\n" .
				"Content-Type: multipart/mixed;\r\n" .
				" boundary=\"{$mime_boundary}\"";
		  $message = "This is a multi-part message in MIME format.\n\n" .
				"--{$mime_boundary}\n" .
				"Content-Type: text/html; charset=utf-8\r\n" .
				"Content-Transfer-Encoding: 7bit\n\n" . $inputmessage . "\n\n";
		 foreach($_FILES as $userfile){
			$tmp_name = $userfile['tmp_name'];
			$type = $userfile['type'];
			$name = $userfile['name'];
			$size = $userfile['size'];
			if (file_exists($tmp_name)){
			   if(is_uploaded_file($tmp_name)){
				  $file = fopen($tmp_name,'rb');
				  $data = fread($file,filesize($tmp_name));
				  fclose($file);
				  $data = chunk_split(base64_encode($data));
			   }
			   $message .= "--{$mime_boundary}\n" .
				  "Content-Type: {$type};\n" .
				  " name=\"{$name}\"\n" .
				  "Content-Disposition: attachment;\n" .
				  " filename=\"{$name}\"\n" .
				  "Content-Transfer-Encoding: base64\n\n" .
			   $data . "\n\n";
			}
		 }
		$message.="--{$mime_boundary}--\n";
		//loop for sending email to technicians
		foreach($selectTech as $valt){
			 $toName='';
			 $resTechVal= $dbf->fetchSingle("technicians","id='$valt'");
			 $to=$resTechVal['email'];
			 $toName=ucfirst($resTechVal['first_name']).' '.ucfirst($resTechVal['middle_name']).' '.ucfirst($resTechVal['last_name']);
			 //replace the techname
			 $message = str_replace(array('%TechName%'),array($toName),$message);
			 //echo $message;exit;
			 @mail($to,$subject,$message, $headers);
		}
	  }else{ 
		$headers = "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=UTF-8\n";
		$headers .= "From:".$from_name." <".$from.">\n";
		if($chkAdmin == 1){
			$headers .= "Cc:".$adminemail."\n";
		}
		//loop for sending email to technicians
		foreach($selectTech as $valt){
		  $toName='';
		  $resTechVal= $dbf->fetchSingle("technicians","id='$valt'");
		  $to=$resTechVal['email'];
		  $toName=ucfirst($resTechVal['first_name']).' '.ucfirst($resTechVal['middle_name']).' '.ucfirst($resTechVal['last_name']);
		  //replace the techname
		  $body=str_replace(array('%TechName%'),array($toName),$inputmessage);
		 // echo $body;exit;
		  @mail($to,$subject,$body,$headers);
		}
	}
	//Email Sending End
	header("Location:send-mass-email?msg=01");exit;
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
function addAattachment(){
	 var row = $('#count').val();
		if(row==10){
			alert("You can't add more than 10 rows");
		}else{
			var nextrow = parseInt(row)+1;
			$("#row_to_clone"+nextrow).show();
			//$("#row_to_clone_btr"+nextrow).show();
			$('#count').val(nextrow);
	 }
}

function delAattachment(){
   var row = $('#count').val();
   //alert(row);
   if(row==1){
	   alert("You can't delete default row");
   }else{
	    var nextrow = parseInt(row)-1;
		$("#row_to_clone"+row).hide();
		$("#attachment"+row).val("");
		$('#count').val(nextrow);
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
                        <div class="headerbg">SEND MASS EMAIL</div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        	<!-----Table area start------->
                        	<div  class="innertable">
                            <div align="center"><?php if($_REQUEST['msg']=="01"){?><span class="greenText">The Email send successfully.</span><?php } ?></div>
                            <div class="spacer"></div>
                         	 <form action="" name="frmMassemail" id="frmMassemail" method="post" onSubmit="return validate_massemail();" enctype="multipart/form-data">
                     		 <input type="hidden" name="action" value="sendemail">
                                <div  class="formtextadd">Subject:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textboxjob" name="EmailSubject" id="EmailSubject"><br/><label for="EmailSubject" id="lblEmailSubject" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Send to Admin:</div>
                                <div  class="textboxc">
                                    <input type="checkbox" name="chkAdmin" id="chkAdmin" value="1">
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
                                <div  class="formtextadd">&nbsp;</div>
                                <div  class="textboxc">&nbsp;(Click here to add more files)&nbsp;&nbsp;<img src="images/plus.gif" width="16" height="16" onClick="addAattachment();" style="cursor:pointer;"/>&nbsp;<img src="images/minus.gif" width="16" height="16" onClick="delAattachment()" style="cursor:pointer;"/>&nbsp;</div>
                                <input type="hidden" name="count" id="count" value="1">
                                <div class="spacer"></div>
                                <div  class="formtextadd">Attachment:</div>
                                <div  class="textboxc"><input type="file" name="attachment1" id="attachment1"/> </div>
                                <?php for($i=2;$i<=10;$i++){?>
                                <div class="spacer"></div>
                                <div id="row_to_clone<?php echo $i; ?>" style="display:none;">
                                <div  class="formtextadd">Attachment:</div>
                                <div  class="textboxc"><input type="file" name="attachment<?php echo $i; ?>" id="attachment<?php echo $i; ?>"/> </div>
                                </div>
                                <?php }?>
                                <div class="spacer"></div>
                                <div align="center">
                                 	<input type="submit" class="buttonText" value="Send Email"/>&nbsp;&nbsp;
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