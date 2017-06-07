<?php 
ob_start();
session_start();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();
//page titlevariable
$pageTitle="Welcome To Out Of The Box";
include 'applicationtop.php';
if($_SESSION['userid']==''){
	header("location:logout");exit;
}
$resUser=$dbf->fetchSingle("users","id='$_REQUEST[id]'");
?>
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
				<?php //include_once 'left-menu.php';?>
                <!-------------left menu--------------->
                <!-------------Main Body--------------->
                <div class="rightcolumjobboard">
            		<div class="rightcoluminner">
                        <div class="headerbg">View User</div>
                        <div class="spacer"></div>
                        <div id="contenttable"  style="border: 1px solid #666;">
                        	<!-----Table area start------->
                            <div class="viewimage"><img src="user_photo/<?php echo $resUser['user_photo'];?>" alt="User Photo" width="90" height="100"/></div>
                        	<div  class="innertable" style="float:right;">
                            <div class="spacer"></div>
                                <div  class="formtextadd">User Name:</div>
                                <div  class="textboxview"><?php echo $resUser['name'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Email ID:</div>
                                <div  class="textboxview"><?php echo $resUser['email'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Password:</div>
                                <div  class="textboxview"><?php echo base64_decode(base64_decode($resUser['password']));?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Mobile No:</div>
                                <div  class="textboxview"><?php echo $resUser['mobile'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">City:</div>
                                <div  class="textboxview"><?php echo $resUser['city'];?></div>
                                <div class="spacer"></div>
                                <?php $res_stateUser=$dbf->getDataFromTable("state","state_name","state_code='$resUser[state]'")?>
                                <div  class="formtextadd">State:</div>
                                <div  class="textboxview"><?php echo $res_stateUser;?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Active Status:</div>
                                <div  class="textboxview"><?php if($resUser['status']=='1'){echo 'Active';}else{echo 'Inactive';}?>
                                </div>
                                 <div class="spacer"></div>
                                 <div><input type="button" class="buttonText" value="Return Back" onClick="window.location='manage-user'"/>
                                 </div>
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