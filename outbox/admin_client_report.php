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
	$("#SrchFrm").attr("action", "admin_client_report");
	$("#SrchFrm").submit();
}
function ClearFields(){
	$('#Delivrycity').val("");
	$('#Delivrystate').val("");
	$('#FromDate').val("");
	$('#ToDate').val("");
	$('#hidaction').val("");
	//document.SrchFrm.submit();
	/*below line added to refreash page as to prevent url 
	mismatch problem in search using pagination.*/
	window.location.href="admin_client_report";
}
//for exporting,print,pdf,word
function print_doc(val,page){
 if(val=='word'){
	$("#SrchFrm").attr("action","admin_client_report_word");
	$("#SrchFrm").submit();
 }else if(val=='excell'){
	$("#SrchFrm").attr("action","admin_client_report_excell");
	$("#SrchFrm").submit(); 
 }else if(val=='pdf'){
	$("#SrchFrm").attr("action","admin_client_report_pdf");
	$("#SrchFrm").submit();
 }else if(val=='print'){
	$("#SrchFrm").attr("action","admin_client_report_print?page="+page);
	$("#SrchFrm").attr("target","_blank");
	$("#SrchFrm").submit(); 
 }
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
                            <div style="float:left;;">Client Report</div>
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
                                <div  class="formtextaddsrch" align="center">City</div>
                                <div class="textboxcsrch">
                                <input type="text" class="textboxsrch" name="Delivrycity" id="Delivrycity" value="<?php echo $_REQUEST['Delivrycity'];?>">
                                </div>
                                <div  class="formtextaddsrch" align="center">State</div>
                                <div class="textboxcsrch">
                                <!--<input type="text" class="textboxsrch" name="Delivrystate" id="Delivrystate" value="<?php //echo $_REQUEST['Delivrystate'];?>">-->
                                <select name="Delivrystate" id="Delivrystate" class="selectboxsrch">
                                    <option value="">--Select State--</option>
                                    <?php foreach($dbf->fetchOrder("state","","state_name asc","","")as $srcState){?>
                                    <option value="<?php echo $srcState['state_code'];?>" <?php if($srcState['state_code']==$_REQUEST['Delivrystate']){echo 'selected';}?>><?php echo $srcState['state_name']?></option>
                                    <?php }?>
                                 </select>
                                </div>
                                <div  class="formtextaddsrchsmall"align="center">From:</div>
                                <div class="textboxcsrchsmall">
                                <input type="text" class="textboxsrch datepick" name="FromDate" id="FromDate" value="<?php echo $_REQUEST['FromDate'];?>" readonly></div>
                                <div  class="formtextaddsrchsmall"align="center">To:</div>
                                <div class="textboxcsrchsmall">
                                <input type="text" class="textboxsrch datepick" name="ToDate" id="ToDate" value="<?php echo $_REQUEST['ToDate'];?>" readonly></div>
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
								$fromdt=date("Y-m-d",strtotime(($_REQUEST['FromDate'])));
								$todt=date("Y-m-d",strtotime(($_REQUEST['ToDate'])));
								
								if($_REQUEST['Delivrycity']!=''){
									$sch=$sch."c.city like '$_REQUEST[Delivrycity]%' AND ";
								}
								if($_REQUEST['Delivrystate']!=''){
									$sch=$sch."c.state='$_REQUEST[Delivrystate]' AND ";
								}
								if($_REQUEST['FromDate']!='' && $_REQUEST['ToDate']==''){
									$sch=$sch."c.created_date >= '$fromdt' AND ";
								}
								if($_REQUEST['FromDate']=='' && $_REQUEST['ToDate']!=''){
									$sch=$sch."c.created_date <= '$todt' AND ";
								}
								if(($_REQUEST['FromDate']!='') && ($_REQUEST['ToDate']!='')){
									$sch=$sch."c.created_date BETWEEN '$fromdt' AND '$todt' AND ";
								}
							   $sch=substr($sch,0,-5);
							   //echo $sch;exit;
							   if($sch!=''){
								 $cond="c.state=s.state_code AND c.status=1 AND c.user_type='client' AND ".$sch;
								  // echo $cond;exit;
							   }
							   elseif($sch==''){
								 $cond="c.state=s.state_code AND c.status=1 AND c.user_type='client'";
							   }
							   //print $cond;
							   //Pagination 
                                $page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
                                $page = ($page == 0 ? 1 : $page);
                                $perpage =100;//limit in each page
                                $startpoint = ($page * $perpage) - $perpage;
                                //-----------------------------------				
                                $num=$dbf->countRows("state s,clients c",$cond); 
								if($num>0){
							?>
                           <div class="table">
                            <div class="table-head">
                                <div class="column" data-label="User Name" style="width:20%;">Client Name</div>
                                <div class="column" data-label="Email ID"  style="width:20%;">Email ID</div>
                                <div class="column" data-label="Contact No" style="width:20%;">Contact No</div>  
                                <div class="column" data-label="City"  style="width:15%;">City</div>
                                <div class="column" data-label="State"  style="width:15%;">State</div>
                                <div class="column" data-label="State"  style="width:10%;">Date</div>
                            </div>
                           <?php
                              foreach($dbf->fetchOrder("state s,clients c",$cond,"c.id DESC LIMIT $startpoint,$perpage","")as $res_client) {?>
                              <div class="row">
                                <div class="column" data-label="User Name"><?php echo $res_client['name'];?></div>
                                <div class="column" data-label="Email ID"><?php echo $res_client['email'];?></div>
                                <div class="column" data-label="Contact No"><?php echo $res_client['phone_no'];?></div>
                                <div class="column" data-label="City"><?php echo $res_client['city'];?></div>
                                <div class="column" data-label="State"><?php echo $res_client['state_name'];?></div>  
                                <div class="column" data-label="Date"><?php echo date('d-m-Y',strtotime($res_client['created_date']));?></div>
                             </div>
                              <?php } ?>
                        	</div>
                            <?php }else{?>
                              <div style="padding-left:40%;border:1px solid #000;color:#F00;">No records founds!!</div>
                            <?php }?>
                            <div  align="center"><?php if($num>0) { echo $dbf->Pages($num,$perpage,"admin_client_report?Delivrycity=$_REQUEST[Delivrycity]&Delivrystate=$_REQUEST[Delivrystate]&FromDate=$_REQUEST[FromDate]&ToDate=$_REQUEST[ToDate]&");}?></div>
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