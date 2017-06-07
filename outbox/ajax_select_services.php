<?php
ob_start();
session_start();
include 'includes/class.Main.php';
$dbf = new User();

?>
    <div>
      <div align="left" class="serprheader prService1">Work Type</div>
      <div align="center" class="serprheader prService">Pay Grade A</div>
      <div align="center" class="serprheader prService">Pay Grade B</div>
      <div align="center" class="serprheader prService">Pay Grade C</div>
      <div align="center" class="serprheader prService">Pay Grade D</div>
      <div align="center" class="serprheader prService">Pay Grade E</div>
      <div align="center" class="serprheader prService">Pay Grade F</div>
      <div align="center" class="serprheader prService">Pay Grade G</div>
      <div align="center" class="serprheader prService">Pay Grade H</div>
      <div align="center" class="serprheader prService">Pay Grade I</div>
      <div align="center" class="serprheader prService">Pay Grade J</div>
      <div style="clear:both;"></div>
   </div>
    <?php
      $i=1;
      $count=$dbf->countRows("work_type","");
      $qry ="SELECT wt.id AS wid, wt.worktype, sp.* FROM work_type wt LEFT JOIN service_price sp ON wt.id=sp.work_type AND sp.equipment='$_REQUEST[eqid]' AND sp.service_id='$_REQUEST[serviceid]' ORDER BY wt.id ASC";
      $result = $dbf->simpleQuery($qry); 
      foreach($result as $vwtype){
     ?>
    <div  class="textboxserview"><input type="hidden" name="WorkType<?php echo $i;?>" value="<?php echo $vwtype['wid'];?>"><?php echo $vwtype['worktype'];?></div>
    <div  class="textboxserprc">
        <input type="text" class="textboxjob" name="PayGradeA<?php echo $i;?>" id="PayGradeA<?php echo $i;?>" onKeyUp="return extractNumber(this,2);" maxlength="8" value="<?php echo $vwtype['gradeA_price'];?>"><br/><label for="PayGradeA" id="lblPayGradeA<?php echo $i;?>" class="redText"></label>
    </div>
    <div  class="textboxserprc">
        <input type="text" class="textboxjob" name="PayGradeB<?php echo $i;?>" id="PayGradeB<?php echo $i;?>" onKeyUp="return extractNumber(this,2);" maxlength="8" value="<?php echo $vwtype['gradeB_price'];?>"><br/><label for="PayGradeB" id="lblPayGradeB<?php echo $i;?>" class="redText"></label>
    </div>
    <div  class="textboxserprc">
        <input type="text" class="textboxjob" name="PayGradeC<?php echo $i;?>" id="PayGradeC<?php echo $i;?>" onKeyUp="return extractNumber(this,2);" maxlength="8" value="<?php echo $vwtype['gradeC_price'];?>"><br/><label for="PayGradeC" id="lblPayGradeC<?php echo $i;?>" class="redText"></label>
   </div>
   <div  class="textboxserprc">
        <input type="text" class="textboxjob" name="PayGradeD<?php echo $i;?>" id="PayGradeD<?php echo $i;?>" onKeyUp="return extractNumber(this,2);" maxlength="8" value="<?php echo $vwtype['gradeD_price'];?>"><br/><label for="PayGradeD" id="lblPayGradeD<?php echo $i;?>" class="redText"></label>
    </div>
    <div  class="textboxserprc">
        <input type="text" class="textboxjob" name="PayGradeE<?php echo $i;?>" id="PayGradeE<?php echo $i;?>" onKeyUp="return extractNumber(this,2);" maxlength="8" value="<?php echo $vwtype['gradeE_price'];?>"><br/><label for="PayGradeE" id="lblPayGradeE<?php echo $i;?>" class="redText"></label>
    </div>
    <div  class="textboxserprc">
        <input type="text" class="textboxjob" name="PayGradeF<?php echo $i;?>" id="PayGradeF<?php echo $i;?>" onKeyUp="return extractNumber(this,2);" maxlength="8" value="<?php echo $vwtype['gradeF_price'];?>"><br/><label for="PayGradeF" id="lblPayGradeF<?php echo $i;?>" class="redText"></label>
    </div>
    <div  class="textboxserprc">
        <input type="text" class="textboxjob" name="PayGradeG<?php echo $i;?>" id="PayGradeG<?php echo $i;?>" onKeyUp="return extractNumber(this,2);" maxlength="8" value="<?php echo $vwtype['gradeG_price'];?>"><br/><label for="PayGradeG" id="lblPayGradeG<?php echo $i;?>" class="redText"></label>
    </div>
    <div  class="textboxserprc">
        <input type="text" class="textboxjob" name="PayGradeH<?php echo $i;?>" id="PayGradeH<?php echo $i;?>" onKeyUp="return extractNumber(this,2);" maxlength="8" value="<?php echo $vwtype['gradeH_price'];?>"><br/><label for="PayGradeH" id="lblPayGradeH<?php echo $i;?>" class="redText"></label>
    </div>
    <div  class="textboxserprc">
        <input type="text" class="textboxjob" name="PayGradeI<?php echo $i;?>" id="PayGradeI<?php echo $i;?>" onKeyUp="return extractNumber(this,2);" maxlength="8" value="<?php echo $vwtype['gradeI_price'];?>"><br/><label for="PayGradeI" id="lblPayGradeI<?php echo $i;?>" class="redText"></label>
    </div>
    <div  class="textboxserprc">
        <input type="text" class="textboxjob" name="PayGradeJ<?php echo $i;?>" id="PayGradeJ<?php echo $i;?>" onKeyUp="return extractNumber(this,2);" maxlength="8" value="<?php echo $vwtype['gradeJ_price'];?>"><br/><label for="PayGradeJ" id="lblPayGradeJ<?php echo $i;?>" class="redText"></label>
    </div>
    <input type="hidden" name="SpPrice<?php echo $i;?>" value="<?php echo $vwtype['id'];?>"/>
    <div class="spacer"></div>
    <?php $i++; }?>
    <div class="spacer" style="height:20px;"></div>
    <div align="center">
    <input type="hidden" name="count" id="count" value="<?php echo $count;?>"/>
    <input type="submit" class="buttonText" value="Submit Form"/>
    <input type="button" class="buttonText3" value="Back" onClick="javascript:window.location.href='manage-service-price'"/>
  </div>
                               