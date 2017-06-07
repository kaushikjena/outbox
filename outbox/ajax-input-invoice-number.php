<?php 
ob_start();
session_start();
include_once './includes/class.Main.php';
//Object initialization
$dbf = new User();
//fetch data from woroderer_notes table
$workorder_notes=$dbf->fetchSingle("workorder_notes","workorder_id='$_REQUEST[id]' AND (user_type='admin' OR user_type='tech') AND waiting_parts!=0");
//Fetch details from work_order table 
$res_wo=$dbf->fetchSingle("work_order","id='$_REQUEST[id]'");
if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='view'){
	//fetch records for invoice number entry.
	 $resArray=$dbf->fetchOrder("work_order w","(w.work_status='Invoiced' OR w.work_status='Completed') AND invoice_no_status=0","id DESC","w.id,w.wo_no,w.work_status");
	 $num =count($resArray);
?>
<div id="maindiv">
         <div  style="margin:2px;">
                <!-------------Main Body--------------->
                <div class="technicianjobboard">
            		<div class="rightcoluminner">
                        <div class="headerbg">Input Invoice# Given By Client &nbsp; &nbsp; (<?php echo $num;?> Orders)</div>
                        <div class="spacer"></div>
                        <div id="contenttable" style="max-height:500px; overflow-y:scroll;">
                        	<!-----Table area start------->
							 	<?php
								if($num >0){
                                ?>
                                <table id="no-more-tables">
                                	<thead>
                                        <tr>
                                            <th width="30%">WO#</th>
                                            <th width="30%">OrderStatus</th>
                                            <th width="40%">Invoice#</th>
                                        </tr>
                                    </thead>
                                    <tbody>	
								    <?php
									//loop start
									foreach($resArray as $key=>$res_JobBoard) { 
									?>
                                 	<tr>
                                        <td data-title="WO#"><?php echo $res_JobBoard['wo_no'];?> <input type="hidden" name="chkOrder[]" id="chkOrder" value="<?php echo $res_JobBoard['id'];?>"/></td>
                                        <td data-title="OrderStatus"><?php echo $res_JobBoard['work_status'];?></td>
                                        <td data-title="Invoice#"><input type="text" name="InvoiceNumber<?php echo $res_JobBoard['id'];?>" id="InvoiceNumber<?php echo $res_JobBoard['id'];?>" class="textboxjob"/></td>
                               		</tr>
                               <?php }?>
                        	  	</tbody>
                            </table>
                             <!-----Table area end------->
                            <div class="spacer"></div>
                            <div align="center">
                                <input type="button" name="submitbtn" id="submitbtn" class="buttonText" value="Submit Invoice#" onclick="update_invoice_number();"/>
                            </div>
                            <?php }else{?><div class="noRecords" align="center">Sorry!!! No Orders are ready for change Invoiced.</div><?php }?>
                             <div class="spacer"></div>
                    	</div>
            		</div>
               </div>
              <!-------------Main Body--------------->
         </div>
  </div>
<?php
}elseif(isset($_REQUEST['choice']) && $_REQUEST['choice']=='update_invoice_number'){
	 ###########update invoice no in work order table#############
	 $finalorderArray = $_REQUEST['finalorderArray'];
	 if(!empty($finalorderArray)){
		 foreach($finalorderArray as $val){
			$valueArr = explode("==",$val);
			$woid = $valueArr[0]; $invoiceno = $valueArr[1];
			$dbf->updateTable("work_order","invoice_no='$invoiceno',invoice_no_update_date=now(),invoice_no_status=1","id='$woid'"); 
		 }
		 print "1";exit;
	 }else{
		 print "2";exit;
	 }
	 ###########update invoice no in work order table#############
}
?>