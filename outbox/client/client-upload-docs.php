<?php 
ob_start();
session_start();
ini_set('memory_limit', '-1');
ini_set('max_execution_time',1800);
include_once '../includes/class.Main.php';
//Object initialization
$dbf = new User();
//fetch from work order doc table
$workOrderDocArray = $dbf->fetchOrder("workorder_doc","workorder_id='$_REQUEST[woid]'","id");
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
                  <form name="frmUpload" id="frmUpload" action="" method="post" autocomplete="off" enctype="multipart/form-data">
                    <input type="hidden" name="wono" id="wono" value="<?php echo $_REQUEST['wono'];?>">
                    <input type="hidden" name="woid" id="woid" value="<?php echo $_REQUEST['woid'];?>">
                    <!-----address div start--------->
                     <div  class="divTechworkStatus">
                       <div class="spacer"></div>
                       <div style="padding-right:5px;">
                       <?php foreach($workOrderDocArray as $workOrderDoc){?>
                       <span class="formtext"><a href="javascript:void('0');" onClick="downLoadDocument('<?php echo $workOrderDoc['wo_document'];?>');"><?php echo $workOrderDoc['wo_document'];?></a></span>&nbsp;<span class="orangeText"><a  href="javascript:void('0');"onclick="viewDocument('<?php echo $workOrderDoc['wo_document'];?>');">[View]</a></span><br/>
                       <?php }?>
                       </div>
                      <div class="spacer" style="height:5px;"></div> 
                    </div>
                    <div class="spacer"></div>
                    <div align="center">
                     <input type="button" name="submitbtn" id="submitbtn" class="buttonText" value="Close Window" onclick="closeFancyBox();"/>
                     </div>
                    <div class="spacer"></div>
                   </form>
                   <!-----Table area end------->
                </div>
        </div>
       </div>
      <!-------------Main Body--------------->
    </div>
</div>