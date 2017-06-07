<?php
ob_start();
session_start();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();
//preparation for login and store user id in session variable
if(isset($_REQUEST['action']) && $_REQUEST['action']=="login" && $_SERVER['REQUEST_METHOD']=='POST'){
	//for sql injection
	$useremail = stripslashes($_REQUEST['EmailLogin']); // Get user name
	$useremail = mysql_real_escape_string($useremail);
	$userpwd = stripslashes($_REQUEST['PasswordLogin']); // Get password
	$userpwd = mysql_real_escape_string($userpwd);
	$password = base64_encode(base64_encode($userpwd)); 
	if($useremail !='' && $userpwd !=''){
		$num=$dbf->countRows('login_view',"email='$useremail' AND password='$password'");
		if($num>0){
			$res_login=$dbf->fetchSingle('login_view',"email='$useremail' AND password='$password'");
			//$res_login=$dbf->fetchSingle('admin',"email='$useremail' AND password='$password'");
			if($res_login['status']=='1'){
				$_SESSION['userid']=$res_login['id']; //assign in session variable
				$_SESSION['usertype']=$res_login['user_type'] ; //assign in session variable
				#########insert record into mychat online users table#######
				$sql="REPLACE INTO mychat_online_users (username,user_type,status,lastlogin) VALUES ('$res_login[username]','$res_login[user_type]','1',now())";
				mysql_query($sql);
				#########insert record into mychat online users table#######
				//redirection to dashboard according to user type
				if($res_login['user_type']=='admin'){
					header("Location:dashboard");exit;
				}elseif($res_login['user_type']=='user'){
					header("Location:dashboard");exit;
				}elseif($res_login['user_type']=='client'){
					header("Location:client/client-dashboard");exit;
				}elseif($res_login['user_type']=='tech'){
					header("Location:tech/tech-dashboard");exit;
				}
			}
			else{
				header("Location:index?msg=002");exit;
			}
		}else{
			header("Location:index?msg=001");exit;
		}
	}else{
		header("Location:index?msg=001");exit;
	}
}
?>