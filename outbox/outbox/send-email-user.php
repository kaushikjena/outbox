<?php 
ob_start();
session_start();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();
if(isset($_REQUEST['choice']) && $_REQUEST['choice']=="show_email"){ 
$clientid= $dbf->getDataFromTable("work_order","client_id","id='$_REQUEST[woid]'");
?>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />
<link rel="stylesheet" href="css/tablejob.css" type="text/css" />
<!--wysiwyg editor-->
<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
<!-- <script type="text/javascript" src="ckeditor/sample.js"></script>-->
<link rel="stylesheet" type="text/css" href="ckeditor/sample.css" />
<!--wysiwyg editor-->
 <div id="maindiv">
     <div  style="margin:2px;">
          <!-------------Main Body--------------->
            <div class="technicianjobboard" style="width:700px;">
                <div class="rightcoluminner">
                    <div class="headerbg">Notification To User</div>
                    <div class="spacer"></div>
                    <div id="contenttable">
                         <div class="spacer"></div>
                         <!--------------------------------------------------------------------------------------->    
                           
                            <div class="table">
                                <div class="table-head">
                                    <div class="column" data-label="chkClient" style="width:4%;">&nbsp;</div> 
                                	<div class="column" data-label="WO NO" style="width:8%;">WO#</div>
                                    <div class="column" data-label="Customer Name"  style="width:10%;">Customer Name</div>
                                    <div class="column" data-label="Email" style="width:8%;">Email</div>  
                                </div>
                                <?php 
                                    $num=$dbf->countRows("cod_scheduled_notify","workorder_id='$_REQUEST[woid]'");
									foreach($dbf->fetch("cod_scheduled_notify","workorder_id='$_REQUEST[woid]'")as $val){
									$wo_no=$dbf->getDataFromTable("work_order","wo_no","id='$_REQUEST[woid]'");
								?>
                                <div class="row">
                                    <div class="column" data-label="checkTech">
                                     <input type="checkbox" name="chkClnt" id="chkClnt" value="" onclick="sendEmailToClient('<?php echo $val['id'];?>', this.checked);" <?php if($val['status']=='1'){echo "checked";}?>>
                                     </div>
                                    <div class="column" data-label="WO NO"><?php echo $wo_no;?></div>                                    
                                    <div class="column" data-label="Customer Name" style="color:#333; font-weight:bold;"><?php echo $val['name'];?></div>
                                    <div class="column" data-label="Email"><?php echo $val['email'];?></div>
                                </div>
                                <?php }?>
                            </div>
                              <?php  if($num ==0){?><div class="noRecords" align="center">No records founds!!</div><?php }?>
                        <!--------------------------------------------------------------------------------------->    
                        <!-----Table area start------->
                        <form action="" name="frmEmailUser" id="frmEmailUser" method="post" enctype="multipart/form-data">
                        <div  class="emailDiv">
                        <div class="spacer"></div>
                            <div  class="formtextadd">User Name:<span class="redText">*</span></div>
                            <div  class="textboxc">
                            <input type="text" class="textboxjob" name="uname" id="uname" value="">
                            <br/><label for="uname" id="lbluname" class="redText"></label>
                            </div>
                            <div class="spacer"></div>
                            <div  class="formtextadd">User Email:<span class="redText">*</span></div>
                            <div  class="textboxc">
                            <input type="text" class="textboxjob" name="uemail" id="uemail" value="">
                            <br/><label for="uemail" id="lbluemail" class="redText"></label>
                            </div>
                            <div class="spacer"></div>
                            <div align="center">
                             <input type="hidden" name="client_id" id="client_id" value="<?php echo $clientid; ?>"/>
                             <input type="hidden" name="woid" id="woid" value="<?php echo $_REQUEST['woid']; ?>"/>
                         <!--<input type="hidden" name="wono" id="wono" value="<?php //echo $_REQUEST['wono'];?>"/>-->
                             <input type="button" class="buttonText" value="save" onClick="sendEmailUser();"/></div>
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
  </div>
<?php }else if(isset($_REQUEST['choice'])&& $_REQUEST['choice']=="storedcod_email"){
	    ob_clean();//print "<pre>";print_r($_REQUEST);exit;
		$woid=addslashes($_REQUEST['woid']);
		$uname=addslashes($_REQUEST['uname']);
		$uemail=addslashes($_REQUEST['uemail']);
		$client_id=addslashes($_REQUEST['client_id']);
		
	    if($uname!="" && $uemail!=""){
			$string="workorder_id='$woid',name='$uname',email='$uemail',client_id='$client_id',created_date=now()";
			$dbf->insertSet("cod_scheduled_notify",$string);
			echo 1;exit;
		}
		/*foreach($_REQUEST['chkClnt'] as $val){
			if($val!=""){
			$dbf->updateTable("cod_scheduled_notify","status=1","id=$val");	
			}
		}*/
   }else if(isset($_REQUEST['choice'])&& $_REQUEST['choice']=="sendnotify_client"){
	     $sid=addslashes($_REQUEST['sid']);
		 $dbf->updateTable("cod_scheduled_notify","status=$_REQUEST[chk]","id=$sid");	
   }
?>
    