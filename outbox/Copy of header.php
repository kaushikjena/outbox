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
    <div class="header">
        <div class="logo"><img src="images/logo.png" /></div>
         <div class="logininfo">
         	<?php if($_SESSION['userid']<>''){?>
            <div class="welcome">Welcome : <span><?php echo $resUserName;?></span></div>
            <div class="logoutdiv"><a href="logout.php"><img src="images/logout.jpg" alt="logout" border="0"/></a></div>
            <?php }?>
         </div>
    </div>
</header>
