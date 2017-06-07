<?php 
ob_start();
session_start();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();
if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='delete_client'){
	$countClient = $dbf->countRows("work_order","created_by='$_REQUEST[clientid]'");
	echo $countClient ;
}
if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='delete_customer'){
	$countCustomer = $dbf->countRows("work_order","client_id='$_REQUEST[custid]'");
	echo $countCustomer ;
}
if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='delete_tech'){
	$countTech = $dbf->countRows("assign_tech","tech_id='$_REQUEST[techid]'");
	echo $countTech ;
}
if(isset($_REQUEST['choice']) && $_REQUEST['choice']=="show_text"){ 
$client_id = $_REQUEST['clientid'];
$resClient = $dbf->strRecordID("clients","tech_instruction","id='$client_id'");

?>
 <div id="maindiv">
     <div  style="margin:2px;">
          <!-------------Main Body--------------->
            <div class="technicianjobboard" style="width:700px;">
                <div class="rightcoluminner">
                    <div class="headerbg">Tech Instruction</div>
                    <div class="spacer"></div>
                    <div id="contenttable">
                        <!-----Table area start------->
                        <div  class="emailDiv">
                        <div class="spacer"></div>
                         <form action="" name="frmEmailTech" id="frmEmailTech" method="post" enctype="multipart/form-data">
                            <div  class="formtextadd">Instruction :<span class="redText">*</span></div>
                            <div  class="textboxc">&nbsp;</div>
                            <div class="spacer"></div>
                            <div>
                            <textarea name="message"  id="message" class="textareaOrder"style="min-height:200px;"><?php echo $resClient['tech_instruction'];?></textarea>
                            
                            </div>
                            <div class="spacer"></div>
                             <div align="center">
                             <input type="hidden" name="clientid" id="clientid" value="<?php echo $client_id; ?>"/>
                             <input type="button" class="buttonText" value="Save Instruction" onClick="saveInstruction();"/></div>
                            </form>
                        </div>
                        <!-----Table area start-------> 
                        <div class="spacer"></div>
                    </div>
            </div>
           </div>
          <!-------------Main Body--------------->
     </div>
  </div>	
<?php }
else if(isset($_REQUEST['choice'])&& $_REQUEST['choice']=="save_text"){
	ob_clean();//print "<pre>";print_r($_REQUEST);exit;
	$client_id=addslashes($_REQUEST['clientid']);
	$message=addslashes($_REQUEST['message']);
	$dbf->updateTable("clients","tech_instruction='$message'","id='$client_id'");
	echo 1;exit;
}
else if(isset($_REQUEST['choice'])&& $_REQUEST['choice']=="fetch_instruction"){
	ob_clean();//print "<pre>";print_r($_REQUEST);exit;
	$client_id=addslashes($_REQUEST['clientid']);
	$techInstruction = $dbf->getDataFromTable("clients","tech_instruction","id='$client_id'");
	//echo $techInstruction;exit;
?>
	<div id="maindiv">
     <div  style="margin:2px;">
          <!-------------Main Body--------------->
            <div class="technicianjobboard" style="width:700px;">
                <div class="rightcoluminner">
                    <div class="headerbg">Tech Instruction</div>
                    <div class="spacer"></div>
                    <div id="contenttable">
                        <!-----Table area start------->
                        <div  class="emailDiv">
                        <div class="spacer"></div>
                         <form action="" name="frmEmailTech" id="frmEmailTech" >
                            <div  class="formtextadd">Instruction :<span class="redText">*</span></div>
                            <div  class="textboxc">&nbsp;</div>
                            <div class="spacer"></div>
                            <?php if($techInstruction !=''){?>
                            <div>
                            <textarea name="message"  id="message" class="textareaOrder"style="min-height:200px;"><?php echo $techInstruction;?></textarea>
                            
                            </div>
                            <?php }else{?>
                            <div class="noRecords" align="center">Sorry !!! No Tech Instruction given by client.</div>
                            <?php }?>
                            <div class="spacer"></div>
                             <div align="center">
                             <input type="hidden" name="clientid" id="clientid" value="<?php echo $client_id; ?>"/>
                             <input type="button" class="buttonText" value="Close Window" onClick="closeFancyBox();"/></div>
                            </form>
                        </div>
                        <!-----Table area start-------> 
                        <div class="spacer"></div>
                    </div>
            </div>
           </div>
          <!-------------Main Body--------------->
     </div>
  </div>
<?php
}
?>