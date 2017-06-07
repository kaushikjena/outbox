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

?>
<header>
	<div class="reslogo" align="center"><img src="../images/logo.png" /></div>
    <div class="header">
        <div class="logo"><img src="../images/logo.png" /></div>
        <a href="../howtouse.ppsx" target="_blank" class="buttonText2 butn_how_to_use">HOW TO USE</a>
        <div class="searchdiv">
         	<form  action="tech-manage-job-search" name="srchAdvance" id="srchAdvance" method="post">
            <div class="seardivtxtbox"><input type="text" name="srchInputBox" id="srchInputBox" class="searchtextbox"/></div>
            <div class="searchbtn"><input  type="image" src="../images/find.png" title="Search"/></div>
            </form>
        </div>
        
        <div class="spacer"></div>
         <div class="logininfo">
            <div class="welcome"><?php if($_SESSION['userid']<>''){?>Welcome : <span><?php echo $resUserName;?></span><?php }?></div>
            <div class="logoutdiv"><a href="../logout.php"><img src="../images/logout.jpg" alt="logout" border="0"/></a></div>
         </div>
    </div>
</header>
