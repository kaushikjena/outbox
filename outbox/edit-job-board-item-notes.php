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
$res_editJobBoard = array(); $resTech = array();
//Fetch details from work_order table 
$res_editJobBoard=$dbf->fetchSingle("clients c,service s,work_order w","c.id=w.client_id AND w.service_id=s.id AND w.id='$_REQUEST[id]'");
/*if(empty($res_editJobBoard)){
	header("location:manage-job-board");exit;
}*/

##########Update Preparation Start ###########
if(isset($_REQUEST['action']) && $_REQUEST['action']=="update" && $_SERVER['REQUEST_METHOD']=='POST'){
	##########Update notes in workorder service table###########
	$cntwotype =$dbf->countRows("workorder_service","workorder_id='$_REQUEST[worid]'");
	for($i=1;$i<=$cntwotype;$i++){
		$hid='hid'.$i;
		$hid=$_REQUEST[$hid];
		$Worktype='Worktype'.$i;
		$Worktype=$_REQUEST[$Worktype];
		$equipname='Equipment'.$i;
		$equip=$_REQUEST[$equipname];
		$modelname='Model'.$i;
		$model=$_REQUEST[$modelname];
		$item='Itemnotes'.$i;
		$itemnotes=$_REQUEST[$item];
		if($equip<>''){
			  //update into workorder service table
			   $string2="item_notes='$itemnotes'";
			   $dbf->updateTable("workorder_service",$string2,"id='$hid'");
		}
	}
	##########Insert Into workorder service table###########
	header("Location:edit-job-board-item-notes?id=$_REQUEST[worid]&msg=001&hidk=$_REQUEST[hidk]");exit;
}
##########Update Preparation End ###########
?>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />
<style type="text/css">
	/* Easy CSS Tooltip - by Koller Juergen [www.kollermedia.at] 
	* {font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px; }*/
	a:hover {text-decoration:none;} /*BG color is a must for IE6*/
	a.tooltip span {display:none; padding:2px 3px 0px 5px; margin-left:6px; margin-top:-70px; width:280px;border-radius:5px;
	-moz-border-radius:5px;}
	a.tooltip:hover span{display:inline; position:absolute; border:3px solid  #ff9812; background:#EEEEEE; color:#000;border-radius:6px;-moz-border-radius:6px;}
</style>
<script type="text/javascript">
/*********Function to print job************/
$(document).ready(function() {
    $('input:text,textarea,select,checkbox').focus(
    function(){
        $(this).css({'background-color' : '#EDE9E4'});
    });

    $('input:text,textarea,select,checkbox').blur(
    function(){
        $(this).css({'background-color' : '#FFFFFF'});
    });
});

$(document).ready(function(){
 $("form :input").each(function(){
  //if($(this).attr("id") !='loginid'){
    $(this).keyup(function(event){
	   var xss =  $(this);
	   var maintainplus = '';
	   var numval = xss.val();
	   curphonevar = numval.replace(/[\\!"£$%^&*+={};:'~()¦<>?|`¬\]\[]/g,'');
	   xss.val(maintainplus + curphonevar) ;
	   var maintainplus = '';
	   xss.focus;
    });
  //}
 });
});
</script>
<!-- Requied for Map --->
<body onLoad="document.createJob.cmbClient.focus();">
    <div id="maindiv">
        <!-------------header--------------->
     	<?php include_once 'header.php';?>
   		<!-------------header--------------->
        <!-------------top menu--------------->
     	<?php include_once 'top-menu.php';?>
   		<!-------------top menu--------------->
         <div id="contentdiv">
                <!-------------Main Body--------------->
                <div class="rightcolumjobboard">
            		<div class="rightcoluminner">
                    <form name="createitemNotes" id="createitemNotes" action="" method="post" autocomplete="off" enctype="multipart/form-data">
                        <div class="headerbg">
                        	<div style="float:left;">EDIT ORDER ITEM NOTES</div>
                        </div>
                        <?php if($_REQUEST['src']=='unapprv'){$path="unapprove-job";}else{$path="manage-job-board?g=$_REQUEST[hidk]";} ?>
                        <?php if($_REQUEST['msg']=='001'){ ?>
							<div align="center" style="color:green;font-weight:bold;">Records updated successfully</div> 
						<?php }else{ ?>
                             <div class="spacer"></div>
                        <?php } ?>
                        <div id="contenttable">
                        <!-----Table area start------->
                        	<input type="hidden" name="action" value="update"/>
                            <input type="hidden" name="worid" id="worid" value="<?php echo $res_editJobBoard['id'];?>">
                            <input type="hidden" name="hidk" id="hidk" value="<?php echo $_REQUEST['hidk'];?>">
                            <div align="center"><?php if($_REQUEST['msg']=='002'){?><span class="redText">This Email ID already exist!</span><?php }?></div>
                        <!-----address div start--------->
                         <div class="spacer"></div>
                         <div> 
                         <div  class="formtextaddjob" style="width:50px;">WO#:</div>
                            <div  class="textboxcjob"><input type="text" class="textboxjob" name="WorkOrder" id="WorkOrder" value="<?php echo $res_editJobBoard['wo_no'];?>"  tabindex="1" readonly>
                            </div>
                         </div>
                         <div class="spacer"></div>
                            <!-----service div start--------->
                            <div  class="divService">
                              <div id="equipid">	
                              <div>
                                  <div align="center" class="jobheader clService">Work Type</div>
                                  <div align="center" class="jobheader clEquipment">Equipment</div>
                                  <div align="center" class="jobheader clModel">Model</div>
                                  <div align="center" class="jobheader clQunt">Description</div>
                                  <div style="clear:both;"></div>
                              </div>
                             <?php
							 $res_woservice = $dbf->fetch("equipment e,work_type wt,workorder_service ws","e.id=ws.equipment AND wt.id=ws.work_type AND ws.workorder_id='$_REQUEST[id]'");
							 $arrWorkservice =array();$i=1;
							 $cntwotype =$dbf->countRows("workorder_service","workorder_id='$_REQUEST[id]'");
							 //$cntwotype=floor($cntwotype/2);
							  for($j=0; $j<$cntwotype;$j++){
								  $arrWorkservice= $res_woservice[$j];
								  $total = ($arrWorkservice['quantity']*$arrWorkservice['outbox_price']);
								  $TechPrice=$arrWorkservice['tech_price'];
							 ?>
                              <div align="center" class="jobbody clService">
                              <input type="hidden" name="hid<?php echo $i;?>" value="<?php echo $arrWorkservice['id'];?>">
                              <input type="text" class="textboxjob" name="Worktype<?php echo $i;?>" id="Worktype<?php echo $i;?>" value="<?php echo $arrWorkservice['worktype'];?>" tabindex="23" readonly>
                              </div>
                              <div align="center" class="jobbody clEquipment">
                               <input type="text" class="textboxjob" name="Equipment<?php echo $i;?>" id="Equipment<?php echo $i;?>" value="<?php echo $arrWorkservice['equipment_name'];?>" tabindex="23" readonly>
                              </div>
                              <div align="center" class="jobbody clModel"><input type="text" class="textboxjob" name="Model<?php echo $i;?>" id="Model<?php echo $i;?>" value="<?php echo $arrWorkservice['model'];?>" tabindex="23" readonly></div>
                              <div align="center" class="jobbody clQunt" style="width:35%" ><textarea name="Itemnotes<?php echo $i;?>" id="Itemnotes<?php echo $i;?>"  class="textareaOrderNote" tabindex="24"><?php echo $arrWorkservice['item_notes'];?></textarea></div>
                              <div style="clear:both; height:5px;"></div>
                              <?php $i++;}?>
                              </div>
                              <div style="clear:both; height:5px;"></div>
                            </div>
                            <!-----service div end--------->
                            <div class="spacer"></div>
                            <div align="center">
                                <input type="submit" name="submitbtn" id="submitbtn" class="buttonText" value="Submit Form" tabindex="39"/>                
                                <input type="button" class="buttonText3" value="Back" tabindex="40" onClick="document.location.href='<?php echo $path;?>'"/>
                            </div>
                          	<div class="spacer"></div>
                           <!-----Table area end------->
                    	</div>
                     </form>
            		</div>
               </div>
              <!-------------Main Body--------------->
         </div>
        <div class="spacer"></div>
        <?php include_once 'footer.php'; ?>
  </div>
</body>
</html>