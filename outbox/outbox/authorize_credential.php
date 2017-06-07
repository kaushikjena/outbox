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
$res_editAuth=$dbf->fetchSingle("authorize_apicredential","");
if($_REQUEST['action']=="update"){
	    $testmode=implode(",",$_REQUEST['test']);
	    $num=$dbf->countRows("authorize_apicredential","");
		if($num>0){
			$string="api_loginid='$_REQUEST[api_logid]',transaction_key='$_REQUEST[tran_key]',test_mode='$testmode'";
			//update into alert_assignment table
			$dbf->updateTable("authorize_apicredential",$string,"id='$_REQUEST[hid]'");
		}else{
			$string="api_loginid='$_REQUEST[api_logid]',transaction_key='$_REQUEST[tran_key]',test_mode='$_REQUEST[test]'";
			//update into alert_assignment table
			$dbf->insertSet("authorize_apicredential",$string);
		}
		header("Location:authorize_credential");exit;
}
?>
<script type="text/javascript">
$(document).ready(function(){
	$("form :input").each(function(){
	 if($(this).attr("id") !='SiteUrl'){
		  $(this).keyup(function(event){
			var xss =  $(this);
			var maintainplus = '';
			var numval = xss.val();
			curphonevar = numval.replace(/[\\!"£$%^&*+_={};:'#~()¦\/<>?|`¬\]\[]/g,'');
			xss.val(maintainplus + curphonevar) ;
			var maintainplus = '';
			xss.focus;
		  });
	 }
	});
});
</script>
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
				<?php include_once 'left-menu.php';?>
                <!-------------left menu--------------->
                <!-------------Main Body--------------->
                <div class="rightcolum">
            		<div class="rightcoluminner">
                        <div class="headerbg">Authorize.net Credentials</div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        	<!-----Table area start------->
                            <div  class="innertable">
                            <div align="center"></div>
                            <div class="spacer"></div>
                         	  <form action="" name="frmAuthorize" id="frmAuthorize" method="post" onSubmit="return validate_editAuthorize();" autocomplete="off">
                              <input type="hidden" name="action" value="update">
                              <input type="hidden" name="hid" value="<?php echo $res_editAuth['id'];?>">
                                <div  class="formtextadd">API Login ID<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="api_logid" id="api_logid" value="<?php echo $res_editAuth['api_loginid'];?>"><br/><label for="api_logid" id="lblapi_logid" class="redText"></label>
                               </div>
                              <div class="spacer"></div>
                              <div  class="formtextadd">Transaction Key<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="tran_key" id="tran_key" value="<?php echo $res_editAuth['transaction_key'];?>"><br/><label for="tran_key" id="lbltran_key" class="redText"></label>
                               </div>
                               <div class="spacer"></div>
                               <div  class="formtextadd">Testing Mode<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="radio" name="test[]" id="test" value="0" <?php if($res_editAuth[	'test_mode']=='0'){echo 'checked';}?>> Test&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="radio"  name="test[]" id="test" value="1" <?php if($res_editAuth['test_mode']=='1'){echo 'checked';}?>>Live<br/><label for="test" id="lbltest" class="redText"></label>
                               </div>
                               <div class="spacer"></div>
                                 <div align="center">
                                 	<input type="submit" class="buttonText" value="Submit Form"/>
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
</body>
</html>