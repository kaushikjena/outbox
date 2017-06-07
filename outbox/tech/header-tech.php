<?php if($_SESSION['userid']<>''){
	if($_SESSION['usertype']=='admin'){
		$resUserName=$dbf->getDataFromTable("admin","name","id='$_SESSION[userid]'");
	}elseif($_SESSION['usertype']=='user'){
		$resUserName=$dbf->getDataFromTable("users","name","id='$_SESSION[userid]'");
	}elseif($_SESSION['usertype']=='tech'){
		$restech=$dbf->fetchSingle("technicians","id='$_SESSION[userid]'");
		$resUserName =$restech['first_name'].' '.$restech['middle_name'].' '.$restech['last_name'];
	}elseif($_SESSION['usertype']=='client'){
		 $resUserName=$dbf->getDataFromTable("clients","name","id='$_SESSION[userid]'");
	}
}
$resVersion = $dbf->strRecordID("versions","name,title","id>0 ORDER BY id DESC LIMIT 1");
?>
<header>
	<div class="reslogo" align="center"><img src="../images/logo.png" /></div>
    <div class="header">
        <div class="logo"><img src="../images/logo.png" /></div>
        <a href="../howtouse.ppsx" target="_blank" class="buttonText2 butn_how_to_use">HOW TO USE</a>
        <span class="version-txt" title="<?php echo $resVersion['title'];?>"><?php echo $resVersion['name'];?></span>
        <form  action="tech-manage-job-search" name="srchAdvance" id="srchAdvance" method="post">
        <div class="searchdiv">
         	<div class="seardivtxtbox"><input type="text" name="srchInputBox" id="srchInputBox" class="searchtextbox" value="<?php echo $_REQUEST['srchInputBox'];?>"/></div>
            <div class="searchbtn"><input  type="image" src="../images/find.png" title="Search"/></div>
        </div>
        </form>
        <form  action="tech-manage-job-search-phone" name="srchAdvance" id="srchAdvance" method="post">
        <div class="searchdiv" style="width:200px;">
         	<div class="seardivtxtbox" style="width:175px;"><input type="text" name="srchInputBoxphno" id="srchInputBoxphno" class="searchtextbox" placeholder="Phone Number" value="<?php echo $_REQUEST['srchInputBoxphno'];?>"/></div>
            <div class="searchbtn"><input  type="image" src="../images/find.png" title="Search"/></div>
        </div>
        </form>
        <div class="spacer"></div>
         <div class="logininfo">
            <div class="welcome"><?php if($_SESSION['userid']<>''){?>Welcome : <span><?php echo $resUserName;?></span><?php }?></div>
            <div class="logoutdiv"><a href="../logout.php"><img src="../images/logout.jpg" alt="logout" border="0"/></a></div>
         </div>
    </div>
</header>
