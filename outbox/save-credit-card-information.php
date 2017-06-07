<?php 
ob_start();
session_start();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();
if(isset($_REQUEST['choice']) && $_REQUEST['choice']=="show_credit_card_form"){ 
?>
<div id="maindiv">
     <div  style="margin:2px;">
          <!-------------Main Body--------------->
            <div class="technicianjobboard" style="width:500px;">
                <div class="rightcoluminner">
                    <div class="headerbg">Credit Card Details</div>
                    <div class="spacer"></div>
                    <div id="contenttable">
                        <!-----Table area start------->
                        <div  class="emailDiv">
                        <div class="spacer"></div>
                         <form action="" name="frmCreditCardInfo" id="frmCreditCardInfo" method="post" enctype="multipart/form-data">
                         	<div  class="formtextadd">Card Type<span class="redText">*</span></div>
                            <div  class="textboxc">
                            <input type="text" class="textboxjob" name="ctype" id="ctype"><br/><label for="fromctype" id="lblfromctype" class="redText"></label>
                            </div>
                            <div class="spacer"></div>
                            <div  class="formtextadd">Card Number<span class="redText">*</span></div>
                            <div  class="textboxc">
                             <input type="text" class="textboxjob" name="cnumber" id="cnumber" ><br/><label for="fromcnumber" id="lblfromcnumber" class="redText"></label>
                            </div>
                            <div class="spacer"></div>
                            <div  class="formtextadd">Expiration Date:<span class="redText">*</span></div>
                            <div  class="textboxc">
                            	<div style="float:left; width:49%;">
                                    <select name="ExpMonth" id="ExpMonth" class="selectboxjob" tabindex="3">
                                        <option value="">--Select Month--</option>
										<?php for($i=1;$i<13;$i++){
                                              if($i==1){$month='(01) January';}
                                              if($i==2){$month='(02) February';}
                                              if($i==3){$month='(03) March';}
                                              if($i==4){$month='(04) April';}
                                              if($i==5){$month='(05) May';}
                                              if($i==6){$month='(06) June';}
                                              if($i==7){$month='(07) July';}
                                              if($i==8){$month='(08) August';}
                                              if($i==9){$month='(09) September';}
                                              if($i==10){$month='(10) October';}
                                              if($i==11){$month='(11) November';}
                                              if($i==12){$month='(12) December';}
                                         ?>
                                         <option value="<?php echo $i;?>"><?php echo $month;?></option>
      									<?php }?>
                                    </select>
                                    </div>
                                    <div style="float:right; width:49%;">
                                     <select name="ExpYear" id="ExpYear" class="selectboxjob" tabindex="4">
                                        <option value="">--Select Year--</option>
                                       <?php for($i=2013;$i<=(2013+48);$i++){?>
                                        <option value="<?php echo $i;?>"><?php echo $i;?></option>
                                        <?php }?>
                                    </select>
                                    </div>
                                    <br/><label for="fromExpYear" id="lblExpYear" class="redText"></label>
                            </div>
                            <div class="spacer"></div>
                            <div  class="formtextadd">CCV Number:</div>
                            <div  class="textboxc">
                            <input type="text" class="textboxjob" name="ccvnumber" id="ccvnumber" >
                            </div>
                            <div class="spacer"></div>
                            <div  class="formtextadd">Card Holder Name:<span class="redText">*</span></div>
                            <div  class="textboxc">
                            <input type="text" class="textboxjob" name="choldername" id="choldername" ><br/><label for="fromcholdername" id="lblfromcholdername" class="redText"></label>
                            </div>
                            <div class="spacer"></div>
                            <div  class="formtextadd">Email Id:<span class="redText">*</span></div>
                            <div  class="textboxc">
                            <input type="text" class="textboxjob" name="cemail" id="cemail" ><br/><label for="fromcemail" id="lblfromcemail" class="redText"></label>
                            </div>
                            <div class="spacer"></div>
                            <div  class="formtextadd">Zip Code:<span class="redText">*</span></div>
                            <div  class="textboxc">
                            <input type="text" class="textboxjob" name="czipcode" id="czipcode" ><br/><label for="fromczipcode" id="lblfromczipcode" class="redText"></label>
                            </div>
                            <div class="spacer"></div>
                             <div align="center">
                             <input type="hidden" name="woid" id="woid" value="<?php echo $_REQUEST['woid']; ?>"/>
                             <input type="button" class="buttonText" value="Save" onClick="save_card_info();"/></div>
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
<?php }else if(isset($_REQUEST['choice'])&& $_REQUEST['choice']=="save_Info"){
	ob_clean();//print "<pre>";print_r($_REQUEST);exit;
	$ctype=$_REQUEST['ctype'];
	$cnumber=$_REQUEST['cnumber'];
	$ExpMonth=$_REQUEST['ExpMonth'];
	$ExpYear=$_REQUEST['ExpYear'];
	$ccvnumber=$_REQUEST['ccvnumber'];
	$choldername=$_REQUEST['choldername'];
	$czipcode=$_REQUEST['czipcode'];
	$woid=$_REQUEST['woid'];
	$cemail=$_REQUEST['cemail'];
	if(($cemail=='') || (filter_var($cemail,FILTER_VALIDATE_EMAIL)== FALSE)){
		echo 3;exit;
	}
	$client_id=$dbf->getDataFromTable("work_order","client_id","id='$woid'");
	//check duplication
	$numInfo=$dbf->countRows("cod_credit_card_info","client_id='$client_id' AND card_number='$cnumber'");
	if(!$numInfo){
		$string="workorder_id='$woid',client_id='$client_id',card_number='$cnumber',card_type='$ctype',expiry_month='$ExpMonth',expiry_year='$ExpYear',ccv_number='$ccvnumber',card_holder_name='$choldername',zip_code='$czipcode',email='$cemail',created_by='$_SESSION[userid]',user_type='$_SESSION[usertype]',created_date=now()";
		$dbf->insertSet("cod_credit_card_info",$string);
		echo 1;exit;
	}
}
##################################################################################################################
#################################TO SHOW THE CREDIT CARD DETAILS WITH COLLECTION OF AMOUNT########################
##################################################################################################################
if(isset($_REQUEST['choice']) && $_REQUEST['choice']=="credit_card_details"){ 
  $woid=$_REQUEST['woid'];
  $clientid=$_REQUEST['client_id'];
  $total_amt=$_REQUEST['total_amt'];
  $num=$dbf->countRows("cod_credit_card_info","workorder_id=$woid AND client_id=$clientid");
  $credit_details=$dbf->fetchSingle("cod_credit_card_info","workorder_id=$woid AND client_id='$clientid' ORDER BY id DESC limit 1");
  //$num=$dbf->countRows("work_order","id='$woid' AND work_status='Invoiced'");
?>
<div id="maindiv">
     <div  style="margin:2px;">
          <!-------------Main Body--------------->
            <div class="technicianjobboard" style="width:500px;">
                <div class="rightcoluminner">
                    <div class="headerbg">Credit Card Details</div>
                    <div class="spacer"></div>
                    <div id="contenttable">
                        <!-----Table area start------->
                        <?php if($num!=0){?>
                        <div  class="emailDiv">
                        <div class="spacer"></div>
                         <form action="" name="frmCreditCardDetails" id="frmCreditCardDetails" method="post" enctype="multipart/form-data">
                            <div class="formtextaddjob">Card Number :</div>
                            <div class="textboxjobview"><?php echo $credit_details['card_number'];?></div>
                            <div class="spacer"></div>
                            <div class="formtextaddjob" style="width:120px;">Card HolderName :</div>
                            <div class="textboxjobview"><?php echo $credit_details['card_holder_name'];?></div>
                            <div class="spacer"></div>
                            <div  class="formtextaddjob">Collect Amount :</div>
                            <div  class="textboxjobview">$ <?php echo number_format($total_amt,2);?></div>
                            <div class="spacer"></div>
                            <div align="center">
                              <input type="button" class="buttonText" value="Cancel" onclick="parent.jQuery.fancybox.close();"/></div>
                            </form>
                        </div>
                        <?php }else{?>
                        <div class="noRecords" style="padding-left:40%;">No records founds!!</div>
                        <?php }?>
                        <!-----Table area start-------> 
                        <div class="spacer"></div>
                    </div>
            </div>
           </div>
          <!-------------Main Body--------------->
     </div>
  </div>	
<?php }?>   