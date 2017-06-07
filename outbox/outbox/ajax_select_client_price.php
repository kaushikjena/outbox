<?php
ob_start();
session_start();
include 'includes/class.Main.php';
$dbf = new User();
if(isset($_REQUEST['choice']) && $_REQUEST['choice']=='clientprice'){?>

	<div>
      <div align="left" class="serprheader prService1">Work Type</div>
      <div align="center" class="serprheader prService">Client Price</div>
      <div align="center" class="serprheader prService">OutBox Price</div>
      <div style="clear:both;"></div>
   </div>
    <?php
      $i=1;
      $count=$dbf->countRows("work_type","");
	  //query for outbox price
	  $qryout ="SELECT wt.id AS wid, wt.worktype, so.id as soid,so.outbox_price FROM work_type wt LEFT JOIN service_price_outbox so ON wt.id=so.work_type AND so.service_id='$_REQUEST[serviceid]' AND so.equipment='$_REQUEST[eqid]' ORDER BY wt.id ASC";
	  $resultout = $dbf->simpleQuery($qryout); 
	  //query for client price
	  $qry ="SELECT wt.id AS wid, wt.worktype, sp.id,sp.client_price FROM work_type wt LEFT JOIN service_price_client sp ON wt.id=sp.work_type AND sp.service_id='$_REQUEST[serviceid]' AND sp.equipment='$_REQUEST[eqid]' AND client_id='$_REQUEST[cid]' ORDER BY wt.id ASC";
	  $result = $dbf->simpleQuery($qry);
	  //print_r($result); 
	  foreach($result as $key =>$vwtype){
     ?>
    <div  class="textboxserview"><input type="hidden" name="WorkType<?php echo $i;?>" value="<?php echo $vwtype['wid'];?>"><?php echo $vwtype['worktype'];?></div>
    <div  class="textboxserprc">
        <input type="text" class="textboxjob" name="ClientPrice<?php echo $i;?>" id="ClientPrice<?php echo $i;?>" onKeyUp="return extractNumber(this,2);" maxlength="8" value="<?php echo $vwtype['client_price'];?>"><br/><label for="ClientPrice" id="lblClientPrice<?php echo $i;?>" class="redText"></label>
    </div>
    <div  class="textboxserprc">
        <input type="text" class="textboxjob" name="OutBoxPrice<?php echo $i;?>" id="OutBoxPrice<?php echo $i;?>" onKeyUp="return extractNumber(this,2);" maxlength="8" value="<?php echo $resultout[$key]['outbox_price'];?>"><br/><label for="OutBoxPrice" id="lblOutBoxPrice<?php echo $i;?>" class="redText"></label>
    </div>
    <input type="hidden" name="SpPrice<?php echo $i;?>" value="<?php echo $vwtype['id'];?>"/>
    <input type="hidden" name="SoPrice<?php echo $i;?>" value="<?php echo $resultout[$key]['soid'];?>"/>
    <div class="spacer"></div>
    <?php $i++; }?>
    <input type="hidden" name="count" id="count" value="<?php echo $count;?>"/>
<?php }?>