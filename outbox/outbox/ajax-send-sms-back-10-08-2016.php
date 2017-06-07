<?php 
ob_start();
session_start();
include_once 'includes/class.Main.php';
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
	###############PREPARATION FOR SEND SMS#################
	//user credentials
	$username = "boxware";
	$password = "80xW4r3!";
	###############FUNCTION FOR SEND SMS#######################
	function do_post_request($url, $data, $optional_headers = 'Content-type:application/x-www-form-urlencoded') {
		$params = array('http'      => array(
			'method'       => 'POST',
			'content'      => $data,
			));
		if ($optional_headers !== null) {
			$params['http']['header'] = $optional_headers;
		}
	
		$ctx = stream_context_create($params);
		$response = @file_get_contents($url, false, $ctx);
		if ($response === false) {
			return "-1|Problem reading data from $url, No status returned\n";
		}
		return $response;
	}
	###############FUNCTION FOR SEND SMS#######################
	$txaMessage=addslashes($_REQUEST['txaMessage']);
	$TechPhoneNo=addslashes($_REQUEST['TechPhoneNo']);
	//REMOVE . ,white spaces,hyphen from the string
	$TechPhoneNo = preg_replace('/[\. -]/', '', $TechPhoneNo);
	//FOR USA TECH PHONE NUMBERS
	$msisdn ='1'.$TechPhoneNo;
	//for testing phone numbers
	//$msisdn = '918895618447';
	$url = 'http://usa.bulksms.com/eapi/submission/send_sms/2/2.0';
	$data = 'username='.$username.'&password='.$password.'&message='.urlencode($txaMessage).'&msisdn='.urlencode($msisdn);
	$response = do_post_request($url, $data);
	//print $response;
	$response =explode("|",$response);
	//print_r($response);
	###############PREPARATION FOR SEND SMS#################
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
								$case = $response[0];
								switch ($case){
									case 0:
										echo "Message send successfully.";
										break;
									case 1:
										echo "Message is Scheduled.";
										break;
									case 22:
										echo "Internal fatal error.";
										break;
									case 23:
										echo "Authentication failure.";
										break;
									case 24:
										echo "Data validation failed.";
										break;
									case 25:
										echo "You do not have sufficient credits.";
										break;
									case 26:
										echo "Upstream credits not available.";
										break;
									case 27:
										echo "You have exceeded your daily quota.";
										break;
									case 28:
										echo "Upstream quota exceeded.";
										break;
									case 40:
										echo "Temporarily unavailable.";
										break;
									case 201:
										echo "Maximum batch size exceeded.";
										break;
									default:
										echo "Problem reading data from $url, No status returned\n";
										break;
								}
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