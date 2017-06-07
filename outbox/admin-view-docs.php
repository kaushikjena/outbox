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
                <div class="headerbg">View Work Order Document</div>
                <div class="spacer"></div>
                <div id="contenttable">
                <!-----Table area start------->
                    <!-----address div start--------->
                     <div  class="divTechworkStatus">
                       <div class="spacer"></div>
                       <div style="padding-right:5px;">
                       <?php 
						$extention=substr($_REQUEST['fname'], strrpos($_REQUEST['fname'], '.')+1);
						if($extention =="doc" || $extention =="docx" || $extention =="xls" || $extention =="xlsx"){
							$src = "https://docs.google.com/viewer?url=".urlencode($baseUrl."workorder_doc/".$_REQUEST['fname'])."&embedded=true";
						}else{
							$src = $baseUrl."workorder_doc/".$_REQUEST['fname'];
						}
					   ?>
                       <iframe src="<?php echo $src;?>" style="width:100%;height:auto;min-height:290px;" frameborder="0"></iframe>
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