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
?>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />
<link rel="stylesheet" href="css/table.css" type="text/css" />
<script type="text/javascript">
//for exporting,print,pdf,word
function print_doc(val,page){
 if(val=='word'){
	 document.SrchFrm.action="admin_technician_ws_details_word";
	 document.SrchFrm.submit();
 }else if(val=='excell'){
	 document.SrchFrm.action="admin_technician_ws_details_excell";
	 document.SrchFrm.submit(); 
 }else if(val=='pdf'){
	 document.SrchFrm.action="admin_technician_ws_details_pdf";
	 document.SrchFrm.submit(); 
 }else if(val=='print'){
	document.SrchFrm.action="admin_technician_ws_details_print";
	document.SrchFrm.target="_blank";
	document.SrchFrm.submit(); 
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
                            <div style="float:left;;">Technician Work Status Detail</div>
                            <div style="float:right;">
                            <a href="javascript:void(0);" onClick="print_doc('word');"><img src="images/word2007.png" style="width:20px; height:20px;" title="Export to Word"/></a>
                            <a href="javascript:void(0);" onClick="print_doc('pdf');"><img src="images/pdf.png" style="width:20px; height:20px;" title="Export to PDF"/></a>
                            <a href="javascript:void(0);" onClick="print_doc('excell');"><img src="images/export_excel.png" style="width:20px; height:20px;" title="Export to Excel"></a>
                            <a href="javascript:void(0);"  onClick="print_doc('print','<?php echo (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);?>');" ><img src="images/print.png" alt="" style="width:20px; height:20px;" title="Print"></a>
                            </div>
                        </div>
                        <div id="contenttable">
                        <div class="spacer"></div>
                          <?php			
							$num=$dbf->countRows("work_order_tech"," wo_no='$_REQUEST[wo_no]'"); 
							if($num>0){
							?>
                           <div class="table">
                            <div class="table-head">
                                <div class="column" data-label="Staus"  style="width:10%;">Order Status</div>
                                <div class="column" data-label="Technician Name" style="width:15%;">Arrival Date</div> 
                                <div class="column" data-label="Customer Name" style="width:15%;">Arrival Time</div> 
                                <div class="column" data-label="Customer Name" style="width:15%;">Departure Time</div> 
                                <div class="column" data-label="Customer Name" style="width:10%;">Duration</div> 
                                <div class="column" data-label="State"  style="width:10%;">Price</div>
                            </div>
                           <?php
                            foreach($dbf->fetchOrder("work_order_tech"," wo_no='$_REQUEST[wo_no]'","id ASC","")as $res_tech) { ?>
                             <div class="row">
                              <div class="column" data-label="work status"><?php echo $res_tech['work_status'];?></div>
                              <div class="column" data-label="date"><?php echo $res_tech['arrival_date'];?></div>    
                              <div class="column" data-label="arrival time"><?php echo $res_tech['arrival_time'];?></div>   
                              <div class="column" data-label="departure time"><?php echo $res_tech['depart_time'];?></div>  
                              <?php $tm_diff=gmdate('H:i:s',strtotime($res_tech['depart_time'])-strtotime($res_tech['arrival_time'])); ?>  
                              <div class="column" data-label="duration"><?php echo $tm_diff;?></div> 
                              <div class="column" data-label="price"><?php echo $res_tech['price'];?></div> 
                             </div>
                              <?php } ?>
                        	</div>
                            <?php }else{?>
                              <div style="padding-left:40%;border:1px solid #000;color:#F00;">No records founds!!</div>
                            <?php }?>
                              <div class="spacer"></div>
                              <div class="spacer"></div>
                              <div style="padding-left:40%;color:#F00;"><input type="button" class="buttonText2" value="    Back    " onClick="javascript:window.location.href='admin_technician_ws_report'"/></div>
                          
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