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
	$x = 0;
	if($_REQUEST['action']=='search' || $_GET["page"]){
	   $x=1; 
	}
?>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />
<link rel="stylesheet" href="css/table.css" type="text/css" />
<script type="text/javascript">
function Search_Records(){
	$("#SrchFrm").attr("action","admin_technician_ws_report");
	$("#SrchFrm").submit();
}
function ClearFields(){
	$('#srchTechnician').val("");
	$('#Delivrywo').val("");
	$('#FromDate').val("");
	$('#ToDate').val("");
	$('#hidaction').val("");
	//document.SrchFrm.submit();
	/*below line added to refreash page as to prevent url 
	mismatch problem in search using pagination.*/
	window.location.href="admin_technician_ws_report";
}
//for exporting,print,pdf,word
function print_doc(val,page){
 if(val=='word'){
	$("#SrchFrm").attr("action","admin_technician_ws_report_word");
	$("#SrchFrm").submit();
 }else if(val=='excell'){
	$("#SrchFrm").attr("action","admin_technician_ws_report_excell");
	$("#SrchFrm").submit(); 
 }else if(val=='pdf'){
	$("#SrchFrm").attr("action","admin_technician_ws_report_pdf");
	$("#SrchFrm").submit(); 
 }else if(val=='print'){
	$("#SrchFrm").attr("action","admin_technician_ws_report_print?page="+page);
	$("#SrchFrm").attr("target","_blank");
	$("#SrchFrm").submit();
 }
}
//for work status details
function admin_technician_ws_details(wo_no){
	window.location.href="admin_technician_ws_details?wo_no="+wo_no;
}
</script>
<body>
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
                        <div class="headerbg">
                            <div style="float:left;;">Technician Workstaus Report</div>
                            <div style="float:right;">
                            <a href="javascript:void(0);" onClick="print_doc('word');"><img src="images/word2007.png" style="width:20px; height:20px;" title="Export to Word"/></a>
                            <a href="javascript:void(0);" onClick="print_doc('pdf');"><img src="images/pdf.png" style="width:20px; height:20px;" title="Export to PDF"/></a>
                            <a href="javascript:void(0);" onClick="print_doc('excell');"><img src="images/export_excel.png" style="width:20px; height:20px;" title="Export to Excel"></a>
                            <a href="javascript:void(0);"  onClick="print_doc('print','<?php echo (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);?>');" ><img src="images/print.png" alt="" style="width:20px; height:20px;" title="Print"></a>
                            </div>
                        </div>
                        <div id="contenttable">
                        	<div style="width:100%;float:left;">
                            <form name="SrchFrm" id="SrchFrm" action="" method="post">
                              <div style="margin-bottom:5px;" align="center">
                                <div  class="formtextaddsrch" align="center">Technician</div>
                                <div class="textboxcsrch">
                                 <select name="srchTechnician" id="srchTechnician" class="selectboxsrch">
                                    <option value="">--Select Tech--</option>
                                    <?php foreach($dbf->fetch("technicians","id>0 ORDER BY first_name ASC")as $tech){?>
                                    <option value="<?php echo $tech['id']?>" <?php if($tech['id']==$_REQUEST['srchTechnician']){echo 'selected';}?>><?php echo $tech['first_name'].'&nbsp;'.$tech['middle_name'].'&nbsp;'.$tech['last_name'];?></option>
                                    <?php }?>
                                </select>
                                </div>
                                <div  class="formtextaddsrch" align="center">Work Order</div>
                                <div class="textboxcsrch">
                                <input type="text" class="textboxsrch" name="Delivrywo" id="Delivrywo" value="<?php echo $_REQUEST['Delivrywo'];?>">
                                </div>
                                <div  class="formtextaddsrch" align="center">Customer</div>
                                <div class="textboxcsrch">
                                <input type="text" class="textboxsrch" name="Customername" id="Customername" value="<?php echo $_REQUEST['Customername'];?>">
                                </div>
                                <div  class="formtextaddsrch" align="center">Order Status</div>
                                <div class="textboxcsrch">
                                <input type="text" class="textboxsrch" name="Workstatus" id="Workstatus" value="<?php echo $_REQUEST['Workstatus'];?>">
                                </div>
                                <div style="float:left;padding-left:40px;">
                                <input type="hidden" name="action"  value="search">
                                <input type="hidden" name="hidaction"  value="<?php echo $x;?>">
                                <input type="button" class="buttonText2" name="SearchRecord" id="SearchRecord" value="Filter Report" onClick="Search_Records();">
                                <input type="button" class="buttonText2" name="Reset" value="Reset Filter" onClick="ClearFields();">
                               </div>
                              </div>
                          </form>
                          <div class="spacer"></div>
                          <?php
						        $sch="";
								if($_REQUEST['srchTechnician']!=''){
									$sch=$sch."t.id = '$_REQUEST[srchTechnician]' AND ";
								}
								if($_REQUEST['Delivrywo']!=''){
									$sch=$sch."wo.wo_no = '$_REQUEST[Delivrywo]' AND ";
								}
								if($_REQUEST['Customername']!=''){
									$sch=$sch."c.name like '$_REQUEST[Customername]%' AND ";
								}
								if($_REQUEST['Workstatus']!=''){
									$sch=$sch."wo.work_status like '$_REQUEST[Workstatus]%' AND ";
								}
							   $sch=substr($sch,0,-5);
							   //echo $sch;exit;
							   if($sch!=''){
								 $cond="c.id=wo.client_id AND wo.wo_no=at.wo_no AND at.tech_id=t.id AND wo.service_id=s.id AND wo.approve_status='1' AND ".$sch;
							   }elseif($sch==''){
								 $cond="c.id=wo.client_id AND wo.wo_no=at.wo_no AND at.tech_id=t.id AND wo.service_id=s.id AND wo.approve_status='1'";
							   }
							   //print $cond;
							   //Pagination 
                                $page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
                                $page = ($page == 0 ? 1 : $page);
                                $perpage =100;//limit in each page
                                $startpoint = ($page * $perpage) - $perpage;
                                //-----------------------------------				
                                $num=$dbf->countRows("service s,clients c,technicians t,assign_tech at,work_order wo",$cond); 
								if($num>0){
							   ?>
                           <div class="table">
                            <div class="table-head">
                                <div class="column" data-label="wo" style="width:10%;">Work Order</div>
                                <div class="column" data-label="Staus"  style="width:10%;">Order Status</div>
                                <div class="column" data-label="Technician Name" style="width:15%;">Technician Name</div> 
                                <div class="column" data-label="Customer Name" style="width:15%;">Customer Name</div> 
                                <div class="column" data-label="Customer Name" style="width:15%;">Service Type</div>  
                                <div class="column" data-label="State"  style="width:10%;">Delivery City</div>
                            </div>
                           <?php
                              foreach($dbf->fetchOrder("service s,clients c,technicians t,assign_tech at,work_order wo",$cond,"wo.id DESC LIMIT $startpoint,$perpage","")as  $res_tech) {
						    ?>
                              <div class="row">
                              <div class="column" data-label="wo no"><b><?php echo $res_tech['wo_no'];?></b></div> 
                              <div class="column" data-label="work status"><b><?php if($res_tech['work_status']<>''){echo $res_tech['work_status'];}else{echo 'Not Started';}?></b></div> 
                              <div class="column" data-label="User Name"><?php echo $res_tech['first_name'].'&nbsp;'.$res_tech['middle_name'].'&nbsp;'.$res_tech['last_name'];?></div>    
                              <div class="column" data-label="date"><?php echo $res_tech['name']?></div>    
                              <div class="column" data-label="date"><?php echo $res_tech['service_name']?></div>   
                              <div class="column" data-label="Email ID"><?php echo $res_tech['city'];?></div> 
                              </div>
                              <?php } ?>
                        	</div>
                            <?php }else{?>
                              <div style="padding-left:40%;border:1px solid #000;color:#F00;">No records founds!!</div>
                            <?php }?>
                            <div  align="center"><?php if($num>0) { echo $dbf->Pages($num,$perpage,"admin_technician_ws_report?srchTechnician=$_REQUEST[srchTechnician]&Delivrywo=$_REQUEST[Delivrywo]&Customername=$_REQUEST[Customername]&Workstatus=$_REQUEST[Workstatus]&");}?></div>
                          </div>
                        </div>
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