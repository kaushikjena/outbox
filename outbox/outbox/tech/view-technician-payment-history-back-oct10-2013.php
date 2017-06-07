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
$viewTechPayhist=$dbf->fetchSingle("work_order_tech_bill wb,technicians t,state st","wb.tech_id=t.id AND st.state_code=t.state AND wb.id='$_REQUEST[id]'");
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
                                <div  class="formtextadd">Technician Name:</div>
                                <div  class="textboxview"><?php echo $viewTechPayhist['first_name'].''.$viewTechPayhist['middle_name'].''.$viewTechPayhist['last_name'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Technician Email:</div>
                                <div  class="textboxview"><?php echo $viewTechPayhist['email'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Technician Phone:</div>
                                <div  class="textboxview"><?php echo $viewTechPayhist['contact_phone'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Technician State:</div>
                                <div  class="textboxview"><?php echo $viewTechPayhist['state_name'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">WoNo:</div>
                                <div  class="textboxview"><?php echo $viewTechPayhist['wo_no'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Subtotal:</div>
                                <div  class="textboxview"><?php echo $viewTechPayhist['subtotal'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Payment Status:</div>
                                <div  class="textboxview"><?php echo $viewTechPayhist['payment_status'];?></div>
                                <div class="spacer"></div>
                                <div  class="formtextadd">Payment Date:</div>
                                <div  class="textboxview"><?php echo date("d-M-Y",strtotime($viewTechPayhist['payment_date']));?></div>
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