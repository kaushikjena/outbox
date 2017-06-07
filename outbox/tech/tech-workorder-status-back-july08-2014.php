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
$workorderdoc = $dbf->getDataFromTable("workorder_doc","wo_document","workorder_id='$_REQUEST[woid]'");
//insert into work order tech table
if($_REQUEST['action']=="insert"){
	$woid = $_REQUEST['woid'];
	 $arrivalDate=date("Y-m-d",strtotime($_REQUEST['ArrivalDate']));
	//update work_order table
	 $string="work_status='$_REQUEST[cmbStatus]'";
	 $dbf->updateTable("work_order",$string,"wo_no='$_REQUEST[wono]'");
	 
	 $techNotes= mysql_real_escape_string($_REQUEST['techNotes']);
	 //insert into work_order_tech table
	 $string1="tech_id='$_SESSION[userid]',wo_no='$_REQUEST[wono]', arrival_date='$arrivalDate', arrival_time='$_REQUEST[ArrivalTime]', depart_time='$_REQUEST[DepartTime]' ,notes='$techNotes', work_status='$_REQUEST[cmbStatus]',created_date=now()";
	 $dbf->insertSet("work_order_tech",$string1);
		###########Update work order doc table#############
		if($_FILES['fileUpload']['name']<>''){
			$file_name=$_REQUEST['wono'].'_'.$_FILES['fileUpload']['name'];
			$path="../workorder_doc/";
			$docimg=$dbf->getDataFromTable("workorder_doc","wo_document","workorder_id='$woid'");
			if($docimg<>''){
				unlink($path.$docimg);
				move_uploaded_file($_FILES['fileUpload']['tmp_name'],$path.$file_name);
				//update workorder doc table
				$string3="wo_document='$file_name'";
				$dbf->updateTable("workorder_doc",$string3,"workorder_id='$woid'");
				
			}else{
				move_uploaded_file($_FILES['fileUpload']['tmp_name'],$path.$file_name);
				//insert into workorder doc table
				$string3="workorder_id=$woid, wo_document='$file_name',created_date=now()";
				$dbf->insertSet("workorder_doc",$string3);
			}
		}
		###########Update Into work order doc table#############
	 header("Location:tech-manage-job-board");exit;
}
?>
<script type="text/javascript">
    $(function() {
        $( ".datepick").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-M-yy',
            yearRange: '-80:+20'
        });
    });
	$(function() {
		$( ".datetime").timepicker({
			ampm:true
		});
	});
    <!--END UI JQUERY DATE TIME PICKER-->
</script>
<div id="maindiv">
    <div  style="margin:2px;">
        <!-------------Main Body--------------->
        <div class="technicianworkboard">
            <div class="rightcoluminner">
                <div class="headerbg">Technician WorkStatus</div>
                <div class="spacer"></div>
                <div id="contenttable">
                <!-----Table area start------->
                  <form name="frmTechStatus" id="frmTechStatus" action="tech-workorder-status" method="post" onSubmit="return validate_techStatus();" autocomplete="off" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="insert"/>
                    <input type="hidden" name="wono" value="<?php echo $_REQUEST['wono'];?>">
                    <input type="hidden" name="woid" value="<?php echo $_REQUEST['woid'];?>">
                    <!-----address div start--------->
                     <div  class="divTechworkStatus">
                        <div class="greenText" align="left">Work Order Details</div>
                        <div  class="formtextaddjoblarge">WO#:</div>
                        <div  class="textboxcjob">
                           <input type="text" class="textboxjob" name="WorkOrder" id="WorkOrder" value="<?php echo $res_techWorkorder['wo_no'];?>" readonly>
                        </div>
                        <div  class="formtextaddjoblong">Service Name:</div>
                        <div  class="textboxcjob">
                        <select name="cmbService" id="cmbService" class="selectboxjob" disabled>
                            <option value="">--Select Service Type--</option>
                            <?php foreach($dbf->fetch("service","id")as $service){?>
                            <option value="<?php echo $service['id'];?>" <?php if($service['id']==$res_techWorkorder['service_id']){echo 'selected';}?>><?php echo $service['service_name'];?></option>
                            <?php }?>
                        </select><br/><label for="cmbService" id="lblcmbService" class="redText"></label> 
                        </div>
                        <div class="spacer"></div>
                        
                        <div  class="formtextaddjoblarge">Work Status:<span class="redText">*</span></div>
                        <div  class="textboxcjob">
                        <select name="cmbStatus" id="cmbStatus"class="selectboxjob">
                            <option value="">--Select Status--</option>
                            <option value="Dispatched">Dispatched</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Completed">Completed</option>
                            <option value="Invoiced">Invoiced</option>
                        </select><br/><label for="cmbStatus" id="lblcmbStatus" class="redText"></label>
                        </div>
                        <div  class="formtextaddjoblong">Arrival Date:<span class="redText">*</span></div>
                        <div  class="textboxcjob"><input type="text" class="textboxjob" name="ArrivalDate" id="ArrivalDate" value="<?php echo date("d-M-Y");?>"  readonly><br/><label for="ArrivalDate" id="lblArrivalDate" class="redText"></label></div>
                        <div class="spacer"></div>
                        <div  class="formtextaddjoblarge">Arrival Time:<span class="redText">*</span></div>
                        <div  class="textboxcjob"><input type="text" class="textboxjob" name="ArrivalTime" id="ArrivalTime"  value="<?php echo date("h:i a");?>" readonly><br/><label for="ArrivalTime" id="lblArrivalTime" class="redText"></label></div>
                       <div  class="formtextaddjoblong">Depart Time:<span class="redText">*</span></div>
                       <div  class="textboxcjob"><input type="text" class="textboxjob datetime" name="DepartTime" id="DepartTime"  readonly><br/><label for="DepartTime" id="lblDepartTime" class="redText"></label></div>                    
                       <div class="spacer"></div>
                       <div class="spacer" style="height:5px;"></div>
                       <div class="greenText" align="left">Work Status Comments:</div>
                       <div><textarea name="techNotes" id="techNotes" class="textareaOrder"></textarea><br/><label for="techNotes" id="lbltechNotes" class="redText"></label></div>
                      <div class="spacer" style="height:5px;"></div>
                      <div  class="formtextaddjoblarge">Upload Doc:</div>
                       <div  class="textboxcjob"><input type="file" name="fileUpload" id="fileUpload"/></div>
                       <div style="float:right; padding-right:5px;"><span class="formtext"><a href="javascript:void(0);" onClick="downLoadDocument('<?php echo $workorderdoc;?>');"><?php echo $workorderdoc;?></a></span></div>
                      <div class="spacer" style="height:5px;"></div> 
                    </div>
                    <div class="spacer"></div>
                    <div align="center">
                        <input type="submit" name="submitbtn" id="submitbtn" class="buttonText" value="Submit Form"/>
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