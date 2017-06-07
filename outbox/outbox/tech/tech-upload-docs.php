<?php 
ob_start();
session_start();
ini_set('memory_limit', '-1');
ini_set('max_execution_time',1800);
include_once '../includes/class.Main.php';
//Object initialization
$dbf = new User();
//Technician assign Details
$res_techWorkorder=$dbf->fetchSingle("clients c,service s,work_order w","c.id=w.client_id AND w.service_id=s.id  AND w.wo_no='$_REQUEST[wono]'");
//fetch from work order doc table
$workOrderDocArray = $dbf->fetchOrder("workorder_doc","workorder_id='$_REQUEST[woid]'","id");
?>
<div id="maindiv">
    <div  style="margin:2px;">
        <!-------------Main Body--------------->
        <div class="technicianworkboard">
            <div class="rightcoluminner">
                <div class="headerbg">Upload and View Work Order Document</div>
                <div class="spacer"></div>
                <div id="contenttable">
                <!-----Table area start------->
                  <form name="frmTechStatus" id="frmTechStatus" action="tech-workorder-status" method="post" onSubmit="return validate_techStatus();" autocomplete="off" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="insert"/>
                    <input type="hidden" name="wono" value="<?php echo $_REQUEST['wono'];?>">
                    <input type="hidden" name="woid" value="<?php echo $_REQUEST['woid'];?>">
                    <!-----address div start--------->
                     <div  class="divTechworkStatus">
                      <div class="spacer" style="height:5px;"></div>
                      <style type="text/css">
						.files { background-color: #eee; width:99%; height:30px; float:left; margin: 0 5px 5px 0; padding:2px; font-size:10px;}
						.progress { width: 0%; background-color:#03C; height:4px; margin-top:5px;}
						</style>
						<script src="../js/mfupload.js" type="text/javascript"></script>
						  <script type="text/javascript">
							$().ready(function() {
								var errors="";									
								$('#upload').mfupload({										
									type		: '',	//all types
									maxsize		: 128,
									post_upload	: "tech_work_status_ajax_upload.php?woid=<?php echo $_REQUEST['woid'];?>&wono=<?php echo $_REQUEST['wono'];?>",
									folder		: "../workorder_doc/",
									ini_text	: "Drag your file to here or Click here (max: 128MB each)",
									over_text	: "Drop Here",
									over_col	: 'white',
									over_bkcol	: 'green',										
									init		: function(){
										//$("#uploaded").empty();
									},
									
									start		: function(result){			
										$("#uploaded").append("<div class='files'>"+result.filename+"<div id='PRO"+result.fileno+"' class='progress'></div></div>");	
									},
							
									loaded		: function(result){
										//alert(result.fileno);	
										$("#PRO"+result.fileno).remove();
										$("#FILE"+result.fileno).html("Uploaded: "+result.filename+" ("+result.size+")");			
									},
							
									progress	: function(result){
										$("#PRO"+result.fileno).css("width", result.perc+"%");
									},
							
									error		: function(error){
										errors += error.filename+": "+error.err_des+"\n";
									},
							
									completed	: function(){											
										if (errors != "") {
											alert(errors);
											errors = "";
										}
									}
								});   	
							})
							</script>
                      <div  class="formtextaddjoblarge">Upload Doc:</div>
                       <div  class="textboxcjob">
                         <div style="position:relative; height:130px;">
                            <div id="upload" style = "border:2px dashed #ddd; width:100px; height:100px; position:absolute; top:10; left:40px;"></div>
                            <div id="uploaded" style = "border: 1px solid #ddd; width:380px; height:102px; position:absolute; top:10; left:166px; overflow-y:auto;">
                            </div>
                        </div>
                       </div>
                       <div class="spacer"></div>
                       <div style="padding-right:5px;">
                       <?php foreach($workOrderDocArray as $workOrderDoc){?>
                       <span class="formtext"><a href="javascript:void(0);" onClick="downLoadDocument('<?php echo $workOrderDoc['wo_document'];?>');"><?php echo $workOrderDoc['wo_document'];?></a></span>&nbsp;<span class="orangeText"><a  href="javascript:void(0);"onclick="viewDocument('<?php echo $workOrderDoc['wo_document'];?>','<?php echo $_REQUEST['wono'];?>','<?php echo $_REQUEST['woid'];?>');">[view]</a></span><br/>
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