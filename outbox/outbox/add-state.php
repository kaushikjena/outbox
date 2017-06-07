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
if($_REQUEST['action']=="insert"){//print"<pre>";print_r($_REQUEST);exit;
	    $num=$dbf->countRows("state","state_code='$_REQUEST[ProfileStateCode]' OR state_name='$_REQUEST[ProfileStateName]'");
		if($num>0){
			header("Location:add-state?msg=002");exit;
		}else{
		$string="state_code='$_REQUEST[ProfileStateCode]',state_name='$_REQUEST[ProfileStateName]',created_date=now()";
		//insert into State table
		$dbf->insertSet("state",$string);
		header("Location:manage-state");exit;
		}
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
<link rel="stylesheet" href="css/table.css" type="text/css" />
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
                        <div class="headerbg">ADD STATE</div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        	<!-----Table area start------->
                            <div  class="innertable">
                            <div align="center"><?php if($_REQUEST['msg']=="002"){?><span  style="color:red;">This State already exist!</span><?php }?></div>
                            <div class="spacer"></div>
                         	  <form action="" name="frmState" id="frmState" method="post" onSubmit="return validate_State();" enctype="multipart/form-data" autocomplete="off">
                              <input type="hidden" name="action" value="insert">
                             	<div  class="formtextadd">State Code<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="ProfileStateCode" id="ProfileStateCode"><br/><label for="ProfileStateCode" id="lblProfileStateCode" class="redText"></label>
                               </div>
                               <div class="spacer"></div>
                               <div  class="formtextadd">State Name<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="ProfileStateName" id="ProfileStateName"><br/><label for="ProfileStateName" id="lblProfileStateName" class="redText"></label>
                               </div>
                              <div class="spacer"></div>
                                 <div align="center">
                                 	<input type="submit" class="buttonText" value="Submit Form"/>
                                    <a href="manage-state" style="text-decoration:none;"><input type="button" class="buttonText3" value="Back"/></a>
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