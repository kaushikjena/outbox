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
	//record for ready to invoice
	 $resArray=$dbf->fetchOrder("work_order w","w.work_status='Ready to Invoice'","id DESC","w.id,w.wo_no,w.work_status");
     $num =count($resArray);
?>
<div id="maindiv">
         <div  style="margin:2px;">
                <!-------------Main Body--------------->
                <div class="technicianjobboard">
            		<div class="rightcoluminner">
                        <div class="headerbg">Ready to Invoice Orders &nbsp; &nbsp; (<?php echo $num;?> Orders)</div>
                        <div class="spacer"></div>
                        <div id="contenttable" style="max-height:500px; overflow-y:scroll;">
                        	<!-----Table area start------->
							 	<?php
								if($num >0){
                                ?>
                                <table id="no-more-tables">
                                	<thead>
                                        <tr>
                                            <th width="20%"><input type="checkbox" name="chkAll" id="chkAll" value="1" onClick="check_all();"/> Slect All</th>
                                            <th width="40%">WO#</th>
                                            <th width="40%">OrderStatus</th>
                                        </tr>
                                    </thead>
                                    <tbody>	
								    <?php
									//loop start
									foreach($resArray as $key=>$res_JobBoard) { 
									?>
                                 	<tr>
                                        <td data-title="CheckBox"><input type="checkbox" name="chkOrder[]" id="chkOrder" value="<?php echo $res_JobBoard['id'];?>"/></td>
                                        <td data-title="WO#"><?php echo $res_JobBoard['wo_no'];?></td>
                                        <td data-title="OrderStatus"><?php echo $res_JobBoard['work_status'];?></td>
                               		</tr>
                               <?php }?>
                        	  	</tbody>
                            </table>
                             <!-----Table area end------->
                            <div class="spacer"></div>
                            <div align="center">
                                <input type="button" name="submitbtn" id="submitbtn" class="buttonText" value="Submit Orders" onclick="change_invoiced();"/>
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
}elseif(isset($_REQUEST['choice']) && $_REQUEST['choice']=='make_invoice'){
	 ###########Insert Into work order notes table#############
	 $orderArray =$_REQUEST['orderArray'];
	 if(!empty($orderArray)){
		 foreach($orderArray as $val){
			$dbf->updateTable("work_order","work_status='Invoiced',invoiced_date=now()","id='$val'"); 
		 }
		 print "1";exit;
	 }else{
		 print "2";exit;
	 }
}
?>