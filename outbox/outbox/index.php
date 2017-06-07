<?php 
ob_start("ob_gzhandler");
session_start();
if(isset($_SESSION['userid']) && $_SESSION['userid'] !=''){
	header('Location:dashboard');
}
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();
//page titlevariable
$pageTitle="Welcome To Out Of The Box";
//echo base64_decode(base64_decode('VW5Jd056QTFNakF3Tmc9PQ=='));
//echo phpinfo();
$resVersion = $dbf->strRecordID("versions","name,title","id>0 ORDER BY id DESC LIMIT 1");
?>
<?php  include_once "applicationtop.php";?>
<script type="text/javascript">
$(document).ready(function(){
	$("form :input").each(function(){
	// if($(this).attr("id") !='SiteUrl'){
		  $(this).keyup(function(event){
			var xss =  $(this);
			var maintainplus = '';
			var numval = xss.val();
			curphonevar = numval.replace(/[\\!"£$%^&*+={};:'#~()¦\/<>?|`¬\]\[]/g,'');
			xss.val(maintainplus + curphonevar) ;
			var maintainplus = '';
			xss.focus;
		  });
	 //}
	});
});
</script>
<link rel="stylesheet" href="css/main.css" type="text/css" />
<link rel="stylesheet" href="css/medium.css" type="text/css" />
<link rel="stylesheet" href="css/narrow.css" type="text/css" />
<link rel="stylesheet" href="css/narrower.css" type="text/css" />
<body>
<div>
	<div>
	   <div class="logo"><img src="images/logo1.png" /></div>
       <span class="version-txt" title="<?php echo $resVersion['title'];?>"><?php echo $resVersion['name'];?></span>
	</div>
	<div class="spacer"></div>
     <form action="login-process.php" method="post" name="frmLogin" id="frmLogin" onSubmit="return validate_login();" autocomplete="off">
      <input type="hidden" name="action" value="login"/>
        <div class="logbg">
          <div class="logleft"><img src="images/logboxbg.png" /></div>
          <div class="logright">
          	<?php if($_REQUEST['msg']=='001'){ ?><p class="redText">Invalid Email ID or Password !</p><?php }?>
            <?php if($_REQUEST['msg']=='002'){ ?><p class="redText">Your Account is blocked!</p><?php }?>
            <div class="idtext">Email ID</div>
            <div class="tfcon">
              <input type="text" class="logintxtbox" name="EmailLogin" id="EmailLogin"><br/>
              <label for="EmailLogin" id="lblEmailLogin" class="redText"></label>
            </div>
            <div class="spacer"></div><div class="spacer"></div>
            <div class="idtext">Password</div>
            <div class="tfcon">
              <input type="password" class="logintxtbox" name="PasswordLogin" id="PasswordLogin"><br/>
              <label for="PasswordLogin" id="lblPasswordLogin" class="redText"></label>
            </div>
            <div class="subcon">
              <div class="spacer"></div>
              <div class="pswtext"><a href="forgot-password">Forgot password?</a></div>
              <div class="subbtn"><input type="image" src="images/submit.png" /></div>
            </div>
            <div class="spacer"></div>
            <div class="spacer" style="padding-top:20px;"></div>
          </div>
      </div>
  </form>
<div class="spacer"></div>
<?php include_once 'footer.php'; ?>
</div>
</body>
</html>
