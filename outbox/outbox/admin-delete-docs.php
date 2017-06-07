<?php 
ob_start();
session_start();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();
if($_SERVER['HTTP_HOST'] == "box-ware.com"){
	$baseUrl="https://".$_SERVER['HTTP_HOST']."/sys/outbox/";//Creating Base Url for SERVER
}elseif($_SERVER['HTTP_HOST'] == "bletprojects.com"){
	$baseUrl="http://".$_SERVER['HTTP_HOST']."/outbox/";//Creating Base Url for SERVER
}else{
	$baseUrl="http://192.168.0.114/outbox/";//Creating Base Url for local
	//$baseUrl=$_SERVER['DOCUMENT_ROOT']."/outbox/";//Creating Base Url for local
}
//echo $baseUrl;
//print "<pre>";
?>
<div id="maindiv">
    <div  style="margin:2px;">
        <!-------------Main Body--------------->
        <div class="technicianworkboard">
            <div class="rightcoluminner">
                <div class="headerbg">Delete Work Order Document</div>
                <div class="spacer"></div>
                <div id="contenttable">
                <!-----Table area start------->
                    <!-----address div start--------->
                     <div  class="divTechworkStatus">
                       <div class="spacer"></div>
                       <div style="padding-right:5px;">
                       <?php 
					   if($_REQUEST['fname']){
						   $woid =	$_REQUEST['woid'];
						   $file_name = $_REQUEST['fname'];
						   $path="workorder_doc/";
						   if(unlink($path.$file_name)){
							   //delete row workorder doc table
								$string="workorder_id='$woid' AND  wo_document='$file_name'";
								$dbf->deleteFromTable("workorder_doc",$string);
								###########Track user activity in work order notes table#############
								$adminNotes="A document is deleted from this order.";
								$strnotes="workorder_id='$woid', user_type='$_SESSION[usertype]', user_id='$_SESSION[userid]', wo_notes='$adminNotes',created_date=now()";
								$dbf->insertSet("workorder_notes",$strnotes);
								###########Track user activity in work order notes table#############
								$msg = "This document deleted successfully.";
						   }else{
							    $msg = "This document deleted successfully.";
						   }
					   }
					   ?>
                       	<div class="greenText" align="center"><?php echo $msg;?></div>
                       </div>
                      <div class="spacer" style="height:5px;"></div> 
                    </div>
                    <div class="spacer"></div>
                    <div align="center">
                     <input type="button"class="buttonText" value="Return Back" onclick="returnBack();"/>
                     </div>
                    <div class="spacer"></div>
                   <!-----Table area end------->
                </div>
        </div>
       </div>
      <!-------------Main Body--------------->
    </div>
</div>