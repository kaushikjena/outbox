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
//Fetch Single Row from email_template Table
$res_email=$dbf->fetchSingle("email_template","id='$_REQUEST[id]'");
//update registration_email table
if($_REQUEST['action']=='update')
{
 	$string="from_email='$_POST[email]', from_name='$_POST[name]', subject='$_POST[subject]', message='$_POST[message]', created_date=now()";
	$dbf->updateTable("email_template",$string,"id='$_REQUEST[hidid]'");
	header("Location:email-template?id=$_REQUEST[hidid]&msg=002");
}
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
                        <div class="headerbg">EDIT EMAIL TEMPLATE</div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        	<!-----Table area start------->
                        	<div  class="emailDiv">
                            <div align="center"><?php if($_REQUEST['msg']=="002"){?><span class="greenText">Record has been updated successfully.</span><?php } ?></div>
                            <div class="spacer"></div>
                         	 <form action="" name="frmEmail" id="frmEmail" method="post" enctype="multipart/form-data">
                     			<input type="hidden" name="action" value="update">
                     			<input type="hidden" name="hidid" id="hidid" value="<?php echo $res_email['id'];?>"/>
                                <div  class="formtextadd">From Email:<span class="redText">*</span></div>
                                <div  class="textboxc">
                    			<input type="text" class="textbox" name="email" id="email" value="<?php echo $res_email['from_email'];?>"><br/><label for="ProfileUserName" id="lblProfileUserName" class="redText"></label>
                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">From Name:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                   <input type="text" class="textbox" name="name" id="name" value="<?php echo $res_email['from_name'];?>"><br/><label for="ProfileEmailID" id="lblProfileEmailID" class="redText"></label>                                </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Subject:<span class="redText">*</span></div>
                                <div  class="textboxc">
                                    <input type="text" class="textbox" name="subject" id="subject" value="<?php echo $res_email['subject'];?>"><br/><label for="ProfilePassword" id="lblProfilePassword" class="redText"></label>          					    </div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Message :<span class="redText">*</span></div>
                                <div  class="textboxc" style="width:500px; color:#093;">&nbsp;(You can change the contents except the variable name within %% symbol.)</div>
                                <div class="spacer"></div>
                                <div>
                                <textarea name="message"  id="message"><?php echo $res_email['message'];?></textarea>
                                <script type="text/javascript">
                                    CKEDITOR.replace( 'message', {
                                       extraPlugins : 'autogrow',
                                        autoGrow_maxHeight : 400
                                        //height :300,
                                        //width : 800
                                    });
                                </script>
                                </div>
                                <div class="spacer"></div>
                                 <div align="center"><input type="submit" class="buttonText" value="Submit Form"/></div>
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