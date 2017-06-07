<?php 
ob_start();
session_start();
include_once '../includes/class.Main.php';
//Object initialization
$dbf = new User();
//page titlevariable
$pageTitle="Welcome To Out Of The Box";
include 'applicationtop-client.php';
if($_SESSION['usertype']!='client'){
	header("location:../logout");exit;
}
$amount = $_REQUEST['amount'];
//client details
$resClientDetails = $dbf->fetchSingle("clients","id='$_SESSION[userid]'");
//admin details
$resAdminDetails = $dbf->fetchSingle("admin","id='1'");
//authorized.net credential from database
$resCredentials = $dbf->fetchSingle("authorize_apicredential","id='1'");
##############################################################
##############REQUEST FOR AUTHORIZED.NET PAYMENT##############
##############################################################
if($_REQUEST['action']=='checkout'){
	$trns_id=time();
	$InvoiceNo= $wo_no;
	$ClientName =urlencode($_REQUEST['ClientName']);
	$ClientEmail =urlencode($_REQUEST['ClientEmail']);
	$ClientAddress = urlencode($_REQUEST['ClientAddress']);
	$ClientCity = urlencode($_REQUEST['ClientCity']);
	$ClientState =urlencode( $_REQUEST['ClientState']);
	$ClientZip = urlencode($_REQUEST['ClientZip']);
	$CardNumber = urlencode($_REQUEST['CardNumber']);
	$ExpMonth =urlencode( $_REQUEST['ExpMonth']);		
	// Month must be padded with leading zero
	$padDateMonth = str_pad($ExpMonth, 2, '0', STR_PAD_LEFT);		
	$ExpYear =urlencode( $_REQUEST['ExpYear']);
	$expdate = $padDateMonth.$ExpYear;
	//$cvv2Number = urlencode($_POST['cardcvv']);
	
	if($resCredentials['test_mode']==0){
		$post_url = "https://test.authorize.net/gateway/transact.dll";
	}else{
		$post_url = "https://secure.authorize.net/gateway/transact.dll";
	}
	$post_values = array(
		// the API Login ID and Transaction Key must be replaced with valid values
		"x_login"			=> $resCredentials['api_loginid'], 
		"x_tran_key"		=> $resCredentials['transaction_key'], 
	
		"x_version"			=> "3.1",
		"x_delim_data"		=> "TRUE",
		"x_delim_char"		=> "|",
		"x_relay_response"	=> "FALSE",
	
		"x_type"			=> "AUTH_CAPTURE",
		"x_method"			=> "CC",
		"x_card_num"		=> $CardNumber, //4111111111111111
		"x_card_code"		=> "",
		"x_exp_date"		=> $expdate, //0115
		"x_trans_id"		=> $trns_id,
	
		"x_amount"			=> $amount,
		"x_currency_code"	=> "USD",
		"x_invoice_num"		=> $InvoiceNo,
		"x_description"		=> "Workorder Transaction",
	
		"x_email"			=> $ClientEmail,
		"x_first_name"		=> $ClientName,
		"x_last_name"		=> "",
		"x_address"			=> $ClientAddress, //1234 Street
		"x_city"			=> $ClientCity,
		"x_state"			=> $ClientState,//WA
		"x_zip"				=> $ClientZip //98004
		// Additional fields can be added here as outlined in the AIM integration
		// guide at: http://developer.authorize.net
	);
	
	// This section takes the input fields and converts them to the proper format
	// for an http post.  For example: "x_login=username&x_tran_key=a1B2c3D4"
	$post_string = "";
	foreach( $post_values as $key => $value )
		{ $post_string .= "$key=" . urlencode( $value ) . "&"; }
	$post_string = rtrim( $post_string, "& " );
	
	// This sample code uses the CURL library for php to establish a connection,
	// submit the post, and record the response.
	// If you receive an error, you may want to ensure that you have the curl
	// library enabled in your php configuration
	$request = curl_init($post_url); // initiate curl object
	curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
	curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
	curl_setopt($request, CURLOPT_POSTFIELDS, $post_string); // use HTTP POST to send form data
	curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response.
	$post_response = curl_exec($request); // execute curl post and store results in $post_response
	// additional options may be required depending upon your server configuration
	// you can find documentation on curl options at http://www.php.net/curl_setopt
	curl_close ($request); // close curl object
	
	// This line takes the response and breaks it into an array using the specified delimiting character
	$response_array = explode($post_values["x_delim_char"],$post_response);
	//print "<pre>";
	//print_r($response_array);
	//echo $response_array[3];
	############################
	 $fp=fopen("auth.txt","w");
	 foreach($response_array as $key => $value){
	  	fwrite($fp,$key.'===='.$value."\t");
	 }
	###########################
}
##############################################################
##############REQUEST FOR AUTHORIZED.NET PAYMENT##############
##############################################################
?>
<link rel="stylesheet" href="../css/innermain.css" type="text/css" />
<link rel="stylesheet" href="../css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="../css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="../css/respmenu.css" type="text/css" />
<body>
    <div id="maindiv">
        <!-------------header--------------->
     	<?php include_once 'header-client.php';?>
   		<!-------------header--------------->
        <!-------------top menu--------------->
     	<?php include_once 'client-top-menu.php';?>
   		<!-------------top menu--------------->
         <div id="contentdiv">
                <!-------------Main Body--------------->
                <div class="rightcolumjobboard">
            		<div class="rightcoluminner">
                        <div class="headerbg">Payment Details</div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        <!-----Table area start------->
                            <!-----div billing start--------->
                           	<div  class="divBilling">
                            <div class="innerBilling">
                            <?php if($response_array[0]==1 && $response_array[2]==1){
								$payment_date = date('Y-m-d');
								//insert into client_payment_history table
								$stringh="client_id='$_SESSION[userid]', transaction_id='$response_array[37]', transaction_amount='$response_array[9]', payment_status='Completed', payment_date='$payment_date', created_date=now()";
								$dbf->insertSet("client_payment_history",$stringh);
								//update work_order_bill table
								foreach($_SESSION['WorkOrder'] as $wno){
									$dbf->updateTable("work_order_bill","payment_status='Completed',payment_date='$payment_date'","wo_no='$wno' AND created_by='$_SESSION[userid]'");
								}
								/*Email sending start for payment*/
								//email to client
								$res_template=$dbf->fetchSingle("email_template","id='13'");
								$from1=$resAdminDetails['email'];
								$from_name1=$resAdminDetails['name'];
								$subject1=$res_template['subject'];
								$input1=$res_template['message'];
								$toemail=$resClientDetails['email'];
								$toName=$resClientDetails['name'];
								$emailbody=str_replace(array('%ClientName%','%TransactionID%','%TransactionAmt%','%TransactionDate%'),array($toName,$response_array[37],$response_array[9],date("d-M-Y",strtotime($payment_date))),$input1);
								$headers = "MIME-Version: 1.0\n";
								$headers .= "Content-type: text/html; charset=UTF-8\n";
								$headers .= "From:".$from_name1." <".$from1.">\n";
								//echo $emailbody;//exit;
								@mail($toemail,$subject1,$emailbody,$headers);
								//email to admin
								$admin_notification = $dbf->getDataFromTable("admin_email_notification","status","id=5");
								$admin_email = $dbf->getDataFromTable("admin_email_notification","to_email","id=5");
								if($admin_notification==1){
									$res_template=$dbf->fetchSingle("email_template","id='14'");
									$from=$resClientDetails['email'];
									$from_name=$resClientDetails['name'];
									$subject=$res_template['subject'];
									$input=$res_template['message'];
									//$toadmin=$resAdminDetails['email'];
									$toadmin=$admin_email;
									$toadminname=$resAdminDetails['name'];
									$body =str_replace(array('%Administrator%','%ClientName%','%TransactionID%','%TransactionAmt%','%TransactionDate%'),array($toadminname,$toName,$response_array[37],$response_array[9],date("d-M-Y",strtotime($payment_date))),$input);
									$headers = "MIME-Version: 1.0\n";
									$headers .= "Content-type: text/html; charset=UTF-8\n";
									$headers .= "From:".$from_name." <".$from.">\n";
									//echo $body;//exit;
									@mail($toadmin,$subject,$body,$headers);
								}
								/*Email sending end*/
								unset($_SESSION['WorkOrder']);
								?>
                           		<div class="greenText" style="padding-left:20px;">Your Transaction done successfully!!</div>
                                <div  class="formtextaddjoblong">Transaction ID:</div>
                                <div  class="textboxbillview"><?php echo $response_array[37];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextaddjoblong">Customer Name:</div>
                                <div  class="textboxbillview"><?php echo $response_array[13]." ".$response_array[14]; ?></div>
                                <div class="spacer"></div>
                                <div  class="formtextaddjoblong">Amount Paid:</div>
                                <div  class="textboxbillview">$ <?php echo $response_array[9]; ?></div>
                                 <div class="spacer"></div>
                                 <div  class="formtextaddjoblong">Transaction Date:</div>
                                <div  class="textboxbillview"><?php echo date("d-M-Y"); ?></div>
                                <div class="spacer"></div>
                                <div  class="formtextaddjoblong">&nbsp;</div>
                                <div class="textboxcBill">
                                <input type="button" class="buttonText3" value="Back" onClick="javascript:window.location.href='client-dashboard'">
                             </div>
                          	<div class="spacer"></div>
                            <?php }else{?>
                            <div class="redText" align="center">Sorry!! Your Transaction failed.</div>
                            <?php }?>
                           	</div>
                        	</div>
                            <!----- div billing end--------->
                           </form>
                           <!-----Table area end------->
                    	</div>
            	</div>
               </div>
              <!-------------Main Body--------------->
         </div>
        <div class="spacer"></div>
        <?php include_once 'footer-client.php'; ?>
  </div>
</body>
</html>