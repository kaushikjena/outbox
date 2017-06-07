<?php 
ob_start();
session_start();
include_once '../includes/class.Main.php';
//Object initialization
$dbf = new User();
//page titlevariable
$pageTitle="Welcome To Out Of The Box";
include 'applicationtop-tech.php';
if($_SESSION['usertype']!='tech'){
	header("location:../logout");exit;
}
//get data for view
$viewTechPayhist =$dbf->fetchSingle("state st,clients c,service s,technicians t,work_order wo,work_order_tech_bill wb","wb.client_id=c.id AND wb.tech_id=t.id AND st.state_code=c.state AND wo.wo_no=wb.wo_no AND wo.service_id=s.id AND  wb.id='$_REQUEST[id]'");
?>
<link rel="stylesheet" href="../css/innermain.css" type="text/css"/>
<link rel="stylesheet" href="../css/innermedium.css" type="text/css"/>
<link rel="stylesheet" href="../css/innernarrow.css" type="text/css"/>
<link rel="stylesheet" href="../css/respmenu.css" type="text/css"/>
<link rel="stylesheet" href="../css/table.css" type="text/css"/>
<body>
    <div id="maindiv">
        <!-------------header--------------->
     	<?php include_once 'header-tech.php';?>
   		<!-------------header--------------->
        <!-------------top menu--------------->
     	<?php include_once 'tech-top-menu.php';?>
   		<!-------------top menu--------------->
         <div id="contentdiv">
                <!-------------Left menu--------------->
				<?php //include_once 'left-menu.php';?>
                <!-------------left menu--------------->
                <!-------------Main Body--------------->
                <div class="rightcolumjobboard">
            		<div class="rightcoluminner">
                        <div class="headerbg">View Technician Payments History</div>
                        <div class="spacer"></div>
                        <div id="contenttable"  style="border: 1px solid #666;">
                        <div  class="innertable">
                                <div class="spacer"></div>
                                <div  class="formtextadd">WoNo:</div>
                                <div  class="textboxview"><?php echo $viewTechPayhist['wo_no'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Customer Name:</div>
                                <div  class="textboxview"><?php echo $viewTechPayhist['name'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Customer State:</div>
                                <div  class="textboxview"><?php echo $viewTechPayhist['state_name'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Service Type:</div>
                                <div  class="textboxview"><?php echo $viewTechPayhist['service_name'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Order Status:</div>
                                <div  class="textboxview"><?php echo $viewTechPayhist['work_status'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Subtotal:</div>
                                <div  class="textboxview">$ <?php echo $viewTechPayhist['subtotal'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Payment Status:</div>
                                <div  class="textboxview"><?php echo $viewTechPayhist['payment_status'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Payment Date:</div>
                                <div  class="textboxview"><?php echo ($viewTechPayhist['payment_date'] <> '0000-00-00')?date("d-M-Y",strtotime($viewTechPayhist['payment_date'])):'0000-00-00';?></div>
                                <div class="spacer"></div>
                                <div align="center"  style="padding-right:60px;"><input type="button" class="buttonText" value="Return Back" onClick="window.location='technician-manage-payments-history'"/>
                                 </div>
                        	</div>
                            <!-----Table area start-------> 
                        	<div class="spacer"></div>
                    	</div>
            	</div>
              <!-------------Main Body--------------->
         </div>
        <div class="spacer"></div>
        <?php include_once 'footer-tech.php'; ?>
    </div>
</body>
</html>