<?php 
ob_start();
session_start();
include_once 'includes/class.Main.php';
include('textmagic-sms-api/TextMagicAPI.php');
//Object initialization
$dbf = new User();
if(isset($_REQUEST['choice']) && $_REQUEST['choice']=="viewsms"){ 
$resTech = $dbf->fetchSingle("technicians ","id='$_REQUEST[techid]'");
?>
 <div id="maindiv">
     <div  style="margin:2px;">
            <!-------------Main Body--------------->
            <div class="technicianjobboard">
                <div class="rightcoluminner">
                    <div class="headerbg">Send SMS</div>
                    <div class="spacer"></div>
                    <div id="contenttable">
                    <!-----Table area start------->
                      <form name="frmTech" id="frmTech"  method="post" autocomplete="off">                        	
                        <div>
                            <div class="spacer" style="height:20px;"></div>
                            <div  class="formtextaddtech">Tech Phone No:<span class="redText">*</span></div>
                            <div  class="textboxctech">
                            <input type="text" class="textboxjob" name="TechPhoneNo" id="TechPhoneNo" value="<?php echo $resTech['contact_phone'];?>" tabindex="1"><br/><label for="TechPhoneNo" id="lblTechPhoneNo" class="redText"></label>
                            </div>
                             <div class="spacer" style="height:20px;"></div>
                             <div  class="formtextaddtech">Message:<span class="redText">*</span></div>
                            <div  class="textboxctech">
                            <textarea class="textareajob" name="txaMessage" id="txaMessage" tabindex="2"></textarea><br/><span class="formtext">(Max 160 characters)</span><br/><label for="txaMessage" id="lbltxaMessage" class="redText"></label>
                            </div>
                            <div class="spacer" style="height:20px;"></div>
                        </div>
                        <!-----service div end--------->
                        <div class="spacer"></div>
                        <div align="center">
                            <input type="hidden" name="techid" id="techid" value="<?php echo $_REQUEST['techid']; ?>"/>
                            <input type="button" name="submitbtn" id="submitbtn" class="buttonText" value="SEND SMS" onclick="send_sms();"/>
                         </div>
                        <div class="spacer"></div>
                       </form>
                       <!-----Table area end------->
                    </div>
            </div>
           </div>
          <!-------------Main Body--------------->
     </div>
  </div>	
<?php }else if(isset($_REQUEST['choice'])&& $_REQUEST['choice']=="sendsms"){
	ob_clean();
	###############FUNCTION FOR SEND SMS#######################
	$txaMessage=addslashes($_REQUEST['txaMessage']);
	$TechPhoneNo=addslashes($_REQUEST['TechPhoneNo']);
	//REMOVE . ,white spaces,hyphen from the string
	$TechPhoneNo = preg_replace('/[\. -]/', '', $TechPhoneNo);
	####################INTEGRATED TEXT MAGIC GATEWAY#########################
	 $tosend="1".$TechPhoneNo; 
	//===================Call to Text magic SMS GATEWAY API===================//
	   try{
			$api = new TextMagicAPI(array(
				"username" => TEXT_MAGIC_API_USER,
				"password" => TEXT_MAGIC_API_PASSWORD
			));
			//$phones = array(16463050962);
			//$phones = array(99912345678);
			$phones = array($tosend);
			$results = $api->send($txaMessage, $phones, true);
			//your data base code to insert; 
	   }catch(Exception $e){
	   }
	##########################################################################
?>
	<div id="maindiv">
     <div  style="margin:2px;">
            <!-------------Main Body--------------->
            <div class="technicianjobboard">
                <div class="rightcoluminner">
                    <div class="headerbg">Send SMS</div>
                    <div class="spacer"></div>
                    <div id="contenttable">
                    <!-----Table area start------->
                        <div>
                            <div class="spacer" style="height:20px;"></div>
                            <div class="greenText" align="center">
							<?php
								echo "Message send successfully.";exit;
							 ?>
                             </div>
                            <div class="spacer" style="height:20px;"></div>
                        </div>
                       <!-----Table area end------->
                    </div>
            </div>
           </div>
          <!-------------Main Body--------------->
     </div>
  </div>
<?php }?>